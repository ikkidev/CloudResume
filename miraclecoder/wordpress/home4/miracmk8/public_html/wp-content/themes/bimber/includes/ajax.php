<?php
/**
 * Ajax functions
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

function bimber_ajax_search() {
	$term = bimber_htmlspecialchars( filter_input( INPUT_GET, 'bimber_term' ) );

	if ( ! $term ) {
		$term = bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_term' ) );
	}

	if ( ! $term ) {
		esc_html_e( 'Search term not set!', 'bimber' );
		exit;
	}

	global $bimber_ajax_search_query;

	add_filter( 'parse_query', 'bimber_search_only_published_posts' );

	$bimber_ajax_search_query = new WP_Query( array(
		's'         		=> $term,
		'posts_per_page'	=> 5 + 1,
	) );

	remove_filter( 'parse_query', 'bimber_search_only_published_posts' );

	ob_start();

	get_template_part( 'template-parts/ajax-search-results' );

	$html = ob_get_clean();

	echo wp_json_encode( array(
		'status' 	=> 'success',
		'html'		=> $html,
	) );
	exit;
}

function bimber_ajax_search_terms() {
	$tax = bimber_htmlspecialchars( filter_input( INPUT_GET, 'taxonomy' ) );
	$term = bimber_htmlspecialchars( filter_input( INPUT_GET, 'term' ) );

	if ( ! $tax || ! $term ) {
		esc_html_e( 'Search params missing!', 'bimber' );
		exit;
	}

	$query = new WP_Term_Query( array(
		'taxonomy' => $tax,
		'search'   => $term,
		'number'   => 10
	) );

	$items = array();

	foreach( $query->get_terms() as $term ){
		$slug = $term->slug;

		$items[] = array(
			'id'    => $slug,
			'text'  => $slug,
		);
	}

	echo wp_json_encode( array(
		'items' => $items,
	) );
	exit;
}

function bimber_ajax_update_post_views() {
	$post_id = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );

	if ( ! $post_id ) {
		esc_html_e( 'Post id not set!', 'bimber' );
		exit;
	}

	do_action( 'bimber_update_post_views', $post_id );
}

/**
 * Exclude posts other than 'published' from search query
 *
 * @param WP_Query $query		Query object.
 */
function bimber_search_only_published_posts( $query ) {
	if ( ! empty( $query->query_vars['s'] ) ) {
		// We need to turn off admin context (by default set for all ajax calls) to not include statuses like 'draft', 'pending' etc.
		$query->is_admin = false;
	}
}

/**
 * Ajax handler for tag search.
 *
 * @since 3.1.0
 */
function bimber_ajax_tag_search() {
	if ( ! isset( $_GET['tax'] ) ) {
		wp_die( 0 );
	}

	$taxonomy = sanitize_key( $_GET['tax'] );
	$tax = get_taxonomy( $taxonomy );
	if ( ! $tax ) {
		wp_die( 0 );
	}

	if ( ! current_user_can( $tax->cap->assign_terms ) ) {
		wp_die( -1 );
	}

	$s = wp_unslash( $_GET['q'] );

	$comma = _x( ',', 'tag delimiter', 'bimber' );
	if ( ',' !== $comma )
		$s = str_replace( $comma, ',', $s );
	if ( false !== strpos( $s, ',' ) ) {
		$s = explode( ',', $s );
		$s = $s[count( $s ) - 1];
	}
	$s = trim( $s );

	/**
	 * Filters the minimum number of characters required to fire a tag search via Ajax.
	 *
	 * @since 4.0.0
	 *
	 * @param int         $characters The minimum number of characters required. Default 2.
	 * @param WP_Taxonomy $tax        The taxonomy object.
	 * @param string      $s          The search term.
	 */
	$term_search_min_chars = (int) apply_filters( 'term_search_min_chars', 2, $tax, $s );

	/*
	 * Require $term_search_min_chars chars for matching (default: 2)
	 * ensure it's a non-negative, non-zero integer.
	 */
	if ( ( $term_search_min_chars == 0 ) || ( strlen( $s ) < $term_search_min_chars ) ){
		wp_die();
	}

	$results = get_terms( $taxonomy, array( 'name__like' => $s, 'fields' => 'id=>slug', 'hide_empty' => false ) );

	echo join( $results, "\n" );
	wp_die();
}