<?php
/**
 * Register Ads Post Type.
 *
 * @package AdAce.
 * @subpackage Ads.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


add_action( 'init', 'adace_register_post_type_ad' );
add_action( 'init', 'adace_register_ad_group_taxonomy' );

/**
 * Register ad post type.
 */
function adace_register_post_type_ad() {
	// Labels for post type.
	$labels = array(
		'name'               => esc_html_x( 'Ads', 'post type general name', 'adace' ),
		'singular_name'      => esc_html_x( 'Ad', 'post type singular name', 'adace' ),
		'menu_name'          => esc_html_x( 'Ads', 'admin menu', 'adace' ),
		'name_admin_bar'     => esc_html_x( 'Ad', 'add new on admin bar', 'adace' ),
		'add_new'            => esc_html_x( 'Add New', 'book', 'adace' ),
		'add_new_item'       => esc_html__( 'Add New Ad', 'adace' ),
		'new_item'           => esc_html__( 'New Ad', 'adace' ),
		'edit_item'          => esc_html__( 'Edit Ad', 'adace' ),
		'view_item'          => esc_html__( 'View Ad', 'adace' ),
		'all_items'          => esc_html__( 'All Ads', 'adace' ),
		'search_items'       => esc_html__( 'Search Ad\'s', 'adace' ),
		'parent_item_colon'  => esc_html__( 'Parent Ad\'s:', 'adace' ),
		'not_found'          => esc_html__( 'No ad\'s found.', 'adace' ),
		'not_found_in_trash' => esc_html__( 'No ad\'s found in Trash.', 'adace' ),
	);
	// Args for post type.
	$args = array(
		'labels'             => $labels,
		'menu_icon'          => 'dashicons-schedule',
		'description'        => esc_html__( 'Description of these amazing ad\'s.', 'adace' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array(
			'slug' => 'adace-ad',
		),
		'capabilities' => array(
			'edit_post'          => 'edit_theme_options',
			'read_post'          => 'edit_theme_options',
			'delete_post'        => 'edit_theme_options',
			'edit_posts'         => 'edit_theme_options',
			'edit_others_posts'  => 'edit_theme_options',
			'delete_posts'       => 'edit_theme_options',
			'publish_posts'      => 'edit_theme_options',
			'read_private_posts' => 'edit_theme_options',
		),
		'has_archive' => false,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array( 'title', 'page-attributes' ),
	);
	// Register.
	register_post_type( 'adace-ad', apply_filters( 'adace_register_post_type_ad_filter', $args ) );
}

/**
 * Register ad group taxonomy.
 */
function adace_register_ad_group_taxonomy() {
	// Labels for post type.
	$labels = array(
		'name'                       => _x( 'Groups', 'taxonomy general name', 'adace' ),
		'singular_name'              => _x( 'Group', 'taxonomy singular name', 'adace' ),
		'search_items'               => __( 'Search Groups', 'adace' ),
		'popular_items'              => __( 'Popular Groups', 'adace' ),
		'all_items'                  => __( 'All Groups', 'adace' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Group', 'adace' ),
		'update_item'                => __( 'Update Group', 'adace' ),
		'add_new_item'               => __( 'Add New Group', 'adace' ),
		'new_item_name'              => __( 'New Group Name', 'adace' ),
		'separate_items_with_commas' => __( 'Separate Groups with commas', 'adace' ),
		'add_or_remove_items'        => __( 'Add or remove Groups', 'adace' ),
		'choose_from_most_used'      => __( 'Choose from the most used Groups', 'adace' ),
		'not_found'                  => __( 'No Groups found.', 'adace' ),
		'menu_name'                  => __( 'Groups', 'adace' ),
	);
	// Args for post type.
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => false,
		'public'            => false,
		'rewrite'           => array(
			'slug' => 'adace-ad-group',
		),
	);
	// Args for supported post type.
	$supported_post_types = array( 'adace-ad' );
	// Register taxonomy.
	register_taxonomy( 'adace-ad-group', $supported_post_types, $args );
}
