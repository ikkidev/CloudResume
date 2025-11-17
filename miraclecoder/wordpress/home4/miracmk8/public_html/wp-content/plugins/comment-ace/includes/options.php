<?php
/**
 * Options
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Check wherever the WP comments type is enabled
 *
 * @return bool
 */
function is_wp_enabled() {
    return 'standard' === get_option( 'cace_wp_enabled', 'standard' );
}

/**
 * Check whenever to show an author badge
 *
 * @return bool
 */
function show_author_badge() {
    return 'standard' === get_option( 'cace_badge_author', 'standard' );
}

/**
 * Check whenever to collapse replies on load
 *
 * @return bool
 */
function collapse_replies() {
    return 'standard' === get_option( 'cace_collapse_replies', 'standard' );
}

/**
 * Return the Load More type
 *
 * @return string
 */
function get_load_more_type() {
    return get_option( 'cace_load_more_type', 'infinite_scroll_on_demand' );
}

/**
 * Check whenever the Reporting is enabled
 *
 * @return bool
 */
function is_reporting_enabled() {
    return 'standard' === get_option( 'cace_reporting', 'standard' );
}

/**
 * Get max length of the Report Text
 *
 * @return integer
 */
function get_report_maxlength() {
    return (int) get_option( 'cace_report_maxlength', 300 );
}

/**
 * Check whenever to send report notification
 *
 * @return bool
 */
function is_report_email_enabled() {
    return 'standard' === get_option( 'cace_report_email', 'standard' );
}

/**
 * Check whenever the Copy Link is enabled
 *
 * @return bool
 */
function is_copy_link_enabled() {
    return 'standard' === get_option( 'cace_copy_link', 'standard' );
}


/**
 * Check whenever the Featured Comments are enabled
 *
 * @return bool
 */
function are_featured_comments_enabled() {
    return 'standard' === get_option( 'cace_wp_featured', 'standard' );
}

/**
 * Return a number of votes required to treat a comment as the featured
 *
 * @return integer
 */
function get_featured_comments_threshold() {
    return (int) get_option( 'cace_wp_featured_theshold', 1 );
}

/**
 * Return a number of the featured comments
 *
 * @return integer
 */
function get_featured_comments_number() {
    return (int) get_option( 'cace_wp_featured_number', 1 );
}

/**
 * Check whenever the sorting is enabled
 *
 * @return bool
 */
function is_sorting_enabled() {
    return 'standard' === get_option( 'cace_sorting', 'standard' );
}

/**
 * Return defined sort types
 *
 * @return array
 */
function get_enabled_sort_types() {
    $types = get_option( 'cace_sort_types', false );
    $active_types = get_active_sort_types();

    if ( ! $types ) {
        $types = $active_types;
    }

    $all_types = get_all_sort_types();

    foreach ( $types as $type_id => $type ) {
        $is_active  = isset( $active_types[ $type_id ] );
        $is_enabled = isset( $type['enabled'] ) && 'standard' === $type['enabled'];

        if ( ! $is_active || ! $is_enabled ) {
            unset( $types[ $type_id ] );
            continue;
        }

        if ( empty( $type['label'] ) ) {
            $types[$type_id]['label'] = $all_types[ $type_id ]['label'];
        }
    }

    return $types;
}

/**
 * Check wherever the type is enabled
 *
 * @param string $type      Sort type.
 * @return bool
 */
function is_sort_type_enabled ($type ) {
    $enabled = get_enabled_sort_types();

    return isset( $enabled[ $type ] );
}

/**
 * Return active sort types
 *
 * @return array
 */
function get_active_sort_types() {
    $active_types = get_all_sort_types();

    if ( ! is_voting_enabled() ) {
        unset( $active_types['top'] );
        unset( $active_types['most_voted'] );
    }

    return $active_types;
}

/**
 * Return default sort types
 *
 * @return array
 */
function get_all_sort_types() {
    return array(
        'top'        => array(
            'enabled' => 'standard',
            'label'   => _x( 'Top', 'Sort order', 'cace' ),
        ),
        'most_voted' => array(
            'enabled' => 'standard',
            'label'   => _x( 'Most Voted', 'Sort order', 'cace' ),
        ),
        'newest'     => array(
            'enabled' => 'standard',
            'label'   => _x( 'Newest', 'Sort order', 'cace' ),
        ),
        'oldest'     => array(
            'enabled' => 'standard',
            'label'   => _x( 'Oldest', 'Sort order', 'cace' ),
        ),
    );
}

/**
 * Return the default sorting order
 *
 * @return bool
 */
function get_default_sorting() {
    // WordPress > Discussion setting.
    $default = ( 'asc' === get_option( 'comment_order' ) ? 'oldest' : 'newest' );

    $sorting = get_option( 'cace_default_sorting', $default );

    $enabled_sort_types = get_enabled_sort_types();

    // Is any sort type enabled?
    if ( ! empty( $enabled_sort_types ) ) {
        // Is sorting enabled?
        if ( ! isset( $enabled_sort_types[ $sorting ] ) ) {
            $type_ids = array_keys( $enabled_sort_types );

            // Fall back to the first enabled type.
            $sorting = $type_ids[0];
        }
    } else {
        // If no types to use, fall back to WordPress settings.
        $sorting = $default;
    }

    return $sorting;
}

/**
 * Return the comment form position
 *
 * @return string
 */
function get_comment_form_position() {
    return get_option( 'cace_comment_form_position', CACE_WP_COMMENT_FORM_BEFORE );
}

/**
 * Check whenever to allow users to reply with GIFs
 *
 * @return bool
 */
function reply_with_gif() {
    return 'standard' === get_option( 'cace_reply_with_gif', 'standard' );
}

/**
 * Return GIPHY App ID
 *
 * @return string
 */
function get_giphy_app_key() {
    return get_option( 'cace_giphy_app_key', '' );
}

/**
 * Check whenever to show character countdown
 *
 * @return bool
 */
function character_countdown() {
	return 'standard' === get_option( 'cace_character_countdown', 'standard' );
}

/**
 * Get max length of a single comment
 *
 * @return integer
 */
function get_comment_maxlength() {
	return (int) get_option( 'cace_comment_maxlength' );
}

/**
 * Check whenever the voting is enabled
 *
 * @return bool
 */
function is_voting_enabled() {
	return 'standard' === get_option( 'cace_voting', 'standard' );
}

/**
 * Check whenever guests can vote
 *
 * @return bool
 */
function is_guest_voting_enabled() {
    return 'standard' === get_option( 'cace_guest_voting', 'none' );
}

/**
 * Return the voting icon type
 *
 * @return string
 */
function get_voting_icon() {
    return get_option( 'cace_voting_icon', 'arrow' );
}


function show_number_of_votes() {
	return 'standard' === get_option( 'cace_voting_number_of_votes', 'standard' );
}

function show_vote_score() {
	return 'standard' === get_option( 'cace_voting_score', 'standard' );
}

/**
 * Facebook
 */

/**
 * Check wherever the FB comments type is enabled
 *
 * @return bool
 */
function is_fb_enabled() {
    return 'standard' === get_option( 'cace_fb_enabled', 'none' );
}

/**
 * Return FB App ID
 *
 * @return string
 */
function get_fb_app_id() {
    return get_option( 'cace_fb_app_id', '' );
}

/**
 * Return number of comments to show on load
 *
 * @return string
 */
function get_fb_comments_number() {
    return get_option( 'cace_fb_comments_number', 5 );
}

/**
 * Return comments order type
 *
 * @return string
 */
function get_fb_comments_order() {
    return get_option( 'cace_fb_comments_order', 'social' );
}

/**
 * Return color scheme
 *
 * @return string
 */
function get_fb_color_scheme() {
    return apply_filters( 'cace_fb_color_scheme', 'light' );
}

/**
 * Check wherever the FB comments type is enabled
 *
 * @return bool
 */
function is_dsq_enabled() {
    return 'standard' === get_option( 'cace_dsq_enabled', 'none' );
}

/**
 * Return Disqus unique shortname
 *
 * @return string
 */
function get_dsq_shortname() {
    return get_option( 'cace_dsq_shortname', '' );
}

/**
 * Return a list of post types to enable the Comment on
 *
 * @return array
 */
function get_enabled_post_types() {
    return get_option( 'cace_post_types', array( 'post', 'snax_quiz', 'snax_poll', 'snax_item' ) );
}
