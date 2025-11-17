<?php
/**
 * WP Customizer panel section to handle post single options
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

// Define Search section.
$wp_customize->add_section( 'bimber_search_section', array(
	'title'    => __( 'Search', 'bimber' ),
	'priority' => 210,
) );

$bimber_option_name = bimber_get_theme_id();


// Input placeholder.
$wp_customize->add_setting( $bimber_option_name . '[search_input_placeholder]', array(
	'default'           => $bimber_customizer_defaults['search_input_placeholder'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );
$wp_customize->add_control( 'bimber_search_input_placeholder', array(
	'label'           => __( 'Input placeholder', 'bimber' ),
	'description'     => esc_html( sprintf( __( 'Available tags: %s', 'bimber' ), implode( ', ', array('%post_count%') ) ) ),
	'section'         => 'bimber_search_section',
	'settings'        => $bimber_option_name . '[search_input_placeholder]',
	'type'            => 'text',
	'input_attrs'     => array(
		'placeholder' => __( 'Leave empty to use the default value', 'bimber' ),
	),
) );
$wp_customize->selective_refresh->add_partial( 'search_form', array(
	'selector'              => '.search-form',
	'settings'              => array( $bimber_option_name . '[search_input_placeholder]' ),
	'render_callback'       => 'get_search_form',
	'container_inclusive'   => true,
) );


// AJAX search results.
$wp_customize->add_setting( $bimber_option_name . '[search_ajax]', array(
	'default'           => $bimber_customizer_defaults['search_ajax'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_search_ajax', array(
	'label'    => __( 'Enable AJAX search results', 'bimber' ),
	'section'  => 'bimber_search_section',
	'settings' => $bimber_option_name . '[search_ajax]',
	'type'     => 'checkbox',
) );

// Template.
$wp_customize->add_setting( $bimber_option_name . '[search_template]', array(
	'default'           => $bimber_customizer_defaults['search_template'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_search_template', array(
	'label'    => __( 'Template', 'bimber' ),
	'section'  => 'bimber_search_section',
	'settings' => $bimber_option_name . '[search_template]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_templates(),
	'columns'  => 2,
) ) );

// Sidebar.
$wp_customize->add_setting( $bimber_option_name . '[search_sidebar_location]', array(
	'default'           => $bimber_customizer_defaults['search_sidebar_location'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_search_sidebar_location', array(
	'label'       => __( 'Sidebar Location', 'bimber' ),
	'section'     => 'bimber_search_section',
	'settings'    => $bimber_option_name . '[search_sidebar_location]',
	'type'        => 'select',
	'choices'     => array(
		'left'          => _x( 'Left', 'sidebar location', 'bimber' ),
		'standard'      => _x( 'Right', 'sidebar location', 'bimber' ),
	),
	'active_callback' => 'bimber_customizer_search_is_template_with_sidebar',
) );

/**
 * Check whether there are many comment types active
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_search_is_template_with_sidebar( $control ) {
	$template = bimber_get_theme_option( 'search', 'template' );
	return strpos( $template, 'sidebar' ) > -1 || strpos( $template, 'bunchy' ) > -1;
}

// Posts Per Page.
$wp_customize->add_setting( $bimber_option_name . '[search_posts_per_page]', array(
	'default'           => $bimber_customizer_defaults['search_posts_per_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_search_posts_per_page', array(
	'label'    => __( 'Entries per page', 'bimber' ),
	'section'  => 'bimber_search_section',
	'settings' => $bimber_option_name . '[search_posts_per_page]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// search inject embeds.
$wp_customize->add_setting( $bimber_option_name . '[search_inject_embeds]', array(
	'default'           => $bimber_customizer_defaults['search_inject_embeds'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_search_inject_embeds', array(
	'label'    => __( 'Inject embeds into featured media', 'bimber' ),
	'section'  => 'bimber_search_section',
	'settings' => $bimber_option_name . '[search_inject_embeds]',
	'type'     => 'select',
	'choices'  => bimber_get_yes_no_options(),
) );

// Pagination.
$wp_customize->add_setting( $bimber_option_name . '[search_pagination]', array(
	'default'           => $bimber_customizer_defaults['search_pagination'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_search_pagination', array(
	'label'    => __( 'Pagination', 'bimber' ),
	'section'  => 'bimber_search_section',
	'settings' => $bimber_option_name . '[search_pagination]',
	'type'     => 'select',
	'choices'  => bimber_get_archive_pagination_types(),
) );

// Hide Elements.
$wp_customize->add_setting( $bimber_option_name . '[search_hide_elements]', array(
	'default'           => $bimber_customizer_defaults['search_hide_elements'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_search_hide_elements', array(
	'label'    => __( 'Hide Elements', 'bimber' ),
	'section'  => 'bimber_search_section',
	'settings' => $bimber_option_name . '[search_hide_elements]',
	'choices'  => bimber_get_archive_elements_to_hide(),
) ) );

// Call To Action Hide Buttons.
$wp_customize->add_setting( $bimber_option_name . '[search_call_to_action_hide_buttons]', array(
	'default'           => $bimber_customizer_defaults['search_call_to_action_hide_buttons'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_search_call_to_action_hide_buttons', array(
	'label'           => __( 'Call to Action - Hide Buttons', 'bimber' ),
	'section'         => 'bimber_search_section',
	'settings'        => $bimber_option_name . '[search_call_to_action_hide_buttons]',
	'choices'         => bimber_get_post_call_to_action_buttons(),
) ) );
