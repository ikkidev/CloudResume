<?php
/**
 * Init Ads
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Load ads common parts.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/functions.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/common/register.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/common/shortcodes.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/common/class-adace-ads-widget.php' );
// Default slots.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/default-sections.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/before-content.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/after-paragraph.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/after-paragraph-2.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/after-paragraph-3.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/after-image.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/after-more.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/middle-content.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/before-last-paragraph.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/after-content.php' );
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/vignette.php' );

require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/default-slots/slideup.php' );

// CommentAce support.
if ( adace_can_use_plugin( 'comment-ace/comment-ace.php' ) && ! is_network_admin() ) {
    require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/plugins/commentace.php' );
}
// Snax support.
if ( adace_can_use_plugin( 'snax/snax.php' ) && ! is_network_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/plugins/snax.php' );
}
// BBpress support.
if ( adace_can_use_plugin( 'bbpress/bbpress.php' ) ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/plugins/bbpress.php' );
}
// AMP support.
if ( adace_can_use_plugin( 'amp/amp.php' ) ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/plugins/amp.php' );
}
// WooCommerce support.
if ( adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/plugins/woocommerce.php' );
}
// BuddyPress support.
if ( adace_can_use_plugin( 'buddypress/bp-loader.php' ) ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/plugins/buddypress.php' );
}

// Load sponsors backend parts.
if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/admin/meta-boxes.php' );
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads/admin/functions.php' );
}
