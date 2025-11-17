<?php
namespace NewfoldLabs\WP\Module\Migration\Services;

use NewFoldLabs\WP\Module\SSO\SSO_Helpers;
/**
 * Migration SSO service
 */
class MigrationSSO {

	/**
	 * Get SSO link
	 */
	public static function get_magic_login_url() {
		$args = array(
			'role'    => 'administrator',
			'orderby' => 'ID',
			'order'   => 'ASC',
			'number'  => 1, // Limit the query to 1 user
			'fields'  => 'ID', // Retrieve only the ID field
		);

		$user_query = new \WP_User_Query( $args );

		// Get the results
		$user_ids = $user_query->get_results();

		// Create token
		$token = SSO_Helpers::generateToken( $user_ids[0] );

		// Save token
		SSO_Helpers::saveToken( $token );

		$query_string = http_build_query(
			array(
				'action' => SSO_Helpers::ACTION,
				'token'  => $token,
			)
		);

		$response = rest_ensure_response( admin_url( '/admin-ajax.php' ) . "?{$query_string}" );
		return $response;
	}
}
