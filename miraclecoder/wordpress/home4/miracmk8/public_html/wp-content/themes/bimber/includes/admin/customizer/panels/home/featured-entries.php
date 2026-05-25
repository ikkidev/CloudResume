<?php
/**
 * WP Customizer panel section to handle Home > Featured Entires options
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

$wp_customize->add_section( 'bimber_home_featured_entries_section', array(
	'title'    => __( 'Featured Entries', 'bimber' ),
	'priority' => 20,
	'panel'    => 'bimber_home_panel',
) );

// Type.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_home_featured_entries', array(
	'label'    => __( 'Type', 'bimber' ),
	'section'  => 'bimber_home_featured_entries_section',
	'settings' => $bimber_option_name . '[home_featured_entries]',
	'type'     => 'select',
	'choices'  => array(
		'most_shared' => __( 'Most Shared', 'bimber' ),
		'most_viewed' => __( 'Most Viewed', 'bimber' ),
		'recent'      => __( 'Recent', 'bimber' ),
		'none'        => __( 'none', 'bimber' ),
	),
) );

// Title.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_title]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_title'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_home_featured_entries_title', array(
	'label'           => __( 'Title', 'bimber' ),
	'section'         => 'bimber_home_featured_entries_section',
	'settings'        => $bimber_option_name . '[home_featured_entries_title]',
	'type'            => 'text',
	'input_attrs'     => array(
		'placeholder' => __( 'Leave empty to use the default value', 'bimber' ),
	),
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) );

// Hide title.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_title_hide]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_title_hide'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_home_featured_entries_title_hide', array(
	'label'    => __( 'Hide Title', 'bimber' ),
	'section'  => 'bimber_home_featured_entries_section',
	'settings' => $bimber_option_name . '[home_featured_entries_title_hide]',
	'type'     => 'checkbox',
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) );

/**
 * Check whether featured entries are enabled for homepage
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_home_has_featured_entries( $control ) {
	if ( ! bimber_customizer_is_posts_page_selected( $control ) ) {
		return false;
	}

	$type = $control->manager->get_setting( bimber_get_theme_id() . '[home_featured_entries]' )->value();

	return 'none' !== $type;
}

/**
 * Check whether featured entries can use gutter option
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_home_featured_can_use_gutter( $control ) {
	if ( ! bimber_customizer_home_has_featured_entries( $control ) ) {
		return false;
	}

	$template = $control->manager->get_setting( bimber_get_theme_id() . '[home_featured_entries_template]' )->value();

	return 'todo-music' !== $template;
}

/**
 * Check whether featured entries tag filter is supported
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_home_featured_entries_tag_is_active( $control ) {
	$has_featured_entries = bimber_customizer_home_has_featured_entries( $control );

	// Skip if home doesn't use the Featured Entries.
	if ( ! $has_featured_entries ) {
		return false;
	}

	$featured_entries_type = $control->manager->get_setting( bimber_get_theme_id() . '[home_featured_entries]' )->value();

	// The most viewed types doesn't support tag filter.
	if ( 'most_viewed' === $featured_entries_type ) {
		return false;
	}

	return apply_filters( 'bimber_customizer_home_featured_entries_tag_is_active', true );
}

// Template.
$bimber_featured_entries_uri = BIMBER_ADMIN_DIR_URI . 'images/templates/featured-entries/';
$bimber_featured_entries_template_choices = array(
	'1-sidebar' => array(
		'label' => '1-sidebar',
		'path'  => $bimber_featured_entries_uri . '1-sidebar.png',
	),
	'1-sidebar-bunchy' => array(
		'label' => '1-sidebar-bunchy',
		'path'  => $bimber_featured_entries_uri . '1-sidebar-bunchy.png',
	),
	'2-2-boxed' => array(
		'label' => '2-2-boxed',
		'path'  => $bimber_featured_entries_uri . '2-2-boxed.png',
	),
	'2-2-stretched' => array(
		'label' => '2-2-stretched',
		'path'  => $bimber_featured_entries_uri . '2-2-stretched.png',
	),
	'3-3-3-boxed' => array(
		'label' => '3-3-3-boxed',
		'path'  => $bimber_featured_entries_uri . '3-3-3-boxed.png',
	),
	'3-3-3-stretched' => array(
		'label' => '3-3-3-stretched',
		'path'  => $bimber_featured_entries_uri . '3-3-3-stretched.png',
	),
	'2-4-4-boxed' => array(
		'label' => '2-4-4-boxed',
		'path'  => $bimber_featured_entries_uri . '2-4-4-boxed.png',
	),
	'2-4-4-stretched' => array(
		'label' => '2-4-4-stretched',
		'path'  => $bimber_featured_entries_uri . '2-4-4-stretched.png',
	),
	'2of3-3v-3v-boxed' => array(
		'label' => '2of-3v-3v-boxed',
		'path'  => $bimber_featured_entries_uri . '2of3-3v-3v-boxed.png',
	),
	'2of3-3v-3v-stretched' => array(
		'label' => '2of-3v-3v-stretched',
		'path'  => $bimber_featured_entries_uri . '2of3-3v-3v-stretched.png',
	),
	'4-4-4-4-boxed' => array(
		'label' => '4-4-4-4-boxed',
		'path'  => $bimber_featured_entries_uri . '4-4-4-4-boxed.png',
	),
	'4-4-4-4-stretched' => array(
		'label' => '4-4-4-4-stretched',
		'path'  => $bimber_featured_entries_uri . '4-4-4-4-stretched.png',
	),
	'todo-music' => array(
		'label' => 'todo-music',
		'path'  => $bimber_featured_entries_uri . 'todo-music.png',
	),
	'3-3v-3v-3v-3v-boxed' => array(
		'label' => '3-3v-3v-3v-3v-boxed',
		'path'  => $bimber_featured_entries_uri . '3-3v-3v-3v-3v-boxed.png',
	),
	'3-3v-3v-3v-3v-stretched' => array(
		'label' => '3-3v-3v-3v-3v-stretched',
		'path'  => $bimber_featured_entries_uri . '3-3v-3v-3v-3v-stretched.png',
	),
	'module-01'	=> array(
		'label' => 'module-01',
		'path'  => $bimber_featured_entries_uri . '1-sidebar-bunchy.png',
	),
	'todo-fashion' => array(
		'label' => 'todo-fashion',
		'path'  => $bimber_featured_entries_uri . 'todo-fashion.png',
	),
);


$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_template]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_template'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_home_featured_entries_template', array(
	'label'    => __( 'Template', 'bimber' ),
	'section'  => 'bimber_home_featured_entries_section',
	'settings' => $bimber_option_name . '[home_featured_entries_template]',
	'type'     => 'select',
	'columns'  => 3,
	'choices'  => $bimber_featured_entries_template_choices,
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) ) );

// Gutter.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_gutter]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_gutter'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_home_featured_entries_gutter', array(
	'label'    => __( 'Gutter', 'bimber' ),
	'section'  => 'bimber_home_featured_entries_section',
	'settings' => $bimber_option_name . '[home_featured_entries_gutter]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
	'active_callback' => 'bimber_customizer_home_featured_can_use_gutter',
) );

// Category.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_category]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_category'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'bimber_sanitize_multi_choice',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_home_featured_entries_category', array(
	'label'           => __( 'Categories', 'bimber' ),
	'section'         => 'bimber_home_featured_entries_section',
	'settings'        => $bimber_option_name . '[home_featured_entries_category]',
	'choices'         => bimber_customizer_get_category_choices(),
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) ) );

// Tag.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_tag]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_tag'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'bimber_sanitize_multi_choice',
) );
$wp_customize->add_control( new Bimber_Customize_Tag_Select_Control( $wp_customize, 'bimber_home_featured_entries_tag', array(
	'label'           => __( 'Tags', 'bimber' ),
	'section'         => 'bimber_home_featured_entries_section',
	'settings'        => $bimber_option_name . '[home_featured_entries_tag]',
	'active_callback' => 'bimber_customizer_home_featured_entries_tag_is_active',
) ) );

// Featured Entries Time range.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_time_range]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_time_range'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_home_featured_entries_time_range', array(
	'label'           => __( 'Time Range', 'bimber' ),
	'section'         => 'bimber_home_featured_entries_section',
	'settings'        => $bimber_option_name . '[home_featured_entries_time_range]',
	'type'            => 'select',
	'choices'         => array(
		'day'   => __( 'Last 24 hours', 'bimber' ),
		'week'  => __( 'Last 7 days', 'bimber' ),
		'month' => __( 'Last 30 days', 'bimber' ),
		'all'   => __( 'All time', 'bimber' ),
	),
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) );

// Hide Elements.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_hide_elements]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_hide_elements'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_home_featured_entries_hide_elements', array(
	'label'           => __( 'Hide Elements', 'bimber' ),
	'section'         => 'bimber_home_featured_entries_section',
	'settings'        => $bimber_option_name . '[home_featured_entries_hide_elements]',
	'choices'         => apply_filters( 'bimber_home_featured_entries_hide_elements_choices', array(
		'shares'        => __( 'Shares', 'bimber' ),
		'views'         => __( 'Views', 'bimber' ),
		'comments_link' => __( 'Comments Link', 'bimber' ),
		'categories'    => __( 'Categories', 'bimber' ),
		'call_to_action'=> __( 'Call to Action', 'bimber' ),
	) ),
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) ) );

// Call To Action Hide Buttons.
$wp_customize->add_setting( $bimber_option_name . '[home_featured_entries_call_to_action_hide_buttons]', array(
	'default'           => $bimber_customizer_defaults['home_featured_entries_call_to_action_hide_buttons'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_home_featured_entries_call_to_action_hide_buttons', array(
	'label'           => __( 'Call to Action - Hide Buttons', 'bimber' ),
	'section'         => 'bimber_home_featured_entries_section',
	'settings'        => $bimber_option_name . '[home_featured_entries_call_to_action_hide_buttons]',
	'choices'         => bimber_get_post_call_to_action_buttons(),
	'active_callback' => 'bimber_customizer_home_has_featured_entries',
) ) );
