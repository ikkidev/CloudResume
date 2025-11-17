<?php

namespace NewFoldLabs\WP\Module\SSO;

class SSO_REST_Controller extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string
	 */
	protected $namespace = 'newfold-sso/v1';

	/**
	 * Registers the settings route
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/sso',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);

	}

	/**
	 * Generate a token and login URL.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_item( $request ) {

		// Create token
		$token = SSO_Helpers::generateToken( get_current_user_id() );

		// Save token
		SSO_Helpers::saveToken( $token );

		$query_string = http_build_query(
			[
				'action' => SSO_Helpers::ACTION,
				'token'  => $token,
			]
		);

		// Return token and the magic login URL
		return rest_ensure_response( admin_url( '/admin-ajax.php' ) . "?{$query_string}" );

	}

	/**
	 * Check permissions for routes.
	 *
	 * @return bool|\WP_Error
	 */
	public function check_permission() {

		// User must have both the permission and ability to login.
		if ( ! current_user_can( 'read' ) ) {
			return new \WP_Error( 'rest_forbidden_context', __( 'Sorry, you are not allowed to access this endpoint.', 'wp-module-sso' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

}
