<?php
/**
 * Next Steps Widget.
 *
 * @package WPModuleNextSteps
 */

namespace NewfoldLabs\WP\Module\NextSteps;

use function NewfoldLabs\WP\ModuleLoader\container;
use function NewfoldLabs\WP\Context\getContext;

/**
 * NewfoldLabs\WP\Module\NextSteps\NextStepsWidget
 *
 * Adds a Next Steps dashboard widget to the WordPress dashboard.
 */
class NextStepsWidget {
	/**
	 * The id of this widget.
	 */
	const ID = 'nfd_next_steps_widget';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register the widget
		\add_action( 'wp_dashboard_setup', array( __CLASS__, 'init' ), 1 );
	}
	/**
	 * Hook to wp_dashboard_setup to add the widget.
	 */
	public static function init() {

		// Register the widget
		\wp_add_dashboard_widget(
			self::ID,
			__( 'Next Steps', 'wp-module-next-steps' ),
			array( __CLASS__, 'widget_render' ),
			null,
			null,
			'column3',
			'high'
		);

		\add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	/**
	 * Render the widget
	 */
	public static function widget_render() {
		$view_file = NFD_NEXTSTEPS_DIR . '/includes/views/next-steps.php';
		include $view_file;
	}

	/**
	 * Load scripts/styles needed for this dashboard widget.
	 *
	 * @return void
	 */
	public static function assets() {
		// enqueue the bluehost-style stylesheet
		// this is registered in Admin.php > assets()
		// \wp_enqueue_style( 'bluehost-style' );
	}

	/**
	 * Add UTM params to a URL
	 *
	 * @param string $url the URL
	 * @return string the URL with UTM params
	 */
	public static function add_utm_params( $url ) {
		$data        = array(
			'utm_source' => 'wp-admin/index.php?widget=next_steps_widget',
			'utm_medium' => 'brand_plugin',
		);
		return $url .= '?' . http_build_query( $data );
	}
}
