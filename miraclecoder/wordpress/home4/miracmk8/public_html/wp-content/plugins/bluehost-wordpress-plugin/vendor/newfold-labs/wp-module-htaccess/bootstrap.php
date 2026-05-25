<?php
/**
 * Bootstrap for wp-module-htaccess.
 *
 * @package NewfoldLabs\WP\Module\Htaccess
 */

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Htaccess\Manager;
use function NewfoldLabs\WP\ModuleLoader\register;

if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			register(
				array(
					'name'     => 'wp-module-htaccess',
					'label'    => __( 'Htaccess', 'wp-module-htaccess' ),
					'callback' => function ( Container $container ) {
						if ( ! defined( 'NFD_MODULE_HTACCESS_DIR' ) ) {
							define( 'NFD_MODULE_HTACCESS_DIR', __DIR__ );
						}

						if ( ! defined( 'NFD_MODULE_HTACCESS_VERSION' ) ) {
							define( 'NFD_MODULE_HTACCESS_VERSION', '1.0.0' );
						}

						$manager = new Manager( $container );
						$manager->boot();
					},
					'isActive' => true,
					'isHidden' => true,
				)
			);
		}
	);
}
