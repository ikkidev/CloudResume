<?php
/**
 * Init Shop the Post
 *
 * @package AdAce.
 * @subpackage Shop the Post.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Add Shop The Post defaults.
add_filter( 'adace_options_defaults', 'adace_options_add_shop_the_post_defaults' );

require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shop-the-post/shortcodes.php' );

// Admin.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-shop-the-post.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-disclosure.php' );
}

// Admin WooCommerce Part.
if ( is_admin() && adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shop-the-post/functions.php' );
}

// Admin Meta Boxes.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/shop-the-post/meta-boxes.php' );
}

/**
 * Add Shop The Post Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_add_shop_the_post_defaults( $defaults ) {
	$defaults = array_merge( $defaults, array(
		'adace_shop_the_post_excerpt'                => '1',
		'adace_shop_the_post_excerpt_hide_on_single' => '0',
		'adace_shop_the_post_disclosure'             => '1',
	) );
	return $defaults;
}
