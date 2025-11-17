<?php
/**
 * Admin Panel Controller class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Plugin main controller
 *
 * @package Commentace
 */
class Admin_Controller {

    /**
     * @var Admin_Settings_Page
     */
    private $settings_page;

    /**
     * Admin_Controller constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/admin/settings/helpers.php';
        require_once plugin_dir_path( CACE_PLUGIN_FILENAME ) . 'includes/admin/settings/config.php';

        $this->settings_page = new Admin_Settings_Page( get_settings_config() );
        $this->wp_comments = new Admin_WordPress_Comments();
    }
}
