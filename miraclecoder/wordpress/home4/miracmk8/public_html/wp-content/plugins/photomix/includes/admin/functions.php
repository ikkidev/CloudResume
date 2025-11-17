<?php
/**
 * Admin Functions
 *
 * @package photomix
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Load stylesheets.
 */
function photomix_admin_enqueue_styles( $hook ) {
	// Editor.
	if ( 'media_page_photomix-new-image' === $hook ) {
		$ver = photomix_get_plugin_version();
		$url = trailingslashit( photomix_get_plugin_url() );

		wp_enqueue_style( 'photomix-admin-main', $url . 'includes/admin/assets/css/main.css', array(), $ver );
		wp_enqueue_style( 'photomix-admin-editor', $url . 'modules/image-editor/assets/css/image-editor.css', array(), $ver );
	}

	// Settings page.
	if ( 'settings_page_photomix-settings' === $hook ) {
		wp_enqueue_style( 'wp-color-picker' );
	}
}

/**
 * Load javascripts.
 */
function photomix_admin_enqueue_scripts( $hook ) {
	$ver = photomix_get_plugin_version();
	$url = trailingslashit( photomix_get_plugin_url() ) . 'includes/admin/assets/js/';

	// Editor.
	if ( 'media_page_photomix-new-image' === $hook ) {
		wp_enqueue_script( 'ma-admin-main', $url . 'main.js', array( 'jquery' ), $ver, true );
	}

	// Settings page.
	if ( 'settings_page_photomix-settings' === $hook ) {
		wp_enqueue_script( 'ma-admin-settings', $url . 'settings.js', array( 'jquery', 'wp-color-picker' ), $ver, true );
	}
}
