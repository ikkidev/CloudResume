<?php
/**
 * WooCommerce Functions
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'adace_get_supported_post_types', 'adace_add_woocommerce_post_type_support' );
/**
 * Add woocommerce post types to supported post types
 *
 * @param array $supported_post_types Supported post types.
 */
function adace_add_woocommerce_post_type_support( $supported_post_types ) {
	$supported_post_types['product'] = esc_html__( 'Product', 'adace' );
	return $supported_post_types;
}
