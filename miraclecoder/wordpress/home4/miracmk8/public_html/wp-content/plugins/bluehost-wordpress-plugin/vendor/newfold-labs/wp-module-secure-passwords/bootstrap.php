<?php
/**
 * Secure Passwords module bootstrap.
 *
 * This module requires users to use more secure passwords by preventing the use of
 * any passwords exposed in data breaches.
 *
 * @package Newfold\WP\Module\Secure_Passwords
 */
use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) ) {

	add_action(
		'plugins_loaded',
		function () {

			register(
				[
					'name'     => 'secure-passwords',
					'label'    => __( 'Secure Passwords', 'newfold-secure-passwords' ),
					'callback' => function ( Container $container ) {
						require __DIR__ . '/secure-passwords.php';
					},
					'isActive' => true,
					'isHidden' => false,
				]
			);

		}
	);

}