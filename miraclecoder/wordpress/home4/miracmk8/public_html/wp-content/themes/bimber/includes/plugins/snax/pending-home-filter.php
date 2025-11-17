<?php
/**
 * Snax plugin functions
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

add_filter( 'bimber_home_filters_disabled',         'bimber_snax_home_filters_disabled' );
add_filter( 'bimber_home_filters_menu_item_args',   'bimber_snax_set_pending_home_filter', 10, 2 );
add_filter( 'bimber_menu_item_to_home_filter',      'bimber_snax_set_pending_as_current_filter', 10, 2 );
add_action( 'bimber_filter_home_query',             'bimber_snax_apply_pending_home_filter', 10, 2 );

/**
 * Disable homepage filters if we are on the Waiting Room page
 *
 * @param bool $disabled        Disabled flag.
 *
 * @return bool
 */
function bimber_snax_home_filters_disabled( $disabled ) {
	if ( ! function_exists( 'snax_get_waiting_room_query_var' ) ) {
		return $disabled;
	}

	$query_var = snax_get_waiting_room_query_var();

	$input_val = filter_input( INPUT_GET, $query_var );

	if ( ! is_null( $input_val ) ) {
		$disabled = true;
	}

	return $disabled;
}

/**
 * Override the "Waiting room" menu item url
 *
 * @param array    $args        Menu args.
 * @param stdClass $item        Menu item object.
 *
 * @return array
 */
function bimber_snax_set_pending_home_filter( $args, $item ) {
	$query_var = snax_get_waiting_room_query_var();

	if ( false !== strpos( $item->url, '?' . $query_var ) ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'pending'
		) );
	}

	return $args;
}

/**
 * If no filters set explicitly, use the Pending as current filter
 *
 * @param array    $filter      Current filter.
 * @param stdClass $item        Menu item.
 *
 * @return array
 */
function bimber_snax_set_pending_as_current_filter( $filter, $item ) {
	if ( false !== strpos( $item->url, snax_get_waiting_room_query_var() ) ) {
		$filter = array(
			'by' => 'pending'
		);
	}

	return $filter;
}

/**
 * Apply the pending posts query args to home query
 *
 * @param stdClass $query           Query object.
 * @param string   $filter_by       Current filter.
 */
function bimber_snax_apply_pending_home_filter( $query, $filter_by ) {
	if ( ! function_exists( 'snax_waiting_room_enabled' ) ) {
		return;
	}

	if ( ! snax_waiting_room_enabled() ) {
		return;
	}

	if ( 'pending' === $filter_by ) {
		$query->set( 'post_status', 'pending' );
		$query->set( 'tax_query', array( array(
			'taxonomy' => snax_get_snax_format_taxonomy_slug(),
			'field' => 'slug',
			'operator' => 'EXISTS',
			),
		) );
		$query->is_archive = true;
	}
}
