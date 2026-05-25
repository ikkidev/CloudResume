<?php
/**
 * Visual Composer plugin functions
 *
 * @package AdAce
 * @subpackage Plugins
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$plugin_path = trailingslashit( dirname( __FILE__ ) );

require_once( $plugin_path . 'elements/ad.php' );