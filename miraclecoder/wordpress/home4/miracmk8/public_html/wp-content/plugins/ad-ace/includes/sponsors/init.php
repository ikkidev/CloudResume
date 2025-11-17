<?php
/**
 * Init Sponsor
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Add Shop The Post defaults.
add_filter( 'adace_options_defaults', 'adace_options_add_sponsor_defaults' );

// Admin.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/sponsors/admin/fields.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-sponsors.php' );
}

// Common.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/sponsors/common/register.php' );

// Front.
if ( ! is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/sponsors/front/get-sponsor.php' );
}

/**
 * Add Sponsor Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_add_sponsor_defaults( $defaults ) {
	$defaults = array_merge( $defaults, array(
		'adace_sponsor_before_post' => 'compact',
		'adace_sponsor_after_post'  => 'full',
	) );
	return $defaults;
}
