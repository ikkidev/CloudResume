<?php

namespace NewfoldLabs\WP\Module\Migration\RestApi;

use NewfoldLabs\WP\Module\Migration\Services\EventService;
use NewfoldLabs\WP\Module\Migration\Services\InstaMigrateService;

/**
 * Class MigrateController
 */
class MigrateController {

	/**
	 * REST namespace
	 *
	 * @var string
	 */
	protected $namespace = 'newfold-migration/v1';

	/**
	 * REST base
	 *
	 * @var string
	 */
	protected $rest_base = '/migrate';

	/**
	 * Registers rest routes for MigrateController.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base . '/connect',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'connect_instawp' ),
					'permission_callback' => array( $this, 'rest_is_authorized_admin' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			$this->rest_base . '/events',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'send_event' ),
					'permission_callback' => array( $this, 'rest_is_authorized_admin' ),
					'args'                => array(
						'key'  => array(
							'required' => true,
							'type'     => 'string',
						),
						'data' => array(
							'required' => true,
							'type'     => 'object',
						),
					),
				),
			)
		);
	}

	/**
	 * Initiates the connnection with instawp plugin
	 *
	 * @return array
	 */
	public function connect_instawp() {
		$insta_service = new InstaMigrateService();
		$response      = $insta_service->run();

		if ( is_wp_error( $response ) ) {
			return $response;
		}
		return wp_send_json_success( $response );
	}

	/**
	 * Sends an application-level event to the Hiive endpoint.
	 *
	 * @param \WP_REST_Request $request The REST request containing 'key' (string) and 'data' (array).
	 *
	 * @return \WP_REST_Response|\WP_Error Success response on success, WP_Error on failure.
	 */
	public function send_event( \WP_REST_Request $request ) {
		$key  = $request->get_param( 'key' );
		$data = $request->get_param( 'data' );

		$result = EventService::send_application_event( $key, $data );

		if ( is_wp_error( $result ) ) {
			return $result;
		}
		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}

	/**
	 * Confirm REST API caller has ADMIN user capabilities.
	 *
	 * @return boolean
	 */
	public static function rest_is_authorized_admin() {
			$admin = 'manage_options';
			return \is_user_logged_in() && \current_user_can( $admin );
	}
}
