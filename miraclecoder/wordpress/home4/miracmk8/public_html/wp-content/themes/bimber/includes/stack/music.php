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
	 * by Roboto, translate this to 'off'. Do not translate into your own language.
	 */
	$roboto = _x( 'on', 'Roboto font: on or off', 'bimber' );

	if ( 'off' !== $roboto ) {
		$r['roboto'] = 'Roboto:400,300,500,600,700,900';
	}

	return $r;
}



add_filter( 'bimber_newsletter_after_content_class',    'bimber_newsletter_class_dark' );
add_filter( 'bimber_newsletter_inside_grid_class',      'bimber_newsletter_class_dark' );
add_filter( 'bimber_newsletter_inside_list_class',      'bimber_newsletter_class_dark' );
add_filter( 'bimber_newsletter_inside_classic_class',   'bimber_newsletter_class_dark' );
add_filter( 'bimber_newsletter_inside_stream_class',    'bimber_newsletter_class_dark' );
add_filter( 'bimber_newsletter_inside_bunchy_class',    'bimber_newsletter_class_dark' );

function bimber_newsletter_class_dark( $classes ) {
	$classes[] = 'g1-dark';

	return $classes;
}

add_filter( 'bimber_get_grid_size', 'bimber_stack_get_grid_size' );
function bimber_stack_get_grid_size( $r ) {
	$r['width'] = 1280;
	$r['gutter_width'] = 20;

	return $r;
}
