<?php
/**
 * Admin Hooks
 *
 * @package photomix
 * @subpackage Hooks
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Assets.
add_action( 'admin_enqueue_scripts', 'photomix_admin_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'photomix_admin_enqueue_scripts' );
