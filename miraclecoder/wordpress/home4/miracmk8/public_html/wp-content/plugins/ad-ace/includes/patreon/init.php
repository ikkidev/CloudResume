<?php
/**
 * Init Patreon.
 *
 * @package AdAce.
 * @subpackage Patreon.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Add Patreon defaults.
add_filter( 'adace_options_defaults', 'adace_options_add_patreon_defaults' );

// Admin.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-patreon.php' );
}

// Common.
require_once( plugin_dir_path( __FILE__ ) . 'class-adace-widget-patreon.php' );

/**
 * Add Patreon Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_add_patreon_defaults( $defaults ) {
	$defaults = array_merge( $defaults, array(
		'adace_patreon_label' => esc_html__( 'Like my blog?', 'adace' ),
		'adace_patreon_title' => esc_html__( 'Donate via Patreon to&nbsp;support me.<br />Thank You!', 'adace' ),
		'adace_patreon_link'  => esc_url( 'https://www.patreon.com/' ),
	) );
	return $defaults;
}
