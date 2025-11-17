<?php
/**
 * REST API endpoint, `wp-json/newfold-data/v1/capabilities`, for pushing capabilities from Hiive.
 *
 * @see \NewfoldLabs\WP\Module\Data\SiteCapabilities
 */

namespace NewfoldLabs\WP\Module\Data\API;

use NewfoldLabs\WP\Module\Data\SiteCapabilities;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;
use WP_REST_Response;

/**
 * REST API controller endpoint to push capabilities from Hiive.
 */
class Capabilities extends WP_REST_Controller {

	/** @var SiteCapabilities $site_capabilities */
	protected $site_capabilities;

	/**
	 * Constructor
	 *
	 * @param SiteCapabilities $site_capabilities The class that loads and saves the capabilities.
	 */
	public function __construct( SiteCapabilities $site_capabilities ) {
		$this->site_capabilities = $site_capabilities;

		$this->namespace = 'newfold-data/v1';
		$this->rest_base = 'capabilities';
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @hooked rest_api_init
	 *
	 * @see register_rest_route()
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'args' => array(),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Check permissions for routes.
	 *
	 * The Hiive request is authenticated in {@see Data::authenticate()} and sets the current user to an administrator.
	 *
	 * @see \NewfoldLabs\WP\Module\Data\Data::authenticate()
	 *
	 * @return bool|WP_Error
	 */
	public function check_permission() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden_context', __( 'Sorry, you are not allowed to access this endpoint.' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * POST or PUT to set, discarding existing capabilities, PATCH to update, preserving existing capabilities that are
	 * not in the request.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function update( $request ) {

		$existing_capabilities = $this->site_capabilities->all( false );
		$new_capabilities      = $request->get_json_params();

		'PATCH' === $request->get_method()
			? $this->site_capabilities->update( $new_capabilities ) // PATCH – update existing list.
			: $this->site_capabilities->set( $new_capabilities ); // POST or PUT – replace list.

		$added_capabilities     = array();
		$updated_capabilities   = array();
		$unchanged_capabilities = array();

		foreach ( $new_capabilities as $capability_name => $capability_value ) {
			if ( ! isset( $existing_capabilities[ $capability_name ] ) ) {
				$added_capabilities[ $capability_name ] = $capability_value;
				continue;
			}
			if ( $existing_capabilities[ $capability_name ] !== $capability_value ) {
				$updated_capabilities[ $capability_name ] = $capability_value;
			} else {
				$unchanged_capabilities[ $capability_name ] = $capability_value;
			}
			unset( $existing_capabilities[ $capability_name ] );
		}

		$removed_capabilities = array_diff_key( $existing_capabilities, $this->site_capabilities->all( false ) );

		$unchanged_capabilities = array_diff(
			array_merge(
				$unchanged_capabilities,
				$existing_capabilities
			),
			$removed_capabilities
		);

		$status = empty( $added_capabilities ) && empty( $updated_capabilities ) && empty( $removed_capabilities )
			? 200 // No changes.
			: 201; // Changes.

		return new WP_REST_Response(
			array(
				'added'     => $added_capabilities,
				'updated'   => $updated_capabilities,
				'removed'   => $removed_capabilities,
				'unchanged' => $unchanged_capabilities,
			),
			$status
		);
	}
}
