<?php
/**
 * Snax plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'adace_ad_link', 'adace_snax_ad_link', 10, 2 );

function adace_snax_ad_link( $link, $link_type ) {
    if ( 'login' === $link_type ) {
        if ( is_user_logged_in() ) {
            $link['url'] = '';
        } else {
            $link['css_class'] = 'snax-login-required';
        }
    }

    return $link;
}
