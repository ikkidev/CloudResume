<?php

namespace NewfoldLabs\WP\Module\AI;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * The class to initialize and load the module
 */
class AI {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param Container $container The primary module container
	 * Instantiate controllers and register routes.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		\add_action( 'init', array( __CLASS__, 'load_text_domain' ), 100 );
	}

	/**
	 * Function to register custom API routes and controllers
	 */
	final public function register_routes() {
		$controllers = array(
			'NewfoldLabs\\WP\\Module\\AI\\RestApi\\AISearchController',
		);

		foreach ( $controllers as $controller ) {
			$instance = new $controller();
			$instance->register_routes();
		}
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function load_text_domain() {

		\load_plugin_textdomain(
			'wp-module-ai',
			false,
			NFD_MODULE_AI_DIR . '/languages'
		);
	}
}
