<?php
/**
 * Settings configuration
 *
 * @package CommentAce
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

function get_settings_config() {
    return apply_filters( 'cace_settings_config', array(
        'cace-settings-wp' => array(
            'title'  => _x( 'WP Comments', 'Settings Page', 'cace' ),
            'fields' => array(
                'cace_wp_enabled' => array(
                    'title'   => _x( 'Enable?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),

                // Appearance.
                'cace_appearance_section' => array(
                    'title'   => '<h2>' . _x( 'Appearance', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                ),
                'cace_design_cards' => array(
                    'title'   => _x( 'Cards Design', 'Settings', 'cace' ),
                    'type'    => 'select',
                    'choices' => array(
                        'none'      => _x( 'none',      'Cards design', 'cace' ),
                        'standard'  => _x( 'solid',     'Cards design', 'cace' ),
                        'simple'    => _x( 'simple',    'Cards design', 'cace' ),
                    ),
                    'default' => 'none',
                ),
                'cace_badge_author' => array(
                    'title'   => _x( 'Show Author Badge?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_collapse_replies' => array(
                    'title'   => _x( 'Collapse Replies On Load?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),

                // Load More.
                'cace_load_more_section' => array(
                    'title'   => '<h2>' . _x( 'Load More', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                ),
                'page_comments' => array(
                    'title'   => _x( 'Enable Load More?', 'Settings', 'cace' ),
                    'description'   => _x( 'If unchecked, all comments are loaded at once', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'value'   => 1,
                    'default' => 0,
                ),
                'cace_load_more_type' => array(
                    'title'   => _x( 'Load More Type', 'Settings', 'cace' ),
                    'type'    => 'select',
                    'choices' => array(
                        'load_more'                 => _x( 'Load More Button', 'Load More Type', 'cace' ),
                        'infinite_scroll_on_demand' => _x( 'Infinite Scroll (first load via click)', 'Load More Type', 'cace' ),
                        'infinite_scroll'           => _x( 'Infinite Scroll', 'Load More Type', 'cace' ),
                    ),
                    'default' => 'infinite_scroll_on_demand',
                ),
                'comments_per_page' => array(
                    'title'   => _x( 'Max Number of Comments To Load At A Time', 'Settings', 'cace' ),
                    'type'    => 'number',
                    'default' => 50,
                    'min'     => 0,
                    'classes' => array( 'small-text' ),
                ),

                // Comment Actions.
                'cace_comment_actions_section' => array(
                    'title'   => '<h2>' . _x( 'Comment Actions', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                ),
                'cace_reporting' => array(
                    'title'   => _x( 'Enable Reporting?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_report_maxlength' => array(
                    'title'       => _x( 'Report Text Length', 'Settings', 'cace' ),
                    'type'        => 'number',
                    'min'         => 1,
                    'placeholder'        => '300',
                    'description' => _x( 'Leave empty to use the default limit.', 'Settings', 'cace' ),
                    'default' => '300',
                    'classes' => array( 'small-text' ),
                ),
                'cace_report_email' => array(
                    'title'   => _x( 'Email Me Whenever Anyone Reports A Comment?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_copy_link' => array(
                    'title'   => _x( 'Enable Copy Link?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),

                // Featured Comments.
                'cace_featured_section' => array(
                    'title'   => '<h2>' . _x( 'Featured Comments', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                ),
                'cace_wp_featured' => array(
                    'title'   => _x( 'Enable Featured Comments?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_wp_featured_theshold' => array(
                    'title'   => _x( 'Min Score To Be Featured', 'Settings', 'cace' ),
                    'type'    => 'number',
                    'default' => 1,
                    'min'     => 1,
                    'placeholder' => '1',
                    'classes' => array( 'small-text' ),
                ),
                'cace_wp_featured_number' => array(
                    'title'   => _x( 'Max Number of Featured Comments', 'Settings', 'cace' ),
                    'type'    => 'number',
                    'default' => 1,
                    'min'     => 1,
                    'placeholder' => '1',
                    'classes' => array( 'small-text' ),
                ),


                // Sorting.
                'cace_sorting_section' => array(
                    'title'   => '<h2>' . _x( 'Sorting', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                ),
                'cace_sorting' => array(
                    'title'   => _x( 'Enable Sorting?', 'Settings', 'cace' ),
                    'inline_description' => ! is_sorting_enabled() ? _x( 'When unchecked, comments are sorted based on WP Dashboard > Settings > Discussion > Other comment settings', 'Settings', 'cace' ) : '',
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_sort_types' => array(
                    'title'       =>
                        _x( 'Sort Types', 'Settings', 'cace' ) .
                        '<p class="description">' . _x( 'Uncheck to disable. Type a custom label to overwrite the default one', 'Settings', 'cace' ) . '</p>',
                    'callback'    => 'sort_types_setting_renderer',
                    'sanitize_callback' => 'sanitize_text_array',
                ),
                'cace_default_sorting' => array(
                    'title'       => _x( 'Default Sorting', 'Settings', 'cace' ),
                    'inline_description' => _x( 'displayed first', 'Settings', 'cace' ),
                    'type'    => 'select',
                    'choices' => array_map( function ( $type ) { return $type['label']; }, get_enabled_sort_types() ),
                    'default' => ( 'asc' === get_option( 'comment_order' ) ? 'oldest' : 'newest' ),
                ),

                // Voting.
                'cace_voting_section' => array(
                    'title'   => '<h2>' . _x( 'Voting', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                ),
                'cace_voting' => array(
                    'title'   => _x( 'Enable Voting?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_guest_voting' => array(
                    'title'   => _x( 'Allow Guest Voting', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'none',
                ),
                'cace_voting_icon' => array(
                    'title'   => _x( 'Voting Icon', 'Settings', 'cace' ),
                    'type' => 'select',
                    'choices' => array(
                        'arrow'     => _x( 'arrow',     'Voting icon', 'cace' ),
                        'caret'     => _x( 'caret',     'Voting icon', 'cace' ),
                        'chevron'   => _x( 'chevron',   'Voting icon', 'cace' ),
                        'plus'      => _x( 'plus',      'Voting icon', 'cace' ),
                        'smile'     => _x( 'smile',     'Voting icon', 'cace' ),
                        'thumb'     => _x( 'thumb',     'Voting icon', 'cace' ),
                    ),
                    'default' => 'arrow',
                ),
                'cace_voting_number_of_votes' => array(
                    'title'   => _x( 'Show Number of Upvotes and Downvotes?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'none',
                ),
                'cace_voting_score' => array(
                    'title'   => _x( 'Show Vote Score?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),

                // Ads.
                'cace_ads_section' => array(
                    'title'   => '<h2>' . _x( 'Ads', 'Settings Section', 'cace' ) . '</h2>',
                    'type'    => 'section',
                    'content' => can_use_plugin( 'ad-ace/ad-ace.php' ) ?
                                sprintf( _x( 'Use the %s ad slot to place ads between comments.', 'Settings', 'cace' ), '<a target="_blank" href="'. esc_url( admin_url( 'options-general.php?page=adace_options&open_slot=adace-after-cace-comments' ) ) .'">'. esc_html( 'After X Comments' ) .'</a>' ) :
                                _x( 'Activate the AdAce plugin to enable ads.', 'Settings', 'cace' ),
                ),
            ),
        ),
        'cace-settings-wp-comment-form' => array(
            'title'  => _x( 'WP Comment Form', 'Settings Page', 'cace' ),
            'fields' => array(
                'cace_comment_form_position' => array(
                    'title'   => _x( 'Form Position', 'Settings', 'cace' ),
                    'type'    => 'select',
                    'choices' => array(
                        CACE_WP_COMMENT_FORM_BEFORE => _x( 'before comments', 'Settings', 'cace' ),
                        CACE_WP_COMMENT_FORM_AFTER  => _x( 'after comments', 'Settings', 'cace' ),
                    ),
                    'default' => CACE_WP_COMMENT_FORM_BEFORE,
                ),
                'cace_comment_maxlength' => array(
                    'title'       => _x( 'Comment Length', 'Settings', 'cace' ),
                    'type'        => 'text',
                    'placeholder'        => '65525',
                    'description' => _x( 'Leave empty to use the characters limit defined by WordPress.', 'Settings', 'cace' ),
                    'default' => '600',
                ),
                'cace_character_countdown' => array(
                    'title'   => _x( 'Enable Character Countdown?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_reply_with_gif' => array(
                    'title'   => _x( 'Enable "Reply with GIF"?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_giphy_app_key' => array(
                    'title'       => _x( 'GIPHY App Key', 'Settings', 'cace' ),
                    'description' => _x( 'Required to access Giphy API and load GIF library', 'Settings', 'cace' ) .
                        sprintf( '. <a href="%s" target="_blank">%s</a>', 'https://bimber.bringthepixel.com/docs/giphy-api/', _x( 'Where do I get it?', 'Settings', 'cace' ) ),
                    'type'        => 'text',
                ),
            ),
        ),
        'cace-settings-fb' => array(
            'title'  => _x( 'Facebook', 'Settings Page', 'cace' ),
            'fields' => array(
                'cace_fb_enabled' => array(
                    'title'   => _x( 'Enable?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_fb_app_id' => array(
                    'title'       => _x( 'Facebook App ID', 'Settings', 'cace' ),
                    'description' => _x( 'Required to access Facebook API and load comments', 'Settings', 'cace' ) .
                                     sprintf( '. <a href="%s" target="_blank">%s</a>', 'https://bimber.bringthepixel.com/docs/facebook-api/', _x( 'Where do I get it?', 'Settings', 'cace' ) ),
                    'type'        => 'text',
                ),
                'cace_fb_comments_number' => array(
                    'title'   => _x( 'Number of Comments', 'Settings', 'cace' ),
                    'type'    => 'number',
                    'default' => 10,
                    'min'     => 1,
                    'placeholder' => '10',
                    'classes' => array( 'small-text' ),
                ),
                'cace_fb_comments_order' => array(
                    'title'   => _x( 'Sort by', 'Settings', 'cace' ),
                    'type' => 'select',
                    'choices' => array(
                        'social'        => _x( 'Social. Uses social signals to surface the highest quality comments', 'Sort order', 'cace' ),
                        'time'          => _x( 'Oldest', 'Sort order', 'cace' ),
                        'reverse_time'  => _x( 'Newest', 'Sort order', 'cace' ),
                    ),
                    'default' => 'social',
                ),
            ),
        ),
        'cace-settings-dsq' => array(
            'title'  => _x( 'Disqus', 'Settings Page', 'cace' ),
            'fields' => array(
                'cace_dsq_enabled' => array(
                    'title'   => _x( 'Enable?', 'Settings', 'cace' ),
                    'type'    => 'checkbox',
                    'default' => 'standard',
                ),
                'cace_dsq_shortname' => array(
                    'title'   => _x( 'Disqus Shortname', 'Settings', 'cace' ),
                    'description' => _x( 'Required to access Disqus API and load comments', 'Settings', 'cace' ) .
                        sprintf( '. <a href="%s" target="_blank">%s</a>', 'https://bimber.bringthepixel.com/docs/disqus-api/', _x( 'Where do I get it?', 'Settings', 'cace' ) ),
                    'type'    => 'text',
                ),
            ),
        ),
        'cace-settings-settings' => array(
            'title'  => _x( 'Settings', 'Settings Page', 'cace' ),
            'fields' => array(
                'cace_post_types' => array(
                    'title'   => _x( 'Enable On Post Types', 'Settings', 'cace' ),
                    'type'    => 'multi_checkbox',
                    'choices' => 'Commentace\get_allowed_post_types',
                    'default' => array( 'post', 'snax_quiz', 'snax_poll', 'snax_item' ),
                    'sanitize_callback' => 'sanitize_text_array',
                ),
            ),
        ),
    ) );
}
