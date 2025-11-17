<?php
/**
 * Shortcode resources loader
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

require_once BIMBER_INCLUDES_DIR . 'shortcodes/title.php';
require_once BIMBER_INCLUDES_DIR . 'shortcodes/collection.php';
require_once BIMBER_INCLUDES_DIR . 'shortcodes/categories.php';
//require_once BIMBER_INCLUDES_DIR . 'shortcodes/featured-collection.php';


