<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

use NewfoldLabs\WP\Module\HelpCenter\Util;

/**
 * APIs for getting the result from the AI service
 */
class UserInteractionController extends \WP_REST_Controller {
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
	protected $rest_base = 'feedback';

	/**
	 * Register the routes for this objects of the controller
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'post_feedback' ),
					'args'                => array(
						'post_id' => array(
							'required' => true,
							'type'     => 'integer',
						),
						'status'  => array(
							'required' => true,
							'type'     => 'string',
						),
					),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Proxy to the AI service to get the responses.
	 *
	 * @param \WP_REST_Request $request Request object
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function post_feedback( \WP_REST_Request $request ) {
		$post_id = $request['post_id'];
		$status  = $request['status'];

		$response = Util::post_feedback( $post_id, $status );

		if ( ! $response ) {
			return new \WP_Error( 'ServerError', $response['error'] );
		}

		return new \WP_REST_Response( true, 200 );
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
