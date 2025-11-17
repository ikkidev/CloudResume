<?php
/**
 * Validator for canonical .htaccess payloads.
 *
 * Performs fast, conservative checks to catch common corruption sources:
 * - Unbalanced BEGIN/END markers
 * - Unbalanced <IfModule> ... </IfModule> pairs
 * - Malformed rewrite flags (e.g., ']]', unmatched '[' or ']')
 * - Forbidden PHP handler directives (AddHandler / SetHandler)
 * - Duplicate exclusive fragment markers (if provided)
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Class Validator
 *
 * @since 1.0.0
 */
class Validator {

	/**
	 * Collected error messages for the last validation run.
	 *
	 * @var string[]
	 */
	protected $errors = array();

	/**
	 * Reset error state.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function reset() {
		$this->errors = array();
	}

	/**
	 * Validate a canonical .htaccess text.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $text                 Htaccess content to validate.
	 * @param string[] $exclusive_block_ids  Optional list of block labels that must be unique
	 *                                       (e.g., array( 'WordPress', 'NFD Skip 404 Handling for Static Files' )).
	 * @return bool True if valid, false if errors found.
	 */
	public function is_valid( $text, $exclusive_block_ids = array() ) {
		$this->reset();

		$text  = (string) $text;
		$lines = explode( "\n", $text );

		$this->check_ifmodule_balance( $lines );
		$this->check_begin_end_balance( $lines );
		$this->check_rewrite_flag_brackets( $lines );
		$this->check_forbidden_handlers_scoped( $text );

		if ( ! empty( $exclusive_block_ids ) ) {
			$this->check_duplicate_exclusive_blocks( $text, (array) $exclusive_block_ids );
		}

		return empty( $this->errors );
	}

	/**
	 * Return errors collected during last validation.
	 *
	 * @since 1.0.0
	 *
	 * @return string[]
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Attempt basic remediation for common issues, returning fixed text.
	 *
	 * Notes:
	 * - Removes forbidden handler lines.
	 * - Fixes double ']]' to single ']' in flags.
	 * - Trims stray trailing spaces.
	 * - Does NOT attempt to invent missing END/BEGIN pairs; that belongs to the composer.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Original text.
	 * @return string Remediated text.
	 */
	public function remediate( $text ) {
		$lines = Text::normalize_lf( (string) $text, false );
		$lines = explode( "\n", $text );
		$out   = array();

		foreach ( $lines as $line ) {
			// Remove forbidden handlers entirely.
			if ( $this->is_forbidden_handler_line( $line ) ) {
				continue;
			}

			// Fix accidental ']]' in rewrite flags.
			if ( preg_match( '/\[[^\]]*\]\]/', $line ) ) {
				$fixed = preg_replace( '/\]\](\s|$)/', ']$1', $line );
				if ( null !== $fixed ) {
					$line = $fixed;
				}
			}

			// Trim trailing spaces/tabs.
			$line = rtrim( $line );

			$out[] = $line;
		}

		$result = implode( "\n", $out );

		// Normalize double blank lines to a single blank line.
		$result = Text::collapse_excess_blanks( $result );

		// Ensure single trailing newline.
		return Text::ensure_single_trailing_newline( $result );
	}

	/**
	 * Check matching of <IfModule NAME> ... </IfModule> with proper pairing.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $lines Lines to inspect.
	 * @return void
	 */
	protected function check_ifmodule_balance( $lines ) {
		$stack = array();

		foreach ( $lines as $i => $line ) {
			if ( preg_match( '/^\s*<IfModule\b([^>]*)>/i', $line, $m ) ) {
				// Extract the module token (first non-space within the tag).
				$token = trim( (string) $m[1] );
				// Normalize spacing: "<IfModule  mod_x.c  >" -> "mod_x.c"
				$token   = preg_replace( '/\s+/', ' ', $token );
				$token   = trim( $token, " \t\r\n>/" );
				$stack[] = '' === $token ? '(unknown)' : $token;
				continue;
			}

			if ( preg_match( '/^\s*<\/IfModule>\s*$/i', $line ) ) {
				if ( empty( $stack ) ) {
					$this->errors[] = 'Unmatched </IfModule> at line ' . ( $i + 1 ) . '.';
				} else {
					array_pop( $stack );
				}
			}
		}

		if ( ! empty( $stack ) ) {
			$this->errors[] = 'Unclosed <IfModule> block(s): ' . implode( ', ', $stack ) . '.';
		}
	}


	/**
	 * Check BEGIN/END marker balance by block label.
	 *
	 * Accepts lines like:
	 *   # BEGIN WordPress
	 *   # END WordPress
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $lines Lines to inspect.
	 * @return void
	 */
	protected function check_begin_end_balance( $lines ) {
		$stack = array();

		foreach ( $lines as $i => $line ) {
			if ( preg_match( '/^\s*#\s*BEGIN\s+(.+?)\s*$/', $line, $m ) ) {
				$label   = trim( $m[1] );
				$stack[] = $label;
				continue;
			}

			if ( preg_match( '/^\s*#\s*END\s+(.+?)\s*$/', $line, $m ) ) {
				$label = trim( $m[1] );
				$last  = empty( $stack ) ? null : array_pop( $stack );
				if ( $last !== $label ) {
					$this->errors[] = 'BEGIN/END mismatch near line ' . ( $i + 1 ) . ' (END ' . $label . ' without matching BEGIN).';
				}
			}
		}

		if ( ! empty( $stack ) ) {
			$this->errors[] = 'Unclosed BEGIN block(s): ' . implode( ', ', $stack ) . '.';
		}
	}

	/**
	 * Check rewrite rule flag bracket matching on each line.
	 *
	 * Looks for a simple mismatch in '[' and ']' counts.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $lines Lines to inspect.
	 * @return void
	 */
	protected function check_rewrite_flag_brackets( $lines ) {
		foreach ( $lines as $i => $line ) {
			if ( preg_match( '/^\s*Rewrite(Rule|Cond)\b/i', $line ) ) {
				$open  = substr_count( $line, '[' );
				$close = substr_count( $line, ']' );

				if ( $open !== $close ) {
					$this->errors[] = 'Unbalanced rewrite flags at line ' . ( $i + 1 ) . ' (found ' . $open . ' "[" vs ' . $close . ' "]").';
				}
			}
		}
	}

	/**
	 * Determine if a line is a forbidden handler directive.
	 *
	 * @since 1.0.0
	 *
	 * @param string $line Line content.
	 * @return bool
	 */
	protected function is_forbidden_handler_line( $line ) {
		$line = ltrim( (string) $line );

		// Disallow common ways to route PHP at the .htaccess level.
		if ( preg_match( '/^(AddHandler|SetHandler|AddType)\b/i', $line ) ) {
			if ( preg_match( '/php|application\/x-httpd-php/i', $line ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Check duplicates for exclusive blocks by label using markers.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $text                Full htaccess text.
	 * @param string[] $exclusive_block_ids Labels that must appear at most once.
	 * @return void
	 */
	protected function check_duplicate_exclusive_blocks( $text, $exclusive_block_ids ) {
		foreach ( $exclusive_block_ids as $label ) {
			$pattern = '/^\s*#\s*BEGIN\s+' . preg_quote( $label, '/' ) . '\s*$/mi';
			if ( preg_match_all( $pattern, $text, $m ) ) {
				$count = is_array( $m[0] ) ? count( $m[0] ) : 0;
				if ( $count > 1 ) {
					$this->errors[] = 'Duplicate exclusive block "' . $label . '" found (' . $count . ' occurrences).';
				}
			}
		}
	}

	/**
	 * Check for forbidden PHP handler directives, but **only** inside the
	 * NFD-managed block if present. If we’re validating just the body text
	 * (no markers), we still check it (to protect our managed payload).
	 *
	 * @since 1.1.0
	 *
	 * @param string $text Full .htaccess text or a body-only string.
	 * @return void
	 */
	protected function check_forbidden_handlers_scoped( $text ) {
		$text = (string) $text;

		$inside = '';
		if ( class_exists( __NAMESPACE__ . '\Config' )
		&& class_exists( __NAMESPACE__ . '\Text' )
		&& method_exists( __NAMESPACE__ . '\Config', 'marker' )
		&& method_exists( __NAMESPACE__ . '\Text', 'extract_from_markers_text' )
		) {
			// If we have a full file, this returns the text inside "# BEGIN <marker>" ... "# END <marker>"
			$inside = Text::extract_from_markers_text( $text, Config::marker() );
		}

		if ( '' === $inside ) {
			return;
		}

		// If we found a managed block, only scan **inside** it.
		// Otherwise (likely validating a body-only string), scan the given text as-is.
		$lines = explode( "\n", Text::normalize_lf( $inside, false ) );
		foreach ( $lines as $i => $line ) {
			if ( $this->is_forbidden_handler_line( $line ) ) {
				$this->errors[] = 'Forbidden PHP handler directive at line ' . ( $i + 1 ) . '.';
			}
		}
	}
}
