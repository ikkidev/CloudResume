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

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Roboto Condensed, translate this to 'off'. Do not translate into your own language.
	 */
	$roboto_condensed = _x( 'on', 'Roboto Condensed font: on or off', 'bimber' );

	if ( 'off' !== $roboto ) {
		$r['roboto'] = 'Roboto:400,300,500,600,700,900';
	}

	if ( 'off' !== $roboto_condensed ) {
		$r['roboto_condensed'] = 'Roboto Condensed:400,300,500,600,700';
	}

	return $r;
}