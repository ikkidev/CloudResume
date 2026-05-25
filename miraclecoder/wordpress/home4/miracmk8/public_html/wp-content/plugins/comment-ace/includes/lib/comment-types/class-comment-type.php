<?php
/**
 * Base Comments class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

abstract class Comment_Type {

    protected $classes;

    /**
     * Constructor
     */
    public function __construct() {
        $this->classes = array();

        $this->init();
    }

    /**
     * Return type unique identifier
     *
     * @return string
     */
    abstract public function get_id();

    /**
     * Return human readable name
     *
     * @return string
     */
    abstract public function get_name();

    /**
     * Get container classes
     *
     * @return array
     */
    public function get_classes() {
        return $this->classes;
    }

    /**
     * Set container classes
     *
     * @param array $classes
     */
    public function set_classes( array $classes ) {
        $this->classes = array_merge( $this->classes, $classes );
    }

    public function get_comment_count() {
        return 0;
    }

    /**
     * Render comments
     *
     * @return void
     */
    abstract public function render();

    /**
     * Init tasks
     */
    protected function init() {
        $this->add_hooks();
    }

    /**
     * Register actions and filters
     */
    protected function add_hooks() {}
}
