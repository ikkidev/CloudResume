<?php
/**
 * WordPress Comment Functions
 *
 * @package Commentace
 */

namespace Commentace\Report;

use function Commentace\is_report_email_enabled;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Add meta describing comment's report
 *
 * @param int    $comment_id            Comment ID.
 * @param int    $reported_by           User ID.
 * @param string $report_text           Message.
 */
function report_comment( $comment_id, $reported_by, $report_text ) {
    // Short circuit.
    if ( apply_filters( 'cace_pre_report_comment', false, $comment_id, $reported_by, $report_text ) ) {
        return;
    }

    update_comment_meta( $comment_id, '_commentace_reported_by', get_current_user_id() );
    update_comment_meta( $comment_id, '_commentace_report_text', $report_text );
}

/**
 * Check whether the comment was reported
 *
 * @param int $comment_id       Comment ID
 *
 * @return bool
 */
function is_comment_reported( $comment_id ) {
    $reported_by = get_comment_meta( $comment_id, '_commentace_reported_by', true );

    return ! empty( $reported_by );
}

/**
 * Return ID of a user how reported that comment
 *
 * @param int $comment_id       Comment ID.
 *
 * @return mixed                User ID or false.
 */
function get_comment_reported_by( $comment_id ) {
    return get_comment_meta( $comment_id, '_commentace_reported_by', true );
}

/**
 * Return comment's report message
 *
 * @param int $comment_id       Comment ID.
 *
 * @return mixed                Report message or false.
 */
function get_comment_report_text( $comment_id ) {
    return get_comment_meta( $comment_id, '_commentace_report_text', true );
}

/**
 * Delete meta data indicating that the comment is reported
 *
 * @param int $comment_id      Comment ID.
 */
function delete_comment_report( $comment_id ) {
    delete_comment_meta( $comment_id, '_commentace_reported_by' );
    delete_comment_meta( $comment_id, '_commentace_report_text' );
}

/**
 * Return number of awaiting reports
 *
 * @return int
 */
function count_reported_comments() {
    global $wpdb;

    return (int) $wpdb->get_var(
        "SELECT 
                    count(*) 
                FROM
                    $wpdb->comments c
                LEFT JOIN    
                    $wpdb->commentmeta cm ON c.comment_ID = cm.comment_id 
                WHERE 
                    c.comment_approved = 1 AND
                    cm.meta_key = '_commentace_reported_by'"
    );
}

/**
 * Send email notification to site's admin
 *
 * @param \WP_Comment $comment          Comment object.
 * @param string      $report_text      Report message.
 */
function send_report_notification( $comment, $report_text ) {
    if ( ! is_report_email_enabled() ) {
        return;
    }

    $post   = get_post( $comment->comment_post_ID );

    $switched_locale = switch_to_locale( get_locale() );

    $blogname        = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $comment_content = wp_specialchars_decode( $comment->comment_content );
    $report_content  = wp_specialchars_decode( $report_text );

    $notify_message = sprintf( __( 'A comment on your post "%s" was reported to delete.' ), $post->post_title ) . "\r\n";
    $notify_message .= sprintf( __( 'Comment: %s' ), "\r\n" . $comment_content ) . "\r\n\r\n";
    $notify_message .= sprintf( __( 'Report: %s' ), "\r\n" . $report_content ) . "\r\n\r\n";
    $notify_message .= __( 'You can see all reports here:' ) . "\r\n";
    $notify_message .= get_admin_url( 'edit-comments.php?comment_status=reported' ) . "\r\n\r\n";

    $notify_message = apply_filters( 'cace_report_notification_text', $notify_message, $comment->comment_ID );

    $wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', wp_parse_url( network_home_url(), PHP_URL_HOST ) );
    $from = "From: \"$blogname\" <$wp_email>";

    $message_headers = "$from\n"
        . 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . "\"\n";

    if ( isset( $reply_to ) ) {
        $message_headers .= $reply_to . "\n";
    }

    $subject = sprintf( __( '[%1$s] Comment reported on your post "%2$s"' ), $blogname, $post->post_title );

    $subject = apply_filters( 'cace_report_notification_subject', $subject, $comment->comment_ID );

    $message_headers = apply_filters( 'cace_report_notification_headers', $message_headers, $comment->comment_ID );

    if ( is_multisite() ) {
        $admin_email = get_site_option('new_admin_email');
    } else {
        $admin_email = get_option('new_admin_email');
    }

    wp_mail( $admin_email, wp_specialchars_decode( $subject ), $notify_message, $message_headers );

    if ( $switched_locale ) {
        restore_previous_locale();
    }
}
