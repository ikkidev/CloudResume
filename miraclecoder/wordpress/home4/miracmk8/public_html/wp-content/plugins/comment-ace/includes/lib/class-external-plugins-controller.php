<?php
/**
 * External Plugins Controller class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class External_Plugins_Controller {

    protected $buddypress;

    public function __construct() {
        $this->load();
    }

    protected function load() {
        if ( can_use_plugin( 'autoptimize/autoptimize.php' ) ) {
            require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/plugins/autoptimize/autoptimize.php';
        }

        if ( can_use_plugin( 'buddypress/bp-loader.php' ) ) {
            $this->buddypress = new \stdClass();

            require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/plugins/buddypress/buddypress.php';
        }

        if ( can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
            require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/plugins/woocommerce/woocommerce.php';
        }

        if ( can_use_plugin( 'snax/snax.php' ) ) {
            require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/plugins/snax/snax.php';
        }
    }

    public function buddypress() {
        return $this->buddypress;
    }
}
