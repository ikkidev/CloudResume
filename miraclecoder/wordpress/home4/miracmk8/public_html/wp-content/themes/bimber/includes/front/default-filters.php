<?php
/**
 * Front hooks
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

// Body classes.
add_filter( 'body_class', 'bimber_body_class_global_layout' );
add_filter( 'body_class', 'bimber_body_class_helpers' );
add_filter( 'body_class', 'bimber_body_class_mobile_logo' );

// Post.
add_filter( 'single_template',		'bimber_post_alter_single_template' );
add_filter( 'post_class', 			'bimber_post_class', 20 );
add_filter( 'the_posts',			'bimber_post_pagination', 10, 2 );
add_action( 'wp_loaded', 			'bimber_post_set_elements_order' );
add_filter( 'bimber_comment_types', 'bimber_register_wp_comment_type' );
add_action( 'template_redirect',    'bimber_single_post_redirect_to_random_post' );

// Home.
add_filter( 'home_template',    'bimber_home_alter_template' );
add_action( 'pre_get_posts', 	'bimber_home_exclude_featured' );
add_action( 'pre_get_posts', 	'bimber_home_apply_main_collection_query_args' );

// Home > Above main collection.
add_action( 'bimber_home_before_main_collection',   'bimber_above_collection_attach_sections', 10 );
add_action( 'wp_loaded', 			                'bimber_above_collection_set_elements_order' );

// Archive.
add_action( 'pre_get_posts', 	        'bimber_archive_exclude_featured' );
add_action( 'pre_get_posts', 	        'bimber_apply_archive_filter' );
add_filter( 'get_the_archive_title',    'bimber_get_the_archive_title' );
add_action( 'wp_enqueue_scripts',       'bimber_enqueue_archive_styles', 80 );
add_filter( 'bimber_entry_cta_button_label', 'bimber_custom_cta_button_label', 10, 1 );

// Injections.
add_action( 'parse_query',                      'bimber_injection_register_slots' );
add_action( 'bimber_home_loop_before_post',     'bimber_injection_render_slots_before', 10, 2 );
add_action( 'bimber_home_loop_after_post',      'bimber_injection_render_slots_after', 10, 2 );
add_action( 'bimber_archive_loop_before_post',  'bimber_injection_render_slots_before', 10, 2 );
add_action( 'bimber_archive_loop_after_post',   'bimber_injection_render_slots_after', 10, 2 );
add_action( 'pre_get_posts', 	                'bimber_injection_adjust_pagination' );
add_filter( 'found_posts', 		                'bimber_injection_count_posts', 1, 2 );

// Lists.
add_action( 'init', 'bimber_update_lists' );

// Page.
add_filter( 'wp_link_pages_args', 'bimber_filter_wp_link_pages_args' );
add_filter( 'wp_link_pages_link', 'bimber_filter_wp_link_pages_link', 10, 2 );
add_action( 'wp_enqueue_scripts', 'bimber_maybe_enqueue_page_styles', 90 );

// Footer.
add_action( 'bimber_above_footer',    'bimber_above_footer_attach_sections', 10 );
add_action( 'wp_loaded', 			    'bimber_above_footer_set_elements_order' );


// Special collections.
add_filter( 'the_content', 'bimber_top_page', 11 );
add_filter( 'the_content', 'bimber_list_hot_entries', 11 );
add_filter( 'the_content', 'bimber_list_popular_entries', 11 );
add_filter( 'the_content', 'bimber_list_trending_entries', 11 );

// Comments.
add_filter( 'comment_form_default_fields', 'bimber_comment_form_default_fields' );
add_filter( 'comment_form_field_comment', 'bimber_comment_form_field_comment' );
add_action( 'comment_form_top', 'bimber_comment_render_avatar_before_form' );
add_filter( 'show_recent_comments_widget_style', '__return_false' );
add_filter( 'comments_template_query_args',	'bimber_show_only_wp_comments' );

// Enqueue assets.
add_action( 'wp_head', 'bimber_internal_dynamic_styles' );
add_action( 'wp_head', 'bimber_render_head_scripts', 5 );
add_action( 'wp_enqueue_scripts', 'bimber_enqueue_head_styles' );
add_action( 'style_loader_tag', 'bimber_fix_rtl_styles', 10, 4 );
add_action( 'wp_enqueue_scripts', 'bimber_enqueue_front_scripts' );
add_filter( 'script_loader_tag', 'bimber_load_front_scripts_conditionally', 10, 2 );

// Meta tags.
add_action( 'wp_head',      'bimber_add_responsive_design_meta_tag', 1 );
remove_action( 'wp_head',   'wp_generator' );
remove_action( 'wp_head',   'adjacent_posts_rel_link_wp_head', 10 );
add_action( 'wp_head',      'bimber_print_skin_mode_script', 99 );
add_action( 'wp_head',      'bimber_print_nsfw_mode_script', 99 );

// Other.
add_filter( 'wp_list_categories', 'bimber_insert_cat_count_span' );
add_filter( 'get_archives_link', 'bimber_insert_archive_count_span' );
add_filter( 'get_calendar', 'bimber_alter_calendar_output' );
add_filter( 'bimber_show_sharebar', 'bimber_hide_sharebar' );
add_filter( 'get_the_excerpt', 'bimber_excerpt_more' );
add_filter( 'excerpt_length', 'bimber_excerpt_length' );
add_filter( 'the_excerpt', 'bimber_excerpt_strip_oembed', 9999, 1 );
add_filter( 'pre_option_posts_per_page', 'bimber_set_posts_per_page', 10, 1 );
add_filter( 'body_class', 'bimber_set_sidebar_location', 10, 1 );

// Dynamic style cache.
add_action( 'template_redirect', 'bimber_load_dynamic_styles' );

// Menu.
add_filter( 'wp_get_nav_menu_items',    'bimber_add_top_nav_menu_item', 20, 2 );
add_filter( 'wp_setup_nav_menu_item',   'bimber_setup_nav_menu_item', 10, 1 );


// Auto Load.
add_action( 'bimber_before_single_content', 'bimber_add_url_waypoint' );

// Tracking codes.
add_action( 'wp_head', 'bimber_add_tracking_code_in_header' );
add_action( 'wp_footer', 'bimber_add_tracking_code_in_footer' );
