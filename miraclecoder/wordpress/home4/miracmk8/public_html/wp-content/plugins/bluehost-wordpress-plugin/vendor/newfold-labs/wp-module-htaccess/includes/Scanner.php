<?php
/**
 * Scanner for the Htaccess module.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Scanner for the Htaccess module.
 *
 * Responsibilities:
 * - Diagnose the current .htaccess for whole-file sanity & HTTP reachability.
 * - Inspect ONLY the managed marker block for drift/corruption.
 * - Remediate our block by reapplying the composed NFD body via Updater.
 * - Restore the latest timestamped backup of the ENTIRE .htaccess, then verify,
 *   and finally re-check/remediate our NFD block.
 *
 * Notes:
 * - Day-to-day self-heal: validate/remediate ONLY the NFD block.
 * - Emergency restore: replace the ENTIRE file from a .bak, then validate and heal our block.
 *
 * @since 1.0.0
 */
class Scanner {
	/**
	 * Marker label used to bracket our managed section.
	 *
	 * @var string
	 */
	protected $marker;

	/**
	 * Updater service for marker-based writes.
	 *
	 * @var Updater
	 */
	protected $updater;

	/**
	 * Validator service for syntax checks.
	 *
	 * @var Validator
	 */
	protected $validator;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Updater   $updater   Updater instance.
	 * @param Validator $validator Validator instance.
	 * @param string    $marker    Optional marker label. Defaults to Config::marker().
	 */
	public function __construct( Updater $updater, Validator $validator, $marker = null ) {
		$this->updater   = $updater;
		$this->validator = $validator;
		$this->marker    = ( null !== $marker ) ? (string) $marker : Config::marker();
	}

	/**
	 * Diagnose current .htaccess (whole-file) + loopback HTTP reachability.
	 *
	 * Use this BEFORE doing risky operations (e.g., restore).
	 *
	 * @since 1.0.0
	 *
	 * @param Context $context Context snapshot.
	 * @return array {
	 *   @type bool     file_valid   True if whole-file validator passes.
	 *   @type string[] file_issues  Validator errors (empty if none).
	 *   @type int      http_status  HTTP status code from HEAD to home (0 if request failed).
	 *   @type bool     reachable    True if status is 200-399 (best-effort).
	 * }
	 */
	public function diagnose( $context ) {
		$result = array(
			'file_valid'  => false,
			'file_issues' => array(),
			'http_status' => 0,
			'reachable'   => false,
		);

		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			$result['file_issues'][] = 'Could not resolve .htaccess path.';
			return $result;
		}

		$text = $this->read_file( $path );
		$text = Text::normalize_lf( $text, true );
		if ( '' === $text ) {
			$result['file_issues'][] = 'File is empty or unreadable.';
		} elseif ( $this->validator->is_valid( $text, array() ) ) {
			// Whole-file sanity (BEGIN/END balance, IfModule balance, flags/handlers).
			$result['file_valid'] = true;
		} else {
			$result['file_issues'] = $this->validator->get_errors();
		}

		// Loopback HTTP check. 500s from Apache due to bad .htaccess happen before PHP.
		$home = method_exists( $context, 'home_url' ) ? (string) $context->home_url() : '';

		// If home URL is not available, try site URL.
		if ( '' === $home && method_exists( $context, 'site_url' ) ) {
			$home = (string) $context->site_url();
		}

		if ( '' !== $home ) {
			// Ensure absolute, normalized URL ending with a slash.
			$probe_url = rtrim( $home, '/' ) . '/';
			$status    = $this->probe_http_status( $probe_url );
		} else {
			// No absolute URL available; skip probe.
			$status = 0;
		}

		$result['http_status'] = $status;
		$result['reachable']   = ( $status >= 200 && $status < 400 );

		return $result;
	}

	/**
	 * Scan ONLY the NFD managed block for drift/corruption.
	 *
	 * Semantics:
	 * - The authoritative comparison is based on the canonical BODY hash
	 *   (i.e., the content inside the managed markers with the two in-block
	 *   header lines removed, and a single optional blank line after them
	 *   ignored; inner "# BEGIN/# END ..." lines are KEPT). This matches
	 *   Updater::get_current_body_hash() and Manager's hashing behavior.
	 * - The header "STATE sha256: ..." (if present) is parsed and returned
	 *   as current_checksum for observability, but a stale header alone
	 *   does NOT cause a mismatch if the underlying BODY matches.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Now compares canonical BODY hashes consistent with Updater/Manager.
	 *
	 * @param Context    $context   Context snapshot.
	 * @param Fragment[] $fragments Enabled NFD fragments to compare against.
	 * @return array {
	 *   @type string   status            One of 'ok', 'missing', 'mismatch', 'invalid', 'error'.
	 *   @type string[] issues            Human-readable issues detected (may be empty).
	 *   @type string   current_checksum  Checksum parsed from the in-block header (may be stale or empty).
	 *   @type string   expected_checksum Canonical BODY checksum used for the actual comparison.
	 *   @type bool     can_remediate     True if a remediation apply should fix drift.
	 * }
	 */
	public function scan( $context, $fragments ) {
		// Initialize the report scaffold.
		$report = array(
			'status'            => 'ok',
			'issues'            => array(),
			'current_checksum'  => '',
			'expected_checksum' => '',
			'can_remediate'     => false,
		);

		// Resolve .htaccess path early to fail fast if unavailable.
		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			$report['status']   = 'error';
			$report['issues'][] = 'Could not resolve .htaccess path.';
			return $report;
		}

		// Ensure WP helpers for marker parsing are available when needed.
		$this->ensure_wp_file_helpers();

		// Read current lines inside our managed marker block (without outer BEGIN/END).
		$current_lines = $this->extract_marker_lines( $path, $this->marker );

		// Track whether the managed block exists at all (used for status below).
		$has_block = ! empty( $current_lines );

		// Parse the header "STATE sha256: ..." (for observability only).
		$current_hash_header         = $this->extract_hash_from_lines( $current_lines );
		$report['expected_checksum'] = $current_hash_header;

		// Build the EXPECTED BODY from the provided fragments.
		$expected_body      = Composer::compose_body_only( $fragments, $context );
		$expected_body_norm = Text::normalize_lf( $expected_body, true );

		// Validate the expected body; attempt remediation if invalid.
		if ( ! $this->validator->is_valid( $expected_body_norm, array() ) ) {
			$report['status']   = 'invalid';
			$report['issues'][] = 'Expected body did not pass validation: ' . implode( ' | ', $this->validator->get_errors() );

			// Try to remediate and re-validate (best effort).
			$expected_body_norm = $this->validator->remediate( $expected_body_norm );
			if ( ! $this->validator->is_valid( $expected_body_norm, array() ) ) {
				$report['issues'][] = 'Expected body remained invalid after remediation.';
				return $report;
			}
		}

		// Compute CURRENT canonical BODY hash (ignores header, keeps inner markers).
		$current_body_hash          = $this->get_current_body_hash(); // Returns '' if block missing/unreadable.
		$report['current_checksum'] = $current_hash_header;

		// Decide status based on BODY hash comparison (authoritative).
		if ( ! $has_block || '' === $current_body_hash ) {
			// Managed block is missing or unreadable.
			$report['status']   = 'missing';
			$report['issues'][] = 'Managed NFD block not found or unreadable.';
		} elseif ( $current_body_hash !== $report['expected_checksum'] ) {
			// Managed block exists: compare canonical BODY hash.
			$report['status']   = 'mismatch';
			$report['issues'][] = 'Managed NFD block BODY hash mismatch (drift detected).';
		} else {
			$report['status'] = 'ok';
			// Optional: flag a stale header checksum without failing the scan.
			if ( '' !== $current_hash_header && $current_hash_header !== $report['expected_checksum'] ) {
				$report['issues'][] = 'Header checksum is stale; BODY matches expected. It will refresh on the next write.';
			}
		}

		// If missing or mismatch, remediation (re-applying expected body) can fix drift.
		$report['can_remediate'] = ( 'missing' === $report['status'] || 'mismatch' === $report['status'] );

		return $report;
	}

	/**
	 * Remediate the NFD block by recomposing from fragments and writing via Updater.
	 *
	 * @since 1.0.0
	 *
	 * @param Context    $context   Context snapshot.
	 * @param Fragment[] $fragments Enabled NFD fragments to write.
	 * @param string     $version   Module version string for header.
	 * @return bool True on success, false on failure.
	 */
	public function remediate( $context, $fragments, $version ) {
		$host          = $context->host();
		$expected_body = Composer::compose_body_only( $fragments, $context );
		$expected_body = Text::normalize_lf( $expected_body, true );

		// Validate/remediate expected body before writing.
		if ( ! $this->validator->is_valid( $expected_body, array() ) ) {
			$expected_body = $this->validator->remediate( $expected_body );
			if ( ! $this->validator->is_valid( $expected_body, array() ) ) {
				return false;
			}
		}

		// Updater will embed header + checksum and no-op if identical.
		return (bool) $this->updater->apply_managed_block( $expected_body, $host, $version );
	}

	/**
	 * Restore the latest .htaccess backup (ENTIRE FILE) with verification and NFD self-heal.
	 *
	 * Steps:
	 *  - Pre-check: whole-file validation + loopback HTTP HEAD; only restore if broken.
	 *  - Restore latest backup over .htaccess.
	 *  - Validate the restored full file.
	 *  - Re-scan the NFD block and remediate it if needed.
	 *
	 * @since 1.0.0
	 *
	 * @param Context    $context   Context snapshot.
	 * @param Fragment[] $fragments Enabled NFD fragments used to recompose our block.
	 * @param string     $version   Module version string for the NFD header.
	 * @return array {
	 *   @type bool     restored           True if a backup was restored.
	 *   @type string   restored_backup    Filename of the backup restored (if any).
	 *   @type bool     full_file_valid    True if whole-file validation passed after restore (or precheck when no restore).
	 *   @type string[] full_file_issues   Validator errors for the restored/current file (if any).
	 *   @type array    nfd_scan           Result of Scanner::scan() after (or without) restore.
	 *   @type bool     nfd_remediated     True if we re-applied our NFD block.
	 *   @type array    precheck           Diagnose() result prior to restore attempt.
	 * }
	 */
	public function restore_latest_backup_verified( $context, $fragments, $version ) {
		$result = array(
			'restored'         => false,
			'restored_backup'  => '',
			'full_file_valid'  => false,
			'full_file_issues' => array(),
			'nfd_scan'         => array(),
			'nfd_remediated'   => false,
			'precheck'         => array(),
		);

		// 1) Precheck current state.
		$pre                = (array) $this->diagnose( $context );
		$result['precheck'] = $pre;

		$needs_restore = ( empty( $pre['file_valid'] ) ) ||
		( isset( $pre['http_status'] ) && $pre['http_status'] >= 500 && $pre['http_status'] < 600 );

		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			$result['full_file_issues'][] = 'Could not resolve .htaccess path.';
			return $result;
		}

		// If we don't need restore, still ensure NFD block is healthy and return.
		if ( ! $needs_restore ) {
			$scan               = (array) $this->scan( $context, $fragments );
			$result['nfd_scan'] = $scan;

			if ( ! empty( $scan['can_remediate'] ) ) {
				$result['nfd_remediated'] = (bool) $this->remediate( $context, $fragments, $version );
			}

			$result['full_file_valid']  = (bool) $pre['file_valid'];
			$result['full_file_issues'] = (array) ( $pre['file_issues'] ?? array() );
			return $result;
		}

		// 2) Resolve the single rolling backup.
		$backs = (array) $this->list_backups();
		if ( empty( $backs ) ) {
			$result['full_file_issues'][] = 'No backup found.';
			return $result;
		}

		$backup_name = (string) $backs[0];
		$src         = dirname( $path ) . DIRECTORY_SEPARATOR . $backup_name;

		if ( ! is_readable( $src ) ) {
			$result['full_file_issues'][] = 'Backup is missing or unreadable.';
			return $result;
		}

		// Restore using Scanner's own helper.
		if ( ! $this->copy_overwrite( $src, $path ) ) {
			$result['full_file_issues'][] = 'Backup restore failed.';
			return $result;
		}

		$result['restored']        = true;
		$result['restored_backup'] = $backup_name;

		// 3) Validate restored file.
		$text = $this->read_file( $path );
		$text = Text::normalize_lf( $text, true );
		if ( '' === $text ) {
			$result['full_file_issues'][] = 'Restored file is empty or unreadable.';
		} elseif ( $this->validator->is_valid( $text, array() ) ) {
			$result['full_file_valid'] = true;
		} else {
			$result['full_file_valid']  = false;
			$result['full_file_issues'] = (array) $this->validator->get_errors();
		}

		// 4) Re-scan and remediate NFD if needed.
		$scan               = (array) $this->scan( $context, $fragments );
		$result['nfd_scan'] = $scan;

		if ( ! empty( $scan['can_remediate'] ) ) {
			$result['nfd_remediated'] = (bool) $this->remediate( $context, $fragments, $version );
		}

		return $result;
	}

	/**
	 * List the single rolling .htaccess backup in the home directory.
	 *
	 * Filename: .htaccess.nfd-backup
	 *
	 * @since 1.0.0
	 *
	 * @return string[] Either ['.htaccess.nfd-backup'] or [] if not present.
	 */
	public function list_backups() {
		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			return array();
		}

		$dir         = dirname( $path );
		$single_name = '.htaccess.nfd-backup';
		$single_full = rtrim( $dir, "/\\ \t\n\r\0\x0B" ) . DIRECTORY_SEPARATOR . $single_name;

		return is_readable( $single_full ) ? array( $single_name ) : array();
	}



	/**
	 * Extract the NFD block’s checksum from marker lines.
	 *
	 * @since 1.0.0
	 *
	 * @param array $lines Lines inside the marker block.
	 * @return string sha256 or empty string.
	 */
	protected function extract_hash_from_lines( $lines ) {
		if ( ! is_array( $lines ) ) {
			return '';
		}
		foreach ( $lines as $line ) {
			if ( preg_match( '/^\s*#\s*STATE\s+sha256:\s*([0-9a-f]{64})\b/i', $line, $m ) ) {
				return (string) $m[1];
			}
		}
		return '';
	}

	/**
	 * Extract lines inside a marker block from an .htaccess file.
	 *
	 * Preserves all inner lines (including nested # BEGIN/# END and comment lines).
	 * Falls back to WP core extract_from_markers() if the Text helper isn't available.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Use Text::extract_from_markers_raw() for consistent parsing.
	 *
	 * @param string $path   Absolute .htaccess path.
	 * @param string $marker Marker label.
	 * @return string[] Lines inside the block (without the outer BEGIN/END) or empty array.
	 */
	protected function extract_marker_lines( $path, $marker ) {
		$path   = (string) $path;
		$marker = (string) $marker;

		if ( '' === $path || '' === $marker ) {
			return array();
		}

		// Prefer our own extractor (preserves all inner content).
		if ( class_exists( __NAMESPACE__ . '\Text' ) && method_exists( __NAMESPACE__ . '\Text', 'extract_from_markers_raw' ) ) {
			$buf = $this->read_file( $path );
			if ( '' === $buf ) {
				return array();
			}
			$lines = Text::extract_from_markers_raw( $buf, $marker );
			return is_array( $lines ) ? $lines : array();
		}

		// Fallback to WP core behavior (skips comment lines inside the block).
		$this->ensure_wp_file_helpers();
		if ( function_exists( 'extract_from_markers' ) ) {
			$lines = extract_from_markers( $path, $marker );
			return is_array( $lines ) ? $lines : array();
		}

		return array();
	}

	/**
	 * Resolve .htaccess path via WordPress helper with ABSPATH fallback.
	 *
	 * @since 1.0.0
	 *
	 * @return string Absolute path or empty string.
	 */
	/**
	 * Resolve .htaccess path via WordPress helper with ABSPATH fallback.
	 * Prefers a Context-provided path if available.
	 *
	 * @since 1.0.0
	 *
	 * @return string Absolute path or empty string.
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

		// Just return the computed path; callers decide whether it's readable.
		return (string) $path;
	}


	/**
	 * Ensure WP marker helpers are available.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function ensure_wp_file_helpers() {
		if ( ! function_exists( 'insert_with_markers' ) || ! function_exists( 'extract_from_markers' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}
	}

	/**
	 * Read an entire file into a string, preferring WP_Filesystem.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path Absolute path.
	 * @return string File contents or empty string on failure.
	 */
	protected function read_file( $path ) {
		$path = (string) $path;
		if ( '' === $path ) {
			return '';
		}

		// Prefer WP_Filesystem if available and initialized.
		if ( function_exists( 'WP_Filesystem' ) ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				// Bootstrap FS API (quietly).
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
			}

			if ( $wp_filesystem && is_object( $wp_filesystem ) ) {
				if ( ! $wp_filesystem->exists( $path ) || ! $wp_filesystem->is_readable( $path ) ) {
					return '';
				}
				$buf = $wp_filesystem->get_contents( $path );
				return is_string( $buf ) ? $buf : '';
			}
		}

		// Fallback: native read with explicit guards (no @ suppression).
		if ( ! is_readable( $path ) ) {
			return '';
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$buf = file_get_contents( $path );

		return is_string( $buf ) ? $buf : '';
	}

	/**
	 * HEAD request to detect 5xx quickly (best-effort).
	 *
	 * Tries HEAD first, then falls back to GET if HEAD is blocked.
	 * Uses secure defaults and follows a few redirects.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url Absolute URL to test.
	 * @return int HTTP status code (0 if request failed).
	 */
	protected function probe_http_status( $url ) {
		if ( ! function_exists( 'wp_remote_request' ) ) {
			return 0;
		}

		// Secure and resilient defaults.
		$args = array(
			'method'      => 'HEAD',
			'timeout'     => 5,
			'redirection' => 3, // follow a few redirects
			'blocking'    => true,
			'sslverify'   => true, // prefer secure by default
			'user-agent'  => 'Newfold Htaccess Scanner',
		);

		$response = wp_remote_request( $url, $args );
		if ( is_wp_error( $response ) ) {
			// Some environments block HEAD; try a short GET as fallback.
			$args['method'] = 'GET';
			$response       = wp_remote_request( $url, $args );
			if ( is_wp_error( $response ) ) {
				return 0;
			}
		}

		return (int) wp_remote_retrieve_response_code( $response );
	}

	/**
	 * Copy a file over another (overwrite).
	 *
	 * @since 1.0.0
	 *
	 * @param string $src Source path.
	 * @param string $dst Destination path.
	 * @return bool
	 */
	protected function copy_overwrite( $src, $dst ) {
		// Prefer WP_Filesystem if available.
		if ( function_exists( 'WP_Filesystem' ) ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
			}

			if ( $wp_filesystem && is_object( $wp_filesystem ) ) {
				$ok = $wp_filesystem->copy( $src, $dst, true, FS_CHMOD_FILE );
				return (bool) $ok;
			}
		}

		// Fallback to native copy + chmod.
		$ok = copy( $src, $dst );
		if ( $ok && function_exists( 'chmod' ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_chmod
			chmod( $dst, 0644 );
		}
		return (bool) $ok;
	}

	/**
	 * Lightweight directory scan (names only).
	 *
	 * Prefers WP_Filesystem; falls back to native ops.
	 *
	 * @since 1.0.0
	 *
	 * @param string $dir Directory path.
	 * @return string[] Filenames (no paths).
	 */
	protected function scan_dir( $dir ) {
		$dir  = (string) $dir;
		$list = array();

		// Prefer WP_Filesystem if available and initialized.
		if ( function_exists( 'WP_Filesystem' ) ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
			}

			if ( $wp_filesystem && is_object( $wp_filesystem ) ) {
				$entries = $wp_filesystem->dirlist( $dir );
				if ( is_array( $entries ) ) {
					foreach ( array_keys( $entries ) as $name ) {
						if ( '.' === $name || '..' === $name ) {
							continue;
						}
						$list[] = $name;
					}
				}
				return $list;
			}
		}

		// Fallback: native directory functions (guarded; minimal phpcs ignores).
		if ( ! is_dir( $dir ) || ! is_readable( $dir ) ) {
			return $list;
		}

		$dh = opendir( $dir );
		if ( false === $dh ) {
			return $list;
		}

		// Pre-read to avoid assignment in the while condition.
		$name = readdir( $dh );
		while ( false !== $name ) {
			if ( '.' !== $name && '..' !== $name ) {
				$list[] = $name;
			}

			$name = readdir( $dh );
		}

		closedir( $dh );

		return $list;
	}

	/**
	 * Return the sha256 of the BODY currently inside the managed block on disk,
	 * ignoring the in-block header (same semantics as Updater::get_current_body_hash()).
	 *
	 * @since 1.1.0
	 *
	 * @return string sha256 or '' if block missing/unreadable.
	 */
	protected function get_current_body_hash() {
		$path = $this->get_htaccess_path();
		if ( '' === $path ) {
			return '';
		}

		$full = $this->read_file( $path );
		if ( '' === $full ) {
			return '';
		}

		// Prefer Text helper used by Updater if available.
		if ( class_exists( __NAMESPACE__ . '\Text' ) && method_exists( __NAMESPACE__ . '\Text', 'extract_from_markers_text' ) ) {
			$inside = Text::extract_from_markers_text( $full, $this->marker );
			if ( '' === $inside ) {
				return '';
			}
			if ( method_exists( __NAMESPACE__ . '\Text', 'canonicalize_managed_body_for_hash' ) ) {
				return hash( 'sha256', Text::canonicalize_managed_body_for_hash( $inside ) );
			}
			// Fallback to local canonicalizer if Text helper missing.
			return hash( 'sha256', $this->canonicalize_body_for_hash_keep_markers( $inside ) );
		}

		// Fallback path: extract with our existing extractor, then canonicalize.
		$lines = $this->extract_marker_lines( $path, $this->marker );
		if ( empty( $lines ) ) {
			return '';
		}
		return $this->compute_body_hash_from_lines( $lines );
	}

	/**
	 * Compute sha256 from block lines, ignoring the two header lines and a single blank after them,
	 * but KEEPING any inner "# BEGIN/# END" lines — matches Updater semantics.
	 *
	 * @since 1.1.0
	 *
	 * @param array $lines Lines inside the marker block (without outer BEGIN/END).
	 * @return string
	 */
	protected function compute_body_hash_from_lines( $lines ) {
		if ( ! is_array( $lines ) ) {
			return '';
		}

		// Drop "Managed by..." if present.
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*Managed by\b/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		// Drop "STATE sha256: ..." if present.
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*STATE\s+sha256:/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		// Optional single blank after header.
		if ( isset( $lines[0] ) && '' === trim( $lines[0] ) ) {
			array_shift( $lines );
		}

		$body = implode(
			"\n",
			array_map(
				static function ( $ln ) {
					return rtrim( (string) $ln, "\r\n" ); },
				$lines
			)
		);
		$body = Text::normalize_lf( $body, false );
		$body = rtrim( $body, "\n" );

		return hash( 'sha256', $body );
	}

	/**
	 * Local canonicalizer for hashing when Text::canonicalize_managed_body_for_hash() isn't available.
	 *
	 * @since 1.1.0
	 *
	 * @param string|string[] $body_or_lines Body string or lines array.
	 * @return string
	 */
	protected function canonicalize_body_for_hash_keep_markers( $body_or_lines ) {
		$lines = is_array( $body_or_lines ) ? $body_or_lines : explode( "\n", Text::normalize_lf( (string) $body_or_lines, false ) );
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*Managed by\b/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*STATE\s+sha256:/i', $lines[0] ) ) {
			array_shift( $lines );
		}
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
