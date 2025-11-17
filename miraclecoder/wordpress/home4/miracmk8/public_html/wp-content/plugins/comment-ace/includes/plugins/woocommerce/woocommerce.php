<?php
/**
 * WooCommerce plugin integration
 *
 * @package Commentace
 */

namespace Commentace\WooCommerce;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'cace_init_comments', 'Commentace\WooCommerce\disable_comments_on_product' );

function disable_comments_on_product( $init ) {
    if ( is_product() ) {
        $init = false;
    }

    return $init;
}
