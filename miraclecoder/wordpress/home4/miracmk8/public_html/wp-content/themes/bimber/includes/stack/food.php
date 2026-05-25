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
	 * by PT Serif, translate this to 'off'. Do not translate into your own language.
	 */
	$pt_serif = _x( 'on', 'PT Serif font: on or off', 'bimber' );

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Spartan, translate this to 'off'. Do not translate into your own language.
	 */
	$spartan = _x( 'on', 'Spartan font: on or off', 'bimber' );

	if ( 'off' !== $pt_serif ) {
		$r['pt_serif'] = 'PT Serif:400,700';
	}

	if ( 'off' !== $spartan ) {
		$r['spartan'] = 'Spartan:400,300,600,700,800';
	}

	return $r;
}