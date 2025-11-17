<?php

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Activation\Activation;

if ( function_exists( 'add_action' ) ) {
	add_action(
		'newfold_container_set',
		function ( Container $container ) {
			new Activation( $container );
		}
	);
}
