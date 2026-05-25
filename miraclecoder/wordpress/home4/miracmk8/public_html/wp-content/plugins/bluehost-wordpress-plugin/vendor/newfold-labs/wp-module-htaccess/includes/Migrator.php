<?php
namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Class Migrator
 *
 * @since 1.0.0
 */
class Migrator {

	/**
	 * Remove legacy blocks by labels. Returns transformed text and count removed.
	 *
	 * NOTE: We assume current NFD body does NOT include inner fragment wrappers,
	 * so a global removal of legacy wrappers is safe.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $text          Full .htaccess content (any line endings).
	 * @param string[] $legacy_labels Labels to remove (e.g., "Newfold Skip 404 Handling for Static Files").
	 * @return array { 'text' => string, 'removed' => int }
	 */
	public function remove_legacy_blocks( $text, $legacy_labels ) {
		// Normalize to LF, remove BOM, normalize NBSPs to spaces.
		$buf = (string) $text;
		$buf = str_replace( array( "\r\n", "\r" ), "\n", $buf );
		$buf = ltrim( $buf, "\xEF\xBB\xBF" );        // UTF-8 BOM
		$buf = str_replace( "\xC2\xA0", ' ', $buf );  // NBSP -> space

		// Nothing to do?
		if ( '' === $buf || empty( $legacy_labels ) || ! is_array( $legacy_labels ) ) {
			return array(
				'text'    => $this->postprocess( $buf ),
				'removed' => 0,
			);
		}

		// Protect the managed wrapper block (Config::marker())
		$managed_label   = Config::marker(); // e.g., 'NFD Htaccess'
		$managed_pattern = '~^[ \t]*#\s*BEGIN\s+' . preg_quote( $managed_label, '~' ) . '\s*$'
		. '(?s:.*?)'
		. '^[ \t]*#\s*END\s+' . preg_quote( $managed_label, '~' ) . '\s*$~mu';

		$managed_start = 0;
		$managed_len   = 0;
		$managed_body  = '';

		if ( preg_match( $managed_pattern, $buf, $m, PREG_OFFSET_CAPTURE ) ) {
			$managed_start = $m[0][1];
			$managed_len   = strlen( $m[0][0] );
			$managed_body  = $m[0][0];
		}

		if ( 0 < $managed_len ) {
			$head = substr( $buf, 0, $managed_start );
			$tail = substr( $buf, $managed_start + $managed_len );
		} else {
			// No managed block found; operate on the whole buffer as "head".
			$head = $buf;
			$tail = '';
		}

		// Build the label set, excluding empties and our managed marker
		$labels = array();
		foreach ( $legacy_labels as $label ) {
			$l = trim( (string) $label );
			if ( '' === $l ) {
				continue;
			}
			if ( 0 === strcasecmp( $l, $managed_label ) ) {
				continue; // never target our managed wrapper
			}
			$labels[ $l ] = true;
		}
		if ( empty( $labels ) ) {
			$out = ( 0 < $managed_len ) ? ( $head . $managed_body . $tail ) : $head;
			return array(
				'text'    => $this->postprocess( $out ),
				'removed' => 0,
			);
		}

		// Remove legacy blocks ONLY in head/tail (not inside managed)
		$removed = 0;

		foreach ( array_keys( $labels ) as $label ) {
			$quoted  = preg_quote( $label, '~' );
			$pattern = '~^[ \t]*#\s*BEGIN\s+' . $quoted . '\s*$'
				. '(?s:.*?)'
				. '^[ \t]*#\s*END\s+' . $quoted . '\s*$~mu';

			// Head
			$next_head = preg_replace( $pattern, '', $head, -1, $count_head );
			if ( null !== $next_head && 0 < $count_head ) {
				$head     = $next_head;
				$removed += (int) $count_head;
			}

			// Tail
			if ( '' !== $tail ) {
				$next_tail = preg_replace( $pattern, '', $tail, -1, $count_tail );
				if ( null !== $next_tail && 0 < $count_tail ) {
					$tail     = $next_tail;
					$removed += (int) $count_tail;
				}
			}
		}

		// Reassemble with the managed region untouched
		$out = ( 0 < $managed_len )
		? ( $head . $managed_body . $tail )
		: $head;

		return array(
			'text'    => $this->postprocess( $out ),
			'removed' => $removed,
		);
	}


	/**
	 * Collapse excessive blanks and ensure single trailing newline.
	 *
	 * @param string $buf LF-normalized text.
	 * @return string
	 */
	private function postprocess( $buf ) {
		$buf = Text::collapse_excess_blanks( (string) $buf );
		return Text::ensure_single_trailing_newline( $buf );
	}
}
