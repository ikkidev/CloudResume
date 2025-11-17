<?php
/**
 * Brand plugins deactivation survey modal.
 *
 * @package NewfoldLabs\WP\Module\Deactivation
 */

namespace NewfoldLabs\WP\Module\Deactivation;

use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class DeactivationSurvey.
 */
class DeactivationSurvey {

	// public $container;
	public $strings;

	/**
	 * DeactivationSurvey constructor.
	 */
	public function __construct() {
		// $this->container = container();

		$defaults = array(
			'surveyAriaTitle'   => __( 'Plugin Deactivation Survey', 'wp-module-deactivation' ),
			'surveyTitle'       => sprintf(
				__( 'Thank you for using the %s plugin!', 'wp-module-deactivation' ),
				ucwords( container()->plugin()->id )
			),
			'surveyDesc'        => __( 'Please take a moment to let us know why you\'re deactivating this plugin.', 'wp-module-deactivation' ),
			'formAriaLabel'     => __( 'Plugin Deactivation Form', 'wp-module-deactivation' ),
			'label'             => __( 'Why are you deactivating this plugin?', 'wp-module-deactivation' ),
			'placeholder'       => __( 'Please share the reason here...', 'wp-module-deactivation' ),
			'submit'            => __( 'Submit & Deactivate', 'wp-module-deactivation' ),
			'submitAriaLabel'   => __( 'Submit and Deactivate Plugin', 'wp-module-deactivation' ),
			'cancel'            => __( 'Cancel', 'wp-module-deactivation' ),
			'cancelAriaLabel'   => __( 'Cancel Deactivation', 'wp-module-deactivation' ),
			'skip'              => __( 'Skip & Deactivate', 'wp-module-deactivation' ),
			'skipAriaLabel'     => __( 'Skip and Deactivate Plugin', 'wp-module-deactivation' ),
			'continue'          => __( 'Continue', 'wp-module-deactivation' ),
			'continueAriaLabel' => __( 'Continue Deactivation', 'wp-module-deactivation' ),
			'sureTitle'         => __( 'Are you sure you want to deactivate?', 'wp-module-deactivation' ),
			'deactivating'      => __( 'Deactivating', 'wp-module-deactivation' ),
			'sureDesc'          => sprintf(
				__( 'If the %s plugin is deactivated, these features will no longer work:', 'wp-module-deactivation' ),
				ucwords( container()->plugin()->id )
			),
			'sureCards'         => array(
				array(
					'title'     => sprintf(
						__( '%s Caching', 'wp-module-deactivation' ),
						ucwords( container()->plugin()->id )
					),
					'desc'      => __( 'Automatically clears the server page cache when your site updates', 'wp-module-deactivation' ),
					'condition' => 'window.NewfoldFeatures.features.performance',
				),
				array(
					'title'     => sprintf(
						__( '%s Staging', 'wp-module-deactivation' ),
						ucwords( container()->plugin()->id )
					),
					'desc'      => __( 'Create a staging copy of your site to safely test changes', 'wp-module-deactivation' ),
					'condition' => 'window.NewfoldFeatures.features.staging',
				),
				array(
					'title'     => __( 'WooCommerce Tools', 'wp-module-deactivation' ),
					'desc'      => __( 'Run campaigns and promotions on your store', 'wp-module-deactivation' ),
					'condition' => 'window.NewfoldRuntime.isWoocommerceActive',
				),
				array(
					'title'     => __( 'Wonder Blocks & Patterns Library', 'wp-module-deactivation' ),
					'desc'      => __( 'Dozens of beautiful block templates and patterns', 'wp-module-deactivation' ),
					'condition' => 'window.NewfoldFeatures.features.patterns',
				),
			),
			'sureHelp'          => sprintf(
				__( 'Need Help? Check the %1$shelp center%2$s for support.', 'wp-module-deactivation' ),
				'<a href="/wp-admin/admin.php?page=' . container()->plugin()->id . '#/help">',
				'</a>'
			),
		);

		// Merge defaults with container values from plugin
		// $this->strings = wp_parse_args(
		// $this->container->has( 'deactivation' ) ?
		// $this->container['deactivation'] :
		// array(),
		// $defaults
		// );
		$this->strings = $defaults;

		$this->deactivation_survey_assets();
		$this->deactivation_survey_runtime();
	}

	/**
	 * Enqueue deactivation survey assets.
	 */
	public function deactivation_survey_assets() {
		$assets_dir = container()->plugin()->url . 'vendor/newfold-labs/wp-module-deactivation/static/';

		// Accessible a11y dialog.
		wp_register_script(
			'nfd-deactivation-a11y-dialog',
			$assets_dir . 'js/a11y-dialog.min.js',
			array(),
			'8.0.4'
		);

		// Deactivation-survey.js.
		wp_enqueue_script(
			'nfd-deactivation-survey',
			$assets_dir . 'js/deactivation-survey.js',
			array( 'newfold-features', 'nfd-deactivation-a11y-dialog' ),
			container()->plugin()->version,
			true
		);

		// Styles.
		wp_enqueue_style(
			'nfd-deactivation-survey-style',
			$assets_dir . 'css/deactivation-survey.css',
			array(),
			container()->plugin()->version
		);
	}

	/**
	 * Localize deactivation survey runtime.
	 */
	public function deactivation_survey_runtime() {
		$plugin_slug = explode( '/', container()->plugin()->basename )[0];

		// Validate strings->cards via condition

		wp_localize_script(
			'nfd-deactivation-survey',
			'newfoldDeactivationSurvey',
			array(
				'eventsEndpoint' => \esc_url_raw( \rest_url() . 'newfold-data/v1/events/' ),
				'restApiNonce'   => wp_create_nonce( 'wp_rest' ),
				'brand'          => container()->plugin()->id,
				'pluginSlug'     => $plugin_slug,
				'strings'        => $this->strings,
			)
		);
	}
}
