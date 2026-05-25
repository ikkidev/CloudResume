<?php
/**
 * Ads Free module
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

add_filter( 'bimber_home_ad_enabled',       'bimber_adace_disable_ad', 10, 1 );
add_filter( 'bimber_archive_ad_enabled',    'bimber_adace_disable_ad', 10, 1 );

function bimber_adace_disable_ad( $enabled ) {
    // Check if AdAce plugin up to date and contains the checker.
    if ( ! function_exists( 'adace_ad_free_mode_enabled' ) ) {
        return $enabled;
    }

    if ( adace_ad_free_mode_enabled() ) {
        $enabled = false;
    }

    return $enabled;
}
