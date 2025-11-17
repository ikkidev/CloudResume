<?php
/**
 * WordPress Comments class
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class WordPress_Comments extends Comment_Type {

    protected $featured_comment_ids = array();

    static $localized;


    public function get_comment_count() {
        return get_comments_number();
    }

    /**
     * Register WordPress Comments type specific actions and filters
     */
    protected function add_hooks() {
        parent::add_hooks();

        // Change comments query arguments.
        add_filter( 'comments_template_query_args', array( $this, 'change_comments_order' ), 10, 1 );

        add_action( 'pre_get_comments', array( $this, 'exclude_featured_comments' ), 10, 1 );

        // Change comments query clauses.
        add_filter( 'comments_clauses', array( $this, 'change_comments_clauses' ), 10, 2 );

        // Change comments rendering callback.
        add_filter( 'wp_list_comments_args', array( $this, 'change_list_comments_callback' ), 10, 1 );

        // Parse embeds inside a comment.
        add_filter( 'comment_text', 'Commentace\parse_comment_embeds', 10, 1 );

        // Trim the comment to the defined length.
        add_filter( 'preprocess_comment', 'Commentace\trim_comment', 10, 1 );

        // Set defaults.
        add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults'), 99 );
        add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults_xl'), 99 );

        // Adjust Discussion options.
        add_filter( 'pre_option_default_comments_page', array( $this, 'adjust_option_default_comments_page' ), 10, 1 );
        add_filter( 'pre_option_comment_order', array( $this, 'adjust_option_comment_order' ), 10, 1 );

        // Comment classes.
        add_filter( 'comment_class', array( $this, 'comment_class' ), 10, 5 );

        add_filter( 'cancel_comment_reply_link', array( $this, 'cancel_comment_reply_link' ), 10, 3);
    }

    public function cancel_comment_reply_link( $formatted_link, $link, $text ) {
        $formatted_link = str_replace( '<a ', '<a class="g1-button g1-button-xs g1-button-subtle" ', $formatted_link  );

        return $formatted_link;
    }


    public function comment_form_defaults( $defaults ) {
        $defaults['class_form'] .= ' comment-form-blur';

        // Placeholders.
        $defaults['comment_field'] = str_replace(
            'id="comment"',
            'id="comment" data-cace-start-discussion="' . esc_attr__( 'Start the discussion&hellip;', 'cace' ) .  '" data-cace-join-discussion="' . esc_attr__( 'Join the discussion&hellip;', 'cace' ) . '"',
            $defaults['comment_field'] );

        if ( reply_with_gif() ) {
            $defaults['comment_field'] = str_replace(
                'class="comment-form-comment',
                'class="comment-form-comment comment-form-comment-with-reply-with-gif',
                $defaults['comment_field']
            );
        }

        if ( character_countdown() ) {
            $defaults['comment_field'] = str_replace(
                'class="comment-form-comment',
                'class="comment-form-comment comment-form-comment-with-character-countdown',
                $defaults['comment_field']
            );
        }

        // Comment maxlength.
        $maxlength = get_comment_maxlength();
        if ( $maxlength ) {
            $defaults['comment_field'] = preg_replace(
                '/maxlength=\"[0-9]*\"/',
                'maxlength="' . $maxlength . '""' ,
                $defaults['comment_field'] );
        }

        // Don't show the "Logged in as..." message.
        $defaults['logged_in_as'] = '';

        $defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s">%4$s</button>';

        $defaults['cancel_reply_before'] = '';
        $defaults['cancel_reply_after'] = '';

        return $defaults;
    }

    public function comment_form_defaults_xl( $defaults ) {
        $defaults['class_form'] .= ' comment-form-xl';

        return $defaults;
    }

    /**
     * Filters the value of an existing option before it is retrieved.
     *
     * @param mixed  $pre_option The value to return instead of the option value. This differs
     *                           from `$default`, which is used as the fallback value in the event
     *                           the option doesn't exist elsewhere in get_option().
     *                           Default false (to skip past the short-circuit).
     *
     * @return mixed
     */
    public function adjust_option_default_comments_page( $pre_option ) {
        $order = $this->get_order();

        switch ( $order ) {
            case 'newest':
            case 'top':
            case 'most_voted':
                $pre_option = 'newest';
                break;

            case 'oldest':
                $pre_option = 'oldest';
                break;
        }

        return $pre_option;
    }

    /**
     * Filters the value of an existing option before it is retrieved.
     *
     * @param mixed  $pre_option The value to return instead of the option value. This differs
     *                           from `$default`, which is used as the fallback value in the event
     *                           the option doesn't exist elsewhere in get_option().
     *                           Default false (to skip past the short-circuit).
     *
     * @return mixed
     */
    public function adjust_option_comment_order( $pre_option ) {
        remove_filter( 'pre_option_comment_order', array( $this, 'adjust_option_comment_order' ), 10 );

        $order = $this->get_order();

        add_filter( 'pre_option_comment_order', array( $this, 'adjust_option_comment_order' ), 10, 1 );

        switch ( $order ) {
            case 'newest':
            case 'top':
            case 'most_voted':
                $pre_option = 'desc';
                break;

            case 'oldest':
                $pre_option = 'asc';
                break;
        }

        return $pre_option;
    }

    /**
     * Set comments order
     *
     * @param array $comment_args       Array of WP_Comment_Query arguments.
     *
     * @return array
     */
    public function change_comments_order( $comment_args ) {
        $order = $this->get_order();

        switch ( $order ) {
            case 'oldest':
                $comment_args['orderby'] = 'comment_date_gmt';
                $comment_args['order']   = 'ASC';
                break;

            case 'newest':
                $comment_args['orderby'] = 'comment_date_gmt';
                $comment_args['order']   = 'ASC';
                break;

            case 'top':
                $comment_args['orderby']        = 'voting_score';   // To use the unique $cache_key (wp-includes/class-wp-comment-query.php at line 415)
                $comment_args['cace_orderby']   = 'voting_score';   // To detect when to override the "comments_clauses" (wp-includes/class-wp-comment-query.php at line 897)
                break;

            case 'most_voted':
                $comment_args['orderby']        = 'most_voted';   // To use the unique $cache_key (wp-includes/class-wp-comment-query.php at line 415)
                $comment_args['cace_orderby']   = 'most_voted';   // To detect when to override the "comments_clauses" (wp-includes/class-wp-comment-query.php at line 897)
                break;
        }

        return $comment_args;
    }

    /**
     * Override Comments Query to exclude featured comments
     *
     * @param \WP_Comment_Query $query_obj          Query object.
     *
     * @return void
     */
    public function exclude_featured_comments( $query_obj ) {
        if ( is_admin() ) {
            return;
        }

        if ( apply_filters( 'cace_do_not_exclude_featured_comments', false ) ) {
            return;
        }

        $order = $this->get_order();

        // Top and Most Voted. Skip.
        if ( in_array( $order, array( 'top', 'most_voted' ) ) ) {
            return;
        }

        $this->calc_featured_comments();

        if ( $this->have_featured_comments() ) {
            // Exclude featured comments from the current query.
            $query_obj->query_vars['comment__not_in'] = $this->get_featured_comment_ids();
        }
    }

    /**
     * Calculate curremt post featured comments
     */
    public function calc_featured_comments() {
        $db = plugin()->db();
        $ids = array();
        $post = get_post();
        $limit = get_featured_comments_number();
        $threshold = get_featured_comments_threshold();

        $query_str = "
            SELECT 
                comments.comment_ID comment_id,
                SUM(votes.value) as score
            FROM {$db->wpdb()->comments} comments
            RIGHT JOIN {$db->get_votes_table_name()} votes ON comments.comment_ID = votes.comment_id 
            WHERE comments.comment_post_ID = $post->ID
            AND comments.comment_parent = 0
            GROUP BY comments.comment_ID
            HAVING score >= $threshold
            ORDER BY score DESC
            LIMIT $limit
        ";

        $res = $db->wpdb()->get_results( $query_str );

        if ( ! empty( $res ) ) {
            $ids = array_map( function ( $val ) { return $val->comment_id; }, $res );
        }

        $this->featured_comment_ids = $ids;
    }

    /**
     * Check whether the featured comments exist
     *
     * @return bool
     */
    public function have_featured_comments() {
        $ids = $this->get_featured_comment_ids();

        return count( $ids ) > 0;
    }

    /**
     * Return IDs of the post featured comments, fetch on first attempt
     *
     * @return array
     */
    public function get_featured_comment_ids() {
        return $this->featured_comment_ids;
    }

    /**
     * Check whether to show Featured Comments on the page
     *
     * @param string $page              Page.
     * @param string $max_page          Number of all pages.
     *
     * @return bool
     */
    public function show_featured_comments_on_page( $page = '', $max_page = '' ) {
        if ( empty( $page ) ) {
            $page = (int) get_query_var( 'cpage' );
        }

        if ( empty( $max_page ) ) {
            $max_page = (int) get_comment_pages_count();
        }

        $order = $this->get_order();

        if ( $page > 0 ) {
            // Oldes. Show only on page 1.
            if ( 'oldest' === $order && $page > 1 ) {
                return false;
            }

            // Newest. Show only on last page.
            if ( 'newest' === $order && $page !== $max_page ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Override comments clauses
     *
     * @param string[]         $clauses             An associative array of comment query clauses.
     * @param WP_Comment_Query $comment_query_obj   Current instance of WP_Comment_Query (passed by reference).
     *
     * @return string[]
     */
    public function change_comments_clauses( $clauses, $comment_query_obj ) {
        global $wpdb;

        // Sort by "Top".
        if ( ! empty( $comment_query_obj->query_vars ) && isset( $comment_query_obj->query_vars['cace_orderby'] ) && 'voting_score' === $comment_query_obj->query_vars['cace_orderby'] ) {
            // If meta_value not exists, set it to 0, allowing to sort by it.
            $clauses['fields'] .= ", if(isnull(meta.meta_value),0,meta.meta_value) as voting_score";

            // Use LEFT JOIN to get even rows without the meta_key exists.
            $clauses['join'] .= " LEFT JOIN $wpdb->commentmeta meta ON ( $wpdb->comments.comment_ID = meta.comment_id ) AND ( meta.meta_key = '_commentace_voting_score' )";

            // Order by the score.
            $clauses['orderby'] = "(0 + voting_score) ASC, " . $clauses['orderby'];
        }

        // Sort by "Most voted".
        if ( ! empty( $comment_query_obj->query_vars ) && isset( $comment_query_obj->query_vars['cace_orderby'] ) && 'most_voted' === $comment_query_obj->query_vars['cace_orderby'] ) {
            // If meta_value not exists, set it to 0, allowing to sort by it.
            $clauses['fields'] .= ", if(isnull(meta.meta_value),0,meta.meta_value) as total_votes";

            // Use LEFT JOIN to get even rows without the meta_key exists.
            $clauses['join'] .= " LEFT JOIN $wpdb->commentmeta meta ON ( $wpdb->comments.comment_ID = meta.comment_id ) AND ( meta.meta_key = '_commentace_voting_total_votes' )";

            // Order by the score.
            $clauses['orderby'] = "(0 + total_votes) ASC, " . $clauses['orderby'];
        }

        return $clauses;
    }

    public function change_list_comments_callback( $parsed_args ) {
        if ( ! is_post_type_enabled() ) {
            return $parsed_args;
        }

        $parsed_args['callback'] = '\\Commentace\render_comment_item_callback';

        return $parsed_args;
    }

    /**
     * Return comments current sort order
     *
     * @return string
     */
    public function get_order() {
        $order = cace_htmlspecialchars( filter_input( INPUT_GET, 'comment-order' ) );

        if ( $order ) {
            if ( ! is_sort_type_enabled( $order ) ) {
                $order = false;
            }
        }

        if ( ! $order )  {
            $order = get_default_sorting();
        }

        return $order;
    }

    /**
     * Get unique id
     *
     * @return string
     */
    public function get_id() {
        return CACE_COMMENT_TYPE_WORDPRESS;
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function get_name() {
        return esc_html_x( 'Our site', 'Comment Type Name', 'cace' );
    }

    /**
     * Render comments
     */
    public function render() {
        $this->enqueue_scripts();

        get_template_part( 'wordpress-comments', '', array(
            'comment_type' => $this
        ) );
    }

    /**
     * Render comment form
     *
     * @param string $position      Position id.
     */
    public function render_comment_form( $position ) {
        if ( $position !== get_comment_form_position() ) {
            return;
        }

        if ( ! comments_open() ) {
            get_template_part( 'notices/comments-closed' );
            return;
        }

        $args = apply_filters( 'cace_wp_comment_form_args', array() );

        comment_form( $args );

        if (reply_with_gif()) {
            get_template_part( 'wp-comments/gif-picker' );
        }
    }

    public function render_load_more_link() {
        if ( get_comment_pages_count() <= 1 ) {
            return;
        }

        $link = 'oldest' === $this->get_order() ? get_next_comments_link() : get_previous_comments_link();
        $url = false;

        if ( preg_match( '/href="([^"]+)"/', $link, $matches ) ) {
            $url = $matches[1];
        }

        if ( empty( $url ) ) {
            return;
        }

        $url = add_query_arg( array(
            'comment-order' => $this->get_order(),
        ), $url );

        $load_label    = __( 'Load more comments', 'cace' );
        $loading_label = __( 'Loading comments...', 'cace' );
        $anchor_text   = 'infinite_scroll' === get_load_more_type() ? $loading_label : $load_label;

        printf( '<p class="comment-list-pagination"><a href="%s" class="cace-load-more g1-button g1-button-s g1-button-simple" data-load-label="%s" data-loading-label="%s">%s</a></p>', $url, esc_attr( $load_label ), esc_attr( $loading_label ), esc_html( $anchor_text ) );
    }

    public function comment_class( $classes, $class, $comment_id, $comment, $post_id ) {
        if ( is_admin() && Report\is_comment_reported( $comment_id ) ) {
            $classes[] = 'cace-reported';
        }

        return $classes;
    }

    protected function get_giphy_api_endpoint( $id ) {
        $app_key = get_giphy_app_key();

        if ( empty( $app_key ) ) {
            return '';
        }

        $limit = apply_filters( 'cace_giphy_gifs_limit', 10 );
        $url = '';

        switch ( $id ) {
            case 'search':
                $url = sprintf( 'https://api.giphy.com/v1/gifs/search?api_key=%s&limit=%d', $app_key, $limit );
                break;

            case 'trending':
                $url = sprintf( 'https://api.giphy.com/v1/gifs/trending?api_key=%s&limit=%d', $app_key, $limit );
                break;
        }

        return $url;
    }

    /**
     * Enqueue scripts
     */
    protected function enqueue_scripts() {
        wp_enqueue_script( 'commentace-wp-comments', plugin()->get_url() . 'assets/js/wp-comments.js', array( 'commentace-comments' ), plugin()->get_version() );

        $config = array(
            'guest_can_vote'    => is_guest_voting_enabled(),
            'collapse_replies'  => collapse_replies(),
            'load_more_type'    => get_load_more_type(),
        );

        if (!self::$localized) {
            wp_localize_script( 'commentace-wp-comments', 'commentace_wp', $config );
        }

        wp_enqueue_script( 'commentace-wp-comment-form',   plugin()->get_url() . 'assets/js/wp-comment-form.js', array( 'commentace-wp-comments' ), plugin()->get_version(), true );

        if ( character_countdown() ) {
            wp_enqueue_script( 'commentace-wp-character-countdown', plugin()->get_url() . 'assets/js/wp-character-countdown.js', array( 'commentace-wp-comment-form' ), plugin()->get_version(), true );
        }

        if ( reply_with_gif() ) {
            wp_enqueue_script( 'commentace-gif-picker', plugin()->get_url() . 'assets/js/gif-picker.js', array( 'commentace-wp-comment-form' ), plugin()->get_version(), true );

            if (!self::$localized) {
                wp_localize_script( 'commentace-gif-picker', 'commentace_gif_picker', array(
                    'search_url'   => $this->get_giphy_api_endpoint( 'search' ),
                    'trending_url' => $this->get_giphy_api_endpoint( 'trending' ),
                ) );
            }

            wp_enqueue_script( 'commentace-wp-reply-with-gif', plugin()->get_url() . 'assets/js/wp-reply-with-gif.js', array( 'commentace-gif-picker' ), plugin()->get_version(), true );
        }

        wp_enqueue_script( 'commentace-wp-comment-list',   plugin()->get_url() . 'assets/js/wp-comment-list.js', array( 'commentace-wp-comments' ), plugin()->get_version(), true );
        wp_enqueue_script( 'commentace-wp-comment',   plugin()->get_url() . 'assets/js/wp-comment.js', array( 'commentace-wp-comment-list' ), plugin()->get_version(), true );

        if ( is_voting_enabled() ) {
            wp_enqueue_script( 'commentace-wp-comment-votes', plugin()->get_url() . 'assets/js/wp-comment-votes.js', array( 'jquery', 'commentace-wp-comment' ), plugin()->get_version(), true );
        }

        if ( is_reporting_enabled() ) {
            wp_enqueue_script( 'commentace-wp-comment-report', plugin()->get_url() . 'assets/js/wp-comment-report.js', array( 'jquery', 'commentace-wp-comment' ), plugin()->get_version(), true );
        }

        if ( is_copy_link_enabled() ) {
            wp_enqueue_script( 'commentace-wp-comment-copy-link', plugin()->get_url() . 'assets/js/wp-comment-copy-link.js', array( 'jquery', 'commentace-wp-comment' ), plugin()->get_version(), true );
        }

        self::$localized = true;
    }
}
