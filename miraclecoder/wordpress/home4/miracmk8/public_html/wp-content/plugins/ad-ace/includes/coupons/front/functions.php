<?php
/**
 * Front functions
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


add_action( 'wp_enqueue_scripts', 'adace_coupon_front_enqueue_scripts' );
/**
 * Register Front Styles
 */
function adace_coupon_front_enqueue_scripts() {
	$ver = adace_get_plugin_version();

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'adace-coupons', adace_get_plugin_url() . 'assets/js/coupons.js', array( 'jquery' ), $ver, false );
}

function adace_get_coupon( $coupon_id = '' ) {
	if ( ! get_post_status( $coupon_id ) ) {
		return '';
	}
	$coupon_thumbnail     = get_the_post_thumbnail( $coupon_id );
	$coupon_discount      = get_post_meta( $coupon_id, 'adace_coupon_discount', true );
	$coupon_discount_code = get_post_meta( $coupon_id, 'adace_coupon_discount_code', true );

	$output = '<div class="adace-coupon-wrap adace-coupon-' . esc_attr( $coupon_id ) . '">';
	$output .= '<div class="adace-coupon">';
	if ( ! empty( $coupon_thumbnail ) ) {
		$output .= '<div class="coupon-thumbnail adace-coupon-thumbnail">' . $coupon_thumbnail . '</div>';
	}
	$output .= '<h3 class="coupon-title adace-coupon-title g1-gamma g1-gamma-1st">' . get_the_title( $coupon_id ) . '</h3>';
	if ( $coupon_discount ) {
		$output .= '<p class="coupon-discount">' . wp_kses_post( $coupon_discount ) . '</p>';
	}
	if ( $coupon_discount_code ) {
		$output .= '<div class="coupon-copy-wrap adace-coupon-copy-wrap">';
		$output .= '<a class="coupon-copy adace-coupon-copy" href="#"><span class="coupon-code adace-coupon-code">' . wp_kses_post( $coupon_discount_code ) . '</span><span class="coupon-action adace-coupon-action g1-button g1-button-solid g1-button-s" data-copied="' . esc_html__( 'Copied', 'adace' ) . '">' . esc_html__( 'Copy', 'adace' ) . '</span></a>';
		$output .= '</div>';
	}
	$output .= '</div>';
	$output .= '</div>';
	return $output;
}
