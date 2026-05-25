<?php
/**
 * Deactivation class file.
 *
 * @package NewfoldLabs\WP\Module\Deactivation
 */

namespace NewfoldLabs\WP\Module\Deactivation;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\Deactivation\DeactivationSurvey;

/**
 * Deactivation class.
 */
class Deactivation {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param Container $container The plugin container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		// deactivation hook
		register_deactivation_hook(
			$container->plugin()->file,
			array( $this, 'on_deactivate' )
		);

		// Plugin deactivation survey.
		\add_action( 'init', array( __CLASS__, 'load_text_domain' ), 100 );
		add_action(
			'admin_head-plugins.php',
			function () {
				new DeactivationSurvey();
			}
		);
	}

	/**
	 * Handle deactivation.
	 *
	 * @return void
	 */
	public function on_deactivate() {
		// disable coming soon mode
		$this->disable_coming_soon();

		// clear relevant transients
		\delete_transient( 'newfold_marketplace' );
		\delete_transient( 'newfold_notifications' );
		\delete_transient( 'newfold_solutions' );

		// flush rewrite rules
		\flush_rewrite_rules();
	}

	/**
	 * Disable the coming soon page.
	 *
	 * @return void
	 */
	public function disable_coming_soon() {
		$coming_soon_service = $this->container->has( 'comingSoon' ) ? $this->container->get( 'comingSoon' ) : null;
		if ( $coming_soon_service && $coming_soon_service->is_enabled() ) {
			$coming_soon_service->disable();
		}
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function load_text_domain() {

		\load_plugin_textdomain(
			'wp-module-deactivation',
			false,
			NFD_DEACTIVATION_DIR . '/languages'
		);
	}
}
