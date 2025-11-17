<?php
/**
 * Archive related functions.
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

// Filters and Actions.
add_action( 'bimber_archive_before_main_collection',   'bimber_archive_before_main_collection', 99 );
add_action( 'bimber_archive_after_main_collection',    'bimber_archive_after_main_collection', 99 );



function bimber_render_archive_featured_class( $r = array() ) {
	$final = array(
		'archive-featured',
	);


	$card_style = 'none';

	if ( is_home() ) {
		$card_style = bimber_get_theme_option( 'cards', 'home_content' );
	} else if ( is_archive() ) {
		$card_style = bimber_get_theme_option( 'cards', 'archive_content' );
	}

	if ( 'solid' === $card_style ) {
		$final[] = 'g1-row-bg-alt';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_archive_featured_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}



function bimber_render_archive_body_class( $r = array() ) {
	$final = array(
		'page-body',
		'archive-body',
		'g1-row',
		'g1-row-layout-page',
		'g1-row-padding-m',
	);

	$card_style = 'none';

	if ( is_home() ) {
		$card_style = bimber_get_theme_option( 'cards', 'home_content' );
	} else if ( is_archive() ) {
		$card_style = bimber_get_theme_option( 'cards', 'archive_content' );
	} else if ( is_search() ) {
		$card_style = bimber_get_theme_option( 'cards', 'search_content' );
	}

	if ( 'solid' === $card_style ) {
		$final[] = 'g1-row-bg-alt';
	}

	$final = array_merge( $final, $r );
	$final = apply_filters( 'bimber_get_archive_body_class', $final );

	echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $final ) )  . '"';
}


function bimber_archive_before_main_collection() {
	if ( 'none' === bimber_get_theme_option( 'archive', 'inject_embeds') ) {
		add_filter( 'bimber_capture_entry_featured_media_embeds', '__return_false', 9999 );
	}
}



function bimber_archive_after_main_collection() {
	if ( 'none' === bimber_get_theme_option( 'archive', 'inject_embeds') ) {
		remove_filter( 'bimber_capture_entry_featured_media_embeds', '__return_false', 9999 );
	}
}