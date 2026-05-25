<?php
namespace WPGDPRC\WordPress\Admin\Pages;

use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Admin;
use WPGDPRC\WordPress\Plugin;

/**
 * Class PageWizard
 * @package WPGDPRC\WordPress\PageAdmin\Pages
 */
class PageWizard extends AbstractPage {

	public static function init() {
		add_action( 'init', [ self::class, 'initHooks' ] );
	}

	public static function initHooks() {
		if ( ! AdminHelper::userIsAdmin() ) {
			return;
		}

		// check for status change before determining if you are allowed to view the page
		Wizard::checkStatusChange();

		if ( Wizard::isCompleted() ) {
			return;
		}

		parent::init();
		Wizard::initHooks();

		add_action( 'admin_enqueue_scripts', [ self::class, 'enqueueAssets' ] );
	}

	/**
	 * Returns the page slug for this page
	 * @return string
	 */
	public static function getPageSlug() {
		return Plugin::PLUGIN_SLUG . '-wizard';
	}

	/**
	 * @return string
	 */
	protected static function getParentSlug() {
		return PageSettings::getPageSlug();
	}

	/**
	 * @return string
	 */
	protected static function getPageTitle() {
		return _x( 'Wizard', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return bool
	 */
	protected static function isSubMenu() {
		return true;
	}

	/**
	 * Generates the page content
	 */
	public static function generate() {
		exit;
	}

	/**
	 * Loads the page content
	 */
	public static function loadPage() {
		parent::loadPage();

		self::enqueueAssets();
		Template::render( 'Admin/Pages/Wizard/main' );
		exit;
	}

	/**
	 * Enqueues the assets needed for the wizard
	 */
	public static function enqueueAssets() {
		wp_enqueue_media();

		// default form styling. To be overwritten later
		wp_enqueue_style( 'forms' );

		Admin::loadAssets();
	}

}
