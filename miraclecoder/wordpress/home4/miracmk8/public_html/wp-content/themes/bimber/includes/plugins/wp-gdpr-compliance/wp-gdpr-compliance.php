<?php
/**
 * WP GDPR Compliance plugin functions
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

add_filter( 'wpgdprc_wordpress_field', 'bimber_wpgdprc_add_required_attr', 10, 1 );

/**
 * @param string $html      HTML with the GDPR checkbox field.
 *
 * @return string
 */
function bimber_wpgdprc_add_required_attr( $html ) {
    $html = str_replace( '<input type="checkbox"', '<input type="checkbox" required="required"', $html );

    return $html;
}