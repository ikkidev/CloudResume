<?php
/**
 * Image Editor module bootstrap
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( is_admin() ) {
	require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'image-editor.php' );
}
