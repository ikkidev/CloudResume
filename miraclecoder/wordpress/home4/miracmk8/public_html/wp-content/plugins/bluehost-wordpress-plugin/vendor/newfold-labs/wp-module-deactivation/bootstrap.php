<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Deactivation\Deactivation;

if ( function_exists( 'add_action' ) ) {
	add_action(
		'newfold_container_set',
		function ( Container $container ) {

			if ( ! defined( 'NFD_DEACTIVATION_DIR' ) ) {
				define( 'NFD_DEACTIVATION_DIR', __DIR__ );
			}

			new Deactivation( $container );
		}
	);
}
