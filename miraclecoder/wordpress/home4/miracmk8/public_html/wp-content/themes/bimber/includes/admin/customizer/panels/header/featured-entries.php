<?php
/**
 * WP Customizer panel section to handle featured entries options
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

$wp_customize->add_section( 'bimber_featured_entries_section', array(
	'title'    => __( 'Featured Entries', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );


// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_visibility]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_visibility'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_featured_entries_visibility', array(
	'label'    => __( 'Visibility', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_visibility]',
	'choices'  => array(
		'home'        => __( 'Home', 'bimber' ),
		'single_post' => __( 'Single post', 'bimber' ),
		'archive'     => __( 'Archive', 'bimber' ),
	),
) ) );




// Template.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_template]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_template'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	// Reload cache when outputing preview screen.
	// It's enough to bind js callback just for one field.
	'sanitize_js_callback'  => 'bimber_delete_transients',
	'transport'         => 'postMessage',
) );


$wp_customize->add_control( 'bimber_featured_entries_template', array(
	'label'    => __( 'Template', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_template]',
	'type'     => 'select',
	'choices'  => array(
		'grid'      => __( 'grid', 'bimber' ),
		'list'      => __( 'list', 'bimber' ),
		'bunchy'    => 'bunchy',
	),
) );


// Above header.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_above_header]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_above_header'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_above_header', array(
	'label'    => __( 'Display above header', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_above_header]',
	'type'     => 'checkbox',
) );


// Full width.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_full_width]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_full_width'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );


$wp_customize->add_control( 'bimber_featured_entries_full_width', array(
	'label'    => __( 'Full width', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_full_width]',
	'type'     => 'checkbox',
	'active_callback' => 'bimber_is_featured_entries_number_active',
) );
// Size.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_size]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_size'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );


$wp_customize->add_control( new Bimber_Customize_Custom_Radio_Control( $wp_customize,  'bimber_featured_entries_size', array(
	'label'    => __( 'Grid Size', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_size]',
	'type'     => 'radio',
	'input_attrs' => array(
		'row-class' => 'radio-single-line',
	),
	'choices'  => array(
		'xs'      	=> 'S',
		'xs-5'    	=> 'M',
		'xs-4'      => 'L',
	),
	'active_callback' => 'bimber_is_featured_entries_size_active',
) ) );

/**
 * Is featured entries size active.
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 * @return bool
 */
function bimber_is_featured_entries_size_active( $control ) {
	$template = bimber_get_theme_option( 'featured_entries', 'template' );
	$active = 'grid' === $template;
	return $active;
}


// Grid Gutter.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_gutter]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_gutter'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_gutter', array(
	'label'    => __( 'Grid gutter', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_gutter]',
	'type'     => 'checkbox',
) );





// Media ratio.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_img_ratio]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_img_ratio'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	// Reload cache when outputing preview screen.
	// It's enough to bind js callback just for one field.
	'sanitize_js_callback'  => 'bimber_delete_transients',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_img_ratio', array(
	'label'    => __( 'Grid image ratio', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_img_ratio]',
	'type'     => 'select',
	'choices'  => array(
		'2-1'   => '2:1',
		'16-9'  => '16:9',
		'4-3'   => '4:3',
		'1-1'   => '1:1',
	),
) );

// Media title.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_img_title]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_img_title'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	// Reload cache when outputing preview screen.
	// It's enough to bind js callback just for one field.
	'sanitize_js_callback'  => 'bimber_delete_transients',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_img_title', array(
	'label'    => __( 'Grid image title', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_img_title]',
	'type'     => 'checkbox',
) );



// Type.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_type]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_type'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	// Reload cache when outputing preview screen.
	// It's enough to bind js callback just for one field.
	'sanitize_js_callback'  => 'bimber_delete_transients',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_type', array(
	'label'    => __( 'Type', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_type]',
	'type'     => 'select',
	'choices'  => array(
		'most_shared' => __( 'Most Shared', 'bimber' ),
		'most_viewed' => __( 'Most Viewed', 'bimber' ),
		'recent'      => __( 'Recent', 'bimber' ),
		'none'        => __( 'none', 'bimber' ),
	),
) );


// Number.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_number]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_number'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );
$wp_customize->add_control( 'bimber_featured_entries_number', array(
	'label'    => __( 'Number of Entries', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_number]',
	'type'     => 'number',
	'input_attrs' => array(
		'min' => 3,
		'max' => 20,
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_is_featured_entries_number_active',
) );

// Number - bunchy.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_number_bunchy]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_number_bunchy'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );
$wp_customize->add_control( 'bimber_featured_entries_number_bunchy', array(
	'label'    => __( 'Number of Entries', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_number_bunchy]',
	'type'     => 'number',
	'input_attrs' => array(
		'min' => 3,
		'max' => 4,
		'class' => 'small-text',
	),
	'active_callback' => 'bimber_is_featured_entries_number_bunchy_active',
) );

/**
 * Is featured entries number active.
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 * @return bool
 */
function bimber_is_featured_entries_number_active( $control ) {
	$template = bimber_get_theme_option( 'featured_entries', 'template' );
	$active = 'grid' === $template || 'list' === $template;
	return $active;
}

/**
 * Is featured entries number active.
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 * @return bool
 */
function bimber_is_featured_entries_number_bunchy_active( $control ) {
	$template = bimber_get_theme_option( 'featured_entries', 'template' );
	$active = 'bunchy' === $template;
	return $active;
}

// Show in main loop?
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_exclude_from_main_loop]', array(
	'default'           => $bimber_customizer_defaults['featured_entries_exclude_from_main_loop'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_exclude_from_main_loop', array(
	'label'    => __( 'Exclude from the main collection?', 'bimber' ),
	'section'  => 'bimber_featured_entries_section',
	'settings' => $bimber_option_name . '[featured_entries_exclude_from_main_loop]',
	'type'     => 'checkbox',
) );

/**
 * Check whether user selected global featued entries
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_has_global_featured_entries( $control ) {
	$type = $control->manager->get_setting( bimber_get_theme_id() . '[featured_entries_type]' )->value();

	return 'none' !== $type;
}

/**
 * Check whether featured entries tag filter is supported
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_global_featured_entries_tag_is_active( $control ) {
	$has_featured_entries = bimber_customizer_has_global_featured_entries( $control );

	// Skip if home doesn't use the Featured Entries.
	if ( ! $has_featured_entries ) {
		return false;
	}

	$featured_entries_type = $control->manager->get_setting( bimber_get_theme_id() . '[featured_entries_type]' )->value();

	// The most viewed types doesn't support tag filter.
	if ( 'most_viewed' === $featured_entries_type ) {
		return false;
	}

	return apply_filters( 'bimber_customizer_global_featured_entries_tag_is_active', true );
}

// Category.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_category]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_category'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'bimber_sanitize_multi_choice',
	'transport'             => 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_featured_entries_category', array(
	'label'           => __( 'Filter by Categories', 'bimber' ),
	'section'         => 'bimber_featured_entries_section',
	'settings'        => $bimber_option_name . '[featured_entries_category]',
	'choices'         => bimber_customizer_get_category_choices(),
	'active_callback' => 'bimber_customizer_has_global_featured_entries',
) ) );


// Tag.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_tag]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_tag'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'bimber_sanitize_multi_choice',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Tag_Select_Control( $wp_customize, 'bimber_featured_entries_tag', array(
	'label'           => __( 'Filter by Tags', 'bimber' ),
	'section'         => 'bimber_featured_entries_section',
	'settings'        => $bimber_option_name . '[featured_entries_tag]',
	'active_callback' => 'bimber_customizer_global_featured_entries_tag_is_active',
) ) );


// Time range.
$wp_customize->add_setting( $bimber_option_name . '[featured_entries_time_range]', array(
	'default'               => $bimber_customizer_defaults['featured_entries_time_range'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_featured_entries_time_range', array(
	'label'           => __( 'Time Range', 'bimber' ),
	'section'         => 'bimber_featured_entries_section',
	'settings'        => $bimber_option_name . '[featured_entries_time_range]',
	'type'            => 'select',
	'choices'         => array(
		'day'   => __( 'Last 24 hours', 'bimber' ),
		'week'  => __( 'Last 7 days', 'bimber' ),
		'month' => __( 'Last 30 days', 'bimber' ),
		'all'   => __( 'All time', 'bimber' ),
	),
	'active_callback' => 'bimber_customizer_has_global_featured_entries',
) );


$wp_customize->selective_refresh->add_partial( 'featured_entries', array(
	'selector'              => '.g1-featured-row',
	'settings'              => array(
		$bimber_option_name . '[featured_entries_visibility]',
		$bimber_option_name . '[featured_entries_template]',
		$bimber_option_name . '[featured_entries_full_width]',
		$bimber_option_name . '[featured_entries_size]',
		$bimber_option_name . '[featured_entries_number]',
		$bimber_option_name . '[featured_entries_number_bunchy]',
		$bimber_option_name . '[featured_entries_above_header]',
		$bimber_option_name . '[featured_entries_gutter]',
		$bimber_option_name . '[featured_entries_img_ratio]',
		$bimber_option_name . '[featured_entries_img_title]',
		$bimber_option_name . '[featured_entries_type]',
		$bimber_option_name . '[featured_entries_category]',
		$bimber_option_name . '[featured_entries_tag]',
		$bimber_option_name . '[featured_entries_time_range]',
	),
	'render_callback'       => 'bimber_render_featured_entries',
	'container_inclusive'   => true,
) );
