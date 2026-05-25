<?php
/**
 * @package G1_Framework
 * @subpackage G1_Theme03
 * @since G1_Theme03 1.0.0
 */

// Prevent direct script access
if ( !defined('ABSPATH') )
    die ( 'No direct script access allowed' );

if ( file_exists( bbpress()->themes_dir . 'default/bbpress-functions.php' ) ) {
    require_once bbpress()->themes_dir . 'default/bbpress-functions.php';
}


class G1_BBPress {

    public function __construct() {
        $this->setup_hooks();
    }

    /**
     * Set up all hooks
     */
    protected function setup_hooks() {
        // Init.
        add_action( 'init', array( $this, 'init' ), 11 );

        // CSS.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_head_styles'), 20 );

        // Widgets.
        add_filter( 'bbp_forums_widget_options', array( $this, 'add_widget_class' ) );
        add_filter( 'bbp_topics_widget_options', array( $this, 'add_widget_class' ) );
        add_filter( 'bbp_views_widget_options', array( $this, 'add_widget_class' ) );

        // Avatars.
        add_filter( 'bbp_after_get_reply_author_link_parse_args', array( $this, 'remove_small_avatars' ) );
        add_filter( 'bbp_after_get_topic_author_link_parse_args', array( $this, 'remove_small_avatars' ) );
        add_action( 'bbp_theme_before_topic_meta', array( $this, 'begin_bigger_avatars') );
        add_action( 'bbp_theme_after_topic_meta', array( $this, 'end_bigger_avatars') );

        // Subforums.
        add_action( 'bbp_theme_before_forum_sub_forums', array( $this, 'alter_sub_forums' ) );
        remove_action( 'bbp_theme_after_forum_sub_forums', array( $this, 'alter_sub_forums' ) );

        // Breadcrumb
        add_action( 'wp', array( $this, 'switch_breadcrumb_nav') );
        add_filter( 'bbp_after_get_breadcrumb_parse_args', array( $this, 'adjust_breadcrumb_markup' ) );
        add_filter( 'bbp_no_breadcrumb', '__return_true' );
        add_filter( 'bimber_show_breadcrumbs', array( $this, 'force_bimber_show_breadcrumbs' ) );

        // Notices.
        add_filter( 'bbp_after_get_single_forum_description_parse_args', array( $this, 'adjust_notice_markup' ) );
        add_filter( 'bbp_after_get_single_topic_description_parse_args', array( $this, 'adjust_notice_markup' ) );

        // Fix nav menu CSS class
        add_filter( 'nav_menu_css_class', array( $this, 'fix_nav_menu_css_class' ), 10, 2 );



        // Markup.
        add_filter( 'bbp_get_reply_author_link', array( $this, 'markup_bbp_get_reply_author_link' ), 10, 2 );

        add_filter( 'bbp_before_get_reply_admin_links_parse_args', array( $this, 'bbp_before_get_reply_admin_links_parse_args' ) );
        add_filter( 'bbp_get_logout_link', array( $this, 'adjust_logout_link'), 10, 2 );
        add_filter( 'bbp_get_user_subscribe_link', array( $this, 'get_user_subscribe_link' ), 10, 4 );
    }

    public function init() {
        add_post_type_support( 'forum', 'thumbnail' );
    }


    /**
     * Enqueue bbPress Plugin integration assets.
     */
    public function enqueue_head_styles() {
        $version = bimber_get_theme_version();
        $stack = bimber_get_current_stack();
        $skin = bimber_get_theme_option( 'global', 'skin' );

        $uri = trailingslashit( get_template_directory_uri() );
        $uri .= 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack;

        // Enqueue global styles.
        wp_enqueue_style( 'bimber-bbpress', $uri . '/bbpress-' . $skin . '.min.css', array(), $version );
        wp_style_add_data( 'bimber-bbpress', 'rtl', 'replace' );


        /**
         * Conditionally load page-specific CSS
         *
         * The whole CSS is divided into multiple files for performance optimization.
         */
        $bbp_load_css_conditionally = apply_filters( 'bimber_bbp_load_css_conditionally', true );

        if ( !$bbp_load_css_conditionally || bbp_is_single_user() ) {
            wp_enqueue_style( 'bimber-bbpress-users-single', $uri . '/bbpress-users-single-' . $skin . '.min.css', array(), $version );
            wp_style_add_data( 'bimber-bbpress-users-single', 'rtl', 'replace' );
        }
    }




    public function adjust_notice_markup( $r ) {
        $r['before'] = str_replace(
            '<div class="bbp-template-notice info">',
            '<div class="g1-message g1-message-info bbp-template-notice info"><div class="g1-message-inner">',
            $r['before']
        );

        $r['after'] = str_replace(
            '</div>',
            '</div></div>',
            $r['after']
        );

        return $r;
    }




    public function fix_nav_menu_css_class( $classes, $item ) {
        $blog_page_id = (int) get_option( 'page_for_posts' );
        $post_type = get_post_type();

        if ( in_array( $post_type, array('forum', 'topic', 'reply' ) ) ) {
            // Remove current_page_parent class from the blog index page
            if ( $blog_page_id == $item->object_id && 'page' == $item->object ) {

                $classes = array_diff( $classes, array( 'current_page_parent') );
            }
        }

        return $classes;
    }


    public function get_user_subscribe_link( $html, $r, $user_id, $object_id ) {
        $html = str_replace(
            'class="subscription-toggle"',
            'class="subscription-toggle g1-button g1-button-xs g1-button-simple"',
            $html
        );

        return $html;
    }


    public function switch_breadcrumb_nav() {
        if ( is_bbpress() ) {
            remove_action( 'g1_content_begin', 'g1_add_breadcrumbs'  );
            add_action( 'g1_content_begin', array( $this, 'render_breadcrumb' ) );
        }
    }

    public function render_breadcrumb() {
        add_filter( 'bbp_no_breadcrumb', '__return_false' );
        bbp_breadcrumb();
        add_filter( 'bbp_no_breadcrumb', '__return_true' );
    }


    /**
     * Adjusts breadcrumb navigation markup to match the one from theme
     *
     * @param $args
     * @return mixed
     */
    public function adjust_breadcrumb_markup( $args ) {
        $args['before']         = '<nav class="g1-nav-breadcrumbs g1-meta"><p class="assistive-text">' . __( 'You are here:', 'g1_theme' ) . '</p><ol>';
        $args['after']          = '</ol></nav>';
        $args['sep']            = '';
        $args['sep_before']     = '';
        $args['sep_after']      = '';
        $args['crumb_before']   = '<li class="g1-nav-breadcrumbs__item">';
        $args['crumb_after']    = '</li>';
        $args['current_before'] = '';
        $args['current_after']  = '';
        $args['home_text']      = __( 'Home', 'g1_theme' );

        return $args;
    }


    public function force_bimber_show_breadcrumbs( $bool ) {
        $bool = $bool || is_bbpress();
        $bool = $bool && !bbp_is_forum_archive();

        return $bool;
    }

    public function adjust_logout_link( $link, $redirect_to ) {
        $link = str_replace( 'class="button ', 'class="g1-button g1-button-s g1-button-solid button', $link );

        return $link;
    }


    /**
     * Adjust markup
     *
     * @param $html
     * @param $r
     *
     * @return mixed
     */
    public function markup_bbp_get_reply_author_link( $html, $r ) {
        $html = str_replace( 'class="bbp-author-name"', 'class="g1-epsilon g1-epsilon-1st bbp-author-name"', $html );

        return $html;
    }

    public function bbp_before_get_reply_admin_links_parse_args( $r ) {
        $r['sep'] = ' &middot; ';

        return $r;
    }



    public function alter_sub_forums() {
        add_filter( 'bbp_after_list_forums_parse_args', array( $this, 'bbp_after_list_forums_parse_args' ) );
    }

    public function bbp_after_list_forums_parse_args( $args ) {
        $args['before'] = '<div class="g1-links"><ul class="bbp-forums-list">';
        $args['after'] = '</ul></div>';
        $args['separator'] = '';

        return $args;
    }

    public function begin_bigger_avatars() {
        add_filter( 'bbp_after_get_topic_author_link_parse_args', array( $this, 'make_bigger_avatars' ) );
    }

    public function end_bigger_avatars() {
        remove_filter( 'bbp_after_get_topic_author_link_parse_args', array( $this, 'make_bigger_avatars' ) );
    }

    public function make_bigger_avatars( $args ) {
        $args['size'] = 40;

        return $args;
    }

    public function remove_small_avatars( $args ) {
        if ( 'both' === $args['type'] && 14 === $args['size'] ) {
//            $args['type'] = 'name';
            $args['size'] = 20;
        }

        return $args;
    }

    public function add_widget_class( $args ) {
        $args['classname'] .= ' g1-links';

        return $args;
    }
}

function G1_BBPress() {
    static $instance;

    if ( !isset( $instance ) )
        $instance = new G1_BBPress();

    return $instance;
}
// Fire in the hole :)
G1_BBPress();










