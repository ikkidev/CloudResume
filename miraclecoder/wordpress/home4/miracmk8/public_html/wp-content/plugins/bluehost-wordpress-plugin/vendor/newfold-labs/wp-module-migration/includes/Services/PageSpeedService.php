<?php

namespace NewfoldLabs\WP\Module\Migration\Services;

/**
 * Class for pagespeed calls.
 */
class PageSpeedService {
	/**
	 * Service endpoint.
	 *
	 * @var string $endpoint
	 */
	protected $endpoint = 'pagespeed';
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
	public function get_pagespeed() {
		$args = array(
			'timeout' => $this->get_timeout(),
		);

		$response = wp_remote_get( $this->get_full_service_url(), $args );
		if ( wp_remote_retrieve_response_code( $response ) === 200 && ! is_wp_error( $response ) ) {
			$body_response = wp_remote_retrieve_body( $response );
			$data_response = json_decode( $body_response, true );
			if ( isset( $data_response['speedIndex'] ) ) {
				return array(
					'status'     => 'success',
					'speedIndex' => $data_response['speedIndex'],
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
		$service_url  = esc_url( NFD_MIGRATION_PROXY_WORKER . "/$this->endpoint?url=$url_to_check" );

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
	/**
	 * Set timeout for the service.
	 *
	 * @param int $timeout timeout for the service.
	 */
	public function set_timeout( $timeout ) {
		$timeout       = intval( $timeout ) > 0 ? intval( $timeout ) : 60;
		$this->timeout = $timeout;
	}
}
