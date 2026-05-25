<?php
/**
 * Register Coupons
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'init', 'adace_register_coupons_post_type' );

/**
 * Register link post type
 */
function adace_register_coupons_post_type() {
	// Labels array.
	$labels = array(
		'name'               => _x( 'Coupons', 'post type general name', 'adace' ),
		'singular_name'      => _x( 'Coupon', 'post type singular name', 'adace' ),
		'menu_name'          => _x( 'Coupons', 'admin menu', 'adace' ),
		'name_admin_bar'     => _x( 'Coupon', 'add new on admin bar', 'adace' ),
		'add_new'            => _x( 'Add New', 'coupon', 'adace' ),
		'add_new_item'       => __( 'Add New Coupon', 'adace' ),
		'new_item'           => __( 'New Coupon', 'adace' ),
		'edit_item'          => __( 'Edit Coupon', 'adace' ),
		'view_item'          => __( 'Show Coupon', 'adace' ),
		'all_items'          => __( 'All Coupons', 'adace' ),
		'search_items'       => __( 'Look for coupons', 'adace' ),
		'parent_item_colon'  => __( 'Parent coupon', 'adace' ),
		'not_found'          => __( 'Coupon not found', 'adace' ),
		'not_found_in_trash' => __( 'Coupon not found in trash', 'adace' ),
	);
	// Args array.
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Affiliate coupons', 'adace' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-tickets',
		'query_var'          => true,
		'rewrite'            => array(
			'slug' => 'coupon',
		),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array(
			'title',
			'thumbnail',
		),
	);
	// Register post type.
	register_post_type( 'adace_coupon', $args );
}
