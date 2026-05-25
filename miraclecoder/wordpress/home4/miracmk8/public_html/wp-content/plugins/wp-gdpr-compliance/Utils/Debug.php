<?php
namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Plugin;

/**
 * Class Debug
 * @package WPGDPRC\Utils
 */
class Debug {

	/**
	 * Determines if currently in debug mode
	 * @return bool
	 */
	public static function debugMode() {
		return isset( $_GET['debug'] ) && in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
	}

	/**
	 * Writes log to debug file
	 * @param mixed $log
	 * @param string $prepend
	 */
	public static function log( $log, $prepend = '' ) {
		if ( ! is_string( $log ) ) {
			$log = print_r( $log, true );
		}
		$path = apply_filters( Plugin::PREFIX . '_debug_path', self::getDebugPath() );

		if ( ! $path ) {
			return;
		}

		error_log( '[' . date( 'd-M-Y H:i:s e' ) . '] ' . trim( implode( ' ', [ $prepend, $log ] ) ) . "\n", 3, $path );

		// make sure the debug log isn't going to explode
		self::limitFile( $path, 2000 );
	}

	/**
	 * Gets path of debug log file
	 * @param string $name
	 * @return bool|string
	 */
	public static function getDebugPath( $name = 'debug.log' ) {
		$file = WP_CONTENT_DIR . '/' . $name;
		if ( ! is_file( $file ) ) {
			file_put_contents( $file, '' );
		}

		if ( in_array( strtolower( (string) WP_DEBUG_LOG ), [ 'true', '1', 1 ], true ) ) {
			return $file;
		}
		if ( is_string( WP_DEBUG_LOG ) ) {
			return WP_DEBUG_LOG;
		}
		return false;
	}

	/**
	 * Limits the file length to prevent server overload
	 * @param string $file_name
	 * @param int $limit
	 * @param string $remove start|end
	 * @return bool
	 */
	public static function limitFile( $file_name, $limit = 1000, $remove = 'start' ) {
		if ( ! is_file( $file_name ) ) {
			return false;
		}

		// convert file into lines for better slicing
		$lines = file( $file_name );
		if ( count( $lines ) < $limit ) {
			return true;
		}

		// negative offset if lines need to be removed from the start of the file
		$offset = $remove == 'start' ? -1 * $limit : 0;

		// slice lines, so only requested lines are left and put to the file (w = overwrite)
		$lines = array_slice( $lines, $offset, $limit, true );
		$file  = fopen( $file_name, 'w' );
		fwrite( $file, implode( '', $lines ) );
		fclose( $file );
		return true;
	}

}
