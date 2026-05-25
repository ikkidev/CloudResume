<?php
/**
 * The Template for displaying collection title.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

do_action( 'bimber_before_title', 'home' );

if ( apply_filters( 'bimber_render_title', true, 'home' ) ) {
	bimber_render_section_title( bimber_get_home_title(), bimber_get_theme_option( 'home', 'title_hide' ), array( 'g1-collection-title' ) );
}
