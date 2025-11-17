<?php
/**
 * WP Customizer panel section to handle the Link post format options
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

$wp_customize->add_section( 'bimber_posts_link_section', array(
	'title'    => __( 'Link Post Format', 'bimber' ),
	'priority' => 30,
	'panel'    => 'bimber_posts_panel',
) );

// Frame icon.
$wp_customize->add_setting( $bimber_option_name . '[post_link_frame_icon]', array(
	'default'           => $bimber_customizer_defaults['post_link_frame_icon'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_post_link_frame_icon', array(
	'label'    => __( 'Show Featured Image Icon', 'bimber' ),
	'section'  => 'bimber_posts_link_section',
	'settings' => $bimber_option_name . '[post_link_frame_icon]',
	'type'     => 'checkbox',
) );

// Domain.
$wp_customize->add_setting( $bimber_option_name . '[post_link_show_domain]', array(
	'default'           => $bimber_customizer_defaults['post_link_show_domain'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_post_link_show_domain', array(
	'label'    => __( 'Show source domain after post title', 'bimber' ),
	'section'  => 'bimber_posts_link_section',
	'settings' => $bimber_option_name . '[post_link_show_domain]',
	'type'     => 'checkbox',
) );

// Single post page.
$wp_customize->add_setting( $bimber_option_name . '[post_link_single_page]', array(
	'default'           => $bimber_customizer_defaults['post_link_single_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_post_link_single_page', array(
	'label'         => __( 'Has a single page?', 'bimber' ),
	'section'  => 'bimber_posts_link_section',
	'settings' => $bimber_option_name . '[post_link_single_page]',
	'type'     => 'select',
	'choices'  => array(
		'none'      => __( 'No', 'bimber' ),
		'standard'  => __( 'Yes', 'bimber' ),
	),
) );

// Visit Direct link button label.
$wp_customize->add_setting( $bimber_option_name . '[post_link_visit_direct_link_label]', array(
	'default'           => $bimber_customizer_defaults['post_link_visit_direct_link_label'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_post_link_visit_direct_link_label', array(
	'label'             => __( 'Button label', 'bimber' ),
	'section'           => 'bimber_posts_link_section',
	'settings'          => $bimber_option_name . '[post_link_visit_direct_link_label]',
	'type'              => 'text',
	'active_callback'   => 'bimber_customizer_has_link_single_page',
) );

// Open method.
$wp_customize->add_setting( $bimber_option_name . '[post_link_open_method]', array(
	'default'           => $bimber_customizer_defaults['post_link_open_method'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_post_link_open_method', array(
	'label'         => __( 'Open method', 'bimber' ),
	'section'  => 'bimber_posts_link_section',
	'settings' => $bimber_option_name . '[post_link_open_method]',
	'type'     => 'select',
	'choices'  => array(
		'new_window'    => __( 'in a new window', 'bimber' ),
		'same_window'   => __( 'in the same window', 'bimber' ),
		'landing_page'  => __( 'using landing page', 'bimber' ),
	),
) );

// Landing page.
$wp_customize->add_setting( $bimber_option_name . '[post_link_landing_page]', array(
	'default'           => $bimber_customizer_defaults['post_link_landing_page'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_post_link_landing_page', array(
	'label'             => __( 'Landing page', 'bimber' ),
	'description'       => __( 'Run an external link redirection from a separate page.', 'bimber' ),
	'section'           => 'bimber_posts_link_section',
	'settings'          => $bimber_option_name . '[post_link_landing_page]',
	'type'              => 'dropdown-pages',
	'active_callback'   => 'bimber_customizer_is_link_opened_using_landing_page',
) );

// Delay.
$wp_customize->add_setting( $bimber_option_name . '[post_link_redirection_delay]', array(
	'default'           => $bimber_customizer_defaults['post_link_redirection_delay'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_post_link_redirection_delay', array(
	'label'             => __( 'Redirection delay (in seconds)', 'bimber' ),
	'description'       => __( 'Set to 0 to disable the delay counter.', 'bimber' ),
	'section'           => 'bimber_posts_link_section',
	'settings'          => $bimber_option_name . '[post_link_redirection_delay]',
	'type'              => 'number',
	'active_callback'   => 'bimber_customizer_is_link_opened_using_landing_page',
) );

/**
 * Check whether landing page selected
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_is_link_opened_using_landing_page( $control ) {
	$open_method = $control->manager->get_setting( bimber_get_theme_id() . '[post_link_open_method]' )->value();

	return ( 'landing_page' === $open_method );
}

/**
 * Check whether link has a single page
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_has_link_single_page( $control ) {
	$open_method = $control->manager->get_setting( bimber_get_theme_id() . '[post_link_single_page]' )->value();

	return ( 'standard' === $open_method );
}
