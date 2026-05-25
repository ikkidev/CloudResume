<?php
/**
 * Init Coupons
 *
 * @package AdAce.
 * @subpackage Coupons.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/coupons/common/register.php' );

if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/coupons/admin/meta-boxes.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/coupons/admin/columns.php' );
}

if ( ! is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/coupons/front/functions.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/coupons/front/shortcode.php' );
}
