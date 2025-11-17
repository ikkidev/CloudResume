<?php
/**
 * Bimber Theme functions for the stack
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.6
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'bimber_get_google_font_families', 'bimber_stack_get_google_font_families' );
function bimber_stack_get_google_font_families( $r ) {
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Fira Sans, translate this to 'off'. Do not translate into your own language.
	 */
	$fira_sans = _x( 'on', 'Fira Sans font: on or off', 'bimber' );

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Fira Sans Condensed, translate this to 'off'. Do not translate into your own language.
	 */
	$fira_sans_condensed = _x( 'on', 'Fira Sans Condensed font: on or off', 'bimber' );

	if ( 'off' !== $fira_sans ) {
		$r['fira_sans']	= 'Fira Sans:400,300,500,600,700,900';
	}

	if ( 'off' !== $fira_sans_condensed ) {
		$r['fira_sans_condensed'] = 'Fira Sans Condensed:400,300,500,600,700,900';
	}

	return $r;
}



add_filter( 'bimber_render_section_title_args', 'bimber_carmania_apply_section_title_class', 10, 2 );

/**
 * Change section title class.
 *
 * @param  array   $args  Args.
 * @param boolean $additional_clases  Additional css classes.
 * @return array
 */
function bimber_carmania_apply_section_title_class( $args, $additional_clases ) {
	if ( is_array( $additional_clases ) && in_array( 'widgettitle', $additional_clases, true ) ){
		return $args;
	}
	$args['class'] = array(
		'g1-beta',
		'g1-beta-2nd',
	);
	return $args;
}
