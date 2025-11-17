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

add_filter( 'bimber_get_entry_flags',   'bimber_adace_add_coupon_flag' );

/**
 * Check if post has coupons
 *
 * @param int|WP_Post $post     Optional. Post ID or WP_Post object.
 *
 * @return bool
 */
function bimber_adace_has_coupon( $post = null ) {
	$post = get_post( $post );
	$bool = ( false !== strpos( $post->post_content, 'adace_coupons' ) );

	return apply_filters( 'bimber_has_coupon', $bool, $post );
}

/**
 * Add the Coupon flag to post entry flags
 *
 * @param array $flags      Flags.
 *
 * @return array
 */
function bimber_adace_add_coupon_flag( $flags ) {
	if ( bimber_adace_has_coupon() ) {
		$flags['coupon'] = array(
			'label' => sprintf( esc_html__( 'Coupon %s Inside', 'bimber' ), '<br>' ),
			'url'   => get_the_permalink(),
			'title' => esc_html__( 'Coupon Inside', 'bimber' ),
		);
	}

	return $flags;
}
