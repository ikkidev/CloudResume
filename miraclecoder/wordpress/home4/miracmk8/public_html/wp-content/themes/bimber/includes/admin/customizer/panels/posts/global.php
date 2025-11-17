<?php
/**
 * WP Customizer panel section to handle posts global options
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_option_name = bimber_get_theme_id();

$wp_customize->add_section( 'bimber_posts_global_section', array(
	'title'    => __( 'General', 'bimber' ),
	'priority' => 10,
	'panel'    => 'bimber_posts_panel',
) );


// Enable Popular collection.
$wp_customize->add_setting( $bimber_option_name . '[posts_popular_enable]', array(
	'default'           => $bimber_customizer_defaults['posts_popular_enable'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_popular_enable', array(
	'label'    => sprintf( __( 'Enable "%s" Collection', 'bimber' ), __( 'Popular', 'bimber' ) ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_popular_enable]',
	'type'     => 'checkbox',
) );

// Enable Hot collection.
$wp_customize->add_setting( $bimber_option_name . '[posts_hot_enable]', array(
	'default'           => $bimber_customizer_defaults['posts_hot_enable'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_hot_enable', array(
	'label'    => sprintf( __( 'Enable "%s" Collection', 'bimber' ), __( 'Hot', 'bimber' ) ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_hot_enable]',
	'type'     => 'checkbox',
) );

// Enable Trending collection.
$wp_customize->add_setting( $bimber_option_name . '[posts_trending_enable]', array(
	'default'           => $bimber_customizer_defaults['posts_trending_enable'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_trending_enable', array(
	'label'    => sprintf( __( 'Enable "%s" Collection', 'bimber' ), __( 'Trending', 'bimber' ) ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_trending_enable]',
	'type'     => 'checkbox',
) );

// Ordered By.
$wp_customize->add_setting( $bimber_option_name . '[posts_lists_ordered_by]', array(
	'default'           => $bimber_customizer_defaults['posts_lists_ordered_by'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_lists_ordered_by', array(
	'label'    => __( 'Generate collections based on', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_lists_ordered_by]',
	'type'     => 'select',
	'choices'  => array(
		'views' => __( 'Views', 'bimber' ),
		'votes' => __( 'Votes', 'bimber' ),
	),
) );

// Quick Nav.
$wp_customize->add_setting( $bimber_option_name . '[posts_top_in_menu]', array(
	'default'           => $bimber_customizer_defaults['posts_top_in_menu'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_top_in_menu', array(
	'label'    => __( 'Top in menu', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_top_in_menu]',
	'type'     => 'select',
	'choices'  => array(
		'single'    => __( 'single', 'bimber' ),
		'separate'  => __( 'separate', 'bimber' ),
	),
) );


// Top posts page.
$wp_customize->add_setting( $bimber_option_name . '[posts_top_page]', array(
	'default'           => $bimber_customizer_defaults['posts_top_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_top_page', array(
	'label'    => __( 'Top posts page', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_top_page]',
	'type'     => 'dropdown-pages',
	'active_callback' => 'bimber_customizer_top_in_menu_single',
) );

// Latest posts page.
$wp_customize->add_setting( $bimber_option_name . '[posts_latest_page]', array(
	'default'           => $bimber_customizer_defaults['posts_latest_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_latest_page', array(
	'label'    => __( 'Latest posts page', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_latest_page]',
	'type'     => 'checkbox',
	'active_callback' => 'bimber_customizer_top_in_menu_separate',
) );


// Hot posts page.
$wp_customize->add_setting( $bimber_option_name . '[posts_hot_page]', array(
	'default'           => $bimber_customizer_defaults['posts_hot_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_hot_page', array(
	'label'    => __( 'Hot posts page', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_hot_page]',
	'type'     => 'dropdown-pages',
	'active_callback' => 'bimber_customizer_top_in_menu_separate',
) );


// Popular posts page.
$wp_customize->add_setting( $bimber_option_name . '[posts_popular_page]', array(
	'default'           => $bimber_customizer_defaults['posts_popular_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_popular_page', array(
	'label'    => __( 'Popular posts page', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_popular_page]',
	'type'     => 'dropdown-pages',
	'active_callback' => 'bimber_customizer_top_in_menu_separate',
) );


// Trending posts page.
$wp_customize->add_setting( $bimber_option_name . '[posts_trending_page]', array(
	'default'           => $bimber_customizer_defaults['posts_trending_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_trending_page', array(
	'label'    => __( 'Trending posts page', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_trending_page]',
	'type'     => 'dropdown-pages',
	'active_callback' => 'bimber_customizer_top_in_menu_separate',
) );

// Views Threshold.
$wp_customize->add_setting( $bimber_option_name . '[posts_excerpt_length]', array(
	'default'           => $bimber_customizer_defaults['posts_excerpt_length'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_excerpt_length', array(
	'label'       => __( 'Excerpt Length (in words)', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_excerpt_length]',
	'type'        => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Views Threshold.
$wp_customize->add_setting( $bimber_option_name . '[posts_views_threshold]', array(
	'default'           => $bimber_customizer_defaults['posts_views_threshold'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_views_threshold', array(
	'label'       => __( 'Hide Views', 'bimber' ),
	'description' => __( 'If you fill in any number here, the views for a specific post are not shown until the view count of this number is reached.', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_views_threshold]',
	'type'        => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Fake Views.
$wp_customize->add_setting( $bimber_option_name . '[posts_fake_view_count_base]', array(
	'default'           => $bimber_customizer_defaults['posts_fake_view_count_base'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_fake_view_count_base', array(
	'label'       => __( 'Fake view count base', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_fake_view_count_base]',
	'type'        => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
	// Disable fake views for new submissions.
	$wp_customize->add_setting( $bimber_option_name . '[posts_fake_view_disable_for_new]', array(
		'default'           => $bimber_customizer_defaults['posts_fake_view_disable_for_new'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_posts_fake_view_disable_for_new', array(
		'label'         => __( 'Disable fake views for new submissions', 'bimber' ),
		'description' 	=> __( 'New users\' submitted posts won\'t be affected with fake views.', 'bimber' ),
		'section'       => 'bimber_posts_global_section',
		'settings'      => $bimber_option_name . '[posts_fake_view_disable_for_new]',
		'type'          => 'select',
		'choices'       => array(
			'standard'	=> __( 'Yes', 'bimber' ),
			'none'		=> __( 'No', 'bimber' ),
		),
	) );
}

// Comments Threshold.
$wp_customize->add_setting( $bimber_option_name . '[posts_comments_threshold]', array(
	'default'           => $bimber_customizer_defaults['posts_comments_threshold'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_comments_threshold', array(
	'label'       => __( 'Hide Comments', 'bimber' ),
	'description' => __( 'If you fill in any number here, the comments for a specific post are not shown until the comment count of this number is reached.', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_comments_threshold]',
	'type'        => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Dates.
$wp_customize->add_setting( $bimber_option_name . '[posts_dates]', array(
	'default'           => $bimber_customizer_defaults['posts_dates'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_dates', array(
	'label'       => __( 'Date to Display', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_dates]',
	'type'        => 'select',
	'choices'     => array(
		'publication'     => __( 'Date of publication', 'bimber' ),
		'modification'    => __( 'Date of modification', 'bimber' ),
		'both'            => _x( 'Both', 'both dates', 'bimber' ),
	),
) );

// Timeago.
$wp_customize->add_setting( $bimber_option_name . '[posts_timeago]', array(
	'default'           => $bimber_customizer_defaults['posts_timeago'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_timeago', array(
	'label'       => __( 'Convert date to time ago', 'bimber' ),
	'description' => __( 'Instead of displaying full date, use timestamps like "4 minutes ago", "1 day ago".', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_timeago]',
	'type'        => 'select',
	'choices'     => array(
		'none'     => __( 'Disabled', 'bimber' ),
		'standard' => __( 'Enabled', 'bimber' ),
	),
) );

/**
 * Check whether user chose single link for Top, in menu
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_top_in_menu_single( $control ) {
	$top_in_menu = $control->manager->get_setting( bimber_get_theme_id() . '[posts_top_in_menu]' )->value();

	return 'single' === $top_in_menu;
}

/**
 * Check whether user chose separate links for Top, in menu
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_top_in_menu_separate( $control ) {
	$top_in_menu = $control->manager->get_setting( bimber_get_theme_id() . '[posts_top_in_menu]' )->value();

	return 'separate' === $top_in_menu;
}


// Auto play videos
$wp_customize->add_setting( $bimber_option_name . '[posts_auto_play_videos]', array(
	'default'           => $bimber_customizer_defaults['posts_auto_play_videos'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_auto_play_videos', array(
	'label'    => __( 'Auto play videos in Stream collections', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_auto_play_videos]',
	'type'     => 'checkbox',
) );

// Auto play videos
$wp_customize->add_setting( $bimber_option_name . '[posts_use_gif_player]', array(
	'default'           => $bimber_customizer_defaults['posts_use_gif_player'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_use_gif_player', array(
	'label'    => __( 'Use GIF player', 'bimber' ),
	'section'  => 'bimber_posts_global_section',
	'settings' => $bimber_option_name . '[posts_use_gif_player]',
	'type'     => 'checkbox',
) );

// Use target blank.
$wp_customize->add_setting( $bimber_option_name . '[posts_set_target_blank]', array(
	'default'           => $bimber_customizer_defaults['posts_set_target_blank'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_set_target_blank', array(
	'label'       => __( 'Open links in new window for infinite scroll in collections', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_set_target_blank]',
	'type'        => 'checkbox',
) );

// FB api key.
$wp_customize->add_setting( $bimber_option_name . '[posts_page_waypoints]', array(
	'default'           => $bimber_customizer_defaults['posts_page_waypoints'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_page_waypoints', array(
	'label'       => __( 'Use pagination URLs for infinite scroll in collections', 'bimber' ),
	'section'     => 'bimber_posts_global_section',
	'settings'    => $bimber_option_name . '[posts_page_waypoints]',
	'type'        => 'checkbox',
) );