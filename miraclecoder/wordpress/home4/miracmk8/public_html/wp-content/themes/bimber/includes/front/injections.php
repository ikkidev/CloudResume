<?php
/**
 * Front injections
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

/**
 * Check whether the injection is allowed for current archive page
 *
 * @return bool
 */
function bimber_is_injection_allowed() {
	$allowed = is_home() || bimber_is_posts_archive();

	return apply_filters( 'bimber_injection_allowed', $allowed );
}

/**
 * Register injection slots
 */
function bimber_injection_register_slots() {

	// ----
	// HOME
	// ----

	// Home / Newsletter.
	if ( 'standard' === bimber_get_theme_option( 'home', 'newsletter' ) ) {
		bimber_register_injection_slot( array(
			'id'                => 'home_newsletter',
			'position'          => absint( bimber_get_theme_option( 'home', 'newsletter_after_post' ) ),
			'repeat'            => absint( bimber_get_theme_option( 'home', 'newsletter_repeat' ) ),
			'render_callback'   => 'bimber_render_newsletter_slot',
			'active_callback'   => 'is_home',
		) );
	}

	// Home / Ad.
    $home_ad_enabled = 'standard' === bimber_get_theme_option( 'home', 'ad' );

	if ( apply_filters( 'bimber_home_ad_enabled', $home_ad_enabled ) ) {
		bimber_register_injection_slot( array(
			'id'                => 'home_ad',
			'position'          => absint( bimber_get_theme_option( 'home', 'ad_after_post' ) ),
			'repeat'            => absint( bimber_get_theme_option( 'home', 'ad_repeat' ) ),
			'render_callback'   => 'bimber_render_ad_slot',
			'active_callback'   => 'is_home',
		) );
	}

	// Home / Product.
	if ( 'standard' === bimber_get_theme_option( 'home', 'product' ) ) {
		// Product #1.
		bimber_register_injection_slot( array(
			'id'                => 'home_product_1',
			'position'          => absint( bimber_get_theme_option( 'home', 'product_after_post' ) ),
			'repeat'            => absint( bimber_get_theme_option( 'home', 'product_repeat' ) ),
			'render_callback'   => 'bimber_render_product_slot',
			'active_callback'   => 'is_home',
		) );
	}

	// -------
	// ARCHIVE
	// -------

	// Archive / Newsletter.
	if ( 'standard' === bimber_get_theme_option( 'archive', 'newsletter' ) ) {
		bimber_register_injection_slot( array(
			'id'                => 'archive_newsletter',
			'position'          => absint( bimber_get_theme_option( 'archive', 'newsletter_after_post' ) ),
			'repeat'            => absint( bimber_get_theme_option( 'archive', 'newsletter_repeat' ) ),
			'render_callback'   => 'bimber_render_newsletter_slot',
			'active_callback'   => 'bimber_is_archive_newsletter_on',
		) );
	}

	// Archive / Ad.
    $archive_ad_enabled = 'standard' === bimber_get_theme_option( 'archive', 'ad' );

    if ( apply_filters( 'bimber_archive_ad_enabled', $archive_ad_enabled ) ) {
		bimber_register_injection_slot( array(
			'id'                => 'archive_ad',
			'position'          => absint( bimber_get_theme_option( 'archive', 'ad_after_post' ) ),
			'repeat'            => absint( bimber_get_theme_option( 'archive', 'ad_repeat' ) ),
			'render_callback'   => 'bimber_render_ad_slot',
			'active_callback'   => 'bimber_is_archive_ad_on',
		) );
	}

	// Archive / Product.
	if ( 'standard' === bimber_get_theme_option( 'archive', 'product' ) ) {
		// Product #1.
		bimber_register_injection_slot( array(
			'id'                => 'archive_product_1',
			'position'          => absint( bimber_get_theme_option( 'archive', 'product_after_post' ) ),
			'repeat'            => absint( bimber_get_theme_option( 'archive', 'product_repeat' ) ),
			'render_callback'   => 'bimber_render_product_slot',
			'active_callback'   => 'bimber_is_archive_product_on',
		) );
	}
}

/**
 * Inject slots into the loop (before post).
 *
 * @param string $template_type             Classic, grid or list.
 * @param int    $post_number               The current position in the loop.
 */
function bimber_injection_render_slots_before( $template_type, $post_number ) {
	if ( ! bimber_is_injection_allowed() ) {
		return;
	}

	$posts_per_page = bimber_get_posts_per_page();
	$current_page   = absint( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
	$page_offset    = ( $current_page - 1 ) * $posts_per_page;

	// $post_number on each page starts from 1.
	$absolute_post_number = $post_number + $page_offset;

	bimber_render_post_injection_slots( $absolute_post_number, $current_page, $posts_per_page, array(
		'template_type' => $template_type,
	) );
}

/**
 * Inject slots into the loop (after post).
 */
function bimber_injection_render_slots_after() {
	global $bimber_injection_slot_after;

	if ( isset( $bimber_injection_slot_after ) ) {
		bimber_render_injection_slot_at( $bimber_injection_slot_after['position'], $bimber_injection_slot_after['params'], false );
	}
}

/**
 * Set maximum number of entries to show on the home page.
 *
 * @param WP_Query $query Home main query.
 */
function bimber_injection_adjust_pagination( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! bimber_is_injection_allowed() ) {
		return;
	}

	$posts_per_page = bimber_get_posts_per_page();
	$offset         = $query->get( 'offset' );

	if ( $posts_per_page <= 0 ) {
		return;
	}

	$query->set( 'bimber_posts_per_page', $posts_per_page );

	$current_page   = absint( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

	// Min post index on current page (starts at 0).
	$min_index = ( $current_page - 1 ) * $posts_per_page;

	// Min post index on next page.
	$max_index = $min_index + $posts_per_page;

	// Adjust number of items on current page.
	$injections_on_page = bimber_count_injections_between_positions( $min_index + 1, $max_index );

	if ( $injections_on_page['count'] > 0 ) {
		if ( empty( $offset ) ) {
			$offset = $min_index;
		}

		$posts_per_page -= $injections_on_page['count'];
	}

	// Adjust current page items offset.
	$injections_on_prev_pages = bimber_count_injections_between_positions( 1, $min_index );

	if ( $injections_on_prev_pages['count'] > 0 ) {
		if ( empty( $offset ) ) {
			$offset = $min_index;
		}

		$offset -= $injections_on_prev_pages['count'];
	}

	$query->set( 'posts_per_page', $posts_per_page );
	$query->set( 'offset', $offset );
}

/**
 * Adjust the home pagination.
 *
 * @param int      $found_posts Number of found posts.
 * @param WP_Query $query Home main query.
 *
 * @return mixed
 */
function bimber_injection_count_posts( $found_posts, $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return $found_posts;
	}

	if ( ! bimber_is_injection_allowed() ) {
		return $found_posts;
	}

	$posts_per_page = bimber_get_posts_per_page();

	if ( $posts_per_page <= 0 ) {
		return $found_posts;
	}

	$extra_posts = bimber_count_injections_before_post( $found_posts );

	$found_posts += $extra_posts;

	return $found_posts;
}

/**
 * Render newsletter slot
 *
 * @param array $params             Params.
 */
function bimber_render_newsletter_slot( $params ) {
	$template_type = $params['template_type'];

	get_template_part( 'template-parts/newsletter/newsletter-inside-' . $template_type );
}

/**
 * Render ad slot
 *
 * @param array $params             Params.
 */
function bimber_render_ad_slot( $params ) {
	$template_type = $params['template_type'];

	$slot_id             = $params['id'];
	$count_by_id         = $params['count_by_id'];
	$same_slots_injected = isset( $count_by_id[ $slot_id ] ) ? $count_by_id[ $slot_id ] : 0;

	global $bimber_ad_offset;

	$bimber_ad_offset = $same_slots_injected;

	get_template_part( 'template-parts/ads/ad-inside-' . $template_type );
}

/**
 * Render product slot
 *
 * @param array $params             Params.
 */
function bimber_render_product_slot( $params ) {
	$template_type       = $params['template_type'];
	$slot_id             = $params['id'];
	$count_by_id         = $params['count_by_id'];
	$same_slots_injected = isset( $count_by_id[ $slot_id ] ) ? $count_by_id[ $slot_id ] : 0;

	global $bimber_product_offset;

	$bimber_product_offset = $same_slots_injected;

	get_template_part( 'woocommerce/content-product-inside-' . $template_type );
}
