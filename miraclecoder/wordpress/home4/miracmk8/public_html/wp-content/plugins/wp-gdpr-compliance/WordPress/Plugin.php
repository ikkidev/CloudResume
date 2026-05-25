<?php
namespace WPGDPRC\WordPress;

use WPGDPRC\Integrations\Plugins\ContactForm;
use WPGDPRC\Integrations\Plugins\GravityForms;
use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Objects\Log;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\UserTypeHelper;

/**
 * Class Plugin
 * @package WPGDPRC\WordPress
 */
class Plugin {

	const PLUGIN_SLUG = 'wp-gdpr-compliance';
	const PREFIX      = WPGDPRC_PREFIX;
	const VERSION     = WPGDPRC_VERSION;
	const DEBUG_MODE  = false;
	const AJAX_NONCE  = self::PREFIX . '-ajax';
	const AJAX_ARG    = 'security';

	const ASSETS_DIR    = 'Assets';
	const RESOURCES_DIR = 'Resources';
	const TEMPLATES_DIR = 'Templates';
	const SVG_DIR       = self::ASSETS_DIR . '/svg';

	/**
	 * Plugin constructor
	 */
	public static function init() {

		add_action( 'plugins_loaded', [ self::class, 'update' ] );
		add_action( 'plugins_loaded', [ self::class, 'pluginsLoaded' ] );
		add_action( 'upgrader_process_complete', [ self::class, 'pluginUpdated' ] );

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( WPGDPRC_FILE ) ) {
			Admin::init();
			Front::init();
			Integration::init();
		}
	}

	/**
	 * Flushes permalinks & stores default settings upon activation
	 */
	public static function activate() {
		flush_rewrite_rules();

		self::initializeTables();

		Settings::setDefaults();
		ContactForm::getInstance()->onPluginActivation();
		GravityForms::getInstance()->onPluginActivation();
	}

	/**
	 * Allows for modifying database values after plugin update/alterations
	 */
	public static function update() {
		// add code when DB needs to be updated
	}

	/**
	 * Removes options upon deactivation
	 */
	public static function deactivate() {
		ContactForm::getInstance()->onPluginDeactivation();
		GravityForms::getInstance()->onPluginDeactivation();
	}

	public static function uninstall() {
		Settings::deleteAll();
	}

	/**
	 * Initialize all DB tables for later use.
	 */
	public static function initializeTables() {
		DataProcessor::installDbTable();
		Log::installDbTable();
		RequestAccess::installDbTable();
		RequestDelete::installDbTable();
	}

	/**
	 * Loads additional filters, dependencies, etc
	 */
	public static function pluginsLoaded() {
		$data = Helper::getPluginData();
		load_plugin_textdomain( 'wp-gdpr-compliance', false, plugin_basename( WPGDPRC_ROOT ) . $data['DomainPath'] );
	}

	public static function pluginUpdated() {
		// Do nothing for now
	}

	/**
	 * Gets the resources url
	 * @param null|string $dir
	 * @return string
	 */
	public static function getAssetsUrl( $dir = null ) {
		$result = plugins_url( self::PLUGIN_SLUG ) . '/' . self::ASSETS_DIR . '/';
		return ! is_null( $dir ) ? $result . $dir . '/' : $result;
	}

	/**
	 * Gets the resources dir
	 * @param null|string $dir
	 * @return string
	 */
	public static function getAssetsDir( $dir = null ) {
		$result = WPGDPRC_ROOT . self::ASSETS_DIR . '/';
		return ! is_null( $dir ) ? $result . $dir . '/' : $result;
	}

	/**
	 * Gets the templates dir
	 * @return string
	 */
	public static function getTemplatesDir() {
		return WPGDPRC_ROOT . self::TEMPLATES_DIR . '/';
	}

	/**
	 * Gets the (inline) svg dir
	 * @return string
	 */
	public static function getSvgDir() {
		return WPGDPRC_ROOT . self::SVG_DIR . '/';
	}

	/**
	 * Lists the JS params for localizing the script
	 * @return array
	 */
	public static function listJsParams() {
		return [
            'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
            'ajaxNonce'       => wp_create_nonce( self::AJAX_NONCE ),
            'ajaxArg'         => self::AJAX_ARG,
            'pluginPrefix'    => self::PREFIX,
            'blogId'          => get_current_blog_id(),
            'isMultiSite'     => is_multisite(),
            'locale'          => get_locale(),
            'showSignUpModal' => $showSignupModal = UserTypeHelper::showSignUpModal(),
            'showFormModal'   => !$showSignupModal && UserTypeHelper::showFormModal(),
		];
	}

	/**
	 * @param string $file
	 * @param array  $dependencies
	 * @return false|string
	 */
	public static function enqueueStyle( $file = '', $dependencies = [] ) {
		if ( empty( $file ) ) {
			return false;
		}

		$handle = sanitize_title_with_dashes( self::PREFIX . '-' . str_replace( '.min.', '.', $file ) );
		wp_enqueue_style( $handle, self::getAssetsUrl( 'css' ) . $file, $dependencies, filemtime( self::getAssetsDir( 'css' ) . $file ) );
		return $handle;
	}

	/**
	 * @param string $url
	 * @param array  $dependencies
	 * @return false|string
	 */
	public static function enqueueExternalStyle( $url = '', $dependencies = [] ) {
		if ( empty( $url ) ) {
			return false;
		}

		$parsedUrl = wp_parse_url( $url );

		$hostname = isset( $parsedUrl['hostname'] ) ? $parsedUrl['hostname'] : '';
		$path     = isset( $parsedUrl['$path'] ) ? $parsedUrl['path'] : '';

		$handle = sanitize_title_with_dashes( self::PREFIX . '-' . $hostname ?? self::PREFIX . $path ?? self::PREFIX );
		wp_enqueue_style( $handle, $url, $dependencies, self::VERSION );
		return $handle;
	}

	/**
	 * @param string $file
	 * @param array  $dependencies
	 * @param false  $in_footer
	 * @return false|string
	 */
	public static function enqueueScript( $file = '', $dependencies = [], $in_footer = false ) {
		if ( empty( $file ) ) {
			return false;
		}

		$handle = sanitize_title_with_dashes( self::PREFIX . '-' . str_replace( '.min.', '.', $file ) );
		wp_enqueue_script( $handle, self::getAssetsUrl( 'js' ) . $file, $dependencies, filemtime( self::getAssetsDir( 'js' ) . $file ), $in_footer );
		return $handle;
	}

}
