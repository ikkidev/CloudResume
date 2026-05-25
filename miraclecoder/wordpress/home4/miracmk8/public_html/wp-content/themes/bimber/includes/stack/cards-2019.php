<?php
/**
 * Bimber Theme functions for the stack
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 7.1
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


add_filter( 'bimber_get_google_font_families', 'bimber_stack_get_google_font_families' );
function bimber_stack_get_google_font_families( $r ) {
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by IBM Plex Sans, translate this to 'off'. Do not translate into your own language.
	 */
	$ibm_plex_sans = _x( 'on', 'IBM Plex Sans font: on or off', 'bimber' );

	if ( 'off' !== $ibm_plex_sans ) {
		$r['ibm_plex_sans'] = 'IBM Plex Sans:400,500,600,700';
	}
}

add_filter( 'bimber_get_grid_size', 'bimber_stack_get_grid_size' );
function bimber_stack_get_grid_size( $r ) {
	$r['width'] = 1134;
	$r['gutter_width'] = 24;

	return $r;
}




//add_action( 'woocommerce_before_shop_loop_item', 'bimber_stack_open_card_container', 1 );
//add_action( 'woocommerce_after_shop_loop_item', 'bimber_stack_close_card_container', 9999 );

function bimber_stack_open_card_container() {
	echo '<div class="g1-card">';
}

function bimber_stack_close_card_container() {
	echo '</div>';
}








//add_filter( 'bimber_get_collection_more_class', 'bimber_stack_add_card_class' );
//add_filter( 'bimber_get_nav_single_class',      'bimber_stack_add_card_class' );

//
//
//add_filter( 'bimber_get_page_body_class', 'bimber_stack_get_page_body_class' );
//function bimber_stack_get_page_body_class( $r ) {
//	$style = 'none';
//
//
//
//	$r = array_diff( $r, array( 'g1-row-padding-m' ) );
//
//	return $r;
//}
//
//add_filter( 'snax_get_format_class', 'snax_stack_get_format_class', 10, 2);
//function snax_stack_get_format_class( $class, $format_id ) {
//	$class[] = 'g1-card';
//
//	return $class;
//}
//
