<?php

use NewfoldLabs\WP\Module\ComingSoon\ComingSoon;
use NewfoldLabs\WP\Module\ComingSoon\Service;
use NewfoldLabs\WP\ModuleLoader\Container;
use WP_Forge\UpgradeHandler\UpgradeHandler;

use function NewfoldLabs\WP\ModuleLoader\register;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// Do not allow multiple copies of the module to be activated.
if ( defined( 'NFD_COMING_SOON_MODULE_VERSION' ) ) {
	return;
}

define( 'NFD_COMING_SOON_MODULE_VERSION', '2.1.2' );

require __DIR__ . '/includes/functions.php';

$old_coming_soon_module_version = get_option( 'nfd_coming_soon_module_version' );

// Allow the initial upgrade routine to run on the front-end, but only once.
if ( ! $old_coming_soon_module_version || is_admin() ) {
	$upgrade_handler = new UpgradeHandler(
		__DIR__ . '/upgrades',
		$old_coming_soon_module_version,
		NFD_COMING_SOON_MODULE_VERSION
	);

	if ( $upgrade_handler->maybe_upgrade() ) {
		// If an upgrade occurred, update the new version in the database to prevent running the routine(s) again.
		update_option( 'nfd_coming_soon_module_version', NFD_COMING_SOON_MODULE_VERSION, true );
	}
}

// Register the module
add_action(
	'plugins_loaded',
	function () {

		register(
			array(
				'name'     => 'coming-soon',
				'label'    => __( 'Coming Soon', 'wp-module-coming-soon' ),
				'callback' => function ( Container $container ) {
					if ( ! defined( 'NFD_COMING_SOON_BUILD_DIR' ) ) {
						define( 'NFD_COMING_SOON_BUILD_DIR', __DIR__ . '/build/' );
					}
					if ( ! defined( 'NFD_COMING_SOON_BUILD_URL' ) ) {
						define( 'NFD_COMING_SOON_BUILD_URL', $container->plugin()->url . 'vendor/newfold-labs/wp-module-coming-soon/build/' );
					}
					if ( ! defined( 'NFD_COMING_SOON_DIR' ) ) {
						define( 'NFD_COMING_SOON_DIR', __DIR__ );
					}
					$container->set(
						'comingSoon',
						$container->service(
							function () {
								return new Service();
							}
						)
					);

					return new ComingSoon( $container );
				},
				'isActive' => true,
				'isHidden' => true,
			)
		);
	}
);
