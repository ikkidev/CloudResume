<?php
/**
 * Plugin hooks
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/*
 * Mashshare.
 */

// Only core loaded.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) ) {
	add_action( 'bimber_render_top_share_buttons', 		'bimber_mashsharer_render_top_share_buttons' );
	add_action( 'bimber_render_bottom_share_buttons',	'bimber_mashsharer_render_bottom_share_buttons' );
	add_action( 'bimber_render_side_share_buttons', 	'bimber_mashsharer_render_side_share_buttons' );
	add_action( 'bimber_render_compact_share_buttons', 	'bimber_mashsharer_render_compact_share_buttons' );
	add_action( 'bimber_render_mini_share_buttons', 	'bimber_mashsharer_render_mini_share_buttons' );

	$mashsharer_execution_order = 1000;

	if ( function_exists( 'getExecutionOrder' ) ) {
		$mashsharer_execution_order = getExecutionOrder();
	}

	remove_filter( 'the_content', 'mashshare_filter_content', $mashsharer_execution_order, 1 );

	add_filter( 'bimber_most_shared_query_args',    'bimber_mashsharer_get_most_shared_query_args', 10, 2 );
	add_filter( 'bimber_entry_share_count',         'bimber_mashsharer_get_share_count' );
	add_filter( 'bimber_show_entry_share_count',    'bimber_mashsharer_show_share_count', 10, 2 );
	add_filter( 'mashsb_opengraph_meta' , 'bimber_mashsharer_fix_empty_og_description' );
	add_action( 'bimber_after_import_content',      'bimber_mashsharer_set_defaults' );

	add_filter( 'mashsb_opengraph_meta', 'bimber_mashsharer_gif_opengraph' ,100,1 );

	// Custom caching rules to not refresh counters on archives.
	// Curl requests coast too much, so reload cache only on a single page.
	if ( ! is_admin() ) {
		add_action( 'init',         'bimber_mashsharer_init_custom_caching_rules' );
		add_filter( 'the_content',  'bimber_mashsharer_activate_curl', 1 );
		add_filter( 'the_content',  'bimber_mashsharer_deactivate_curl', 9999 );
	}

	add_filter( 'bimber_archive_filters', 'bimber_mashsharer_add_most_shares_filter', 10, 1 );
	add_action( 'bimber_apply_archive_filter_most_shares', 'bimber_mashsharer_apply_archive_filter_most_shares', 10, 1 );

	add_action( 'woocommerce_share', 'bimber_mashsharer_add_shares_to_single_product' );
}

// Core loaded but not Networks addon.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) && ! bimber_can_use_plugin( 'mashshare-networks/mashshare-networks.php' ) ) {
	add_filter( 'mashsb_array_networks',    'bimber_mashsharer_array_networks' );
	add_action( 'init',                     'bimber_mashsharer_register_new_networks' );
	add_action( 'plugins_loaded',           'bimber_mashsharer_add_networks_class' );
}

// Core and Networks addon loaded.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) && bimber_can_use_plugin( 'mashshare-networks/mashshare-networks.php' ) ) {
	add_action( 'init', 'bimber_mashsharer_deregister_new_networks' );
}

// Core and ShareBar addon loaded.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) && bimber_can_use_plugin( 'mashshare-sharebar/mashshare-sharebar.php' ) ) {
	// Disable our built-in bar.
	add_filter( 'bimber_show_sharebar', '__return_false', 99 );
}

/*
 * WP QUADS - Quick AdSense Reloaded.
 */

if ( bimber_can_use_plugin( 'quick-adsense-reloaded/quick-adsense-reloaded.php' ) ) {
	add_action( 'after_setup_theme', 'bimber_quads_register_ad_locations' );
	add_filter( 'quads_has_ad', 'bimber_quads_hide_ads', 10, 2 );
	remove_action('admin_print_footer_scripts', 'quads_check_ad_blocker');
}


/*
 * bbPress.
 */

if ( bimber_can_use_plugin( 'bbpress/bbpress.php' ) ) {
	add_filter( 'bimber_setup_sidebars',	'bimber_bbpress_setup_sidebars' );
	add_filter( 'bimber_sidebar',			'bimber_bbpress_sidebar' );
	add_filter( 'get_the_excerpt',          'bimber_bbpress_remove_snax_content', 16 );
	add_filter( 'bbp_default_styles',		'bimber_disable_bbp_default_styles', 10, 1 );
}


/*
 * Auto Load Next Post.
 */

if ( bimber_can_use_plugin( 'auto-load-next-post/auto-load-next-post.php' ) ) {
	// Disable plugin's partial location function that doesn't support child themes.
	remove_action( 'template_redirect', 'auto_load_next_post_template_redirect' );

	// Use custom function with child theme support (till plugin doesn't fix it).
	add_action( 'template_redirect', 'bimber_auto_load_next_post_template_redirect' );

	add_filter( 'auto_load_next_post_general_settings', 'bimber_auto_load_next_post_general_settings' );

	// Return values valid for the theme.
	add_filter( 'pre_option_auto_load_next_post_content_container', 	'bimber_auto_load_next_post_content_container' );
	add_filter( 'pre_option_auto_load_next_post_title_selector', 		'bimber_auto_load_next_post_title_selector' );
	add_filter( 'pre_option_auto_load_next_post_navigation_container', 	'bimber_auto_load_next_post_navigation_container' );
	add_filter( 'pre_option_auto_load_next_post_comments_container',	'bimber_auto_load_next_post_comments_container' );
}

/*
 * Easy Google Fonts.
 */
if ( bimber_can_use_plugin( 'easy-google-fonts/easy-google-fonts.php' ) ) {
	add_filter( 'tt_font_get_option_parameters',    'bimber_egf_register_theme_font_options' );
	add_filter( 'tt_font_get_settings_page_tabs',   'bimber_egf_disable_default_typography_tab' );
}

/**
 * Disable plugin welcome redirects.
 *
 * We use TGM Plugin Activation to install some plugins.
 * We must be sure there are no redirects during the activation queue.
 */

add_action( 'after_setup_theme', 'bimber_disable_plugin_welcome_redirects' );

function bimber_disable_plugin_welcome_redirects() {
	if ( get_transient( '_bimber_demo_import_started' ) ) {
		delete_transient( 'quads_activation_redirect' );
		delete_transient( '_vc_page_welcome_redirect' );
		add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true',99 );
	}
}



// AdAce features.
add_filter( 'adace_support_patreon', '__return_true' );
add_filter( 'adace_support_coupons', '__return_true' );
add_filter( 'adace_support_sponsor', '__return_true' );

