<?php
namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Small text utilities for stable whitespace handling.
 *
 * @since 1.0.0
 */
final class Text {

	/**
	 * Normalize line endings to LF and optionally trim trailing newlines.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Input text.
	 * @param bool   $trim_trailing_newlines When true, trims trailing "\n".
	 * @return string
	 */
	public static function normalize_lf( $text, $trim_trailing_newlines = true ) {
		$out = str_replace( array( "\r\n", "\r" ), "\n", (string) $text );
		return $trim_trailing_newlines ? rtrim( $out, "\n" ) : $out;
	}

	/**
	 * Trim leading/trailing whitespace-only lines (preserves inner spacing).
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Input text.
	 * @return string
	 */
	public static function trim_surrounding_blank_lines( $text ) {
		$out = preg_replace( '/^\s+|\s+$/u', '', (string) $text );
		return ( null === $out ) ? '' : $out;
	}

	/**
	 * Ensure exactly one trailing newline.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Input text.
	 * @return string
	 */
	public static function ensure_single_trailing_newline( $text ) {
		return rtrim( (string) $text, "\r\n" ) . "\n";
	}

	/**
	 * Collapse sequences of 3+ blank lines to 2.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text LF-normalized text.
	 * @return string
	 */
	public static function collapse_excess_blanks( $text ) {
		return preg_replace( "/\n{3,}/", "\n\n", (string) $text );
	}

	/**
	 * Remove any inner fragment marker lines from a body (but keep other comments).
	 *
	 * Lines like:
	 *   # BEGIN Something
	 *   # END Something
	 *
	 * @since 1.0.0
	 *
	 * @param string $text Body text (LF-normalized or not).
	 * @return string Body without marker lines.
	 */
	public static function strip_inner_markers_from_body( $text ) {
		$txt   = self::normalize_lf( (string) $text, false );
		$lines = explode( "\n", $txt );
		$out   = array();

		foreach ( $lines as $line ) {
			if ( preg_match( '/^\s*#\s*(BEGIN|END)\b/i', $line ) ) {
				// Drop fragment wrapper lines only.
				continue;
			}
			$out[] = $line;
		}

		$out = implode( "\n", $out );
		// Clean up extra blank lines introduced by dropping markers.
		$out = self::collapse_excess_blanks( $out );
		return self::normalize_lf( $out, true ); // trim trailing newline for stable hash
	}

	/**
	 * Extract lines between "# BEGIN <marker>" and "# END <marker>" from a text buffer.
	 *
	 * Differences from WordPress core extract_from_markers():
	 * - Operates on a string buffer (not a filename) for reuse across classes.
	 * - Preserves ALL lines between markers (including comment lines and nested markers).
	 * - Does NOT trim or normalize away blank lines (callers can post-process if desired).
	 *
	 * @since 1.0.0
	 *
	 * @param string $buf    Full file contents. Newlines may be mixed; they will be normalized to LF internally.
	 * @param string $marker Marker label, e.g. "NFD Htaccess".
	 * @return string[] Lines between markers (without the outer BEGIN/END lines). Empty array if not found/invalid.
	 */
	public static function extract_from_markers_raw( $buf, $marker ) {
		$buf    = self::normalize_lf( (string) $buf, false );
		$marker = (string) $marker;

		if ( '' === $buf || '' === $marker ) {
			return array();
		}

		$begin = '/^\s*#\s*BEGIN\s+' . preg_quote( $marker, '/' ) . '\s*$/m';
		$end   = '/^\s*#\s*END\s+' . preg_quote( $marker, '/' ) . '\s*$/m';

		if ( ! preg_match( $begin, $buf, $mb, PREG_OFFSET_CAPTURE ) ) {
			return array();
		}
		if ( ! preg_match( $end, $buf, $me, PREG_OFFSET_CAPTURE ) ) {
			return array();
		}

		$start = $mb[0][1] + strlen( $mb[0][0] );
		// Skip exactly one newline after the BEGIN line (if present), to start cleanly on the next line.
		if ( isset( $buf[ $start ] ) && "\n" === $buf[ $start ] ) {
			++$start;
		}
		$stop = $me[0][1];
		if ( $stop <= $start ) {
			return array();
		}

		$inside = substr( $buf, $start, $stop - $start );
		$inside = rtrim( $inside, "\n" ); // keep intra-block formatting, drop trailing NL artifacts.

		return explode( "\n", $inside );
	}

	/**
	 * Convenience: same as extract_from_markers_raw(), but returns a string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $buf    Full file contents.
	 * @param string $marker Marker label.
	 * @return string Inside text (without outer markers), or '' if not found/invalid.
	 */
	public static function extract_from_markers_text( $buf, $marker ) {
		$lines = self::extract_from_markers_raw( $buf, $marker );
		return empty( $lines ) ? '' : implode( "\n", $lines );
	}

	/**
	 * Canonicalize a managed block body for hashing:
	 * - Removes the two in-block header lines ("Managed by...", "STATE sha256: ...")
	 * - Removes a single blank separator line that may follow those headers
	 * - Keeps nested fragment markers and all other content
	 * - Normalizes LF; trims trailing newlines only
	 *
	 * @since 1.0.0
	 *
	 * @param string|string[] $body_or_lines Body as string or array of lines.
	 * @return string Canonical string for hashing.
	 */
	public static function canonicalize_managed_body_for_hash( $body_or_lines ) {
		$lines = is_array( $body_or_lines )
			? $body_or_lines
			: explode( "\n", self::normalize_lf( (string) $body_or_lines, false ) );

		// Drop in-block header lines if present.
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*Managed by\b/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		if ( isset( $lines[0] ) && preg_match( '/^\s*#\s*STATE\s+sha256:/i', $lines[0] ) ) {
			array_shift( $lines );
		}
		// Optional single blank line after the headers.
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

		// Normalize once more and remove trailing NLs for a stable hash.
		return rtrim( self::normalize_lf( $canon, false ), "\n" );
	}
}
