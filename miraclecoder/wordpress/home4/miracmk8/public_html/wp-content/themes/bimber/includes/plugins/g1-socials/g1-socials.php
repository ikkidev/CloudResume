<?php
/**
 * G1 Socials plugin functions
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

// G1 Socials features.
add_filter( 'g1_socials_support_instagram', '__return_true' );
add_filter( 'g1_socials_support_youtube',   '__return_true' );
add_filter( 'g1_socials_support_twitter',   '__return_true' );


add_action( 'init', 'bimber_g1_socials_init' );
function bimber_g1_socials_init() {
	add_filter( 'bimber_display_g1_socials_in_author_box', 'bimber_display_g1_socials_in_author_box', 5 );
}

function bimber_display_g1_socials_in_author_box( $bool ) {
	$plugin = G1_Socials();

	$bool = (bool) get_option( $plugin->get_option_name() . '_enable_user_profiles', true );

	return $bool;
}