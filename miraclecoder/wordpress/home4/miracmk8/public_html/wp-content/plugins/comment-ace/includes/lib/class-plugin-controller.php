<?php
/**
 * Plugin Controller class
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
class Plugin_Controller {

    private static $instance;

    protected $comments_controller;
    protected $database_controller;
    protected $ajax_controller;
    protected $admin_controller;
    protected $external_plugins_controller;

    private function __construct() {
        $this->init();
        $this->add_hooks();
    }

    /**
     * Init plugin
     */
    private function init() {
        register_activation_hook(   $this->get_basename(), array( __CLASS__, 'activated' ) );
        register_deactivation_hook( $this->get_basename(), array( __CLASS__, 'deactivated' ) );
        register_uninstall_hook( 	$this->get_basename(), array( __CLASS__, 'uninstalled' ) );

        $this->database_controller = new Database_Controller();
        $this->ajax_controller     = new Ajax_Controller();
        $this->widgets_controller  = new Widgets_Controller();
        $this->external_plugins_controller  = new External_Plugins_Controller();

        if ( is_admin() ) {
            $this->admin_controller = new Admin_Controller();
        }
    }

    /**
     * Register actions and filters
     */
    private function add_hooks() {
        // Load text domain.
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        // Check database for updates.
        add_action( 'plugins_loaded', array( $this, 'update_database' ) );

        // Add Setting link on plugins page.
        add_filter( 'plugin_action_links', array( $this, 'add_plugin_settings_link' ), 10, 2 );

        add_action( 'wp', array( $this, 'init_comments' ) );


        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }

    /**
     * Run when the plugin is activated
     */
    public static function activated() {}

    /**
     * Run when the plugin is deactivated
     */
    public static function deactivated() {}

    /**
     * Run when the plugin is uninstalled
     */
    public static function uninstalled() {}

    /**
     * Enqueue CSS
     */
    public function enqueue_styles() {
        wp_enqueue_style( 'cace-all', plugin()->get_url() . 'assets/css/all.min.css', array(), plugin()->get_version() );
        wp_style_add_data( 'cace-all', 'rtl', 'replace' );
    }

    public function init_comments() {
        $init = is_post_type_enabled();

        if ( apply_filters( 'cace_init_comments', $init ) ) {
            $this->comments_controller = new Comments_Controller();
        }
    }

    /**
     * Initializes language domain
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'cace', false, 'comment-ace/languages' );
    }

    /**
     * Run database migrations
     */
    public function update_database() {
        if ( $this->db()->update_required() ) {
               $this->db()->update();
        }
    }

    /**
     * Add the Settings page link
     *
     * @param array  $links             Plugin's actions.
     * @param string $file              Current plugin filename.
     *
     * @return array
     */
    public function add_plugin_settings_link( $links, $file ) {
        $basename = $this->get_basename();

        if ( $basename === $file ) {
            $links[] = '<a href="' . esc_url( admin_url( add_query_arg( array( 'page' => 'cace-settings-wp' ), 'admin.php' ) ) ) . '">'. esc_html__( 'Settings', 'cace' ) .'</a>';
        }

        return $links;
    }

    /**
     * Returns class instance
     *
     * @return Plugin_Controller
     */
    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Return comments controller instance
     *
     * @return Comments_Controller
     */
    public function comments() {
        return $this->comments_controller;
    }

    /**
     * Return database controller instance
     *
     * @return Database_Controller
     */
    public function db() {
        return $this->database_controller;
    }

    /**
     * Return external plugins controller instance
     *
     * @return External_Plugins_Controller
     */
    public function external_plugins() {
        return $this->external_plugins_controller;
    }

    /**
     * Return the plugin directory base path
     *
     * @return string
     */
    public function get_dir() {
        return plugin_dir_path( CACE_PLUGIN_FILENAME );
    }

    /**
     * Return the plugin directory url
     *
     * @return string
     */
    public function get_url() {
        return trailingslashit( plugin_dir_url( CACE_PLUGIN_FILENAME ) );
    }

    /**
     * Return the plugin basename
     *
     * @return string
     */
    public function get_basename() {
        return plugin_basename( CACE_PLUGIN_FILENAME );
    }

    /**
     * Return the plugin version
     *
     * @return string
     */
    public function get_version() {
        $data = get_plugin_data( CACE_PLUGIN_FILENAME );

        return ! empty( $data['Version'] ) ? $data['Version'] : false;
    }

    /**
     * Check if the plugin runs in its debug mode
     *
     * @return bool
     */
    public function in_debug_mode() {
        $in_debug_mode = defined( 'CACE_DEBUG_MODE' ) ? CACE_DEBUG_MODE : false;

        $via_url = filter_input( INPUT_GET, 'cace-debug-mode', FILTER_SANITIZE_STRING );

        if ( $via_url ) {
            $in_debug_mode = true;
        }

        return $in_debug_mode;
    }
}
