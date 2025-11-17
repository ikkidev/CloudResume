<?php
namespace NewfoldLabs\WP\Module\Htaccess;

/**
 * Module configuration (constants & hooks).
 *
 * @since 1.0.0
 */
final class Config {

	/**
	 * Default marker label.
	 *
	 * @since 1.0.0
	 */
	const DEFAULT_MARKER = 'NFD Htaccess';

	/**
	 * Resolve the marker label, allowing a WP filter override.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function marker() {
		$marker = self::DEFAULT_MARKER;

		// Allow site owners / tests to override the label.
		if ( function_exists( 'apply_filters' ) ) {
			$marker = (string) apply_filters( 'nfd_htaccess_marker_label', $marker );
		}

		return $marker;
	}
}
