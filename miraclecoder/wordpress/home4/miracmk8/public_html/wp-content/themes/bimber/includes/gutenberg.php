<?php
/**
 * Gutenberg related functions
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
add_filter( 'the_content', 'bimber_gutenberg_adjust_markup', 999 );



/**
 * Adjust HTML markup, so we don't have to style the same elements twice.
 *
 * @param $content string
 *
 * @return string
 */
function bimber_gutenberg_adjust_markup( $content  ) {
	$s = array(); // Search.
	$r = array(); // Replace.

	// Button block.
	$s[] = 'wp-block-button__link';
	$r[] = 'wp-block-button__link g1-button g1-button-solid g1-button-l';

	// File block.
	$s[] = 'wp-block-file__button';
	$r[] = 'wp-block-file__button g1-button g1-button-simple g1-button-s';

	// Latest Comments block.
	$s[] = 'wp-block-latest-comments__comment-meta';
	$r[] = 'wp-block-latest-comments__comment-meta g1-meta';
	$s[] = 'wp-block-latest-comments__comment-link';
	$r[] = 'wp-block-latest-comments__comment-link entry-title g1-epsilon g1-epsilon-1st';

	$content = str_replace( $s, $r, $content );

	return $content;
}