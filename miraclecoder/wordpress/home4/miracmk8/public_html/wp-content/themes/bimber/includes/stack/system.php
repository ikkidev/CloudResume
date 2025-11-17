<?php
/**
 * Bimber Theme functions for the System stack
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 6.5
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


add_filter( 'bimber_render_section_title_args', 'bimber_stack_apply_section_title_class', 10, 2 );

/**
 * Change section title class.
 *
 * @param  array   $args  Args.
 * @param boolean $additional_clases  Additional css classes.
 * @return array
 */
function bimber_stack_apply_section_title_class( $args, $additional_clases ) {
	$args['class'] = array(
		'g1-epsilon',
		'g1-epsilon-2nd',
	);
	return $args;
}