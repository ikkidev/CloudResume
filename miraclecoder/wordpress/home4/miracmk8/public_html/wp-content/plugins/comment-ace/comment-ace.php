<?php
/**
 * Plugin Name: CommentAce
 * Description: WordPress, Facebook and Disqus comments
 * Author: bringthepixel
 * Version: 1.0.4
 * Author URI: http://www.bringthepixel.com
 * Text Domain: cace
 * Domain Path: /languages/
 * License: Located in the 'Licensing' folder
 * License URI:Located in the 'Licensing' folder
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Plugin filename.
define( 'CACE_PLUGIN_FILENAME', __FILE__ );

require_once( plugin_dir_path( __FILE__ ) . 'constants.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/options.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-comment-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/wp-report-functions.php' );

// Init plugin.
plugin();
