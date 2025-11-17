<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Marketplace\Marketplace;

use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			register(
				array(
					'name'     => 'marketplace',
					'label'    => __( 'Marketplace', 'wp-module-marketplace' ),
					'callback' => function ( Container $container ) {
						if ( ! defined( 'NFD_MARKETPLACE_DIR' ) ) {
							define( 'NFD_MARKETPLACE_DIR', __DIR__ );
						}
						new Marketplace( $container );
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);
}
