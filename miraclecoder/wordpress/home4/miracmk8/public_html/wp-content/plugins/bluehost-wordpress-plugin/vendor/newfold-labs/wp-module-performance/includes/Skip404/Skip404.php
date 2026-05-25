<?php
/**
 * Skip404
 *
 * Manages registration/unregistration of a .htaccess fragment that prevents
 * WordPress 404 handling for static-file requests when the path looks like
 * a static asset but the file/dir isn't present. Uses the centralized
 * HtaccessApi fragment registry to ensure safe, debounced writes.
 *
 * @package NewfoldLabs\WP\Module\Performance\Skip404
 * @since 1.0.0
 */

namespace NewfoldLabs\WP\Module\Performance\Skip404;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Performance\OptionListener;
use NewfoldLabs\WP\Module\Htaccess\Api as HtaccessApi;
use NewfoldLabs\WP\Module\Performance\Skip404\Fragments\Skip404Fragment;

/**
 * Handles Skip 404 functionality.
 *
 * Registers/unregisters a fragment that short-circuits requests for typical
 * static extensions (css/js/images, etc.) so Apache stops rewrite processing
 * early instead of punting them into WP's 404 handler.
 *
 * @since 1.0.0
 */
class Skip404 {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Option name for skip 404 setting.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'newfold_skip_404_handling';

	/**
	 * Human-friendly marker text printed in BEGIN/END comments.
	 *
	 * @var string
	 */
	const MARKER = 'Newfold Skip 404 Handling for Static Files';

	/**
	 * Globally-unique fragment identifier used by the registry.
	 *
	 * @var string
	 */
	const FRAGMENT_ID = 'nfd.skip404.static';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Container $container The dependency injection container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		new OptionListener( self::OPTION_NAME, array( __CLASS__, 'maybe_add_rules' ) );

		// Bootstrap-register into the in-memory registry (no write) in maintenance contexts.
		// Admin (runs for real wp-admin screens)
		add_action( 'admin_init', array( __CLASS__, 'bootstrap_register' ), 20 );

		// REST (runs when REST is bootstrapped; constants definitely set)
		add_action( 'rest_api_init', array( __CLASS__, 'bootstrap_register' ), 20 );

		// AJAX (runs on admin-ajax.php requests)
		add_action(
			'init',
			function () {
				if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) {
					\NewfoldLabs\WP\Module\Performance\Skip404\Skip404::bootstrap_register();
				}
			},
			5
		);

		// CRON/CLI (admin_init doesn’t fire there)
		add_action(
			'init',
			function () {
				if ( ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() )
					|| ( defined( 'WP_CLI' ) && WP_CLI ) ) {
						\NewfoldLabs\WP\Module\Performance\Skip404\Skip404::bootstrap_register();
				}
			},
			5
		);

		add_filter( 'newfold_update_htaccess', array( $this, 'on_update_htaccess' ) );
		add_filter( 'newfold-runtime', array( $this, 'add_to_runtime' ), 100 );
	}

	/**
	 * Detect if the feature needs to be performed or not.
	 *
	 * @since 1.0.0
	 *
	 * @param Container $container Dependency injection container.
	 * @return bool
	 */
	public static function is_active( Container $container ): bool {
		return (bool) $container->has( 'isApache' ) && $container->get( 'isApache' );
	}

	/**
	 * Get value for SKIP404 option.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function get_value(): bool {
		return (bool) get_option( self::OPTION_NAME, true );
	}

	/**
	 * When updating .htaccess, also update our rules as appropriate.
	 *
	 * Also cleans up an older EPC option if set.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function on_update_htaccess(): void {
		self::maybe_add_rules( self::get_value() );

		// Remove the old option from EPC, if it exists.
		if ( $this->container->get( 'hasMustUsePlugin' ) && absint( get_option( 'epc_skip_404_handling', 0 ) ) ) {
			update_option( 'epc_skip_404_handling', 0 );
			delete_option( 'epc_skip_404_handling' );
		}
	}

	/**
	 * Conditionally add or remove .htaccess rules based on option value.
	 *
	 * @since 1.0.0
	 *
	 * @param bool|null $should_skip_404_handling If we should enable Skip 404.
	 * @return void
	 */
	public static function maybe_add_rules( $should_skip_404_handling ): void {
		(bool) $should_skip_404_handling ? self::add_rules() : self::remove_rules();
	}

	/**
	 * Register (or replace) our fragment with the current settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function add_rules(): void {
		HtaccessApi::register(
			new Skip404Fragment(
				self::FRAGMENT_ID,
				self::MARKER
			),
			true // queue apply to coalesce writes
		);
	}

	/**
	 * Unregister our fragment.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function remove_rules(): void {
		HtaccessApi::unregister( self::FRAGMENT_ID );
	}

	/**
	 * Add to Newfold SDK runtime.
	 *
	 * @since 1.0.0
	 *
	 * @param array $sdk SDK data.
	 * @return array SDK data.
	 */
	public function add_to_runtime( $sdk ): array {
		$values = array(
			'is_active' => $this->get_value(),
		);

		return array_merge( $sdk, array( 'skip404' => $values ) );
	}

	/**
	 * Populate the registry so reconciliation/apply can “see” this fragment
	 * in admin, cron, CLI, REST and AJAX requests. No writes are queued.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function bootstrap_register(): void {
		// Respect the feature toggle; if disabled, don't register.
		if ( false === self::get_value() ) {
			return;
		}

		// Register into the in-memory registry ONLY (no apply/write).
		HtaccessApi::register(
			new Skip404Fragment( self::FRAGMENT_ID, self::MARKER ),
			false
		);
	}
}
