<?php

namespace NewfoldLabs\WP\Module\Migration\Steps;

use NewfoldLabs\WP\Module\Migration\Steps\AbstractStep;
use NewfoldLabs\WP\Module\Migration\Services\HostingInfoService;

/**
 * Connection to InstaWp step.
 *
 * @package wp-module-migration
 */
class SourceHostingInfo extends AbstractStep {

	/**
	 * Source host url.
	 *
	 * @var string Source host url.
	 */
	private $source_host_url;

	/**
	 * Source hosting details.
	 *
	 * @var array Source hosting details.
	 */
	private $hosting_info;

	/**
	 * Construct. Init basic parameters.
	 *
	 * @param string $source_host_url Source host url.
	 */
	public function __construct( $source_host_url ) {
		$this->source_host_url = $source_host_url;
		$this->set_step_slug( 'SourceHostingInfo' );
		$this->run();
	}

	/**
	 * Execute the step.
	 *
	 * @return void
	 */
	protected function run() {

		if ( ! empty( $this->source_host_url ) ) {

			$plain_domain = $this->get_plain_domain( $this->source_host_url );

			$hosting_info_service = new HostingInfoService( $plain_domain );

			$hosting_info = $hosting_info_service->get_info();

			if ( isset( $hosting_info['status'] ) && 'success' === $hosting_info['status'] ) {
				$this->hosting_info = $hosting_info;
				$this->success();
			} else {
				$this->set_response( array( 'message' => __( 'Source hosting details not retrieved correctly', 'wp-module-migration' ) ) );
				$this->retry();
			}
		}
	}

	/**
	 * Set the step as successful and store the API key.
	 *
	 * @return void
	 */
	protected function success() {
		parent::success();

		$this->set_data( 'SourceHostingData', $this->hosting_info );
	}


	/**
	 * Get the plain domain from a domain.
	 *
	 * @param string $domain The domain to get the plain domain from.
	 * @return string The plain domain.
	 */
	public function get_plain_domain( string $domain ): string {
		if ( ! preg_match( '#^https?://#', $domain ) ) {
			$domain = 'http://' . $domain;
		}
		return isset( $parsed['scheme'], $parsed['host'] ) ? $parsed['scheme'] . '://' . $parsed['host'] : $domain;
	}
}
