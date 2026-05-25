<?php
namespace WPGDPRC\WordPress\Admin\Pages;

use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Debug;
use WPGDPRC\Utils\Wizard;

/**
 * Class AbstractPage
 * @package WPGDPRC\WordPress\Admin\Pages
 */
abstract class AbstractPage {

	const KEY_ID     = 'id';
	const KEY_DELETE = 'delete';

	/**
	 * AbstractPage constructor
	 */
	public static function init() {
		add_action( 'admin_menu', [ static::class, 'addAdminMenu' ], 9 );
	}

	/**
	 * Adds the page to the admin menu
	 */
	public static function addAdminMenu() {
		if ( static::isSubMenu() ) {
			$hook = add_submenu_page( static::getParentSlug(), static::getPageTitle(), static::getMenuTitle(), static::getCapability(), static::getMenuSlug(), [ static::class, 'generate' ] );
		} else {
			$hook = add_menu_page( static::getPageTitle(), static::getMenuTitle(), static::getCapability(), static::getMenuSlug(), [ static::class, 'generate' ], static::getMenuIcon(), static::getPosition() );
		}
		add_action( 'load-' . $hook, [ static::class, 'loadPage' ] );
	}

	/**
	 * Loads additional actions and filters for this page only
	 */
	public static function loadPage() {
		static::addScreenOptions();
	}

	/**
	 * @return string
	 */
	abstract protected static function getParentSlug();

	/**
	 * @return string
	 */
	abstract protected static function getPageTitle();

	/**
	 * Generates the page content
	 */
	abstract public static function generate();

	/**
	 * @return string
	 */
	protected static function getMenuIcon() {
		return '';
	}

	/**
	 * @return string
	 */
	protected static function getMenuTitle() {
		return static::getPageTitle();
	}

	/**
	 * @return string
	 */
	protected static function getMenuSlug() {
		return static::getPageSlug();
	}

	/**
	 * @return bool
	 */
	protected static function isSubMenu() {
		return false;
	}

	/**
	 * Gets the capability users need to see this page
	 * Optional to override
	 * @return string
	 */
	protected static function getCapability() {
		return 'manage_options';
	}

	/**
	 * Gets the position in the admin bar
	 * Optional to override
	 * @return string|int|null
	 */
	protected static function getPosition() {
		return null;
	}

	/**
	 * Adds other screen options to this page
	 * Optional to override
	 */
	public static function addScreenOptions() {}

	/**
	 * @param $status
	 * @param $option
	 * @param $value
	 * @return mixed
	 */
	public static function setScreenOptions( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Returns the page slug for this page
	 * @return string
	 */
	public static function getPageSlug() {
		return '';
	}

	/**
	 * Returns the key used for settings for this page
	 * @return string
	 */
	public static function getPageKey() {
		return str_replace( '-', '_', sanitize_key( static::getPageSlug() ) );
	}

	/**
	 * Gets admin page url
	 * @return string
	 */
	public static function getPageUrl() {
		return AdminHelper::getPageUrl( static::getPageSlug() );
	}

}
