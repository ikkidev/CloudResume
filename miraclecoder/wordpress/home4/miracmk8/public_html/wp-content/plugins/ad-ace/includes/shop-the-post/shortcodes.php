<?php
/**
 * Shortcodes
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

add_shortcode( 'adace_shop_the_post', 'adace_shop_the_post_shortcode' );

/**
 * Shop the post shortcode
 *
 * @param array $atts       Shortcode attributes.
 *
 * @return string
 */
function adace_shop_the_post_shortcode( $atts ) {
	// WooCommerce is required.
	if ( ! shortcode_exists( 'products' ) ) {
		return esc_html__( 'WooCommerce plugin is required to render the [adace_shop_the_post] shortcode.', 'adace' );
	}

	$atts = shortcode_atts(
		array(
			'ids' => '',
		),
		$atts,
		'adace_shop_the_post'
	);

	$ids = explode( ',', $atts['ids'] );
	$ids = array_map( 'absint', $ids );

	$columns = apply_filters( 'adace_shop_the_post_shortcode_columns', 4 );

	return do_shortcode( '[products orderby="post__in" ids="' . implode( ',', $ids ) . '" columns="' . $columns . '"]' );
}
