<?php

use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) ) {

	add_action(
		'plugins_loaded',
		function () {

			register(
				[
					'name'     => 'notifications',
					'label'    => __( 'Notifications', 'wp-module-notifications' ),
					'callback' => function () {
						require __DIR__ . '/notifications.php';
					},
					'isActive' => true,
					'isHidden' => true,
				]
			);

		}
	);

}
