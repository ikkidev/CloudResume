<?php

namespace NewfoldLabs\WP\Module\Performance\Services;

/**
 * Class for handling internationalization.
 */
class I18nService {
	/**
	 * Version of plugin for versioning the scripts.
	 *
	 * @var version
	 */
	protected $version;

	/**
	 * Init the i18n service
	 *
	 * @param Container $container the container
	 */
	public function __construct( $container ) {
		$this->version = $container->plugin()->version;
		// add_action( 'newfold/performance/load_default_translations', array( $this, 'prepare_and_load_js_translations' ) );
		add_action( 'load-toplevel_page_' . $container->plugin()->id, array( $this, 'prepare_and_load_js_translations' ), 1 );
		add_action( 'init', array( $this, 'load_text_domain' ) );
		add_action( 'load-tools_page_nfd-performance', array( $this, 'prepare_and_load_js_translations' ), 1 );
	}

	/**
	 * Load module text domain
	 *
	 * @return void
	 */
	public function load_text_domain() {
		$this::load_php_translations(
			'wp-module-performance',
			NFD_PERFORMANCE_PLUGIN_LANGUAGES_DIR
		);
	}

	/**
	 * Enqueue script for translations of the performance panel settings
	 */
	public function prepare_and_load_js_translations() {

		add_action(
			'admin_enqueue_scripts',
			function () {
				$this::load_js_translations(
					'wp-module-performance',
					'nfd-performance',
					NFD_PERFORMANCE_PLUGIN_LANGUAGES_DIR
				);

				$this::load_js_translations(
					'wp-module-performance',
					'nfd-performance-bulk-optimizer',
					NFD_PERFORMANCE_PLUGIN_LANGUAGES_DIR
				);

				$this::load_js_translations(
					'wp-module-performance',
					'nfd-performance-image-bulk-optimizer',
					NFD_PERFORMANCE_PLUGIN_LANGUAGES_DIR
				);
			},
			100
		);
	}

	/**
	 * Loads the PHP translations from .mo files in the languages dir.
	 * The .mo file must be named $textdomain-$locale.mo
	 *
	 * @param [string] $textdomain The text domain.
	 * @param [string] $languages_dir The directory containing the .mo files.
	 * @return boolean
	 */
	public static function load_php_translations( $textdomain, $languages_dir ) {
		$loaded_ptd = load_plugin_textdomain(
			$textdomain,
			false,
			$languages_dir
		);

		$current_language = get_locale();
		$loaded_td        = load_textdomain( 'wp-module-performance', $languages_dir . '/' . $textdomain . '-' . $current_language . '.mo' );

		return $loaded_ptd && $loaded_td;
	}

	/**
	 * Localizes a particular script using a JSON file present in the languages dir.
	 * The JSON file must be named $domain-$locale-$script_slug.json.
	 * Note: The script must be registered before this function is called.
	 *
	 * @param [string] $textdomain The text domain.
	 * @param [string] $script_handle The handle of the registered script.
	 * @param [string] $languages_dir The directory containing the .json file for the script.
	 * @return boolean
	 */
	public static function load_js_translations( $textdomain, $script_handle, $languages_dir ) {
		return wp_set_script_translations(
			$script_handle,
			$textdomain,
			$languages_dir
		);
	}
}
