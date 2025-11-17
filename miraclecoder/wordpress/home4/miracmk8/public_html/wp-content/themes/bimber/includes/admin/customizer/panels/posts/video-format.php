<?php
/**
 * WP Customizer panel section to handle the Video post format options
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

$wp_customize->add_section( 'bimber_posts_video_section', array(
	'title'    => __( 'Video Post Format', 'bimber' ),
	'priority' => 28,
	'panel'    => 'bimber_posts_panel',
) );


// Template.
$wp_customize->add_setting( $bimber_option_name . '[post_video_template]', array(
	'default'           => $bimber_customizer_defaults['post_video_template'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_post_video_template', array(
	'label'    => __( 'Template', 'bimber' ),
	'section'  => 'bimber_posts_video_section',
	'settings' => $bimber_option_name . '[post_video_template]',
	'type'     => 'select',
	'choices'  => bimber_get_post_templates(),
	'columns'  => 2,
) ) );

// Frame icon.
$wp_customize->add_setting( $bimber_option_name . '[post_video_frame_icon]', array(
	'default'           => $bimber_customizer_defaults['post_video_frame_icon'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_post_video_frame_icon', array(
	'label'    => __( 'Show Featured Image Icon', 'bimber' ),
	'section'  => 'bimber_posts_video_section',
	'settings' => $bimber_option_name . '[post_video_frame_icon]',
	'type'     => 'checkbox',
) );

// Single Featured Media Allow Video.
$wp_customize->add_setting( $bimber_option_name . '[post_video_single_featured_media_allow_video]', array(
	'default'           => $bimber_customizer_defaults['post_video_single_featured_media_allow_video'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_post_video_single_featured_media_allow_video', array(
	'label'    => __( 'Use embeds instead of featured images', 'bimber' ),
	'section'  => 'bimber_posts_video_section',
	'settings' => $bimber_option_name . '[post_video_single_featured_media_allow_video]',
	'type'     => 'checkbox',
) );

// Hide video in post content.
$wp_customize->add_setting( $bimber_option_name . '[post_video_hide_in_content]', array(
	'default'           => $bimber_customizer_defaults['post_video_hide_in_content'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_post_video_hide_in_content', array(
	'label'    => __( 'Hide video in post content', 'bimber' ),
	'section'  => 'bimber_posts_video_section',
	'settings' => $bimber_option_name . '[post_video_hide_in_content]',
	'type'     => 'checkbox',
) );