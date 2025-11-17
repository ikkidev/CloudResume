<?php

use NewfoldLabs\WP\Module\Facebook\Facebook;
use NewfoldLabs\WP\ModuleLoader\Container;

use function NewfoldLabs\WP\ModuleLoader\register;

// Exit if accessed directly
if (!defined('ABSPATH')) {
	return;
}

if (function_exists('add_action')) {
	add_action(
		'plugins_loaded',
		function () {
			register(
				[
					'name' => 'facebook',
					'label' => __('Facebook', 'wp-module-facebook'),
					'callback' => function (Container $container) {
						if (!defined('NFD_FACEBOOK_WORKER')) {
							define('NFD_FACEBOOK_WORKER', 'https://hiive.cloud/workers/facebook-connect/');
						}
						if (!defined('NFD_FACEBOOK_GRAPH_BASE')) {
							define('NFD_FACEBOOK_GRAPH_BASE', 'https://graph.facebook.com');
						}
						if (!defined('MONTH_IN_SECONDS')) {
							define('MONTH_IN_SECONDS', '60 * 60 * 24 * 30');
						}
						if ( ! defined( 'NFD_FACEBOOK_VERSION' ) ) {
							define( 'NFD_FACEBOOK_VERSION', '1.2.1' );
						}
						if ( ! defined( 'NFD_FACEBOOK_DIR' ) ) {
							define( 'NFD_FACEBOOK_DIR', __DIR__ );
						}						
						new Facebook($container);
					},
					'isActive' => true,
					'isHidden' => true,
				]
			);
		}
	);
}
