<?php
/**
 * 3rd party plugins integration
 *
 * @package AdAce
 * @subpackage Plugins
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$plugins_path = trailingslashit( dirname( __FILE__ ) );

require_once $plugins_path . 'elementor/elementor.php';

if ( adace_can_use_plugin( 'js_composer/js_composer.php' ) ) {
	require_once( $plugins_path . 'visual-composer/visual-composer.php' );
}

if ( adace_can_use_plugin( 'snax/snax.php' ) ) {
    require_once( $plugins_path . 'snax/snax.php' );
}

if ( adace_can_use_plugin( 'restrict-content-pro/restrict-content-pro.php' ) ) {
    require_once( $plugins_path . 'restrict-content-pro/restrict-content-pro.php' );
}

if ( adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
    require_once( $plugins_path . 'woocommerce/woocommerce.php' );
}
