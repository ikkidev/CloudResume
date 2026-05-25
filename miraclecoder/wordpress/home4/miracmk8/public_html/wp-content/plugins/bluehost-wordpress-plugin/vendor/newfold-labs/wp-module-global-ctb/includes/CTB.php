<?php
namespace NewfoldLabs\WP\Module\GlobalCTB;

use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * This class adds click to buy functionality.
 **/
class CTB {

	/**
	 * Dependency injection container.
	 *
	 * @var Container
	 */
	protected $container;


	/**
	 * Constructor.
	 *
	 * @param Container $container The module container.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		// Module functionality goes here
		add_action( 'rest_api_init', array( CTBApi::class, 'registerRoutes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'ctb_scripts' ) );
		add_action( 'admin_footer', array( $this, 'ctb_footer' ) );
		add_filter( 'newfold-runtime', array( $this, 'add_to_runtime' ) );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function ctb_scripts() {
		$assetsDir = container()->plugin()->url . 'vendor/newfold-labs/wp-module-global-ctb/static/';

		// load the a11y dialog lib
		wp_register_script(
			'a11y-dialog',
			$assetsDir . 'a11y-dialog.min.js',
			array(),
			'7.4.0',
			false
		);

		// load ctb script
		wp_enqueue_script(
			'newfold-global-ctb',
			$assetsDir . 'ctb.js',
			array( 'a11y-dialog', 'wp-api-fetch', 'nfd-runtime' ),
			container()->plugin()->version,
			true
		);

		// Inline script for global vars for ctb
		wp_localize_script(
			'newfold-global-ctb', // script handle
			'nfdgctb', // js object
			array(
				'eventendpoint' => \esc_url_raw( \get_home_url() . '/index.php?rest_route=/newfold-data/v1/events/' ),
				'brand'         => container()->plugin()->brand,
			)
		);

		// Styles
		wp_enqueue_style(
			'newfold-global-ctb-style',
			$assetsDir . 'ctb.css',
			array(),
			container()->plugin()->version
		);
	}

	/**
	 * Add container to footer for modal components
	 *
	 * @return void
	 */
	public function ctb_footer() {
		echo "<div id='nfd-global-ctb-container' aria-hidden='true'></div>";
	}

	/**
	 * Adds values to the runtime object.
	 *
	 * @param array $sdk The runtime object.
	 *
	 * @return array Modified runtime object.
	 *
	 * @see newfold/wp-module-runtime
	 */
	public function add_to_runtime( $sdk ) {

		return array_merge( $sdk, array( 'locale' => get_locale() ) );
	}
}
