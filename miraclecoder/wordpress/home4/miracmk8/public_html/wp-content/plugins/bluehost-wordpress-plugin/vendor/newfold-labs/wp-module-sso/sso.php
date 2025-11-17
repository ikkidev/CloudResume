<?php

use NewFoldLabs\WP\Module\SSO\SSO_AJAX_Handler;
use NewFoldLabs\WP\Module\SSO\SSO_REST_Controller;

new SSO_AJAX_Handler();

add_action(
	'rest_api_init',
	function () {
		$instance = new SSO_REST_Controller();
		$instance->register_routes();
	}
);

add_action(
	'cli_init',
	function() {
		WP_CLI::add_command(
			'newfold sso',
			'NewFoldLabs\WP\Module\SSO\SSO_CLI',
			array(
				'shortdesc' => 'Single sign-on functionality for WordPress.',
				'longdesc'  => 'Handle single sign-on from Newfold hosting platforms and get magic link.' .
							PHP_EOL . 'Associative Args: --username --role --email --id --min=MINUTES_UNTIL_EXPIRE --url-only',
			)
		);
	}
);

\add_action( 'init',
	function() {
		\load_plugin_textdomain(
			'wp-module-sso',
			false,
			NFD_SSO_DIR . '/languages'
		);
	}, 
	100 
);
