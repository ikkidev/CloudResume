<?php
/**
 * Archive template tags
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
 * Whether of not to show home featured content.
 *
 * @return mixed|void
 */
function bimber_show_home_featured_entries() {
	$bool = true;

	$home_settings = bimber_get_home_settings();
	if ( 'none' === $home_settings['featured_entries']['type'] ) {
		$bool = false;
	}

	$paged = absint( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

	// Show only on the first page.
	if ( $paged > 1 ) {
		$bool = false;
	}

	return apply_filters( 'bimber_show_home_featured_entries', $bool );
}

/**
 * Whether or not to show archive featured content
 *
 * @return mixed|void
 */
function bimber_show_archive_featured_entries() {
	$bool = true;

	$archive_settings = bimber_get_archive_settings();
	if ( 'none' === $archive_settings['featured_entries']['type'] ) {
		$bool = false;
	}

	$paged = absint( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

	// Show only on first page.
	if ( $paged > 1 ) {
		$bool = false;
	}
	$post_types = get_query_var( 'post_type' );
	$is_post_archive = true;
	if ( is_array( $post_types ) && 'post' !== $post_types ) {
		$is_post_archive = false;
	}
	if ( is_array( $post_types ) ) {
		$is_post_archive = in_array( 'post', $post_types, true );
	}
	if ( $is_post_archive ) {
		return apply_filters( 'bimber_show_archive_featured_entries', $bool );
	} else {
		return false;
	}
}
