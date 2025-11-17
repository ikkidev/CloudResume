<?php
namespace NewfoldLabs\WP\Module\Migration\Services;

/**
 * Utility Service
 */
class UtilityService {
	/**
	 * Get the api key from worker
	 *
	 * @param string $brand name of the brand
	 */
	public static function get_insta_api_key( $brand ) {
		$insta_cf_worker = NFD_MIGRATION_PROXY_WORKER . '/token?brand=' . $brand;
		$insta_cf_data   = wp_remote_get(
			$insta_cf_worker,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
					'PHP_VERSION'   => PHP_VERSION,
					'migration_key' => true,
					'site_url'      => get_option( 'siteurl', '' ),
				),
			)
		);
		$insta_response  = json_decode( wp_remote_retrieve_body( $insta_cf_data ) );

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		return $insta_response ? base64_decode( $insta_response->data ) : '';
	}

	/**
	 * Get migration status and source url by instaWp api
	 *
	 * @param string $migrate_group_uuid migration group id (it is stored in instawp_last_migration_details option).
	 * @return array
	 */
	public static function get_migration_data( $migrate_group_uuid ) {
		if ( ! empty( $migrate_group_uuid ) ) {
			$token = self::get_insta_api_key( BRAND_PLUGIN );
			if ( $token ) {
				$response = wp_remote_get(
					'https://app.instawp.io/api/v2/migrates-v3/status/' . $migrate_group_uuid,
					array(
						'headers' => array(
							'Authorization' => 'Bearer ' . $token,
						),
					)
				);
				if ( wp_remote_retrieve_response_code( $response ) === 200 && ! is_wp_error( $response ) ) {
					$body = wp_remote_retrieve_body( $response );
					return json_decode( $body, true );
				}
			}
		}

		return array();
	}
}
