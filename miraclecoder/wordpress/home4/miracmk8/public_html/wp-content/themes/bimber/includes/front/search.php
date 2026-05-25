<?php
/**
 * Search related functions.
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

// Filters and actions.
add_action( 'bimber_search_before_main_collection',   'bimber_search_disable_embeds_before_main_collection', 99 );
add_action( 'bimber_search_after_main_collection',    'bimber_search_enable_embeds_after_main_collection', 99 );




function bimber_search_disable_embeds_before_main_collection() {
	if ( 'none' === bimber_get_theme_option( 'search', 'inject_embeds') ) {
		add_filter( 'bimber_capture_entry_featured_media_embeds', '__return_false', 9999 );
	}
}

function bimber_search_enable_embeds_after_main_collection() {
	if ( 'none' === bimber_get_theme_option( 'search', 'inject_embeds') ) {
		remove_filter( 'bimber_capture_entry_featured_media_embeds', '__return_false', 9999 );
	}
}