<?php
/**
 * Plugin Name: AdAce
 * Description: Ad manager
 * Author: bringthepixel
 * Version: 1.3.24
 * Author URI: http://www.bringthepixel.com
 * Text Domain: adace
 * Domain Path: /languages/
 * License: Located in the 'Licensing' folder
 * License URI:Located in the 'Licensing' folder
 *
 * @package AdAce
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
function adace_get_plugin_dir() {
	return plugin_dir_path( __FILE__ );
}

/**
 * Return the plugin directory url
 *
 * @param bool $skip_filter         Disable url filter.
 *
 * @return string
 */
function adace_get_plugin_url( $skip_filter = false ) {
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );

	if ( $skip_filter ) {
		return $url;
	}

	return apply_filters( 'adace_plugin_url', $url );
}

/**
 * Return the plugin directory url
 *
 * @return string
 */
function adace_get_plugin_basename() {
	return plugin_basename( __FILE__ );
}

/**
 * Get plugin data.
 *
 * @return string
 */
function adace_get_plugin_data() {
	$slug = get_plugin_data( __FILE__ );

	if ( ! empty( $data[ $slug ] ) ) {
		$output = $data[ $slug ];
	} else {
		$output = false;
	}

	return apply_filters( 'adace_get_plugin_data_filter', $output, $slug );
}

/**
 * Return the plugin version
 *
 * @return string
 */
function adace_get_plugin_version() {
	$version = false;
	$data = get_plugin_data( __FILE__ );

	if ( ! empty( $data['Version'] ) ) {
		$version = $data['Version'];
	}

	return $version;
}

/**
 * Get template part
 *
 * @param string $slug File slug.
 * @param string $name File name.
 */
function adace_get_template_part( $slug, $name = null ) {
	// Trim off any slashes from the slug.
	$slug = ltrim( $slug, '/' );

	if ( empty( $slug ) ) {
		return;
	}

	$parent_dir_path = trailingslashit( get_template_directory() );
	$child_dir_path  = trailingslashit( get_stylesheet_directory() );

	$files = array(
		$child_dir_path . 'adace/' . $slug . '.php',
		$parent_dir_path . 'adace/' . $slug . '.php',
		adace_get_plugin_dir() . 'templates/' . $slug . '.php',
	);

	if ( ! empty( $name ) ) {
		array_unshift(
			$files,
			$child_dir_path . 'adace/' . $slug . '-' . $name . '.php',
			$parent_dir_path . 'adace/' . $slug . '-' . $name . '.php',
			adace_get_plugin_dir() . 'templates/' . $slug . '-' . $name . '.php'
		);
	}

	$located = '';

	foreach ( $files as $file ) {
		if ( empty( $file ) ) {
			continue;
		}

		if ( file_exists( $file ) ) {
			$located = $file;
			break;
		}
	}

	if ( strlen( $located ) ) {
		load_template( $located, false );
	}
}

/**
 * Check if plugin is active
 *
 * @param string $plugin Plugin name.
 */
function adace_can_use_plugin( $plugin ) {
	// Detect plugin. For use on Front End only.
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	return is_plugin_active( $plugin );
}


/**
 * Get Options Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_get_defaults( $option_key ) {
	$defaults = apply_filters( 'adace_options_defaults', array() );
	if ( isset( $defaults[ $option_key ] ) ) {
		return $defaults[ $option_key ];
	} else {
		return false;
	}
}

// Include all other plugin files.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/migration.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/functions.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/rewrite-rules.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-slots.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-ads-free.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-general.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/plugins/functions.php' );

// Load Ads.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/init.php' );

// Load Ads Free.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads-free/init.php' );

// Load Sponsors.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/sponsors/init.php' );
// Load Links.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/links/init.php' );
// Load Shop the post.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shop-the-post/init.php' );
// Load Shoppable Images.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shoppable-images/init.php' );
// Load Coupons.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/coupons/init.php' );
// Get My Widget.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/get-my-widget/init.php' );
// Patreon.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/patreon/init.php' );
// Adblock detector.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/adblock-detector/init.php' );

require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-advanced.php' );
