<?php
/**
 * Widgets Controller class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Widgets_Controller {

    public function __construct() {
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    /**
     * Init widgets
     */
    public function widgets_init() {
        register_widget( 'Commentace\\Widget_Recent_Comments' );
    }
}
