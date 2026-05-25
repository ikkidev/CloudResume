<?php

namespace NewfoldLabs\WP\Module\Migration\Data;

use function NewfoldLabs\WP\Context\getContext;

/**
 * Manages all the constants for the Migration module.
 */
class Constants {
	/**
	 * Constructor for the Constants class.
	 *
	 * @param object $container Plugin container instance.
	 */
	public function __construct( $container ) {
		// Basic plugin constants.
		defined( 'NFD_MIGRATION_PLUGIN_URL' ) || define( 'NFD_MIGRATION_PLUGIN_URL', $container->plugin()->url );
		defined( 'NFD_MIGRATION_DIR' ) || define( 'NFD_MIGRATION_DIR', __DIR__ );

		// Determine brand-specific values.
		$plugin_brand = $container->plugin()->id;
		$proxy_brand  = $plugin_brand;

		if ( 'atomic' === getContext( 'platform' ) ) {
			$plugin_brand = 'bh-cloud';
		}

		// Define brand-dependent constants.
		defined( 'NFD_MIGRATION_PROXY_WORKER' ) || define( 'NFD_MIGRATION_PROXY_WORKER', 'https://migrate.' . $proxy_brand . '.com' );
		defined( 'BRAND_PLUGIN' ) || define( 'BRAND_PLUGIN', $plugin_brand );
		defined( 'INSTAWP_MIGRATE_ENDPOINT' ) || define( 'INSTAWP_MIGRATE_ENDPOINT', 'migrate/' . $plugin_brand );
		defined( 'NFD_MIGRATION_BRAND_WHITELIST' ) || define( 'NFD_MIGRATION_BRAND_WHITELIST', array( 'bluehost', 'hostgator' ) );
	}
}
