<?php

final class Elementor_Bimber_Extension {

	//const VERSION;
	//const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	//const MINIMUM_PHP_VERSION;

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		//add_action( 'plugins_loaded', [ $this, 'init' ] );
		add_action( 'after_setup_theme', [ $this, 'init' ] );
	}

	public function init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		// Controls.
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );

		// JS/CSS.
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		$base_uri = BIMBER_PLUGINS_DIR_URI . 'elementor/controls/';
		$ver = bimber_get_theme_version();

		// CSS.
		wp_enqueue_style( 'bimber-elementor-controls', $base_uri . 'css/bimber-controls.css', [], $ver );

		// JS.
		wp_enqueue_script( 'bimber-elementor-controls', $base_uri . 'js/bimber-controls.js', [ 'elementor-editor' ], $ver, true );
	}

	public function widget_styles() {
		$version = bimber_get_theme_version();
		$stack = bimber_get_current_stack();
		$skin = bimber_get_theme_option( 'global', 'skin' );

		$uri = trailingslashit( get_template_directory_uri() );

		wp_enqueue_style( 'bimber-elementor', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/elementor-' . $skin . '.min.css', array(), $version );
		wp_style_add_data( 'bimber-elementor', 'rtl', 'replace' );
	}


	public function add_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'bimber',
			[
				'title' => __( 'Bimber', 'bimber' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	public function init_widgets() {

		// Include Widget files
		require_once( BIMBER_PLUGINS_DIR . 'elementor/widgets/bimber-collection.php' );
		require_once( BIMBER_PLUGINS_DIR . 'elementor/widgets/bimber-categories.php' );
		require_once( BIMBER_PLUGINS_DIR . 'elementor/widgets/bimber-mc4wp-form.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Bimber_Collection_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Bimber_Categories_Widget() );

		// @todo Maybe we should move it to the proper file?
		if ( bimber_can_use_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Bimber_MC4WP_Form_Widget() );
		}
	}

	public function init_controls() {

		require_once( BIMBER_PLUGINS_DIR . 'elementor/controls/class-bimber-terms-control.php' );
		require_once( BIMBER_PLUGINS_DIR . 'elementor/controls/class-bimber-radio-control.php' );

		$controls_manager = \Elementor\Plugin::$instance->controls_manager;

		$controls_manager->register_control( 'bimber_terms', new \Elementor\Bimber_Terms_Control() );
		$controls_manager->register_control( 'bimber_radio', new \Elementor\Bimber_Radio_Control() );

	}
}

Elementor_Bimber_Extension::instance();
