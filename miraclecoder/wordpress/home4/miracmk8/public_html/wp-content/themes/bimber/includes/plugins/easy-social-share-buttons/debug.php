<?php
/**
 * East Social Share Buttons debug
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

/**
 * Display ESSB debug info
 */
function bimber_essb_display_debug_log() {
	$debug = bimber_htmlspecialchars( filter_input( INPUT_GET, 'bimber-essb-debug' ) );

	if ( is_null( $debug ) ) {
		return;
	}

	// Migrations.
	echo '<h2>Migrations</h2>';
	echo 'Init setup: ' . ( get_option( 'bimber_essb_init_setup' ) ? 'DONE' : 'not installed' );

	// Options.
	echo '<h2>ESSB Options</h2>';
	echo '<pre>';
	print_r( get_option( ESSB3_OPTIONS_NAME ) );
	echo '</pre>';

	exit;
}