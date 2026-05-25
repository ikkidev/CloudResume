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

add_action( 'bimber_amp_after_single_content',            'bimber_add_shop_the_post', 1 );
add_action( 'bimber_after_single_content',                'bimber_add_shop_the_post', 1 );
add_action( 'bimber_after_classic_content',               'bimber_add_shop_the_post', 25 );
add_action( 'bimber_after_classic_no_sidebar_content',    'bimber_add_shop_the_post', 25 );
add_action( 'bimber_after_zigzag_content',                'bimber_add_shop_the_post', 25 );
add_action( 'bimber_after_page',                          'bimber_add_shop_the_post', 25 );

/**
 * Add shop the post.
 */
function bimber_add_shop_the_post() {
	get_template_part( 'template-parts/post/shop-the-post' );
}
