<?php
/** Home related functions.
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
add_action( 'bimber_home_before_main_collection',   'bimber_home_before_main_collection', 99 );
add_action( 'bimber_home_after_main_collection',    'bimber_home_after_main_collection', 99 );
add_filter( 'bimber_page_header_class',             'bimber_home_page_header_class' );


function bimber_home_before_main_collection() {
	if ( 'none' === bimber_get_theme_option( 'home', 'inject_embeds') ) {
		add_filter( 'bimber_capture_entry_featured_media_embeds', '__return_false', 9999 );
	}
}

function bimber_home_after_main_collection() {
	if ( 'none' === bimber_get_theme_option( 'home', 'inject_embeds') ) {
		remove_filter( 'bimber_capture_entry_featured_media_embeds', '__return_false', 9999 );
	}
}


function bimber_home_page_header_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'screen-reader-text';
	}

	return $classes;
}


