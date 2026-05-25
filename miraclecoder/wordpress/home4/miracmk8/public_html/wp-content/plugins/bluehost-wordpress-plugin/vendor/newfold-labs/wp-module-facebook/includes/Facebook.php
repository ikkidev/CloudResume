<?php
namespace NewfoldLabs\WP\Module\Facebook;

use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * Class Facebook
 *
 * @package NewfoldLabs\WP\Module\Facebook
 */
class Facebook {
	/**
	 * Container loaded from the brand plugin.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Identifier for script handle.
	 *
	 * @var string
	 */
	public static $handle = 'nfd-facebook';

	/**
	 * Array map of API controllers.
	 *
	 * @var array
	 */
	protected $controllers = array(
		'NewfoldLabs\\WP\\Module\\Facebook\\RestApi\\FacebookController',
	);

	/**
	 * Facebook constructor.
	 *
	 * @param Container $container Container loaded from the brand plugin.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		\add_action( 'init', array( __CLASS__, 'load_text_domain' ), 100 );
		add_filter(
			'load_script_translation_file',
			array( $this, 'load_script_translation_file' ),
			10,
			3
		);
		add_action( 'load-toplevel_page_' . $container->plugin()->id, array( $this, 'register_assets' ) );
	}

	/**
	 * Register routes for the module.
	 *
	 * @return void
	 */
	public function register_routes() {
		foreach ( $this->controllers as $controller ) {
			$rest_api = new $controller();
			$rest_api->register_routes();
		}
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function load_text_domain() {

		\load_plugin_textdomain(
			'wp-module-facebook',
			false,
			NFD_FACEBOOK_DIR . '/languages'
		);
	}

	/**
	 * Load WP dependencies into the page.
	 */
	public function register_assets() {
		$asset_file = NFD_FACEBOOK_DIR . '/build/index.asset.php';
		$dir        = $this->container->plugin()->url . 'vendor/newfold-labs/wp-module-facebook/';
		if ( file_exists( $asset_file ) ) {
			$asset = require $asset_file;
			\wp_register_script(
				self::$handle,
				// using dummy i18n-handle.js instead of index.js
				// due to the build file being pulled in as a npmjs pacakge.
				$dir . 'assets/i18n-handle.js',
				array(),
				$asset['version']
			);
		}
		\wp_set_script_translations(
			self::$handle,
			'wp-module-facebook',
			NFD_FACEBOOK_DIR . '/languages'
		);
		\wp_enqueue_script( self::$handle );
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
		if ( $handle === self::$handle ) {
			$path   = NFD_FACEBOOK_DIR . '/languages/';
			$locale = determine_locale();

			$file_base = 'default' === $domain
				? $locale
				: $domain . '-' . $locale;
			$file      = $path . $file_base . '-' . md5( 'build/index.js' )
						. '.json';

		}

		return $file;
	}
}
