<?php

use NewfoldLabs\WP\Module\Migration\Migration;
use NewfoldLabs\WP\ModuleLoader\Container;

use function NewfoldLabs\WP\ModuleLoader\register;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			register(
				array(
					'name'     => 'migration',
					'label'    => __( 'Migration', 'wp-module-migration' ),
					'callback' => function ( Container $container ) {
						new Migration( $container );
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);
}
