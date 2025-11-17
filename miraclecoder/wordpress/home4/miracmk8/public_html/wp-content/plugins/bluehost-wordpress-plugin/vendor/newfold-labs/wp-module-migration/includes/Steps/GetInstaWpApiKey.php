<?php

namespace NewfoldLabs\WP\Module\Migration\Steps;

use NewfoldLabs\WP\Module\Migration\Steps\AbstractStep;
use NewfoldLabs\WP\Module\Data\Helpers\Encryption;
use NewfoldLabs\WP\Module\Migration\Services\UtilityService;

/**
 * Get InstaWp api key step.
 *
 * @package wp-module-migration
 */
class GetInstaWpApiKey extends AbstractStep {
	/**
	 * InstaWP Connect plugin API key used for connecting the instaWP plugin
	 *
	 * @var $insta_api_key
	 */
	private $insta_api_key = '';

	/**
	 * Encryption instance
	 *
	 * @var NewfoldLabs\WP\Module\Data\Helpers\Encryption instance
	 */
	protected $encrypter;

	/**
	 * Construct. Init basic parameters.
	 */
	public function __construct() {
		$this->set_step_slug( 'GetInstaWpApiKey' );
		$this->set_max_retries( 2 );
		$this->encrypter = new Encryption();
		$this->set_status( $this->statuses['running'] );
		$this->run();
	}

	/**
	 * Execute the step.
	 *
	 * @return void
	 */
	protected function run() {
		$this->insta_api_key = $this->encrypter->decrypt( get_option( 'newfold_insta_api_key', false ) );
		if ( ! $this->insta_api_key ) {
			$this->insta_api_key = UtilityService::get_insta_api_key( BRAND_PLUGIN );
			if ( $this->insta_api_key ) {
				$this->set_data( 'insta_api_key', $this->insta_api_key );
				update_option( 'newfold_insta_api_key', $this->encrypter->encrypt( $this->insta_api_key ) );
				$this->success();
			} else {
				$this->retry();
				$this->set_response(
					array(
						'message' => esc_html__( 'Cannot get Api key.', 'wp-module-migration' ),
					),
				);
			}
		} else {
			$this->set_data( 'insta_api_key', $this->insta_api_key );
			$this->success();
		}
	}
}
