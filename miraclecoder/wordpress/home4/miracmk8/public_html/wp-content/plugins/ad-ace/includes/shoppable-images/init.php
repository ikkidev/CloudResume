<?php
/**
 * Init Shoppable Images.
 *
 * @package AdAce.
 * @subpackage Shoppable Images.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Add Shop The Post defaults.
add_filter( 'adace_options_defaults', 'adace_options_add_shoppable_images_defaults' );
// Admin.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shoppable-images/admin/functions.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-shoppable-image.php' );
}
// Common.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shoppable-images/common/functions.php' );
// Front.
if ( ! is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shoppable-images/front/functions.php' );
}

/**
 * Add Shoppable Images Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_add_shoppable_images_defaults( $defaults ) {
	$defaults = array_merge( $defaults, array(
		'adace_shoppable_images_animate_pins' => '1',
	) );
	return $defaults;
}
