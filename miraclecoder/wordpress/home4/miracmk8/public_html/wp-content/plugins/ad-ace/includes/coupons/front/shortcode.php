<?php
/**
 * Shortcode Functions
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_shortcode( 'adace_coupons', 'adace_coupons_shortcode' );
/**
* Image Map (shortcode).
*
* @param array $atts Shortcode attributes.
* @return string HTML
*/
function adace_coupons_shortcode( $atts ) {
	// Fill shortcode atts.
	$atts = shortcode_atts(
		array(
			'ids' => '',
		),
		$atts,
		'adace_coupons'
	);
	$coupons_ids = false;
	if ( ! empty( $atts['ids'] ) ) {
		$coupons_ids = explode( ',', $atts['ids'] );
	}
	if ( false === $coupons_ids ) {
		$fill_query_args = array(
			'post_type'      => 'adace_coupon',
			'posts_per_page' => 10,
			'fields'         => 'ids',
		);
		$fill_query = new WP_Query( $fill_query_args );
		// If we get results form tax query add them to proper query.
		if ( $fill_query->have_posts() ) {
			$coupons_ids = $fill_query->posts;
		} else {
			return;
		}
	}
	$output = '';
	foreach ( $coupons_ids as $coupon_id ) {
		$output .= adace_get_coupon( $coupon_id );
	}
	return apply_filters( 'adace_shoppable_image_shortcode', $output );
}
