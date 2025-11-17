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

/**
 * Register custom ad locations
 */
function bimber_quads_register_ad_locations() {
	if ( ! function_exists( 'quads_register_ad' ) ) {
		return;
	}

	quads_register_ad( array(
		'location'    => 'bimber_before_header_theme_area',
		'description' => esc_html__( 'Before header theme area', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_before_content_theme_area',
		'description' => esc_html__( 'Before content theme area', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_after_featured_content',
		'description' => esc_html__( 'After featured entries', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_before_related_entries',
		'description' => esc_html__( 'Before "You May Also Like" section', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_before_more_from',
		'description' => esc_html__( 'Before "More From" section', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_before_comments',
		'description' => esc_html__( 'Before comments area', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_before_dont_miss',
		'description' => esc_html__( 'Before "Don\'t Miss" section', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_inside_grid',
		'description' => esc_html__( 'Inside grid collection', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_inside_list',
		'description' => esc_html__( 'Inside list collection', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_inside_classic',
		'description' => esc_html__( 'Inside classic collection', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_inside_stream',
		'description' => esc_html__( 'Inside stream collection', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_left_stream',
		'description' => esc_html__( 'On the left side of stream collection', 'bimber' ),
	) );

	quads_register_ad( array(
		'location'    => 'bimber_right_stream',
		'description' => esc_html__( 'On the right side of stream collection', 'bimber' ),
	) );
}


/**
 * Hide ads on specific pages
 *
 * @param bool $bool        Whether or not the ad is visible.
 *
 * @return bool
 */
function bimber_quads_hide_ads( $bool ) {
	if ( is_404() || is_search() ) {
		$bool = false;
	}

	return $bool;
}
