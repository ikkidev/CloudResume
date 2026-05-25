<?php
/**
 * RCP plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'adace_ad_link', 'adace_rcp_ad_link', 10, 2 );

function adace_rcp_ad_link( $link, $link_type ) {
    if ( 'rcp_register' === $link_type ) {
        $link['url'] = rcp_get_registration_page_url();
    }

    return $link;
}
