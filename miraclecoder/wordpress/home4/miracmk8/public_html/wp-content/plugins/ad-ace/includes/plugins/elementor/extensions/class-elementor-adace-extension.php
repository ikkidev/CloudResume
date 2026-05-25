<?php

final class Elementor_AdAce_Extension {

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
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		//add_action( 'after_setup_theme', [ $this, 'init' ] );
	}

	public function init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	public function add_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'adace',
			[
				'title' => __( 'AdAce', 'adace' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	public function init_widgets() {
		$dir = trailingslashit( adace_get_plugin_dir() ) . 'includes/plugins/elementor/';

		// Include Widget files
		require_once( $dir . 'widgets/adace-ad.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_AdAce_Ad_Widget() );
	}
}

Elementor_AdAce_Extension::instance();
