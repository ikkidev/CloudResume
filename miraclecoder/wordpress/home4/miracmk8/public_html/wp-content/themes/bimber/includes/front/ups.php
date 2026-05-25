<?php
/**
 * Front attach slide|pop ups functions
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

add_action( 'wp_footer', 'bimber_attach_ups', 100 );
/**
 * Attach boxed newsletter section.
 */
function bimber_attach_ups() {
	// Popup.
	if ( bimber_get_theme_option( 'newsletter', 'popup' ) ) {
		get_template_part( 'template-parts/newsletter/newsletter-popup' );
	}

	if ( bimber_get_theme_option( 'newsletter', 'slideup' ) ) {
		get_template_part( 'template-parts/newsletter/newsletter-slideup' );
	}

}
