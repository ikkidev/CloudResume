<?php

namespace NewfoldLabs\WP\Module\Staging;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Manages all the constants for the staging module.
 */
class Constants {
	/**
	 * Constructor for the Constants class.
	 *
	 * @param Container $container The module container.
	 */
	public function __construct( $container ) {
		if ( ! defined( 'NFD_STAGING_BUILD_DIR' ) ) {
			define( 'NFD_STAGING_BUILD_DIR', dirname( __DIR__, 1 ) . '/build' );
		}

		if ( ! defined( 'NFD_STAGING_BUILD_URL' ) ) {
			define( 'NFD_STAGING_BUILD_URL', $container->plugin()->url . 'vendor/newfold-labs/wp-module-staging/build' );
		}
	}
}
