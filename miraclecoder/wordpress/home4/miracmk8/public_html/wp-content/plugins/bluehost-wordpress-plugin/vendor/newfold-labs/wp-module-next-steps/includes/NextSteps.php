<?php

namespace NewfoldLabs\WP\Module\NextSteps;

use NewfoldLabs\WP\ModuleLoader\Container;
use NewfoldLabs\WP\Module\NextSteps\RedirectHelper;
use NewfoldLabs\WP\Module\NextSteps\PlanFactory;

use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * NextSteps - Main module class for managing next steps functionality
 *
 * This class handles all the core functionalities for the Next Steps module,
 * including widget and portal rendering, asset management, and integration
 * with the RedirectHelper system for plugin-dependent tasks.
 *
 * Key Features:
 * - Next Steps widget and portal rendering
 * - Asset management and localization
 * - Integration with RedirectHelper for plugin-dependent tasks
 * - REST API endpoints for next steps management
 *
 * @package NewfoldLabs\WP\Module\NextSteps
 * @since 1.0.0
 * @author Newfold Labs
 */
class NextSteps {
	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Steps API class instance.
	 *
	 * @var StepsApi
	 */
	protected static $steps_api;

	/**
	 * Constructor for the NextSteps class.
	 *
	 * @param Container $container The module container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		new PlanFactory();
		new PlanSwitchTriggers( $container );
		new TaskCompletionTriggers( $container );
		self::$steps_api = new StepsApi();

		\add_action( 'rest_api_init', array( $this, 'init_steps_apis' ) );
		\add_action( 'admin_enqueue_scripts', array( __CLASS__, 'nextsteps_widget' ) );
		\add_action( 'admin_enqueue_scripts', array( __CLASS__, 'nextsteps_portal' ) );

		// Initialize plugin redirect functionality
		RedirectHelper::init();

		new I18nService( $container );
		if ( is_admin() ) {
			new NextStepsWidget();
		}
	}

	/**
	 * Initialize the Entitilement API Controller.
	 */
	public function init_steps_apis(): void {
		self::$steps_api->register_routes();
	}

	/**
	 * Add to the Newfold subnav.
	 *
	 * @param array $subnav The nav array.
	 * @return array The filtered nav array
	 */
	public static function add_nfd_subnav( $subnav ) {
		$next_steps = array(
			'title'    => __( 'Next Steps', 'wp-module-next-steps' ),
			'route'    => 'next-steps',
			'priority' => 10,
			'callback' => array( __CLASS__, 'render_next_steps_page' ),
		);
		array_push( $subnav, $next_steps );
		return $subnav;
	}

	/**
	 * Render "NextSteps" page root
	 *
	 * @return void
	 */
	public static function render_next_steps_page() {
		echo '<div id="nfd-next-steps-app"></div>';
	}

	/**
	 * Enqueue widget app assets.
	 */
	public static function nextsteps_widget() {
		// Always register assets for extensibility (other modules might depend on them)
		$asset_file = NFD_NEXTSTEPS_DIR . '/build/next-steps-widget/bundle.asset.php';
		$build_dir  = NFD_NEXTSTEPS_PLUGIN_URL . 'vendor/newfold-labs/wp-module-next-steps/build/next-steps-widget/';

		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;
		} else {
			return;
		}

		$style_deps  = array(
			container()->plugin()->id . '-style',
		);
		$script_deps = array(
			container()->plugin()->id . '-script',
			'newfold-hiive-events',
		);
		// check if woo is active to determins if quick-add-product should be enqueued
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && wp_script_is( 'quick-add-product', 'registered' ) ) {
			array_push( $script_deps, 'quick-add-product' ); // from ecommerce module
			array_push( $style_deps, 'quick-add-product' ); // from ecommerce module
		}
		\wp_register_script(
			'next-steps-widget',
			$build_dir . 'bundle.js',
			array_merge(
				$asset['dependencies'],
				$script_deps,
			),
			$asset['version'],
			true
		);

		\wp_register_style(
			'next-steps-widget-style',
			$build_dir . 'next-steps-widget.css',
			$style_deps,
			$asset['version']
		);

		// Only enqueue on dashboard pages
		$screen = \get_current_screen();
		if ( isset( $screen->id ) &&
			false !== strpos( $screen->id, 'dashboard' ) && // on dashboard page
			false === strpos( $screen->id, 'nfd-onboarding' ) // but not onboarding page
		) {
			\wp_enqueue_script( 'next-steps-widget' );
			\wp_enqueue_style( 'next-steps-widget-style' );

			// Get current plan data
			$current_plan    = PlanRepository::get_current_plan();
			$next_steps_data = $current_plan ? $current_plan->to_array() : array();

			\wp_localize_script(
				'next-steps-widget',
				'NewfoldNextSteps',
				$next_steps_data
			);
		}
	}

	/**
	 * Enqueue Fill app assets.
	 */
	public static function nextsteps_portal() {
		// Always register assets for extensibility (other modules might depend on them)
		$asset_file = NFD_NEXTSTEPS_DIR . '/build/next-steps-portal/bundle.asset.php';
		$build_dir  = NFD_NEXTSTEPS_PLUGIN_URL . 'vendor/newfold-labs/wp-module-next-steps/build/next-steps-portal/';

		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;
		} else {
			return;
		}

		$style_deps  = array(
			container()->plugin()->id . '-style',
		);
		$script_deps = array(
			container()->plugin()->id . '-script',
			'newfold-hiive-events',
			'nfd-portal-registry',
		);
		// check if woo is active to determins if quick-add-product should be enqueued
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			array_push( $script_deps, 'quick-add-product' );
			array_push( $style_deps, 'quick-add-product' );
		}

		\wp_register_script(
			'next-steps-portal',
			$build_dir . 'bundle.js',
			array_merge(
				$asset['dependencies'],
				$script_deps,
			),
			$asset['version'],
			true
		);

		\wp_register_style(
			'next-steps-portal-style',
			$build_dir . 'next-steps-portal.css',
			$style_deps,
			$asset['version']
		);

		// Only enqueue on plugin pages
		$screen = \get_current_screen();
		if ( isset( $screen->id ) && false !== strpos( $screen->id, container()->plugin()->id ) ) {
			\wp_enqueue_script( 'next-steps-portal' );
			\wp_enqueue_style( 'next-steps-portal-style' );

			// Get current plan data
			$current_plan    = PlanRepository::get_current_plan();
			$next_steps_data = $current_plan ? $current_plan->to_array() : array();

			\wp_localize_script(
				'next-steps-portal',
				'NewfoldNextSteps',
				$next_steps_data
			);
		}
	}
}
