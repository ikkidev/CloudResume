<?php
namespace WPGDPRC\WordPress\Admin\Pages;

use WPGDPRC\WordPress\Plugin;

/**
 * Class PageAdmin
 * @package WPGDPRC\WordPress\PageAdmin\Pages
 */
class PageAdmin extends AbstractPage {

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
		return '';
	}

	/**
	 * @return string
	 */
	protected static function getPageTitle() {
		return _x( 'Cookie Information', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	protected static function getMenuIcon() {
		return 'dashicons-lock';
	}

	/**
	 * Generates the page content
	 */
	public static function generate() {
		// TODO: Implement generate() method.
	}

}
