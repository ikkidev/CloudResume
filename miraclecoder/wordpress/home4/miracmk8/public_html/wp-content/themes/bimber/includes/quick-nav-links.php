<?php
/**
 * Header Links
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

//add_filter( 'nav_menu_item_args', 'bimber_quick_nav_menu_item_args', 10, 3 );

function bimber_quick_nav_menu_item_args( $args, $item, $depth ) {
	// Only for the Quick Nav menu.
	if ( $args->theme_location !== 'bimber_quick_nav' ) {
		return $args;
	}

	// Only for top menu items.
	if ( $depth > 0 ) {
		return $args;
	}

	// Reset.
	$args->link_before = '';

	// Post category.
	if ( 'taxonomy' === $item->type && 'category' === $item->object ) {
		//$args->link_before = '';
	}

	// Post tag.
	if ( 'taxonomy' === $item->type && 'post_tag' === $item->object ) {
		//$args->link_before = '';
	}

	// Latest posts page.
	$latest_page_url = bimber_get_latest_page_url();

	if ( $item->url === $latest_page_url ) {
		$args->link_before = '<span class="entry-flag entry-flag-latest"></span>';
	}

	// Popular posts page.
	$popular_page_url = bimber_get_popular_page_url();

	if ( $item->url === $popular_page_url ) {
		$args->link_before = '<span class="entry-flag entry-flag-popular"></span>';
	}

	// Hot posts page.
	$hot_page_url = bimber_get_hot_page_url();

	if ( $item->url === $hot_page_url ) {
		$args->link_before = '<span class="entry-flag entry-flag-hot"></span>';
	}

	// Trending posts page.
	$trending_page_url = bimber_get_trending_page_url();

	if ( $item->url === $trending_page_url ) {
		$args->link_before = '<span class="entry-flag entry-flag-trending"></span>';
	}

	return apply_filters( 'bimber_quick_nav_menu_item_args', $args, $item );
}

/**
 * Get the id of the "Top" page.
 *
 * @return int
 */
function bimber_get_top_page_id() {
	$page_id = bimber_get_theme_option( 'posts', 'top_page' );

	if ( ! $page_id ) {
		$page_id = - 1;
	}

	return (int) apply_filters( 'bimber_top_page_id', $page_id );
}

/**
 * Whether or not we are on the "Top" page
 *
 * @return bool
 */
function bimber_is_top_page() {
	return is_page( bimber_get_top_page_id() );
}

/**
 * Get the URL of the "Top" page.
 *
 * @return int
 */
function bimber_get_top_page_url() {
	return get_permalink( bimber_get_top_page_id() );
}

/**
 * Get the label of the "Top" page
 *
 * @return string
 */
function bimber_get_top_page_label() {
	return get_the_title( bimber_get_top_page_id() );
}

/**
 * Check whether the Hot collection should be used
 *
 * @return bool
 */
function bimber_is_latest_page_enabled() {
	$is_enabled = (bool) bimber_get_theme_option( 'posts', 'latest_page' );

	// Check if enabled.
	if ( ! $is_enabled ) {
		return false;
	}

	// Check if page selected.
	$url = bimber_get_latest_page_url();

	return ! empty( $url );
}

/**
 * Whether or not we are on the "Latest" page
 *
 * @return bool
 */
function bimber_is_latest_page() {
	return is_home();
}

/**
 * Get the URL of the "Latest" page
 *
 * @return mixed|void
 */
function bimber_get_latest_page_url() {
	$show_on_front = get_option( 'show_on_front' );

	$url = '';

	// Front page displays > Your latest posts.
	if ( 'posts' === $show_on_front ) {
		$url = get_home_url();
	// Front page displays > A static page > Posts page.
	} else {
		$page_for_posts_id = (int) get_option( 'page_for_posts' );

		// Posts page selected?
		if ( $page_for_posts_id > 0 ) {
			$url = get_permalink( get_option( 'page_for_posts' ) );
		}
	}

	return apply_filters( 'bimber_latest_page_url', $url );
}

/**
 * Get the label of the "Latest" page
 *
 * @return string|void
 */
function bimber_get_latest_page_label() {
	if ( 'page' === get_option( 'show_on_front' ) ) {
		$posts_page_id = absint( get_option( 'page_for_posts' ) );
	} else {
		$posts_page_id = 0;
	}

	if ( $posts_page_id > 0 ) {
		$label = get_the_title( $posts_page_id );
	} else {
		$label = __( 'Latest', 'bimber' );
	}

	return $label;
}

/**
 * Check whether the Hot collection should be used
 *
 * @return bool
 */
function bimber_is_hot_collection_enabled() {
	$is_enabled = (bool) bimber_get_theme_option( 'posts', 'hot_enable' );

	// Check if enabled.
	if ( ! $is_enabled ) {
		return false;
	}

	// Check if page selected.
	$url = bimber_get_hot_page_url();

	return ! empty( $url );
}

/**
 * Get the id of the "Hot" page.
 *
 * @return int
 */
function bimber_get_hot_page_id() {
	$page_id = bimber_get_theme_option( 'posts', 'hot_page' );

	if ( ! $page_id ) {
		$page_id = - 1;
	}

	return (int) apply_filters( 'bimber_hot_page_id', $page_id );
}

/**
 * Whether or not we are on the "Hot" page
 *
 * @return bool
 */
function bimber_is_hot_page() {
	return is_page( bimber_get_hot_page_id() );
}

/**
 * Get the URL of the "Hot" page.
 *
 * @return int
 */
function bimber_get_hot_page_url() {
	return get_permalink( bimber_get_hot_page_id() );
}

/**
 * Get the label of the "Hot" page
 *
 * @return string
 */
function bimber_get_hot_page_label() {
	return get_the_title( bimber_get_hot_page_id() );
}

/**
 * Check whether the Popular collection should be used
 *
 * @return bool
 */
function bimber_is_popular_collection_enabled() {
	$is_enabled = (bool) bimber_get_theme_option( 'posts', 'popular_enable' );

	// Check if enabled.
	if ( ! $is_enabled ) {
		return false;
	}

	// Check if page selected.
	$url = bimber_get_popular_page_url();

	return ! empty( $url );
}

/**
 * Get the id of the "Popular" page.
 *
 * @return int
 */
function bimber_get_popular_page_id() {
	$page_id = bimber_get_theme_option( 'posts', 'popular_page' );

	if ( ! $page_id ) {
		$page_id = - 1;
	}

	return (int) apply_filters( 'bimber_popular_page_id', $page_id );
}

/**
 * Whether or not we are on the "Popular" page
 *
 * @return bool
 */
function bimber_is_popular_page() {
	return is_page( bimber_get_popular_page_id() );
}

/**
 * Get the URL of the "Popular" page.
 *
 * @return int
 */
function bimber_get_popular_page_url() {
	return get_permalink( bimber_get_popular_page_id() );
}

/**
 * Get the label of the "Popular" page
 *
 * @return string
 */
function bimber_get_popular_page_label() {
	return get_the_title( bimber_get_popular_page_id() );
}

/**
 * Check whether the Trending collection should be used
 *
 * @return bool
 */
function bimber_is_trending_collection_enabled() {
	$is_enabled = (bool) bimber_get_theme_option( 'posts', 'trending_enable' );

	// Check if enabled.
	if ( ! $is_enabled ) {
		return false;
	}

	// Check if page selected.
	$url = bimber_get_trending_page_url();

	return ! empty( $url );
}

/**
 * Get the id of the "Trending" page.
 *
 * @return int
 */
function bimber_get_trending_page_id() {
	$page_id = bimber_get_theme_option( 'posts', 'trending_page' );

	if ( ! $page_id ) {
		$page_id = - 1;
	}

	return (int) apply_filters( 'bimber_trending_page_id', $page_id );
}

/**
 * Whether or not we are on the "Trending" page
 *
 * @return bool
 */
function bimber_is_trending_page() {
	return is_page( bimber_get_trending_page_id() );
}

/**
 * Get the URL of the "Trending" page.
 *
 * @return int
 */
function bimber_get_trending_page_url() {
	return get_permalink( bimber_get_trending_page_id() );
}

/**
 * Get the label of the "Trending" page
 *
 * @return string
 */
function bimber_get_trending_page_label() {
	return get_the_title( bimber_get_trending_page_id() );
}

/**
 * Return list of collections to show on Top page
 *
 * @return array
 */
function bimber_get_top_page_collections() {
	$collections = array();

	if ( bimber_is_trending_collection_enabled() ) {
		$collections['trending'] = array(
			'label' => _x( 'Today', 'Top page', 'bimber' ),
			'url'	=> add_query_arg( bimber_get_top_page_collections_url_var(), 'trending' ),
		);
	}

	if ( bimber_is_hot_collection_enabled() ) {
		$collections['hot'] = array(
			'label' => _x( 'This month', 'Top page', 'bimber' ),
			'url'	=> add_query_arg( bimber_get_top_page_collections_url_var(), 'hot' ),
		);
	}

	if ( bimber_is_popular_collection_enabled() ) {
		$collections['popular'] = array(
			'label' => _x( 'All time', 'Top page', 'bimber' ),
			'url'	=> add_query_arg( bimber_get_top_page_collections_url_var(), 'popular' ),
		);
	}

	return apply_filters( 'bimber_top_page_collections', $collections );
}

/**
 * Return selected collection on Top page
 *
 * @param string $default			Default value.
 *
 * @return string
 */
function bimber_get_top_page_current_collection($default = '' ) {
	$url_var = bimber_get_top_page_collections_url_var();

	$collection = bimber_htmlspecialchars( filter_input( INPUT_GET, $url_var ) );

	if ( ! $collection ) {
		$collection = $default;
	}

	return $collection;
}

/**
 * Collection filter name to use in url
 *
 * @return string
 */
function bimber_get_top_page_collections_url_var() {
	return apply_filters( 'bimber_top_page_collections_url_var', 'collection' );
}
