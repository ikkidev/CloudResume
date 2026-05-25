<?php
/**
 * Init Get My Widget
 *
 * @package AdAce.
 * @subpackage Get My Widget.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Add Get My Widget defaults.
add_filter( 'adace_options_defaults', 'adace_options_add_get_my_widget_defaults' );

// Admin.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/get-my-widget/admin/functions.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-disclosure.php' );
}

// Commons.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/get-my-widget/common/functions.php' );

// Front.
if ( ! is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/get-my-widget/front/functions.php' );
}

/**
 * Add Get My Widget Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_add_get_my_widget_defaults( $defaults ) {
	$defaults = array_merge( $defaults, array(
		'adace_disclosure_text' => esc_html__( 'I use affiliate links', 'adace' ),
	) );
	return $defaults;
}
