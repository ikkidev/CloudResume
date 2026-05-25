<?php
/**
 * Sets up the default filters and actions.
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

// Set up.
add_action( 'after_setup_theme', 'bimber_setup_theme' );
add_action( 'after_switch_theme', 'bimber_load_default_options' );
add_action( 'widgets_init', 'bimber_setup_sidebars', 1 );
add_action( 'after_setup_theme', 'bimber_setup_wpml' );
add_action( 'after_setup_theme', 'bimber_setup_content_width', 0 );
add_filter( 'widget_title', 'bimber_allow_empty_widget_title', 9999 );
add_filter( 'safe_style_css', 'bimber_safe_style_css' );

// Migration.
add_action( 'after_setup_theme', 'bimber_run_migration' );
add_action( 'after_setup_theme', 'bimber_run_bunchy_migration',20 );
add_action( 'after_setup_theme', 'bimber_run_migrations',30 );
add_action( 'after_setup_theme', 'bimber_regenerate_dynamic_style_after_update', 20 );
add_action( 'bimber_after_import_content', 'bimber_regenerate_dynamic_style_after_demo_import', 20 );

// Widgets.
add_action( 'widgets_init', 'bimber_widgets_init' );
add_filter( 'dynamic_sidebar_params', 'bimber_sticky_widgets' );
add_action( 'dynamic_sidebar_after', 'bimber_sticky_close_combined_wrapper' );

// Lists.
add_action( 'bimber_update_hot_posts',      'bimber_calculate_hot_posts' );
add_action( 'bimber_update_popular_posts',  'bimber_calculate_popular_posts' );
add_action( 'bimber_update_trending_posts', 'bimber_calculate_trending_posts' );

// Lists.
add_filter( 'bimber_popular_post_ids',     'bimber_calculate_popular_post_ids_if_empty', 10, 2 );
add_filter( 'bimber_hot_post_ids',         'bimber_calculate_hot_post_ids_if_empty', 10, 2 );
add_filter( 'bimber_trending_post_ids',    'bimber_calculate_trending_post_ids_if_empty', 10, 2 );

// Ajax.
add_action( 'wp_ajax_bimber_search',                    'bimber_ajax_search' );
add_action( 'wp_ajax_bimber_search_terms',              'bimber_ajax_search_terms' );
add_action( 'wp_ajax_nopriv_bimber_search',             'bimber_ajax_search' );
add_action( 'wp_ajax_bimber_update_post_views',         'bimber_ajax_update_post_views' );
add_action( 'wp_ajax_nopriv_bimber_update_post_views',  'bimber_ajax_update_post_views' );

// Fakes.
add_filter( 'bimber_entry_view_count', 	'bimber_fake_view_count', 11 );

// Shortcodes.
add_filter( 'shortcode_atts_video', 'bimber_wp_video_shortcode_atts', 10, 3 );

// Clean up.
add_action( 'publish_post', 	'bimber_delete_transients' );
