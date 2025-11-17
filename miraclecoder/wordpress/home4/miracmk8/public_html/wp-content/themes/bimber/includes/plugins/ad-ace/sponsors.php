<?php
/**
 * WP QUADS plugin functions
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

add_action( 'pre_get_posts',                    'bimber_adace_remove_sponsor_filters' );

/**
 * Inject sponsor before content
 */
function bimber_adace_sponsor_before_content() {
	$template = get_option( 'adace_sponsor_before_post' );
	$content = '';
	if ( 'compact' === $template ) {
		$content = adace_get_sponsor_box_compact();
	}
	if ( 'full' === $template ) {
		$content = adace_get_sponsor_box_full();
	}
	echo $content;
}

/**
 * Remove after content sponsor filter, we handle this in the theme.
 *
 * @return void
 */
function bimber_adace_remove_sponsor_filters() {
	remove_filter( 'the_content', 					'adace_sponsor_before_post_inject' );
}
