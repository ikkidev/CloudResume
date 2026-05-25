<?php

namespace NewfoldLabs\WP\Module\ComingSoon;

/**
 * Admin library class
 */
final class PrePublishModal {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( isComingSoonActive() ) {
			\add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'register_assets' ) );
		}
	}

	/**
	 * Register assets.
	 */
	public static function register_assets() {
		$asset_file = NFD_COMING_SOON_BUILD_DIR . '/prePublishWarning/bundle.asset.php';

		if ( is_readable( $asset_file ) ) {

			$asset = include_once $asset_file;

			\wp_register_script(
				'nfd-coming-soon-pre-publish-warning',
				NFD_COMING_SOON_BUILD_URL . '/prePublishWarning/bundle.js',
				array_merge( $asset['dependencies'], array() ),
				$asset['version'],
				true
			);

			ComingSoon::load_js_translations(
				'nfd-coming-soon-pre-publish-warning',
				'wp-module-coming-soon',
				NFD_COMING_SOON_DIR . '/languages'
			);

			\wp_enqueue_script( 'nfd-coming-soon-pre-publish-warning' );
		}
	}
}
