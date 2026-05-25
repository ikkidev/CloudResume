<?php

namespace NewfoldLabs\WP\Module\Migration\Services;

/**
 * Class for pagespeed calls.
 */
class HostingInfoService {
	/**
	 * Service endpoint.
	 *
	 * @var string $endpoint
	 */
	protected $endpoint = 'hostinginfo';
	/**
	 * Timeout for the service.
	 *
	 * @var int $timeout
	 */
	protected $timeout = 60;
	/**
	 * URL to check.
	 *
	 * @var string $url_to_check
	 */
	protected $url_to_check;

	/**
	 * Construct. Init basic parameters.
	 *
	 * @param string $url url to get speed index.
	 */
	public function __construct( $url ) {
		$this->url_to_check = $url;
	}

	/**
	 * Get url to check.
	 *
	 * @return string
	 */
	public function get_url_to_check() {
		return $this->url_to_check;
	}
	/**
	 * Get speed index.
	 *
	 * @return array
	 */
	public function get_info() {

		$args = array(
			'timeout' => $this->get_timeout(),
		);

		$response = wp_remote_get( $this->get_full_service_url(), $args );

		if ( wp_remote_retrieve_response_code( $response ) === 200 && ! is_wp_error( $response ) ) {
			$body_response = wp_remote_retrieve_body( $response );
			$data_response = json_decode( $body_response, true );

			if ( ! isset( $data_response['error'] ) && isset( $data_response['url'] ) ) {
				return array(
					'status' => 'success',
					'url'    => $data_response['url'] ?? '',
					'ip'     => $data_response['ip'] ?? '',
					'isp'    => $data_response['isp'] ?? '',
					'org'    => $data_response['org'] ?? '',
					'as'     => $data_response['as'] ?? '',
				);
			} else {
				return array(
					'status'  => 'error',
					'message' => __( 'Error decoding response', 'wp-module-migration' ),
				);
			}
		} elseif ( is_wp_error( $response ) ) {
			return array(
				'status'  => 'error',
				'message' => $response->get_error_message(),
			);
		} else {
			return array(
				'status'  => 'error',
				'message' => wp_remote_retrieve_response_code( $response ) . ' ' . wp_remote_retrieve_response_message( $response ),
			);
		}
	}

	/**
	 * Get base service url.
	 *
	 * @return string
	 */
	public function get_full_service_url() {
		$url_to_check = rawurlencode( $this->get_url_to_check() );
		$service_url  = esc_url( apply_filters( 'nfd_build_url', NFD_MIGRATION_PROXY_WORKER . "/$this->endpoint?url=$url_to_check" ) );

		return $service_url;
	}
	/**
	 * Get timeout for the service.
	 *
	 * @return int
	 */
	public function get_timeout() {
		return $this->timeout;
	}
}
