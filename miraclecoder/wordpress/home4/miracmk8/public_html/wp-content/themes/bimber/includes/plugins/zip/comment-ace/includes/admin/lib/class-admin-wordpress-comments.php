<?php
/**
 * WordPress Comment List Functions
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.

use function Commentace\Report\is_comment_reported;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

class Admin_WordPress_Comments {

    public function __construct() {
        $this->register_hooks();
    }

    protected function register_hooks() {
        add_action( 'admin_enqueue_scripts',            array( $this, 'enqueue_comment_list_scripts' ), 10, 1 );
        add_action( 'admin_menu',                       array( $this, 'display_pending_reports_count' ) );
        add_filter( 'views_edit-comments',              array( $this, 'add_comment_list_filters' ), 10, 1 );
        add_filter( 'comments_list_table_query_args',   array( $this, 'apply_comment_list_filters' ), 10, 1 );
        add_filter( 'manage_edit-comments_columns',     array( $this, 'add_comment_list_columns' ), 10, 1 );
        add_action( 'manage_comments_custom_column',    array( $this, 'display_comment_list_column_content' ), 10, 2 );
        add_filter( 'comment_row_actions',              array( $this, 'add_comment_row_actions' ), 10, 2 );

        add_action( 'admin_enqueue_scripts',            array( $this, 'admin_enqueue_styles' ) );

        // Add link to the plugin settings on the Discussion Settions page.
        add_action( 'admin_footer', array( $this, 'add_link_on_discussion_settings_page' ) );

        // Remove comment votes.
        add_action( 'deleted_comment', array( $this, 'deleted_comment' ), 10, 1 );

        // Bulk actions.
        add_filter( 'bulk_actions-edit-comments',        array( $this, 'register_bulk_actions' ), 10, 1 );
        add_filter( 'handle_bulk_actions-edit-comments', array( $this, 'handle_bulk_actions' ), 10, 3 );
        add_action( 'admin_notices',                     array( $this, 'display_bulk_actions_notices' ) );
    }


    public function admin_enqueue_styles() {
        wp_enqueue_style( 'cace-admin-styles', plugin()->get_url() . 'assets/css/admin/style.css', array(), plugin()->get_version() );
    }


    protected function in_reported_view() {
        return 'reported' === cace_htmlspecialchars( filter_input( INPUT_GET, 'cace_filter' ) );
    }

    public function enqueue_comment_list_scripts( $hook ) {
        if( $hook === 'edit-comments.php' && $this->in_reported_view() ) {
            wp_enqueue_script( 'commentace-admin-wp-comment-list',plugin()->get_url() . 'assets/js/admin/wp-comment-list.js', array(), plugin()->get_version() );
        }
    }

    public function display_pending_reports_count() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        global $menu;

        $awaiting_count = Report\count_reported_comments();

        if ( $awaiting_count < 1 ) {
            return;
        }

        $awaiting_count_i18n = number_format_i18n( $awaiting_count );
        $awaiting_count_text = sprintf( _nx( '%s Comment reported', '%s Comments reported', $awaiting_count, 'Reporting', 'cace' ), $awaiting_count_i18n );

        $menu[25][0] .= '<span class="cace-awaiting-reports awaiting-mod count-' . absint( $awaiting_count ) . '"><span class="pending-count" aria-hidden="true">' . $awaiting_count_i18n . '</span><span class="comments-reported-text screen-reader-text">' . $awaiting_count_text . '</span></span>';
    }

    public function add_comment_list_filters( $views ) {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return $views;
        }

        $class            = $this->in_reported_view() ? 'current' : '';
        $query_string     = remove_query_arg( array( 'cace_filter', 'reported' ) );
        $query_string     = remove_query_arg( array( 'comment_status' ), $query_string );
        $query_string     = add_query_arg( 'cace_filter', 'reported', $query_string );

        $count = Report\count_reported_comments();
        $count_html = $count > 0 ? ' <span class="cace-reported-filter-count count">('. $count .')</span>' : '';

        $my_views['reported'] = sprintf( '<a href="%s" class="%s">%s%s</a>', esc_url( $query_string ), esc_attr( $class ), _x( 'Reported', 'Comments Filter', 'cace' ), $count_html );

        $views = array_slice( $views, 0, 3, true ) + $my_views + array_slice( $views, 3, null, true );

        return $views;
    }

    public function add_comment_list_columns( $cols ) {
        if ( ! $this->in_reported_view() ) {
            return $cols;
        }

        $new_cols = array(
            'cace_report' => _x( 'Reported', 'Admin Comment List', 'cace' ),
        );

        $cols = array_slice( $cols, 0, 3, true ) + $new_cols + array_slice( $cols, 3, null, true );

        return $cols;
    }

    public function display_comment_list_column_content( $column, $comment_ID ) {
        if ( ! $this->in_reported_view() ) {
            return;
        }

        if ( 'cace_report' !== $column ) {
            return;
        }

        if ( ! Report\is_comment_reported( $comment_ID ) ) {
            return;
        }

        $reported_by = Report\get_comment_reported_by( $comment_ID );

        $user_link = sprintf( '<a href="%s" target="_blank">%s</a>', get_author_posts_url( $reported_by ), get_the_author_meta( 'display_name', $reported_by ) );
        printf( esc_html_x( 'Reported to delete by %s due:', 'Admin Comment List', 'cace' ), $user_link );
        echo '<br />';
        echo esc_html( Report\get_comment_report_text( $comment_ID ) );
    }

    public function apply_comment_list_filters( $args ) {
        if ( $this->in_reported_view() ) {
            $args['meta_query'] = array(
                array(
                    'key'     => '_commentace_reported_by',
                    'compare' => 'EXISTS',
                )
            );
        }

        return $args;
    }

    public function add_comment_row_actions( $actions, $comment ) {
        if ( ! $this->in_reported_view() ) {
            return $actions;
        }

        $new_actions['reject_report'] = sprintf(
            '<a href="" class="cace-row-action cace-reject-report vim-u aria-button-if-js" data-comment-id="%d" aria-label="%s">%s</a>',
            absint( $comment->comment_ID ),
            esc_attr_x( 'Reject report for this comment', 'Comment List Action', 'cace' ),
            _x( 'Reject report', 'Comment List Action', 'cace' )
        );

        $actions = $new_actions + $actions;

        return $actions;
    }

    public function add_link_on_discussion_settings_page() {
        global $pagenow;

        if ( 'options-discussion.php' !== $pagenow ) {
            return;
        }

        ?>
        <div id="cace_comment_order_link" style="display:none;">
            <?php printf( __( 'You\'re using the CommentAce plugin so please refer to its %s to adjust sorting', 'cace' ), '<a href="' . admin_url( 'admin.php?page=cace-settings-wp' ) . '">' . _x( 'settings', 'Settings Link', 'cace' ) . '</a>' ) ?>
        </div>
        <script type="text/javascript">
            (function() {
                let label = document.querySelector('#comment_order').parentNode;
                let link = document.querySelector('#cace_comment_order_link');

                label.after(link);
                link.style.display = 'block';
            })();
        </script>
        <?php
    }

    /**
     * Fires immediately after a comment is deleted from the database.
     *
     * @param int        $comment_id The comment ID.
     */
    public function deleted_comment( $comment_id ) {
        Votes::delete_comment_votes( $comment_id );
    }

    public function register_bulk_actions( $bulk_actions ) {
        $bulk_actions['cace_reject_report'] = _x( 'Reject report', 'Comment List Action', 'cace');

        return $bulk_actions;
    }

    public function handle_bulk_actions( $redirect_to, $doaction, $comment_ids ) {
        if ( $doaction !== 'cace_reject_report' ) {
            return $redirect_to;
        }

        foreach ( $comment_ids as $comment_id ) {
            Report\delete_comment_report( $comment_id );
        }

        $redirect_to = add_query_arg( 'bulk_rejected_comments', count( $comment_ids ), $redirect_to );

        return $redirect_to;
    }

    public function display_bulk_actions_notices() {
        if ( ! empty( $_REQUEST['bulk_rejected_comments'] ) ) {
            $count = intval( $_REQUEST['bulk_rejected_comments'] );

            printf( '<div id="message" class="updated fade">' .
                _nx( 'Rejected %s reports.',
                    'Rejected %s reports.',
                    $count,
                    'Comment List Action',
                    'cace'
                ) . '</div>', $count );
        }
    }
}
