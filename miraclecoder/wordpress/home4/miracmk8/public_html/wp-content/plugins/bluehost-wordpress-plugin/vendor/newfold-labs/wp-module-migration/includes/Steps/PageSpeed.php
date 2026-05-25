<?php

namespace NewfoldLabs\WP\Module\Migration\Steps;

use NewfoldLabs\WP\Module\Migration\Steps\AbstractStep;
use NewfoldLabs\WP\Module\Migration\Services\PageSpeedService;

/**
 * Get Speed Index by PageSpeed api for url.
 *
 * @package wp-module-migration
 */
class PageSpeed extends AbstractStep {
	/**
	 * URL to get speed index.
	 *
	 * @var string $url
	 */
	protected $url = '';
	/**
	 * Construct. Init basic parameters.
	 *
	 * @param string $url url to get speed index.
	 * @param string $type type of the step.
	 */
	public function __construct( $url, $type = 'source' ) {
		$step_slug = 'PageSpeed_' . $type;
		$this->set_step_slug( $step_slug );
		$this->set_max_retries( 2 );
		$this->url = $url;
		$this->set_status( $this->statuses['running'] );
		$this->run();
	}

	/**
	 * Execute the step.
	 *
	 * @return void
	 */
	protected function run() {
		$pagespeed_service = new PageSpeedService( $this->url );
		$pagespeed         = $pagespeed_service->get_pagespeed();
		$this->set_data( 'url', $this->url );
		if ( isset( $pagespeed['speedIndex'] ) ) {
			$this->set_data( 'speedIndex', $pagespeed['speedIndex'] );
			$this->success();
		} else {
			$this->set_response(
				array(
					'message' => $pagespeed['message'],
				)
			);
			$this->retry();

		}
	}
}
