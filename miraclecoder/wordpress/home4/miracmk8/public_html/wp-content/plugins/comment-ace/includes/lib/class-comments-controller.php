<?php
/**
 * Comments Controller class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Comments_Controller {

    private $types;

    /**
     *
     */
    public function __construct() {
        $this->init();
        $this->add_hooks();
    }

    /**
     * Return registered comment types
     */
    public function get_types() {
        return apply_filters( 'cace_comment_types', $this->types );
    }

    /**
     * Return comment type object
     *
     * @param string $type
     *
     * @return bool|Comment_Type        Comment_Type object if enabled, false otherwise.
     */
    public function get_type( $type ) {
        return $this->is_type_enabled( $type ) ?  $this->types[ $type ] : false;
    }

    /**
     * Check if the type is enabled
     *
     * @param string $type      Comment type.
     *
     * @return bool
     */
    public function is_type_enabled( $type ) {
        return isset( $this->types[ $type ] );
    }

    /**
     * On init
     */
    protected function init() {
        $this->types = array();

        // WordPress.
        if ( is_wp_enabled() ) {
            $this->types[ CACE_COMMENT_TYPE_WORDPRESS ] = new WordPress_Comments();
        }

        // Facebook.
        if ( is_fb_enabled() ) {
            $this->types[ CACE_COMMENT_TYPE_FACEBOOK ] = new Facebook_Comments();
        }

        // Disqus.
        if ( is_dsq_enabled() ) {
            $this->types[ CACE_COMMENT_TYPE_DISQUS ] = new Disqus_Comments();
        }
    }

    /**
     * Register hooks
     */
    protected function add_hooks() {
        $types = $this->get_types();

        if ( empty( $types ) ) {
            return;
        }

        // Hijack comments.php template.
        add_filter( 'comments_template', array( $this, 'override_comments_template' ), 11 );

        // Load CSS.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ), 11 );

        // Load JS.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Preload fonts.
        add_action( 'wp_head', array( $this, 'preload_iconfont') );
    }

    /**
     * Replace the theme's comments template with our
     *
     * @param string $template      Comments template file path
     *
     * @return string
     */
    public function override_comments_template( $template ) {
        // Check post type.
        // Normally, it's not necessary as the check if done in the plugin controller,
        // but there are some special circumstances this check can be useful (e.g. for
        // Snax Item comments loaded inside a post).
        if ( ! is_post_type_enabled() ) {
            return $template;
        }

        $template = plugin()->get_dir() . 'templates/comments.php';

        return $template;
    }

    /**
     * Enqueue CSS
     */
    public function enqueue_styles() {
        wp_enqueue_style( 'cace-form', plugin()->get_url() . 'assets/css/form.min.css', array(), plugin()->get_version() );
        wp_style_add_data( 'cace-form', 'rtl', 'replace' );
    }

    /**
     * Dequeue CSS
     */
    public function dequeue_styles() {
        //wp_dequeue_style( 'bimber-comments' );
    }

    /**
     * Enqueue JS
     */
    public function enqueue_scripts() {
        if ( ! is_single() ) {
            return;
        }

        wp_enqueue_script( 'commentace-core',       plugin()->get_url() . 'assets/js/core.js', array(), plugin()->get_version() );
        wp_enqueue_script( 'commentace-comments',   plugin()->get_url() . 'assets/js/comments.js', array( 'commentace-core' ), plugin()->get_version() );

        global $wp_locale;

        // Defaults.
        $decimals      = 0;
        $dec_point     = '.';
        $thousands_sep = ',';

        if ( isset( $wp_locale ) ) {
            $dec_point = $wp_locale->number_format['decimal_point'];
            $thousands_sep = html_entity_decode ( $wp_locale->number_format['thousands_sep'] );
        }

        $config = array(
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
            'nonce'         => wp_create_nonce( 'commentace-action' ),
            'in_debug_mode' => plugin()->in_debug_mode(),
            'user_logged_in'=> is_user_logged_in(),
            'login_url'     => wp_login_url(),
            'number_format' => array(
                'decimals'      => $decimals,
                'dec_point'     => $dec_point,
                'thousands_sep' => $thousands_sep,
            ),
            'i18n' => array(
                'remove'              => __( 'Remove', 'cace' ),
                'login_required'      => __( 'You must be logged in to perform that action', 'cace' ),
                'copied_to_clipboard' => __( 'Copied to your clipboard', 'cace' ),
            ),
        );

        wp_localize_script( 'commentace-core', 'commentace', $config );
    }

    /**
     * Preload fonts
     */
    public function preload_iconfont() {
        echo '<link rel="preload" href="' . plugin()->get_url() . 'assets/css/iconfont/fonts/cace.woff" as="font" type="font/woff" crossorigin="anonymous">';
    }
}
