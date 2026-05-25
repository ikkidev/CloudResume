<?php
/**
 * Init Ads
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Load ads common parts.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/ads-free/functions.php' );