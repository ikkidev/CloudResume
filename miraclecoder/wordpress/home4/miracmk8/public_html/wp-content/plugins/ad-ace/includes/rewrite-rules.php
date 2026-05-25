<?php
/**
 * Options Page for Sponsors
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'admin_init', 'adace_save_rewrite_rules' );

if ( adace_rewrite_plugin_name() ) {
	add_filter( 'adace_plugin_url',     'adace_change_plugin_dir_name' );
	add_filter( 'mod_rewrite_rules',    'adace_change_plugin_name_rewrite_rules' );
}

/**
 * Check whether the plugin name should be rewritten
 *
 * @return bool
 */
function adace_rewrite_plugin_name() {
	return 'standard' === get_option( 'adace_rewrite_plugin_name', 'none' );
}


/**
 * Change plugin name
 *
 * @param string $url       Plugin url
 *
 * @return string
 */
function adace_change_plugin_dir_name( $url ) {
	$current_dir = '/ad-ace/';

	$custom_plugin_name = get_option( 'adace_plugin_name' );

	if ( ! empty( $custom_plugin_name ) ) {
		$new_dir = sprintf( '/%s/', $custom_plugin_name );

		$url = str_replace( $current_dir, $new_dir, $url );
	}

	return $url;
}

/**
 * Return rules to map plugin name (Apache version)
 *
 * @return string
 */
function adace_get_plugin_name_rewrite_rules() {
	$current_plugin_name = get_option( 'adace_plugin_name' );

	$rules  = PHP_EOL . "# AdAce Rules - Rewrite plugin name" . PHP_EOL;
	$rules .= "<IfModule mod_rewrite.c>" . PHP_EOL;
	$rules .= "RewriteEngine On" . PHP_EOL;
	$rules .= "RewriteRule ^(.*)/$current_plugin_name/(.*)$ $1/ad-ace/$2 [L]" . PHP_EOL;
	$rules .= "</IfModule>" . PHP_EOL . PHP_EOL;

	return $rules;
}

/**
 * Return rules to map plugin name (Nginx version)
 *
 * @return string
 */
function adace_get_plugin_name_rewrite_rules_nginx() {
	$current_plugin_name = get_option( 'adace_plugin_name' );

	$rules  = PHP_EOL . "# AdAce Rules - Rewrite plugin name" . PHP_EOL;
	$rules .= "location ~ ^(.*)/$current_plugin_name/(.*) {" . PHP_EOL;
	$rules .= "    rewrite ^(.*)/$current_plugin_name/(.*)$ $1/ad-ace/$2;" . PHP_EOL;
	$rules .= "}" . PHP_EOL;

	return $rules;
}

/**
 * Map new plugin name to real dir
 *
 * @param string  $rewrite
 *
 * @return string
 */
function adace_change_plugin_name_rewrite_rules( $rewrite ) {
	$rules  = adace_get_plugin_name_rewrite_rules();

	return $rules . $rewrite;
}

/**
 * Save rules
 */
function adace_save_rewrite_rules() {
	$page           = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
	$tab            = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
	$updated        = filter_input( INPUT_GET, 'settings-updated', FILTER_VALIDATE_BOOLEAN );

	if ( $page === 'adace_options' && 'adace_advanced' === $tab  && $updated ) {
		$current_plugin_name = get_option( 'adace_plugin_name' );

		// Generate if not set.
		if ( ! $current_plugin_name ) {
			update_option( 'adace_plugin_name', md5( home_url() ) . '-plugin' );
		}

		save_mod_rewrite_rules();
	}
}
