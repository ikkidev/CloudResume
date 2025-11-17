<?php

namespace NewfoldLabs\WP\Module\Marketplace;

use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class for handling the initialization of the marketplace module.
 */
class Marketplace {

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

		// Module functionality goes here
		add_action( 'rest_api_init', array( MarketplaceApi::class, 'registerRoutes' ) );
		add_action( 'wp_loaded', array( Themes::class, 'init' ) );
		add_action( 'wp_loaded', array( PluginsMarketplace::class, 'init' ) );

		\add_filter( 'nfd_plugin_subnav', array( $this, 'add_nfd_subnav' ) );

		\add_action( 'init', array( $this, 'loadTextDomain' ), 100 );

		\add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_filter(
			'load_script_translation_file',
			array( $this, 'load_script_translation_file' ),
			10,
			3
		);
	}

	/**
	 * Add to the Newfold subnav.
	 *
	 * @param array $subnav The nav array.
	 * @return array The filtered nav array
	 */
	public static function add_nfd_subnav( $subnav ) {
		$brand       = container()->get( 'plugin' )['id'];
		$marketplace = array(
			'title'    => __( 'Marketplace', 'wp-module-marketplace' ),
			'route'    => $brand . '#/marketplace',
			'priority' => 25,
		);
		array_push( $subnav, $marketplace );
		return $subnav;
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function loadTextDomain() {
		\load_plugin_textdomain(
			'wp-module-marketplace',
			false,
			dirname( plugin_basename( NFD_MARKETPLACE_DIR ) ) . '/' . basename( NFD_MARKETPLACE_DIR ) . '/languages'
		);
	}

	/**
	 * Register empty script and script translations
	 */
	public static function assets() {

		// Register features API script
		$scriptPath = container()->plugin()->url . 'vendor/newfold-labs/wp-module-marketplace/assets/js/i18n-handle.js';
		wp_register_script(
			'newfold-marketplace',
			$scriptPath,
			array( 'wp-api-fetch', 'wp-i18n' ),
			container()->plugin()->version,
			true
		);

		wp_enqueue_script( 'newfold-marketplace' );

		\wp_set_script_translations(
			'newfold-marketplace',
			'wp-module-marketplace',
			container()->plugin()->url . 'vendor/newfold-labs/wp-module-marketplace/languages'
		);
	}

	/**
	 * Filters the file path for the JS translation JSON.
	 *
	 * If the script handle matches the module's handle, builds a custom path using
	 * the languages directory, current locale, text domain, and a hash of the script.
	 *
	 * @param string $file   Default translation file path.
	 * @param string $handle Script handle.
	 * @param string $domain Text domain.
	 * @return string Modified file path for the translation JSON.
	 */
	public function load_script_translation_file( $file, $handle, $domain ) {
		if ( 'newfold-marketplace' === $handle ) {
			$path   = NFD_MARKETPLACE_DIR . '/languages/';
			$locale = determine_locale();

			$file_base = 'default' === $domain
				? $locale
				: $domain . '-' . $locale;
			$file      = $path . $file_base . '-' . md5( 'components/marketplace/index.js' )
				. '.json';

		}

		return $file;
	}
}
