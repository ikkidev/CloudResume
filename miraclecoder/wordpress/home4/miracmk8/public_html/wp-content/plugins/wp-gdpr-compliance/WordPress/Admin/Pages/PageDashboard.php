<?php
namespace WPGDPRC\WordPress\Admin\Pages;

use WPGDPRC\Utils\Debug;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Plugin;

/**
 * Class PageDashboard
 * @package WPGDPRC\WordPress\PageAdmin\Pages
 */
class PageDashboard extends AbstractPage {

	const TAB_DEFAULT    = 'dashboard';
	const TAB_PROCESSORS = 'processors';
	const TAB_SETTINGS   = 'settings';
	const TAB_PREMIUM    = 'business';

	/**
	 * Returns the page slug for this page
	 * @return string
	 */
	public static function getPageSlug() {
		return Plugin::PLUGIN_SLUG;
	}

	/**
	 * @return string
	 */
	protected static function getParentSlug() {
		return Plugin::PLUGIN_SLUG;
	}

	/**
	 * @return string
	 */
	protected static function getPageTitle() {
		return _x( 'Dashboard', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return bool
	 */
	protected static function isSubMenu() {
		return true;
	}

	/**
	 * Gets the url for the dashboard tab
	 * @param null|string $tab
	 * @return string
	 */
	public static function getTabUrl( $tab = null ) {
		if ( is_null( $tab ) ) {
			$tab = self::TAB_DEFAULT;
		}
		return add_query_arg( [ 'tab' => $tab ], self::getPageUrl() );
	}

	/**
	 * Lists all dashboard tabs
	 * @return array
	 */
	public static function listTabs() {
		$list = [
			self::TAB_DEFAULT    => [
				'title' => _x( 'Dashboard', 'admin', 'wp-gdpr-compliance' ), // This one gets translated
				'slug'  => sanitize_title( 'Dashboard' ), // This one is used as a slug.
				'icon'  => 'tachometer-alt-fast',
			],
			self::TAB_PROCESSORS => [
				'title' => _x( 'Cookie pop-up', 'admin', 'wp-gdpr-compliance' ),
				'slug'  => sanitize_title( 'Consent pop-up' ),
				'intro' => sprintf(
					/* translators: %s: Link to premium */
					_x( 'Our free plugin lets you manually add data processors to your cookie pop-up. <strong>Want to automate this process? <br>%s</strong>', 'admin', 'wp-gdpr-compliance' ),
					Elements::getLink(
						Config::premiumUrl(),
						_x( 'Try out our premium plan for free for 30 days', 'admin', 'wp-gdpr-compliance' ),
						[
							'target' => '_blank',
							'class'  => 'wpgdprc-sign-up-button',
						]
					)
				),
				'icon'  => 'list-ul',
			],
			self::TAB_SETTINGS   => [
				'title' => _x( 'Settings', 'admin', 'wp-gdpr-compliance' ),
				'slug'  => sanitize_title( 'Settings' ),
				'intro' => _x( 'Change settings of the Privacy Policy, Consent bar and pop-up, Request user data form and Integrations.', 'admin', 'wp-gdpr-compliance' ),
				'icon'  => 'cog',
			],
			self::TAB_PREMIUM    => [
				'title' => _x( 'Upgrade', 'admin', 'wp-gdpr-compliance' ),
				'slug'  => sanitize_title( 'Business' ),
				'icon'  => 'stars',
			],
		];

		foreach ( $list as $slug => $data ) {
			$list[ $slug ]['url']    = self::getTabUrl( $slug );
			$list[ $slug ]['action'] = self::getFormAction( $slug );
			$list[ $slug ]['active'] = false;
			$list[ $slug ]['class']  = '';
		}
		return $list;
	}

	/**
	 * Gets the current dashboard tab
	 * @return string
	 */
	public static function getCurrentTab() {
		$list = self::listTabs();
		$tab  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : self::TAB_DEFAULT;
		return isset( $list[ $tab ] ) ? $tab : self::TAB_DEFAULT;
	}

	/**
	 * Prepares the tab list with current/active
	 * @return array
	 */
	public static function prepareTabs() {
		$current = self::getCurrentTab();
		$list    = self::listTabs();

		foreach ( $list as $slug => $data ) {
			if ( $slug !== $current ) {
				continue;
			}

			$list[ $slug ]['active'] = true;
			$list[ $slug ]['class']  = 'active';
		}
		return $list;
	}

	/**
	 * @param string|null $tab
	 * @return string
	 */
	public static function getTabIntro( $tab = null ) {
		if ( is_null( $tab ) ) {
			$tab = static::getCurrentTab();
		}

		$list = self::listTabs();
		if ( ! isset( $list[ $tab ] ) ) {
			return '';
		}
		return isset( $list[ $tab ]['intro'] ) ? $list[ $tab ]['intro'] : '';
	}

	/**
	 * @param null|string $tab
	 * @return string
	 */
	public static function getFormAction( $tab = null ) {
		if ( is_null( $tab ) ) {
			$tab = self::getCurrentTab();
		}

		switch ( $tab ) {
			case self::TAB_SETTINGS:
				return 'options.php';
		}
		return '';
	}

	/**
	 * Generates the page content
	 */
	public static function generate() {
		do_action( Plugin::PREFIX . '_before_page' );

		Template::render(
			'Admin/Pages/dashboard',
			[
				'current'      => self::getCurrentTab(),
				'tabs'         => self::prepareTabs(),
				'setting_tabs' => PageSettings::listSections(),
			]
		);

		do_action( Plugin::PREFIX . '_after_page' );
	}

}
