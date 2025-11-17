<?php
/**
 * Ajax Controller class
 *
 * @package CommentAce
 */

namespace Commentace;

use Commentace\Ajax\Response;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}

class Ajax_Controller {

    public function __construct() {
        $this->add_hooks();
    }

    protected function add_hooks() {
        // Parse embeds inside a comment.
        add_filter( 'comment_text', 'Commentace\parse_comment_embeds', 10, 1 );

        // Trim the comment to the defined length.
        add_filter( 'preprocess_comment', 'Commentace\trim_comment', 10, 1 );

        // Handle comment submission.
        add_action( 'wp_ajax_commentace_comment', array( $this, 'comment' ) );
        add_action( 'wp_ajax_nopriv_commentace_comment', array( $this, 'comment' ) );

        // Handle vote.
        add_action( 'wp_ajax_commentace_vote', array( $this, 'vote' ) );
        add_action( 'wp_ajax_nopriv_commentace_vote', array( $this, 'vote' ) );

        // Handle report.
        add_action( 'wp_ajax_commentace_report', array( $this, 'report' ) );

        // Handle reject report.
        add_action( 'wp_ajax_commentace_reject_report', array( $this, 'reject_report' ) );
    }

    /**
     * Vote handler action
     */
    public function vote() {
        check_ajax_referer( 'commentace-action', 'security' );

        // Comment id.
        $comment_id = (int) filter_input( INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT );

        if ( 0 === $comment_id ) {
            Response::error( 'Comment ID not set' );
            exit;
        }

        $user_id = get_current_user_id();
        $vote_added = filter_input( INPUT_POST, 'vote_added', FILTER_SANITIZE_STRING );
        $vote_removed = filter_input( INPUT_POST, 'vote_removed', FILTER_SANITIZE_STRING );

        $log = array();

        // Remove user's previous vote, if requested.
        if ($vote_removed) {
            // Find vote.
            $vote = Votes::find_by_user_comment( $user_id, $comment_id, $vote_removed );

            if ( $vote ) {
                try {
                    $vote->delete();

                    $log[] = sprintf( 'Vote (ID: %d, type: %s) removed', $vote->get_id(), $vote_removed );
                } catch ( \Exception $e ) {
                    Response::error( $e->getMessage() );
                    exit;
                }
            }
        }

        if ( $vote_added ) {
            // Vote not exists, create a new one.
            try {
                $vote = new Vote();
                $vote->set_comment_id( $comment_id );
                $vote->set_value( $vote_added );
                $vote->set_author_id( $user_id );
                $vote->set_author_ip( get_client_ip() );
                $vote->set_author_host( get_client_host() );

                $vote->save();

                $log[] = sprintf( 'Vote (ID: %d, type: %s) added', $vote->get_id(), $vote_added );
            } catch (\Exception $e) {
                Response::error( $e->getMessage() );
                exit;
            }
        }

        Response::success( implode( '. ', $log ), array(
            'vote_id'       => $vote->get_id(),
        ) );
        exit;
    }

    /**
     * Comment submission handler action
     */
    public function comment() {
        check_ajax_referer( 'commentace-action', 'security' );

        $post_data = wp_unslash( $_POST );

        $comment = wp_handle_comment_submission( $post_data );

        if ( is_wp_error( $comment ) ) {
            Response::error( $comment->get_error_message(), array(
                'error_data' => $comment->get_error_data(),
            ) );
            exit;
        }

        $comment_status = wp_get_comment_status( $comment );

        ob_start();
            global $comment_depth;

            // Store original value.
            $orig_comment_depth = $comment_depth;

            // For get_comment_class() that uses global value.
            $comment_depth = (int) $post_data['parent_depth'] + 1;

            render_comment_item_callback(
                $comment,
                array(
                    'cace_is_new' => true
                ),
                $comment_depth
            );

            // Restore.
            $comment_depth = $orig_comment_depth;
        $html = ob_get_clean();

        Response::success( sprintf( _x( 'Commend %d successfully added.', 'Comment Submission', 'cace' ), $comment->comment_ID ), array(
            'comment_html'   => $html,
            'comment_status' => $comment_status,
        ) );
        exit;
    }

    /**
     * Report comment handler action
     */
    public function report() {
        check_ajax_referer( 'commentace-action', 'security' );

        $post_data = wp_unslash( $_POST );

        $comment_id = (int) $post_data['comment_id'];

        if ( ! $comment_id ) {
            Response::error( 'Missing comment ID!' );
            exit;
        }

        $comment = get_comment( $comment_id );

        if ( ! $comment ) {
            Response::error( 'Comment not found!' );
            exit;
        }

        $report_text = trim( $post_data['report_text'] );

        if ( empty( $report_text ) ) {
            Response::error( 'Empty report text!' );
            exit;
        }

        $max_length = get_report_maxlength();

        // Trim if needed.
        if ( $max_length > 0 && mb_strlen( $report_text ) > $max_length ) {
            $report_text = mb_strimwidth( $report_text, 0, $max_length );
        }

        $report_text = apply_filters( 'pre_comment_content', $report_text );

        $comment_status = wp_get_comment_status( $comment );

        if ( 'approved' !== $comment_status ) {
            Response::error( 'Wrong comment status!' );
            exit;
        }

        // Mark as reported.
        Report\report_comment( $comment_id, get_current_user_id(), $report_text );

        ob_start();
        global $comment_depth;

        // Store original value.
        $orig_comment_depth = $comment_depth;

        // For get_comment_class() that uses global value.
        $comment_depth = (int) $post_data['comment_depth'];

        $item_args = apply_filters( 'cace_ajax_reported_comment_args', array() );

        render_comment_item_callback( $comment, $item_args, $comment_depth );

        // Restore.
        $comment_depth = $orig_comment_depth;
        $html = ob_get_clean();

        Report\send_report_notification( $comment, $report_text );

        Response::success( sprintf( _x( 'Commend %d successfully reported.', 'Reporting', 'cace' ), $comment->comment_ID ), array(
            'comment_html'   => $html,
        ) );
        exit;
    }

    /**
     * Reject comment report handler action
     */
    public function reject_report() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        $post_data = wp_unslash( $_POST );

        $comment_id = (int) $post_data['comment_id'];

        if ( ! $comment_id ) {
            Response::error( 'Missing comment ID!' );
            exit;
        }

        $comment = get_comment( $comment_id );

        if ( ! $comment ) {
            Response::error( 'Comment not found!' );
            exit;
        }

        $comment_status = wp_get_comment_status( $comment );

        if ( 'approved' !== $comment_status ) {
            Response::error( 'Wrong comment status!' );
            exit;
        }

        // Mark as reported.
        Report\delete_comment_report( $comment->comment_ID );

        Response::success( sprintf( _x( 'Report rejected.', 'Reporting', 'cace' ), $comment->comment_ID ), array(
            'info' => _x( 'Comment moved to the Approved', 'Reporting', 'cace' )
        ) );
        exit;
    }
}
