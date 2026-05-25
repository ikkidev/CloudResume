<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

use NewfoldLabs\WP\Module\Data\SiteCapabilities;

/**
 * APIs for cehcking if we have the capability to render Help Center
 */
class CapabilityController extends \WP_REST_Controller {
	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'nfd-help-center/v1';

	/**
	 * The base of this controller's route
	 *
	 * @var string
	 */
	protected $rest_base = 'capability';

	/**
	 * Register the routes for this objects of the controller
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_capability' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/brand',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_brand' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Get the capability from module data
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_capability() {
		$capability   = new SiteCapabilities();
		$help_enabled = $capability->get( 'canAccessHelpCenter' );

		return new \WP_REST_Response( $help_enabled, 200 );
	}

	/**
	 * Get the current brand from module data
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_brand() {
		$brand = NFD_HELPCENTER_PLUGIN_BRAND;
		if ( ! $brand ) {
			return new \WP_Error(
				'rest_not_found',
				__( 'We could not find the brand', 'wp-module-help-center' ),
				array( 'status' => 401 )
			);
		}

		return new \WP_REST_Response( $brand, 200 );
	}

	/**
	 * Check permissions for routes.
	 *
	 * @return \WP_Error|boolean
	 */
	public function check_permission() {
		if ( ! current_user_can( 'read' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				__( 'You must be authenticated to make this call', 'wp-module-help-center' ),
				array( 'status' => 401 )
			);
		}
		return true;
	}
}
