<?php
/**
 * RCP Menu Endpoints
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

add_filter( 'bimber_menu_endpoints',                'bimber_rcp_menu_endpoints' );
add_filter( 'bimber_menu_item_obj',                 'bimber_rcp_menu_item_obj', 10, 2 );
add_filter( 'bimber_home_filters_disabled',         'bimber_rcp_home_filters_disabled' );
add_filter( 'bimber_home_filters_menu_item_args',   'bimber_rcp_home_filters_menu_item_args', 10, 2 );
add_filter( 'bimber_menu_item_to_home_filter',      'bimber_rcp_set_pending_as_current_filter', 10, 2 );
add_action( 'bimber_filter_home_query',             'bimber_rcp_apply_pending_home_filter', 10, 2 );
add_action( 'pre_get_posts', 	                    'bimber_rcp_apply_posts_filter',5, 1 );
add_filter( 'template_include', 	                'bimber_rcp_apply_posts_template', 1, 1 );

/**
 * Register custom endpoints
 *
 * @param array $items      Menu endpoints.
 *
 * @return array
 */
function bimber_rcp_menu_endpoints( $items ) {
	// Paid.
	$items[] = array(
		'url'       => '#',
		'label'     => _x( 'Paid', 'Restrict Content Pro menu endpoint', 'bimber' ),
		'classes'   => 'menu-item-type-g1-paid bimber-paid-posts-nav',
	);

	// Free.
	$items[] = array(
		'url'       => '#',
		'label'     => _x( 'Free', 'Restrict Content Pro menu endpoint', 'bimber' ),
		'classes'   => 'menu-item-type-g1-free bimber-free-posts-nav',
	);

	return $items;
}

/**
 * Set endpoints urls
 *
 * @param WP_Post $menu_item        The menu item.
 * @param string  $menu_item_id     Unique menu item id.
 *
 * @return WP_Post
 */
function bimber_rcp_menu_item_obj( $menu_item, $menu_item_id ) {
	switch ( $menu_item_id ) {
		case 'paid-posts' :
			$menu_item->url = bimber_rcp_get_paid_posts_url();
			break;

		case 'free-posts' :
			$menu_item->url = bimber_rcp_get_free_posts_url();
			break;
	}

	return $menu_item;
}

/**
 * Disable homepage filters if we are on the Paid/Free posts page
 *
 * @param bool $disabled        Disabled flag.
 *
 * @return bool
 */
function bimber_rcp_home_filters_disabled( $disabled ) {
	$paid_query_var = bimber_rcp_get_paid_posts_query_var();

	$paid_input_val = filter_input( INPUT_GET, $paid_query_var );

	if ( ! is_null( $paid_input_val ) ) {
		$disabled = true;
	}

	$free_query_var = bimber_rcp_get_free_posts_query_var();

	$free_input_val = filter_input( INPUT_GET, $free_query_var );

	if ( ! is_null( $free_input_val ) ) {
		$disabled = true;
	}

	return $disabled;
}

/**
 * Override the "Paid/Free" menu item url
 *
 * @param array    $args        Menu args.
 * @param stdClass $item        Menu item object.
 *
 * @return array
 */
function bimber_rcp_home_filters_menu_item_args( $args, $item ) {
	$paid_query_var = bimber_rcp_get_paid_posts_query_var();

	if ( false !== strpos( $item->url, '?' . $paid_query_var ) ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => bimber_rcp_get_paid_posts_query_var(),
		) );
	}

	$free_query_var = bimber_rcp_get_free_posts_query_var();

	if ( false !== strpos( $item->url, '?' . $free_query_var ) ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => bimber_rcp_get_free_posts_query_var(),
		) );
	}

	return $args;
}

/**
 * If no filters set explicitly, use the Paid/Free as current filter
 *
 * @param array    $filter      Current filter.
 * @param stdClass $item        Menu item.
 *
 * @return array
 */
function bimber_rcp_set_pending_as_current_filter( $filter, $item ) {
	$paid_query_var = bimber_rcp_get_paid_posts_query_var();

	if ( false !== strpos( $item->url, $paid_query_var ) ) {
		$filter = array(
			'by' => bimber_rcp_get_paid_posts_query_var(),
		);
	}

	$free_query_var = bimber_rcp_get_free_posts_query_var();

	if ( false !== strpos( $item->url, $free_query_var ) ) {
		$filter = array(
			'by' => bimber_rcp_get_free_posts_query_var(),
		);
	}

	return $filter;
}

/**
 * Apply the Paid/Free posts query args to home query
 *
 * @param stdClass $query           Query object.
 * @param string   $filter_by       Current filter.
 */
function bimber_rcp_apply_pending_home_filter( $query, $filter_by ) {
	if ( bimber_rcp_get_paid_posts_query_var() === $filter_by ) {
		$query->set( 'meta_query', array(
				array(
					'key' => '_is_paid',
					'value' => '1',
					'compare' => '=',
					'type' => 'numeric'
				)
			)
		);
	}

	if ( bimber_rcp_get_free_posts_query_var() === $filter_by ) {
		$query->set( 'meta_query', array(
				array(
					'key' => '_is_paid',
					'compare' => 'NOT EXISTS',
				)
			)
		);
	}
}

/**
 * Apply the Paid/Free filter on the query.
 *
 * @param WP_Query $query Archive main query.
 */
function bimber_rcp_apply_posts_filter( $query ) {
	$is_collection = is_archive() || is_home() ;

	$paid_query_var = isset( $_GET[ bimber_rcp_get_paid_posts_query_var() ] );

	if ( $is_collection && $query->is_main_query() && $paid_query_var ) {
		$query->set( 'meta_query', array(
				array(
					'key' => '_is_paid',
					'value' => '1',
					'compare' => '=',
					'type' => 'numeric'
				)
			)
		);
	}

	$free_query_var = isset( $_GET[ bimber_rcp_get_free_posts_query_var() ] );

	if ( $is_collection && $query->is_main_query() && $free_query_var ) {
		$query->set( 'meta_query', array(
				array(
					'key' => '_is_paid',
					'compare' => 'NOT EXISTS',
				)
			)
		);
	}
}

/**
 * Apply archive template to the Paid/Free posts
 *
 * @param  string $template Template.
 * @return string
 */
function bimber_rcp_apply_posts_template( $template ) {
	$paid_query_var = isset( $_GET[ bimber_rcp_get_paid_posts_query_var() ] );
	$free_query_var = isset( $_GET[ bimber_rcp_get_free_posts_query_var() ] );

	if ( ( $paid_query_var || $free_query_var ) && is_home() ) {
		global $wp_query;
		$template = get_archive_template();
	}

	return $template;
}

/**
 * Return url to paid posts
 *
 * @return string
 */
function bimber_rcp_get_paid_posts_url() {
	$url = add_query_arg( array(
		bimber_rcp_get_paid_posts_query_var() => '',
	), trailingslashit( get_home_url() ) );

	return apply_filters( 'bimber_rcp_paid_posts_url', $url );
}

/**
 * Return url to free posts
 *
 * @return string
 */
function bimber_rcp_get_free_posts_url() {
	$url = add_query_arg( array(
		bimber_rcp_get_free_posts_query_var() => '',
	), trailingslashit( get_home_url() ) );

	return apply_filters( 'bimber_rcp_free_posts_url', $url );
}

/**
 * Get Paid posts filter query var.
 *
 * @return string
 */
function bimber_rcp_get_paid_posts_query_var() {
	return apply_filters( 'bimber_rcp_paid_posts_query_var', 'paid' );
}

/**
 * Get Free posts filter query var.
 *
 * @return string
 */
function bimber_rcp_get_free_posts_query_var() {
	return apply_filters( 'bimber_rcp_free_posts_query_var', 'free' );
}
