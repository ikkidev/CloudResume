<?php
/**
 * Common Links Functions
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'init', 'adace_register_link_post_type' );
/**
 * Register link post type
 */
function adace_register_link_post_type() {
	// Labels array.
	$labels = array(
		'name'               => _x( 'Links', 'post type general name', 'adace' ),
		'singular_name'      => _x( 'Links', 'post type singular name', 'adace' ),
		'menu_name'          => _x( 'Links', 'admin menu', 'adace' ),
		'name_admin_bar'     => _x( 'Link', 'add new on admin bar', 'adace' ),
		'add_new'            => _x( 'Add New', 'link', 'adace' ),
		'add_new_item'       => __( 'Add New Link', 'adace' ),
		'new_item'           => __( 'New Link', 'adace' ),
		'edit_item'          => __( 'Edit Link', 'adace' ),
		'view_item'          => __( 'Show Link', 'adace' ),
		'all_items'          => __( 'All Links', 'adace' ),
		'search_items'       => __( 'Look for links', 'adace' ),
		'parent_item_colon'  => __( 'Parent link', 'adace' ),
		'not_found'          => __( 'Link not found', 'adace' ),
		'not_found_in_trash' => __( 'Link not found in trash', 'adace' ),
	);
	// Args array.
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Promoted links on the internet.', 'adace' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-networking',
		'query_var'          => true,
		'rewrite'            => array(
			'slug' => 'link',
		),
		'capability_type' => 'post',
		'has_archive'     => false,
		'hierarchical'    => false,
		'menu_position'   => null,
		'supports'        => array(
			'title',
			'thumbnail',
		),
	);
	// Register post type.
	register_post_type( 'adace_link', $args );
}

add_action( 'init', 'adace_register_link_taxonomy' );
/**
 * Register Sponsor taxonomy.
 */
function adace_register_link_taxonomy() {
	// Labels for post type.
	$labels = array(
		'name'                       => _x( 'Categories', 'taxonomy general name', 'adace' ),
		'singular_name'              => _x( 'Category', 'taxonomy singular name', 'adace' ),
		'search_items'               => __( 'Search Categories', 'adace' ),
		'popular_items'              => __( 'Popular Categories', 'adace' ),
		'all_items'                  => __( 'All Categories', 'adace' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Category', 'adace' ),
		'update_item'                => __( 'Update Category', 'adace' ),
		'add_new_item'               => __( 'Add New Category', 'adace' ),
		'new_item_name'              => __( 'New Category Name', 'adace' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'adace' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'adace' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'adace' ),
		'not_found'                  => __( 'No categories found.', 'adace' ),
		'menu_name'                  => __( 'Categories', 'adace' ),
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
			'slug' => 'adace-link-category',
		),
	);
	// Args for supported post type.
	$supported_post_types = array( 'adace_link' );
	// Register taxonomy.
	register_taxonomy( 'adace_link_category', $supported_post_types, $args );
}
