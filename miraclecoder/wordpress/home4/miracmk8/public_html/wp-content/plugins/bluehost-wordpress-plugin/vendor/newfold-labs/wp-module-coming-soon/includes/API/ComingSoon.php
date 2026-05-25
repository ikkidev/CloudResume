<?php
/**
 * Coming Soon Module API
 *
 * @package NewfoldLabs\WP\Module\ComingSoon\API
 */

namespace NewfoldLabs\WP\Module\ComingSoon\API;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class ComingSoon
 *
 * @package NewfoldLabs\WP\Module\ComingSoon\API
 */
class ComingSoon {
	/**
	 * The namespace for the API.
	 *
	 * @var string
	 */
	private $namespace = 'newfold-coming-soon/v1';

	/**
	 * The coming soon service provider.
	 *
	 * @var \NewfoldLabs\WP\Module\ComingSoon\Service
	 */
	private $coming_soon_service;

	/**
	 * ComingSoon constructor.
	 */
	public function __construct() {
		$this->coming_soon_service = container()->get( 'comingSoon' );
		$this->register_routes();
	}

	/**
	 * Register ComingSoon API routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/status',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'check_permissions' ),
				'callback'            => array( $this, 'check_status' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/enable',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'permission_callback' => array( $this, 'check_permissions' ),
				'callback'            => array( $this, 'enable' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/disable',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'permission_callback' => array( $this, 'check_permissions' ),
				'callback'            => array( $this, 'disable' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/last-changed',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'check_permissions' ),
				'callback'            => array( $this, 'last_changed_timestamp' ),
			)
		);
	}

	/**
	 * Check if the coming soon page is enabled.
	 *
	 * @return array
	 */
	public function check_status() {
		return array( 'comingSoon' => $this->coming_soon_service->is_enabled() );
	}

	/**
	 * Enable the coming soon page.
	 *
	 * @return array
	 */
	public function enable() {
		$this->coming_soon_service->enable();
		return array( 'comingSoon' => true );
	}

	/**
	 * Disable the coming soon page.
	 *
	 * @return array
	 */
	public function disable() {
		$this->coming_soon_service->disable();
		return array( 'comingSoon' => false );
	}

	/**
	 * Get the last changed timestamp value.
	 *
	 * @return array
	 */
	public function last_changed_timestamp() {
		$last_changed = $this->coming_soon_service->get_last_changed_timestamp( true );
		if ( ! $last_changed ) {
			return array( 'lastChanged' => false );
		}

		return array( 'lastChanged' => $last_changed );
	}

	/**
	 * Check if the current user has permissions to access the API.
	 * Or if the service provider is not available, return an error.
	 *
	 * @return bool|\WP_Error
	 */
	public function check_permissions() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( 'rest_forbidden', esc_html__( 'You cannot access the resource.', 'wp-module-coming-soon' ), array( 'status' => 401 ) );
		}
		if ( ! container()->has( 'comingSoon' ) ) {
			return new \WP_Error( 'rest_forbidden', esc_html__( 'Coming Soon module service provider error.', 'wp-module-coming-soon' ), array( 'status' => 401 ) );
		}
		return true;
	}
}
