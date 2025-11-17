<?php
namespace NewfoldLabs\WP\Module\Migration\RestApi;

/**
 * Instantiate controllers and register routes.
 */
final class RestApi {

	/**
	 * Option settings
	 *
	 * @var array
	 */
	protected $options = array(
		'nfd_migrate_site' => 'boolean',
	);

	/**
	 * List of custom REST API controllers
	 *
	 * @var array
	 */
	protected $controllers = array(
		'NewfoldLabs\\WP\\Module\\Migration\\RestApi\\MigrateController',
	);

	/**
	 * Setup the custom REST API
	 */
	public function __construct() {
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
			$instance = new $controller();
			$instance->register_routes();
		}
		self::register_settings();
	}
	/**
	 * Register settings.
	 */
	public function register_settings() {
		foreach ( $this->options as $option => $type ) {
			\register_setting(
				'general',
				$option,
				array(
					'show_in_rest' => true,
					'type'         => $type,
					'description'  => __( 'NFD migration Options', 'wp-module-migration' ),
				)
			);
		}
	}
}
