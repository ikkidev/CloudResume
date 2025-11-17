<?php
/**
Plugin Name:    Photomix
Description:    Mix photos without a graphics editor
Author:         bringthepixel
Version:        1.0.3
Author URI:     http://www.bringthepixel.com
Text Domain:    photomix
Domain Path:    /languages/
License: 		Located in the 'Licensing' folder
License URI: 	Located in the 'Licensing' folder

@package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Return the plugin directory base path
 *
 * @return string
 */
function photomix_get_plugin_dir() {
	return trailingslashit( plugin_dir_path( __FILE__ ) );
}

/**
 * Return the plugin directory url
 *
 * @return string
 */
function photomix_get_plugin_url() {
	return trailingslashit( plugin_dir_url( __FILE__ ) );
}

/**
 * Return the plugin basename
 *
 * @return string
 */
function photomix_get_plugin_basename() {
	return plugin_basename( __FILE__ );
}

/**
 * Return the plugin version
 *
 * @return string
 */
function photomix_get_plugin_version() {
	$version = false;
	$data = get_plugin_data( __FILE__ );

	if ( ! empty( $data['Version'] ) ) {
		$version = $data['Version'];
	}

	return $version;
}

// Common.
require_once( photomix_get_plugin_dir() . 'includes/functions.php' );
require_once( photomix_get_plugin_dir() . 'includes/hooks.php' );

// Admin side.
if ( is_admin() ) {
	require_once( photomix_get_plugin_dir() . 'includes/admin/functions.php' );
	require_once( photomix_get_plugin_dir() . 'includes/admin/ajax.php' );
	require_once( photomix_get_plugin_dir() . 'includes/admin/settings.php' );
	require_once( photomix_get_plugin_dir() . 'includes/admin/hooks.php' );
}

// Modules.
require_once( photomix_get_plugin_dir() . 'modules/image-editor/loader.php' );

// Init.
register_activation_hook( 	photomix_get_plugin_basename(), 'photomix_activate' );
register_deactivation_hook( photomix_get_plugin_basename(), 'photomix_deactivate' );
register_uninstall_hook( 	photomix_get_plugin_basename(), 'photomix_uninstall' );
