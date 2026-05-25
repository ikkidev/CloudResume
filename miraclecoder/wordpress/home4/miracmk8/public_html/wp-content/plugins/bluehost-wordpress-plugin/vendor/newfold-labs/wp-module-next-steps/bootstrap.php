<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\NextSteps\NextSteps;
use function NewfoldLabs\WP\ModuleLoader\register;

// Do not allow multiple copies of the module to be active
if ( defined( 'NFD_NEXTSTEPS_MODULE_VERSION' ) ) {
	return;
}

define( 'NFD_NEXTSTEPS_MODULE_VERSION', '1.4.5' );

// Define constants immediately so they're available when classes are loaded
if ( ! defined( 'NFD_NEXTSTEPS_DIR' ) ) {
	define( 'NFD_NEXTSTEPS_DIR', __DIR__ );
}
if ( ! defined( 'NFD_NEXTSTEPS_BUILD_DIR' ) ) {
	define( 'NFD_NEXTSTEPS_BUILD_DIR', __DIR__ . '/build/' );
}

if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {

			register(
				array(
					'name'     => 'next-steps',
					'label'    => __( 'Next Steps', 'wp-module-next-steps' ),
					'callback' => function ( Container $container ) {
						if ( ! defined( 'NFD_NEXTSTEPS_PLUGIN_URL' ) ) {
							define( 'NFD_NEXTSTEPS_PLUGIN_URL', $container->plugin()->url );
						}
						if ( ! defined( 'NFD_NEXTSTEPS_PLUGIN_DIRNAME' ) ) {
							define( 'NFD_NEXTSTEPS_PLUGIN_DIRNAME', dirname( $container->plugin()->basename ) );
						}

						new NextSteps( $container );
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);
}
