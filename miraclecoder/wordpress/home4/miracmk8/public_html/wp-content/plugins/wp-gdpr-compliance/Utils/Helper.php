<?php
namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Helper
 * @TODO    move/split up to appropriate files - just here for now
 * @package WPGDPRC\Utils
 */
class Helper {

	/**
	 * Returns the current URL
	 * @return string
	 */
	public static function currentCleanUrl(): string {
		return remove_query_arg( static::listOneTimeArgs(), static::getCurrentUrl() );
	}

	/**
	 * @return bool
	 */
	public static function isDevSite(): bool {
		if ( function_exists( 'wp_get_environment_type' ) ) {
			return in_array( wp_get_environment_type(), [ 'development', 'staging', 'local' ], true );
		}
		return apply_filters( Plugin::PREFIX . '_develop_site', false );
	}

	/**
	 * Lists one time URL args
	 * @return array
	 */
	public static function listOneTimeArgs(): array {
		return [];
	}

	/**
	 * Gets current URL
	 * @return string
	 */
	public static function getCurrentUrl(): string {
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
            $protocol = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' );
            $host = esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) );
            $uri =  isset($_SERVER['REQUEST_URI']) ? '' : esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			return $protocol . '://' . $host . $uri;
        }

		return static::getSiteUrl();
	}

	/**
	 * Gets clean site URL (with trailing slash)
	 * @return string
	 */
	public static function getSiteUrl(): string {
		return rtrim( rtrim( get_site_url( get_current_blog_id() ) ), '/' ) . '/';
	}

	/**
	 * Strips obsolete parts from URL for pretty display
	 * @param string $url
	 * @param bool   $full
	 * @return string
	 */
	public static function stripUrl( string $url = '', bool $full = true ): string {
		$remove = [ 'https://', 'http://' ];
		if ( $full ) {
			$remove[] = 'www.';
		}
		return rtrim( str_replace( $remove, '', $url ), '/' );
	}

	/**
	 * Converts all array keys to lowercase keys
	 * @param array $array
	 * @param bool  $deep
	 * @return array
	 */
	public static function lowerKeys( array $array = [], bool $deep = false ): array {
		if ( ! is_iterable( $array ) ) {
			return $array;
		}
		foreach ( $array as $key => $value ) {
			unset( $array[ $key ] );
			$array[ strtolower( $key ) ] = $deep ? self::lowerKeys( $value, $deep ) : $value;
		}
		return $array;
	}

	/**
	 * Shorten way to get value from array
	 * @param array  $array
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public static function getArrayValue( array $array = [], string $key = '', $default = null ) {
		return ! empty( $array[ $key ] ) ? $array[ $key ] : $default;
	}


	/**
	 * Checks whether string starts with a given string
	 * @param string $haystack
	 * @param string $needle
	 * @return bool
	 */
	public static function startsWith( string $haystack = '', string $needle = '' ): bool {
		return ( substr( $haystack, 0, strlen( $needle ) ) === $needle );
	}

	/**
	 * Checks whether string ends with a given string
	 * @param string $haystack
	 * @param string $needle
	 * @return bool
	 */
	public static function endsWith( string $haystack = '', string $needle = '' ): bool {
		$length = strlen( $needle );
		return ( $length === 0 ) || ( substr( $haystack, -$length ) === $needle );
	}

	/**
	 * Gets string between $start & $end
	 * @param string $haystack
	 * @param string $start
	 * @param string $end
	 * @return bool|string
	 */
	public static function getBetween( string $haystack = '', string $start = '', string $end = '' ) {
		$haystack = ' ' . $haystack;
		$init     = strpos( $haystack, $start );
		if ( $init === false ) {
			return '';
		}

		$init  += strlen( $start );
		$length = strpos( $haystack, $end, $init ) - $init;
		return substr( $haystack, $init, $length );
	}

	/**
	 * Truncates string to given length
	 * @param string $string
	 * @param int    $length
	 * @return string
	 */
	public static function limitString( string $string = '', int $length = 150 ): string {
		$string = rtrim( trim( $string ) );
		if ( strlen( $string ) <= $length ) {
			return $string;
		}

		$string = wordwrap( $string, $length );
		$string = explode( "\n", $string, 2 );
		return rtrim( trim( (string) $string[0] ) );
	}

	/**
	 * @param string $string
	 * @param int    $length
	 * @param string $more
	 * @return string
	 */
	public static function shortenStringByWords( string $string = '', int $length = 20, string $more = '...' ): string {
		$words = preg_split( "/[\n\r\t ]+/", $string, $length + 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $words ) > $length ) {
			array_pop( $words );
			return implode( ' ', $words ) . $more;
		}
		return implode( ' ', $words );
	}

	/**
	 * Lists all text entities
	 * @param string $html
	 * @return string
	 */
	public static function getTxtEntities( string $html = '' ): string {
		return strtr( $html, array_flip( get_html_translation_table( HTML_ENTITIES ) ) );
	}

	/**
	 * Cleans the string from trailing & preceding spaces
	 * @param string $string
	 * @return string
	 */
	public static function cleanExtraSpaces( string $string = '' ): string {
		return trim( rtrim( $string ) );
	}

	/**
	 * @return array
	 */
	public static function getPluginData(): array {
		return get_plugin_data( WPGDPRC_ROOT_FILE );
	}

	/**
	 * @param string $hex
	 * @param int    $percent
	 * @return string
	 */
	public static function darkenHex( string $hex = '', int $percent = 255 ): string {
		// Steps should be between -255 and 255. Negative = darker, positive = lighter
		$percent = max( -255, min( 255, $percent ) );

		$brightness = -255; // darken
		$percent    = $percent * $brightness / 100;

		// Normalize into a six character long hex string
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) === 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Split into three parts: R, G and B
		$color_parts = str_split( $hex, 2 );
		$return      = '#';

		foreach ( $color_parts as $color ) {
			$color   = hexdec( $color ); // Convert to decimal
			$color   = max( 0, min( 255, $color + $percent ) ); // Adjust color
			$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
		}

		return $return;
	}

	/**
	 * Sanitizes array to safe strings
	 * @param array $array
	 * @return array
	 */
	public static function sanitizeStringArray( array $array = [] ): array {
		if ( empty( $array ) ) {
			return $array;
		}

		foreach ( $array as $key => $value ) {
			if ( is_object( $value ) ) {
				$value = (array) $value;
			}
			if ( is_array( $value ) ) {
				$array[ $key ] = self::sanitizeStringArray( $value );
				continue;
			}
			$array[ $key ] = sanitize_text_field( $value );
		}
		return $array;
	}

	/**
	 * @param string $string
	 * @param string $pattern
	 * @return bool|string
	 */
	public static function removePattern( string $string = '', string $pattern = '' ) {
		if ( empty( $string ) ) {
			return false;
		}

		preg_match( $pattern, $string, $matches );
		if ( empty( $matches ) ) {
			return false;
		}

		return preg_replace( $pattern, '', $string );
	}

	/**
	 * @param array  $keys
	 * @param array  $data
	 * @param string $default
	 * @return array
	 */
	public static function fillArray( array $keys = [], array $data = [], string $default = '' ): array {
		if ( empty( $keys ) || ! is_iterable( $keys ) ) {
			return [];
		}
		if ( empty( $data ) || ! is_iterable( $data ) ) {
			return $keys;
		}

		$result = [];
		foreach ( $keys as $key ) {
			$result[ $key ] = $data[ $key ] ?? $default;
		}
		return $result;
	}

	/**
	 * @param string $hook
	 * @return bool
	 */
	public static function filterExists( string $hook = '' ): bool {
		if ( empty( $hook ) ) {
			return false;
		}

		global $wp_filter;
		return isset( $wp_filter[ $hook ] );
	}

	/**
	 * Checks premium mode status and returns premium alternative value when needed.
	 * @param mixed $default Value to use when premium mode is NOT active
	 * @param mixed $premium Value to use when premium mode is active
	 * @return mixed
	 */
	public static function getPremiumAlternative( $default, $premium ) {
		return Settings::isPremium() ? $premium : $default;
	}

	/**
	 * Polyfill for array key first php 7.3
	 *
	 * @param array $arr
	 *
	 * @return int|string|null
	 */
	public static function arrayKeyFirst( array $arr ) {
		foreach ( $arr as $key => $unused ) {
			return $key;
		}
		return null;
	}
}
