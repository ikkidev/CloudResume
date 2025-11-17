<?php
/**
 * Shares module loader
 *
 * @package Bimber
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$shares_dir_path = trailingslashit( dirname( __FILE__ ) );

// Common functions.
require_once $shares_dir_path . 'common.php';
require_once $shares_dir_path . 'positions.php';

// Functions loaded both on frontend and backend (ajax calls).
if ( bimber_shares_enabled() ) {
	require_once $shares_dir_path . 'share-buttons.php';
}
