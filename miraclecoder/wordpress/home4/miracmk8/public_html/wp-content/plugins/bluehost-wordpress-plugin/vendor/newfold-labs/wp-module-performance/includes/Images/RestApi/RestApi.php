<?php

namespace NewfoldLabs\WP\Module\Performance\Images\RestApi;

/**
 * Instantiate controllers and register routes.
 */
final class RestApi {
	/**
	 * Dependency injection container.
	 *
	 * @var \NewfoldLabs\WP\Container\Container
	 */
	protected $container;

	/**
	 * List of custom REST API controllers
	 *
	 * @var array
	 */
	protected $controllers = array(
		'NewfoldLabs\\WP\\Module\\Performance\\Images\\RestApi\\ImagesController',
	);


	/**
	 * Setup the custom REST API.
	 *
	 * @param \NewfoldLabs\WP\Container\Container $container Dependency injection container.
	 */
	public function __construct( $container ) {
		$this->container = $container;
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the custom REST API routes
	 */
	public function register_routes() {
		foreach ( $this->controllers as $controller ) {
			/**
			 * Get an instance of the WP_REST_Controller.
			 *
			 * @var $instance WP_REST_Controller
			 */
			$instance = new $controller( $this->container );
			$instance->register_routes();
		}
	}
}
