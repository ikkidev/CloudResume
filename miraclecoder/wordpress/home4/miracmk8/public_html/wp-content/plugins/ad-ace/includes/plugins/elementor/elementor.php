<?php
/**
 * Elementor Page Builder plugin functions
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

$plugin_path = trailingslashit( dirname( __FILE__ ) );
require_once( $plugin_path . 'extensions/class-elementor-adace-extension.php' );
