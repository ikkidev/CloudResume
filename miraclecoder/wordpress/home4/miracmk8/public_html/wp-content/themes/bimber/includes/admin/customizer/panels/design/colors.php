<?php
/**
 * WP Customizer panel section to handle Design > Colors options
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


// <meta name="theme-color">
$wp_customize->add_setting( $bimber_option_name . '[meta_theme_color]', array(
	'default'           => $bimber_customizer_defaults['meta_theme_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'none',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_meta_theme_color', array(
	'label'    => __( 'Theme Color', 'bimber' ),
	'description' =>
		__( 'Define colors for elements of the browser.', 'bimber' ) . ' ' .
		__( 'Keep in mind that it may only work on certain platforms or browsers.', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[meta_theme_color]',
) ) );

// Background Color.
$wp_customize->add_setting( $bimber_option_name . '[global_background_color]', array(
	'default'           => $bimber_customizer_defaults['global_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_global_background_color', array(
	'label'    => __( 'Boxed Layout Background', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[global_background_color]',
) ) );

// Background Color.
$wp_customize->add_setting( $bimber_option_name . '[global_skinmode_background_color]', array(
	'default'           => $bimber_customizer_defaults['global_skinmode_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_global_skinmode_background_color', array(
	'label'    => __( 'Skinmode Boxed Layout Background', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[global_skinmode_background_color]',
) ) );

// Divider.
$wp_customize->add_setting( 'bimber_global_cs_1_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_global_cs_1_divider', array(
	'section'  => 'bimber_design_colors_section',
	'settings' => 'bimber_global_cs_1_divider',
	'html'     => '<hr /><h2>' . esc_html__( 'Basic Color Scheme', 'bimber' ) . '</h2>',
) ) );


// Accent 1 (cs1).
$wp_customize->add_setting( $bimber_option_name . '[content_cs_1_accent1]', array(
	'default'           => $bimber_customizer_defaults['content_cs_1_accent1'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_content_cs_1_accent1', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[content_cs_1_accent1]',
) ) );

// Divider.
$wp_customize->add_setting( 'bimber_global_cs_2_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_global_cs_2_divider', array(
	'section'  => 'bimber_design_colors_section',
	'settings' => 'bimber_global_cs_2_divider',
	'html'     =>
		'<hr />
		<h2>' . esc_html__( 'Secondary Color Scheme', 'bimber' ) . '</h2>
		<p>' . esc_html__( 'Will be applied to buttons, badges &amp; flags.', 'bimber' ) . '</p>',
) ) );


// Background Color (cs2).
$wp_customize->add_setting( $bimber_option_name . '[content_cs_2_background_color]', array(
	'default'           => $bimber_customizer_defaults['content_cs_2_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_content_cs_2_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[content_cs_2_background_color]',
) ) );

// Background Gradient Color (cs2).
$wp_customize->add_setting( $bimber_option_name . '[content_cs_2_background2_color]', array(
	'default'           => $bimber_customizer_defaults['content_cs_2_background2_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_content_cs_2_background2_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[content_cs_2_background2_color]',
) ) );


// Text 1 (cs2).
$wp_customize->add_setting( $bimber_option_name . '[content_cs_2_text1]', array(
	'default'           => $bimber_customizer_defaults['content_cs_2_text1'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_content_cs_2_text1', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_design_colors_section',
	'settings' => $bimber_option_name . '[content_cs_2_text1]',
) ) );


$dev = defined( 'BTP_DEV' ) && BTP_DEV;
if ( ! $dev ) {
	return;
}

// Bending cat.
$wp_customize->add_setting( $bimber_option_name . '[bending_cat]', array(
	'default'           => $bimber_customizer_defaults['bending_cat'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_bending_cat', array(
	'label'    => __( 'Enable Bending Cat', 'bimber' ),
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[bending_cat]',
	'type'     => 'checkbox',
) );

// Page width.
$wp_customize->add_setting( $bimber_option_name . '[page_width]', array(
	'default'           => $bimber_customizer_defaults['page_width'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new Bimber_Customize_Custom_Range_Control( $wp_customize, 'bimber_page_width', array(
	'label'    => __( 'Page Width', 'bimber' ),
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[page_width]',
	'input_attrs' => array(
		'min'   => 1024,
		'max'   => 1920,
	),
) ) );
