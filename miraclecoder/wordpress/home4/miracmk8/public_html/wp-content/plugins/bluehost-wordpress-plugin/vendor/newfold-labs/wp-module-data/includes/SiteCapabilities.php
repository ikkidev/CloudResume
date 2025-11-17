<?php

namespace NewfoldLabs\WP\Module\Data;

use NewfoldLabs\WP\Module\Data\API\Capabilities;
use NewfoldLabs\WP\Module\Data\Helpers\Transient;

/**
 * Class SiteCapabilities
 *
 * Class that handles fetching, caching, and checking of site capabilities.
 *
 * @package NewfoldLabs\WP\Module\Data
 */
class SiteCapabilities {

	/**
	 * Implementation of transient functionality which uses the WordPress options table when an object cache is present.
	 *
	 * @var Transient
	 */
	protected $transient;

	/**
	 * Constructor.
	 *
	 * @param ?Transient $transient Inject instance of Transient class.
	 */
	public function __construct( ?Transient $transient = null ) {
		$this->transient = $transient ?? new Transient();
	}

	/**
	 * Get the value of a capability.
	 *
	 * @used-by \NewfoldLabs\WP\Module\AI\SiteGen\SiteGen::check_capabilities()
	 * @used-by \NewfoldLabs\WP\Module\AI\Utils\AISearchUtil::check_capabilities()
	 * @used-by \NewfoldLabs\WP\Module\AI\Utils\AISearchUtil::check_help_capability()
	 * @used-by \NewfoldLabs\WP\Module\ECommerce\ECommerce::__construct()
	 * @used-by \NewfoldLabs\WP\Module\HelpCenter\CapabilityController::get_capability()
	 * @used-by \NewfoldLabs\WP\Module\Onboarding\Data\Config::get_site_capability()
	 *
	 * @param string $capability Capability name.
	 */
	public function get( string $capability ): bool {
		return $this->exists( $capability ) && $this->all()[ $capability ];
	}

	/**
	 * Merge a new list of capabilities into the existing list and save.
	 *
	 * @used-by Capabilities::update()
	 *
	 * @param array<string, bool> $capabilities
	 *
	 * @return bool True if the value was changed, false otherwise.
	 */
	public function update( array $capabilities ): bool {
		$updated_capabilities = array_merge( $this->all( false ), $capabilities );
		return $this->set( $updated_capabilities );
	}

	/**
	 * Save a list of capabilities, overwriting the existing list.
	 *
	 * @used-by self::fetch()
	 * @used-by Capabilities::update()
	 *
	 * @param array<string, bool> $capabilities
	 *
	 * @return bool True if the value was set, false otherwise.
	 */
	public function set( array $capabilities ): bool {
		return $this->transient->set( 'nfd_site_capabilities', $capabilities, 4 * constant( 'HOUR_IN_SECONDS' ) );
	}

	/**
	 * Get all capabilities.
	 *
	 * @param bool $fetch_when_absent Make a request to Hiive to fetch capabilities when not present in cache (default: `true`).
	 *
	 * @return array<string, bool> List of capabilities and if they are enabled or not.
	 */
	public function all( bool $fetch_when_absent = true ): array {
		$capabilities = $this->transient->get( 'nfd_site_capabilities' );

		if ( is_array( $capabilities ) ) {
			return $capabilities;
		}

		if ( $fetch_when_absent ) {
			$capabilities = $this->fetch();
			$this->set( $capabilities );
			return $capabilities;
		}

		return array();
	}

	/**
	 * Check if a capability exists.
	 *
	 * @param string $capability Capability name.
	 */
	protected function exists( string $capability ): bool {
		return array_key_exists( $capability, $this->all() );
	}

	/**
	 * Fetch all capabilities from Hiive.
	 *
	 * @return array<string, bool>
	 */
	protected function fetch(): array {
		$capabilities = array();

		$response = wp_remote_get(
			constant( 'NFD_HIIVE_URL' ) . '/sites/v1/capabilities',
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
					'Authorization' => 'Bearer ' . HiiveConnection::get_auth_token(),
				),
			)
		);

		if ( wp_remote_retrieve_response_code( $response ) === 200 && ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );
			if ( $data && is_array( $data ) ) {
				$capabilities = $data;
			}
		}

		return $capabilities;
	}
}
