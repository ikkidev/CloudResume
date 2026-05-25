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
	 * by Work Sans, translate this to 'off'. Do not translate into your own language.
	 */
	$work_sans = _x( 'on', 'Work Sans font: on or off', 'bimber' );

	if ( 'off' !== $work_sans ) {
		$r['work_sans'] = 'Work Sans:400,300,500,600,700,800,900';
	}

	return $r;
}