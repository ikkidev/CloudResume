<?php
/**
 * Plugin Name:       Cookie Information | Free WP GDPR Consent Plugin
 * Plugin URI:        https://cookieinformation.com/
 * Description:       The consent solution for non-businesses. This is a free plug-in that offers basic features.
 * Author:            Cookie Information
 * Author URI:        https://cookieinformation.com/
 * Version:           2.0.23
 * Text Domain:       wp-gdpr-compliance
 * Domain Path:       /Resources/languages
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace WPGDPRC;

use Exception;
use WPGDPRC\WordPress\Plugin;

// check if this is a valid setup
if ( ! defined( 'ABSPATH' ) || ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

// init autoloader
spl_autoload_register( __NAMESPACE__ . '\\autoload' );

// define plugin constants
define( 'WPGDPRC_ROOT', dirname( __FILE__ ) . '/' );
define( 'WPGDPRC_ROOT_FILE', __FILE__ );
define( 'WPGDPRC_FILE', plugin_basename( __FILE__ ) );
define( 'WPGDPRC_VERSION', '2.0.23' );
define( 'WPGDPRC_PREFIX', strtolower( __NAMESPACE__ ) );

// register activation & deactivation hook to add/remove plugin specific database options
register_activation_hook( __FILE__, [ Plugin::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ Plugin::class, 'deactivate' ] );
register_uninstall_hook( __FILE__, [ Plugin::class, 'uninstall' ] );

// load plugin functionality
Plugin::init();

/**
 * Auto loads classes
 *
 * @param $class
 * @throws Exception
 */
function autoload( $class ) {
	if ( ! strstr( $class, __NAMESPACE__ ) ) {
		return;
	}

	$result  = str_replace( __NAMESPACE__ . '\\', '', $class );
	$result  = str_replace( '\\', '/', $result );
	$result .= '.php';

	if ( ! file_exists( WPGDPRC_ROOT . $result ) ) {
		return;
	}

	require $result;
}
