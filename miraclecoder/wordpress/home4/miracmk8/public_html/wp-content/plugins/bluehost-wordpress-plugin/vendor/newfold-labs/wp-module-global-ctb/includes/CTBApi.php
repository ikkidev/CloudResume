<?php

namespace NewFoldLabs\WP\Module\GlobalCTB;

use NewfoldLabs\WP\Module\Data\HiiveConnection;
use NewfoldLabs\WP\Module\Data\SiteCapabilities;
use WP_Error;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class CTBApi
 *
 * @package NewfoldLabs\CTB
 */
class CTBApi {

	/**
	 * Register notification routes.
	 */
	public static function registerRoutes() {

		// Add route for fetching a CTB
		register_rest_route(
			'newfold-ctb/v2',
			'/ctb/(?P<id>[a-zA-Z0-9-]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'callback'            => function ( \WP_REST_Request $request ) {
					$response = wp_remote_get(
						NFD_HIIVE_URL . '/sites/v2/ctb/' . $request->get_param( 'id' ) . '',
						array(
							'headers' => array(
								'Content-Type'  => 'application/json',
								'Accept'        => 'application/json',
								'Authorization' => 'Bearer ' . HiiveConnection::get_auth_token(),
							),
							'timeout' => 20,
						)
					);

					if ( $response instanceof WP_Error ) {
						return $response;
					}

					return new \WP_REST_Response( json_decode( wp_remote_retrieve_body( $response ) ), wp_remote_retrieve_response_code( $response ) );
				},
			)
		);
	}
}
