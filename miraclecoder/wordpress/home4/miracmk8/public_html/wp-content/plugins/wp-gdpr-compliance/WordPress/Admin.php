<?php
namespace WPGDPRC\WordPress;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Objects\Log;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\UserTypeHelper;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Admin\Actions;
use WPGDPRC\WordPress\Admin\Pages\PageAdmin;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Admin\Pages\PageRequests;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Admin\Pages\PageWizard;
use WPGDPRC\WordPress\Front\Consent\Bar as ConsentBar;

/**
 * Class Admin
 * @package WPGDPRC\WordPress
 */
class Admin {

	/**
	 * Admin constructor
	 */
	public static function init() {
		add_action( 'admin_init', [ self::class, 'initDatabase' ] );
		add_action( 'admin_init', [ self::class, 'initModal' ] );
		add_action( 'admin_enqueue_scripts', [ self::class, 'loadAssets' ] );
		add_action( 'admin_notices', [ self::class, 'activationNotice' ] );

		static::initPages();

		Settings::registerSettings();

		// add (custom) admin actions
		Actions::init();

		// WP_List_Table is not loaded automatically
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		}
	}

	/**
	 * Adds admin pages
	 */
	public static function initPages() {
		PageAdmin::init();
		PageDashboard::init();
		PageRequests::init();
		PageSettings::init();
		PageWizard::init();

		// add 'Settings' to WP plugin overview
		add_filter( 'plugin_action_links_' . WPGDPRC_FILE, [ self::class, 'settingsLink' ] );
	}

	/**
	 * Adds/Updates database tables
	 */
	public static function initDatabase() {
		DataProcessor::installDbTable();
		Log::installDbTable();
		RequestAccess::installDbTable();
		RequestDelete::installDbTable();
	}

	/**
	 * Update modal last shown
	 */
	public static function initModal() {
		$wizardStatus = Wizard::isCompleted();
		$lastShown    = UserTypeHelper::getLastShown();

		if ( $wizardStatus === false ) {
			return;
		}

		if ( $lastShown !== false ) {
			return;
		}

		UserTypeHelper::setLastShown( strtotime( '-32 days' ) );
	}

	/**
	 * Registers/ enqueues scripts & styles for the admin
	 */
	public static function loadAssets() {

		$handle = Plugin::enqueueStyle( 'admin.css' );
		wp_add_inline_style( $handle, ConsentBar::listCustomStyleVars() );

		Plugin::enqueueExternalStyle( 'https://use.typekit.net/bxb3nkp.css' );

		// enqueue Google font style
		$google_font = ConsentBar::getGoogleFontUrl();
		if ( $google_font ) {
			wp_enqueue_style( Plugin::PREFIX . '-google-font', $google_font, [], Plugin::VERSION );
		}

		wp_enqueue_script( 'hubspot', '//js.hsforms.net/forms/v2.js', [], Plugin::VERSION );

		$handle = Plugin::enqueueScript( 'admin.min.js', [ 'jquery' ] );
		wp_localize_script( $handle, 'wpgdprcAdmin', Plugin::listJsParams() );
	}

	/**
	 * Adds settings page link to plugin overview
	 * @param array $list
	 * @return array
	 */
	public static function settingsLink( $list = [] ) {
		$new = [
			/* translators: %1s: The plugin name */
			'settings' => Elements::getLink( Wizard::isCompleted() ? PageSettings::getPageUrl() : PageWizard::getPageUrl(), _x( 'Settings', 'admin', 'wp-gdpr-compliance' ), [ 'aria-label' => sprintf( _x( '%1s Settings', 'admin', 'wp-gdpr-compliance' ), Config::pluginName() ) ] ),
		];
		return $new + $list;
	}

	/**
	 * Adds notice upon activating the plugin
	 */
	public static function activationNotice() {
		$option = Settings::ACTIVATION_KEY;
		$shown  = get_option( $option, false );
		if ( $shown ) {
			return;
		}

		update_option( $option, 'notice_shown' );
		$link = Elements::getLink( esc_url( PageWizard::getPageUrl() ), _x( 'Settings', 'admin', 'wp-gdpr-compliance' ) );

		/* translators: %1s: Plugin name %1s: Link */
        AdminHelper::wrapNotice( sprintf( _x( '<strong>Awesome!</strong> You have activated the %1s plugin.', 'admin', 'wp-gdpr-compliance' ), Config::pluginName() ) . '<br/>' . sprintf( _x( 'To update the settings and read about what this plugin can do, go to our %1s page.', 'admin', 'wp-gdpr-compliance' ), $link ) );
    }

}
