<?php
/**
 * WooCommerce plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'adace_ad_link', 'adace_wc_ad_link', 10, 2 );

function adace_wc_ad_link( $link, $link_type ) {
    if ( 'wc_free_ad_product' === $link_type ) {
        $product_id = adace_ad_free_get_wp_product_id();

        $product = wc_get_product( $product_id );

        if ( $product ) {
            $link['url'] = get_permalink( $product_id );
        } else {
            $link['url'] = '';
        }
    }

    return $link;
}
