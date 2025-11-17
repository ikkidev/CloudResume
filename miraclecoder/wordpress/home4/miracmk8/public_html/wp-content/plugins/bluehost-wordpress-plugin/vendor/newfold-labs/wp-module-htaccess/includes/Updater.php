<?php
/**
 * Updater that manages ONLY the Newfold-managed block via markers,
 * leaving native WordPress rules and any other content untouched.
 *
 * Adds an in-block header and checksum, and skips writes if unchanged.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Class Updater
 *
 * @since 1.0.0
 */
class Updater {

	/**
	 * Marker label used to bracket our managed section.
	 *
	 * @var string
	 */
	protected $marker;

	/**
	 * Updater constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $marker Optional custom marker label (defaults to Config::marker()).
	 */
	public function __construct( $marker = null ) {
		$this->marker = ( null !== $marker ) ? (string) $marker : Config::marker();
	}

	/**
	 * Apply (insert/replace) our managed block in .htaccess.
	 *
	 * The final block will look like:
	 *
	 *   # BEGIN NFD Htaccess
	 *   # Managed by Newfold Htaccess Manager vX.Y (example.com)
	 *   # STATE sha256: <body-hash> applied: 2025-08-27T15:03:34Z
	 *   <fragment-1>
	 *   <blank>
	 *   <fragment-2>
	 *   ...
	 *   # END NFD Htaccess
	 *
	 * @since 1.0.0
	 *
	 * @param string $body           Concatenated NFD fragments (no trailing newline required).
	 * @param string $host           Host label for header (e.g., example.com).
	 * @param string $version        Module version string for header.
	 * @param array  $legacy_labels  Optional array of legacy labels to remove (during Updater writes only).
	 * @return bool True on success, false on failure or no-op when unchanged.
	 */
	public function apply_managed_block( $body, $host, $version, $legacy_labels = array() ) {
		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			return false;
		}

		$this->ensure_wp_file_helpers();

		// If the .htaccess file is missing, do nothing.
		if ( false === file_exists( $path ) ) {
			return false;
		}

		// Normalize incoming body and compute checksum.
		$body_norm   = Text::normalize_lf( (string) $body, true );
		$body_hash   = hash( 'sha256', $body_norm );
		$applied_iso = gmdate( 'Y-m-d\TH:i:s\Z' );

		// Build the lines we intend to write inside our markers.
		$lines = $this->build_block_lines( $body_norm, $host, $version, $body_hash, $applied_iso );

		// Read full current file once (LF-normalized).
		$current_full = $this->read_file( $path );
		$current_full = Text::normalize_lf( $current_full, false );

		// Derive current block lines from the in-memory text (avoid extra disk IO).
		$pair  = $this->get_marker_regex_pair();
		$begin = $pair[0];
		$end   = $pair[1];

		$current_lines = array();
		if ( preg_match( $begin, $current_full, $mb, PREG_OFFSET_CAPTURE ) && preg_match( $end, $current_full, $me, PREG_OFFSET_CAPTURE ) ) {
			$start = $mb[0][1] + strlen( $mb[0][0] ) + 1; // after BEGIN line + newline
			$stop  = $me[0][1];                           // start of END line
			if ( $stop > $start ) {
				$inside        = substr( $current_full, $start, $stop - $start );
				$current_lines = explode( "\n", rtrim( $inside, "\n" ) );
			}
		}

		// Check if any legacy blocks exist that we plan to remove.
		$migrator   = new Migrator();
		$has_legacy = false;
		if ( is_array( $legacy_labels ) && ! empty( $legacy_labels ) ) {
			$probe      = $migrator->remove_legacy_blocks( $current_full, $legacy_labels );
			$has_legacy = ( ! empty( $probe['removed'] ) && $probe['removed'] > 0 );
		}

		// EMPTY BODY: delete the block instead of writing a blank block
		if ( '' === $body_norm ) {
			// If no block and no legacy to remove, nothing to do.
			if ( empty( $current_lines ) && ! $has_legacy ) {
				return true;
			}

			// Refresh backup before modifying.
			if ( ! $this->refresh_single_backup( $path ) ) {
				return false;
			}

			// Start from current file, remove legacy, then remove managed block (all in-memory).
			$after_mig = $has_legacy ? $migrator->remove_legacy_blocks( $current_full, $legacy_labels ) : array( 'text' => $current_full );
			$txt       = $after_mig['text'];

			// Remove the managed block entirely (in-memory).
			if ( preg_match( $begin, $txt, $mb, PREG_OFFSET_CAPTURE ) && preg_match( $end, $txt, $me, PREG_OFFSET_CAPTURE ) ) {
				$start = $mb[0][1];
				$stop  = $me[0][1] + strlen( $me[0][0] );
				if ( $stop > $start ) {
					$txt = substr( $txt, 0, $start ) . substr( $txt, $stop );
				}
			}

			// Run patch cleanup/reapply with current enabled fragments
			$context   = Context::from_wp( array() );
			$fragments = Api::registry()->enabled_fragments( $context );
			$txt       = $this->apply_fragment_patches( $txt, $fragments, $context );

			$txt = Text::collapse_excess_blanks( $txt );
			$txt = Text::ensure_single_trailing_newline( $txt );

			// Single atomic write.
			if ( ! $this->write_file_atomic( $path, $txt ) ) {
				return false;
			}

			// Post-change health check.
			if ( $this->scan_for_issues() ) {
				$this->restore_backup( $path );
				return false;
			}

			return true;
		}
		// EMPTY BODY

		// Remove legacy blocks (in-memory).
		$after_mig = $has_legacy
		? $migrator->remove_legacy_blocks( $current_full, $legacy_labels )
		: array(
			'text'    => $current_full,
			'removed' => 0,
		);

		// Inject/replace the managed block (in-memory).
		$final = $this->inject_or_replace_managed_block( $after_mig['text'], $lines );

		// Apply fragment patches now (operate on the full file text).
		$context   = Context::from_wp( array() );
		$fragments = Api::registry()->enabled_fragments( $context );

		$final = $this->apply_fragment_patches( $final, $fragments, $context );

		// Normalize once for a clean compare
		$final_norm   = Text::normalize_lf( (string) $final, false );
		$current_norm = Text::normalize_lf( (string) $current_full, false );

		// Reuse current STATE line (sha + applied timestamp) for the compare,
		// so we don't rewrite just to bump the timestamp.
		$state_re = '/^\s*#\s*STATE\s+sha256:\s*[0-9a-f]{64}\s+applied:\s+.+$/mi';

		$current_state = null;
		if ( preg_match( $state_re, $current_norm, $m ) ) {
			$current_state = $m[0];
		}

		$final_for_compare = $final_norm;
		if ( null !== $current_state ) {
			// Replace the STATE line in the candidate with the current file's STATE line.
			$final_for_compare = preg_replace( $state_re, $current_state, $final_for_compare, 1 );
		}

		// If nothing really changed (ignoring timestamp), no-op.
		if ( $final_for_compare === $current_norm ) {
			return true;
		}

		// No-op if the patched result equals what's currently on disk.
		if ( $final === $current_full ) {
			return true;
		}

		// Refresh backup before modifying.
		if ( ! $this->refresh_single_backup( $path ) ) {
			return false;
		}

		// Single atomic write to disk.
		if ( ! $this->write_file_atomic( $path, $final ) ) {
			return false;
		}

		// Post-write health check.
		if ( $this->scan_for_issues() ) {
			$this->restore_backup( $path );
			return false;
		}

		return true;
	}

	/**
	 * Apply regex patches provided by fragments (optional patches()).
	 *
	 * @param string     $full_text Complete .htaccess text (LF normalized preferred).
	 * @param Fragment[] $fragments Enabled fragments.
	 * @param mixed      $context   Optional context.
	 * @return string
	 */
	protected function apply_fragment_patches( $full_text, $fragments, $context ) {
		$text = (string) $full_text;

		// Normalize to LF so anchors behave.
		$text = Text::normalize_lf( (string) $text, false );

		// --- Robust, id-agnostic removal of all NFD PATCH blocks (no regex backflips) ---
		$lines    = preg_split( '/\n/', (string) $text );
		$out      = array();
		$in_patch = false;
		$removed  = 0;

		for ( $i = 0, $n = count( $lines ); $i < $n; $i++ ) {
			$line = $lines[ $i ];

			// BEGIN marker?
			if ( ! $in_patch && preg_match( '/^\s*#\s*NFD\s+PATCH\b.*\bBEGIN\b/i', $line ) ) {
				// Eat a single preceding blank from the already-built output, if any.
				if ( ! empty( $out ) && '' === trim( end( $out ) ) ) {
					array_pop( $out );
				}
				$in_patch = true;
				++$removed;
				continue; // skip the BEGIN line
			}

			if ( $in_patch ) {
				// END marker? (consume it + one following blank line, if present)
				if ( preg_match( '/^\s*#\s*NFD\s+PATCH\b.*\bEND\b/i', $line ) ) {
					// If next line exists and is blank, skip it too.
					if ( $i + 1 < $n && '' === trim( $lines[ $i + 1 ] ) ) {
						++$i;
					}
					$in_patch = false;
				}
				continue; // skip any line while inside a patch block
			}

			$out[] = $line;
		}

		$text = implode( "\n", $out );

		// 4) Tidy spacing.
		$text = Text::collapse_excess_blanks( $text );
		// remove a single leading blank line if any
		$text = preg_replace( '~^\h*\R~u', '', (string) $text, 1 );
		$text = Text::ensure_single_trailing_newline( $text );

		// Identify ranges for scoped patching (WordPress + managed).
		$wp_begin = '~^[ \t]*#\s*BEGIN\s+WordPress\s*$~m';
		$wp_end   = '~^[ \t]*#\s*END\s+WordPress\s*$~m';

		$managed_label = Config::marker();
		$mg_begin      = '~^[ \t]*#\s*BEGIN\s+' . preg_quote( $managed_label, '~' ) . '\s*$~m';
		$mg_end        = '~^[ \t]*#\s*END\s+' . preg_quote( $managed_label, '~' ) . '\s*$~m';

		$wp_range = null;
		if ( preg_match( $wp_begin, $text, $m1, PREG_OFFSET_CAPTURE ) && preg_match( $wp_end, $text, $m2, PREG_OFFSET_CAPTURE ) ) {
			$start = $m1[0][1];
			$stop  = $m2[0][1] + strlen( $m2[0][0] );
			if ( $stop > $start ) {
				$wp_range = array( $start, $stop );
			}
		}

		$mg_range = null;
		if ( preg_match( $mg_begin, $text, $m3, PREG_OFFSET_CAPTURE ) && preg_match( $mg_end, $text, $m4, PREG_OFFSET_CAPTURE ) ) {
			$start = $m3[0][1];
			$stop  = $m4[0][1] + strlen( $m4[0][0] );
			if ( $stop > $start ) {
				$mg_range = array( $start, $stop );
			}
		}

		// Re-apply patches from currently enabled fragments.
		foreach ( (array) $fragments as $fragment ) {
			if ( ! $fragment instanceof Fragment || ! method_exists( $fragment, 'patches' ) ) {
				continue;
			}

			$patches = (array) $fragment->patches( $context );
			if ( empty( $patches ) ) {
				continue;
			}

			foreach ( $patches as $patch ) {
				$scope       = isset( $patch['scope'] ) ? (string) $patch['scope'] : 'full';
				$pattern     = isset( $patch['pattern'] ) ? (string) $patch['pattern'] : '';
				$replacement = isset( $patch['replacement'] ) ? (string) $patch['replacement'] : '';
				$limit       = isset( $patch['limit'] ) ? (int) $patch['limit'] : -1;

				if ( '' === $pattern ) {
					continue;
				}

				if ( 'full' === $scope ) {
					$patched = preg_replace( $pattern, $replacement, $text, $limit );
					if ( null !== $patched ) {
						$text = $patched;
					}
					continue;
				}

				$range = null;
				if ( 'wp_block' === $scope && null !== $wp_range ) {
					$range = $wp_range;
				} elseif ( 'managed_block' === $scope && null !== $mg_range ) {
					$range = $mg_range;
				}

				if ( null === $range ) {
					continue;
				}

				list( $seg_start, $seg_stop ) = $range;
				$segment                      = substr( $text, $seg_start, $seg_stop - $seg_start );
				$patched                      = preg_replace( $pattern, $replacement, $segment, $limit );
				if ( null === $patched ) {
					continue;
				}

				$text = substr( $text, 0, $seg_start ) . $patched . substr( $text, $seg_stop );

				// Adjust ranges if sizes changed.
				$delta = strlen( $patched ) - strlen( $segment );
				if ( 'wp_block' === $scope && null !== $wp_range ) {
					$wp_range = array( $wp_range[0], $wp_range[1] + $delta );
				}
				if ( 'managed_block' === $scope && null !== $mg_range ) {
					$mg_range = array( $mg_range[0], $mg_range[1] + $delta );
				}
			}
		}
		$text = Text::collapse_excess_blanks( $text );
		$text = Text::ensure_single_trailing_newline( $text );
		return $text;
	}

	/**
	 * Build the full set of lines for the NFD block (header + body).
	 *
	 * @param string $body_norm  Normalized body text.
	 * @param string $host       Host label.
	 * @param string $version    Version string.
	 * @param string $body_hash  sha256 of body_norm.
	 * @param string $applied_iso UTC timestamp.
	 * @return array
	 */
	protected function build_block_lines( $body_norm, $host, $version, $body_hash, $applied_iso ) {
		$header = array(
			'# Managed by Newfold Htaccess Manager v' . $version . ' (' . $host . ')',
			'# STATE sha256: ' . $body_hash . ' applied: ' . $applied_iso,
		);

		$body_lines = ( '' === $body_norm ) ? array() : explode( "\n", $body_norm );

		// Separate header and body with a blank line if body exists.
		$lines = $header;
		if ( ! empty( $body_lines ) ) {
			$lines[] = '';
			$lines   = array_merge( $lines, $body_lines );
		}

		return $lines;
	}

	/**
	 * Locate .htaccess via WP helpers with ABSPATH fallback.
	 *
	 * @return string
	 */
	protected function get_htaccess_path() {
		$path = '';

		if ( function_exists( 'get_home_path' ) ) {
			$home = get_home_path();
			if ( is_string( $home ) && '' !== $home ) {
				$path = rtrim( $home, "/\\ \t\n\r\0\x0B" ) . DIRECTORY_SEPARATOR . '.htaccess';
			}
		}

		if ( '' === $path && defined( 'ABSPATH' ) ) {
			$path = rtrim( ABSPATH, "/\\ \t\n\r\0\x0B" ) . DIRECTORY_SEPARATOR . '.htaccess';
		}

		return $path;
	}

	/**
	 * Ensure WordPress marker helper functions are loaded.
	 *
	 * @return void
	 */
	protected function ensure_wp_file_helpers() {
		if ( ! function_exists( 'insert_with_markers' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}
		if ( ! function_exists( 'wp_delete_file' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
	}

	/**
	 * Compute the single backup file path for the given .htaccess.
	 *
	 * Uses a stable filename in the same directory to avoid creating many backups.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Htaccess file path.
	 * @return string Backup path.
	 */
	protected function get_backup_path( $path ) {
		$dir  = dirname( (string) $path );
		$name = '.htaccess.nfd-backup';
		return rtrim( $dir, "/\\ \t\n\r\0\x0B" ) . DIRECTORY_SEPARATOR . $name;
	}

	/**
	 * Refresh the single backup file to reflect the current .htaccess contents.
	 *
	 * This keeps exactly one backup (".htaccess.nfd-backup") that always contains
	 * the most recent pre-write state, avoiding filesystem bloat while ensuring
	 * we can roll back the last change.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Htaccess file path.
	 * @return bool True if backup was written (or updated), false on failure.
	 */
	protected function refresh_single_backup( $path ) {
		$backup = $this->get_backup_path( $path );

		// Read current .htaccess (empty string if missing/unreadable is acceptable).
		$current = $this->read_file( $path );

		// Overwrite or create the single backup with the latest contents.
		return $this->write_file_atomic( $backup, $current );
	}


	/**
	 * Restore the backup over the current .htaccess.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Htaccess file path.
	 * @return bool True on success, false otherwise.
	 */
	protected function restore_backup( $path ) {
		$backup = $this->get_backup_path( $path );
		if ( ! is_readable( $backup ) ) {
			return false;
		}
		$buf = $this->read_file( $backup );
		return $this->write_file_atomic( $path, $buf );
	}

	/**
	 * Read a file's contents safely. Returns empty string on failure.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path File path.
	 * @return string
	 */
	protected function read_file( $path ) {
		$path = (string) $path;
		if ( '' === $path || ! is_readable( $path ) ) {
			return '';
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$buf = file_get_contents( $path );
		return is_string( $buf ) ? $buf : '';
	}

	/**
	 * Write file contents atomically where possible.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Destination path.
	 * @param string $data Contents to write.
	 * @return bool True on success.
	 */
	protected function write_file_atomic( $path, $data ) {
		$path = (string) $path;
		$tmp  = $path . '.tmp-' . uniqid( 'nfd', true );

		// Ensure wp_delete_file() exists for cleanup fallback.
		if ( ! function_exists( 'wp_delete_file' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		// Capture existing mode to preserve permissions.
		$mode = null;
		if ( file_exists( $path ) ) {
			$mode = fileperms( $path );
		}

		// Write tmp file (binary, no truncation surprises).
		$fp = fopen( $tmp, 'wb' );
		if ( false === $fp ) {
			return false;
		}
		$bytes = fwrite( $fp, (string) $data );
		if ( false === $bytes ) {
			fclose( $fp );
			wp_delete_file( $tmp );
			return false;
		}
		fflush( $fp );
		// Try to fsync for extra safety (ignored if not supported).
		if ( function_exists( 'fsync' ) ) {
			fsync( $fp );
		}
		fclose( $fp );

		// Apply old mode to tmp (best effort).
		if ( null !== $mode ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			chmod( $tmp, $mode & 0777 );
		}

		// Try POSIX atomic rename first.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.rename_rename
		if ( rename( $tmp, $path ) ) {
			return true;
		}

		// Cross-FS fallback: copy then rename/delete.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
		if ( copy( $tmp, $path ) ) {
			wp_delete_file( $tmp );
			return true;
		}

		wp_delete_file( $tmp );
		return false;
	}


	/**
	 * Run a post-write health check using the Scanner.
	 *
	 * Criteria for "issues":
	 * - Whole-file validator fails, OR
	 * - Loopback HTTP status indicates server error (5xx).
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if issues were detected; false if file appears healthy.
	 */
	protected function scan_for_issues() {
		// Build a minimal context snapshot for the scanner.
		$context = class_exists( __NAMESPACE__ . '\Context' )
			? Context::from_wp( array() )
			: null;

		$scanner   = new Scanner( $this, new Validator() );
		$diagnosis = $scanner->diagnose( $context );

		$bad_file = ( empty( $diagnosis['file_valid'] ) );
		$bad_http = ( isset( $diagnosis['http_status'] ) && $diagnosis['http_status'] >= 500 && $diagnosis['http_status'] < 600 );

		return ( $bad_file || $bad_http );
	}

	/**
	 * Compute sha256 of the BODY within a marker block (ignores header comments).
	 *
	 * @since 1.0.0
	 *
	 * @param array $lines Lines inside the block (without BEGIN/END).
	 * @return string
	 */
	protected function compute_body_hash_from_lines( $lines ) {
		if ( ! is_array( $lines ) ) {
			return '';
		}

		$state_index = -1;
		foreach ( $lines as $i => $line ) {
			if ( preg_match( '/^\s*#\s*STATE\s+sha256:\s*[0-9a-f]{64}\b/i', $line ) ) {
				$state_index = $i;
				break;
			}
		}

		$start = ( -1 === $state_index ) ? 0 : $state_index + 1;
		if ( $start < count( $lines ) && '' === trim( $lines[ $start ] ) ) {
			++$start; // skip single blank separator after header
		}

		$body = implode( "\n", array_slice( $lines, $start ) );
		$body = Text::normalize_lf( $body, true );

		return hash( 'sha256', $body );
	}

	/**
	 * Return the sha256 of the BODY currently inside the managed block on disk,
	 * ignoring the in-block header. Returns '' if the block is missing.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	/**
	 * Return the sha256 of the BODY currently inside the managed block on disk,
	 * ignoring only the in-block STATE/header line. Returns '' if the block is missing.
	 *
	 * Includes any inner fragment markers (e.g. "# BEGIN Something" ... "# END Something")
	 * so the hash matches bodies that were persisted/written with those markers.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_current_body_hash() {
		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			return '';
		}

		$full = $this->read_file( $path );
		if ( '' === $full ) {
			return '';
		}

		$inside = Text::extract_from_markers_text( $full, $this->marker );
		if ( '' === $inside ) {
			return '';
		}

		return hash( 'sha256', Text::canonicalize_managed_body_for_hash( $inside ) );
	}

	/**
	 * Build the full block text including BEGIN/END markers from body lines.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $lines Lines to place inside markers.
	 * @return string
	 */
	protected function render_markered_block( $lines ) {
		$payload = implode( "\n", (array) $lines );
		$payload = rtrim( $payload, "\n" );
		$out     = '# BEGIN ' . $this->marker . "\n" . $payload . "\n# END " . $this->marker . "\n";
		return $out;
	}

	/**
	 * Replace existing managed block or append a new one, returning full file text.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $current Full existing .htaccess (LF-normalized).
	 * @param string[] $lines   Lines for inside the NFD markers.
	 * @return string
	 */
	protected function inject_or_replace_managed_block( $current, $lines ) {
		$txt = (string) $current;

		$pair  = $this->get_marker_regex_pair();
		$begin = $pair[0];
		$end   = $pair[1];

		$block = $this->render_markered_block( $lines );

		// If there is an existing block, replace it.
		if ( preg_match( $begin, $txt, $mb, PREG_OFFSET_CAPTURE ) && preg_match( $end, $txt, $me, PREG_OFFSET_CAPTURE ) ) {
			$start = $mb[0][1];
			$stop  = $me[0][1] + strlen( $me[0][0] );
			if ( $stop > $start ) {
				$txt = substr( $txt, 0, $start ) . $block . substr( $txt, $stop );
			}
		} else {
			// Append with a separating newline if needed.
			if ( '' !== rtrim( $txt, "\n" ) ) {
				$txt .= "\n";
			}
			$txt .= $block;
		}

		// Normalize spacing and ensure trailing newline.
		$txt = Text::collapse_excess_blanks( $txt );

		return Text::ensure_single_trailing_newline( $txt );
	}

	/**
	 * Return regex patterns for BEGIN/END markers for a marker label.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $marker Override (defaults to $this->marker).
	 * @return array{string,string} { $begin, $end }
	 */
	protected function get_marker_regex_pair( $marker = null ) {
		$label = preg_quote( ( null !== $marker ? (string) $marker : $this->marker ), '/' );
		return array(
			'/^\s*#\s*BEGIN\s+' . $label . '\s*$/m',
			'/^\s*#\s*END\s+' . $label . '\s*$/m',
		);
	}

	/**
	 * Return the raw text inside "# BEGIN <marker>" ... "# END <marker>".
	 * Preserves *all* lines (including comment lines) between the markers.
	 *
	 * @param string $buf    Full file contents (LF normalized recommended).
	 * @param string $marker Marker label, e.g. "NFD Htaccess".
	 * @return string Inside text (without BEGIN/END lines), or '' if not found/invalid.
	 */
	protected function slice_inside_markers_preserve_comments( $buf, $marker ) {
		$buf = (string) $buf;
		if ( '' === $buf ) {
			return '';
		}

		$begin = '/^\s*#\s*BEGIN\s+' . preg_quote( (string) $marker, '/' ) . '\s*$/m';
		$end   = '/^\s*#\s*END\s+' . preg_quote( (string) $marker, '/' ) . '\s*$/m';

		if ( ! preg_match( $begin, $buf, $mb, PREG_OFFSET_CAPTURE ) ) {
			return '';
		}
		if ( ! preg_match( $end, $buf, $me, PREG_OFFSET_CAPTURE ) ) {
			return '';
		}

		$start = $mb[0][1] + strlen( $mb[0][0] );
		// Move past the newline after the BEGIN line if present.
		if ( isset( $buf[ $start ] ) && "\n" === $buf[ $start ] ) {
			++$start;
		}
		$stop = $me[0][1];
		if ( $stop <= $start ) {
			return '';
		}

		return substr( $buf, $start, $stop - $start );
	}

	/**
	 * Canonicalize the managed body for hashing:
	 * - drop the two in-block header lines ("Managed by...", "STATE sha256: ...")
	 * - drop a single blank line immediately after those headers (if present)
	 * - KEEP any nested "# BEGIN/# END" lines (we want them hashed)
	 * - normalize to LF and trim trailing newlines
	 *
	 * @param string|string[] $body_or_lines Body text or array of lines.
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function canonicalize_body_for_hash_keep_markers( $body_or_lines ) {
		if ( is_array( $body_or_lines ) ) {
			$lines = $body_or_lines;
		} else {
			$norm  = Text::normalize_lf( (string) $body_or_lines, false );
			$lines = explode( "\n", $norm );
		}

		// Remove the two header lines if present.
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*Managed by\b/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*STATE\s+sha256:/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		// Optional single blank separator after header.
		if ( isset( $lines[0] ) && '' === trim( $lines[0] ) ) {
			array_shift( $lines );
		}

		$canon = implode(
			"\n",
			array_map(
				static function ( $ln ) {
					return rtrim( (string) $ln, "\r\n" ); },
				$lines
			)
		);

		return rtrim( Text::normalize_lf( $canon, false ), "\n" );
	}
}
