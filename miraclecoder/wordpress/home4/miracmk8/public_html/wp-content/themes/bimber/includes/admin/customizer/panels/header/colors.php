<?php
/**
 * WP Customizer panel section to handle header design options
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

$wp_customize->add_section( 'bimber_header_builder_colors_section_a', array(
	'title'    => __( 'Row: A', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );
$wp_customize->add_section( 'bimber_header_builder_colors_section_b', array(
	'title'    => __( 'Row: B', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );
$wp_customize->add_section( 'bimber_header_builder_colors_section_c', array(
	'title'    => __( 'Row: C', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );
$wp_customize->add_section( 'bimber_header_builder_colors_section_canvas', array(
	'title'    => __( 'Off-Canvas', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );
$wp_customize->add_section( 'bimber_header_builder_colors_section_submenus', array(
	'title'    => __( 'Submenus', 'bimber' ),
	'panel'    => 'bimber_header_panel',
) );
// Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_text_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_text_color]',
) ) );
// Acces Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_accent_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_accent_color]',
) ) );
// Background.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_background_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_background_color]',
) ) );
// Optional Gradient Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_gradient_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_gradient_color]',
) ) );
// Optional Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_border_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_border_color', array(
	'label'    => __( 'Optional Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_border_color]',
) ) );
// Button Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_button_background]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_button_background'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_button_background', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_button_background]',
	'priority' => 100,
) ) );
// Button Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_button_text]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_button_text'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_button_text', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_button_text]',
	'priority' => 100,
) ) );

// Skinmode heading.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_skinmode_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_header_builder_a_skinmode_header', array(
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_skinmode_header]',
	'priority' => 200,
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Dark or Light Skin Mode', 'bimber' ) . '</h2>',
) ) );

// Skinmode Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_skinmode_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_skinmode_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_skinmode_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_skinmode_text_color]',
	'priority' => 210,
) ) );

// Skinmode Accent Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_skinmode_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_skinmode_accent_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_skinmode_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_skinmode_accent_color]',
	'priority' => 220,
) ) );

// Skinmode Background.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_skinmode_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_skinmode_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_skinmode_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_skinmode_background_color]',
	'priority' => 240,
) ) );

// Skinmode Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_skinmode_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_skinmode_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_skinmode_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_skinmode_gradient_color]',
	'priority' => 245,
) ) );

// Skinmode Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_a_skinmode_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_a_skinmode_border_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_a_skinmode_border_color', array(
	'label'    => __( 'Optional Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_a',
	'settings' => $bimber_option_name . '[header_builder_a_skinmode_border_color]',
	'priority' => 250,
) ) );




// Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_text_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_text_color]',
) ) );
// Acces Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_accent_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_accent_color]',
) ) );
// Background.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_background_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_background_color]',
) ) );
// Optional Gradient Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_gradient_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_gradient_color]',
) ) );
// Optional Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_border_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_border_color', array(
	'label'    => __( 'Optional Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_border_color]',
) ) );
// Button Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_button_background]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_button_background'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_button_background', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_button_background]',
	'priority' => 100,
) ) );
// Button Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_button_text]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_button_text'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_button_text', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_button_text]',
	'priority' => 100,
) ) );

// Skinmode heading.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_skinmode_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_header_builder_b_skinmode_header', array(
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_skinmode_header]',
	'priority' => 200,
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Dark or Light Skin Mode', 'bimber' ) . '</h2>',
) ) );

// Skinmode Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_skinmode_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_skinmode_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_skinmode_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_skinmode_text_color]',
	'priority' => 210,
) ) );

// Skinmode Accent Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_skinmode_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_skinmode_accent_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_skinmode_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_skinmode_accent_color]',
	'priority' => 220,
) ) );

// Skinmode Background.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_skinmode_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_skinmode_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_skinmode_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_skinmode_background_color]',
	'priority' => 240,
) ) );

// Skinmode Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_skinmode_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_skinmode_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_skinmode_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_skinmode_gradient_color]',
	'priority' => 245,
) ) );



// Skinmode Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_b_skinmode_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_b_skinmode_border_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_b_skinmode_border_color', array(
	'label'    => __( 'Optional Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_b',
	'settings' => $bimber_option_name . '[header_builder_b_skinmode_border_color]',
	'priority' => 250,
) ) );



// Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_text_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_text_color]',
) ) );
// Acces Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_accent_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_accent_color]',
) ) );
// Background.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_background_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_background_color]',
) ) );
// Optional Gradient Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_gradient_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_gradient_color]',
) ) );
// Optional Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_border_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_border_color', array(
	'label'    => __( 'Optional Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_border_color]',
) ) );

// Button Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_button_background]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_button_background'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_button_background', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_button_background]',
	'priority' => 100,
) ) );
// Button Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_button_text]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_button_text'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_button_text', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_button_text]',
	'priority' => 100,
) ) );

// Skinmode heading.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_skinmode_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_header_builder_c_skinmode_header', array(
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_skinmode_header]',
	'priority' => 200,
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Dark or Light Skin Mode', 'bimber' ) . '</h2>',
) ) );

// Skinmode Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_skinmode_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_skinmode_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_skinmode_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_skinmode_text_color]',
	'priority' => 210,
) ) );

// Skinmode Accent Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_skinmode_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_skinmode_accent_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_skinmode_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_skinmode_accent_color]',
	'priority' => 220,
) ) );

// Skinmode Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_skinmode_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_skinmode_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_skinmode_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_skinmode_background_color]',
	'priority' => 240,
) ) );

// Skinmode Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_skinmode_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_skinmode_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_skinmode_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_skinmode_gradient_color]',
	'priority' => 240,
) ) );

// Skinmode Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_c_skinmode_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_c_skinmode_border_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_c_skinmode_border_color', array(
	'label'    => __( 'Optional Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_c',
	'settings' => $bimber_option_name . '[header_builder_c_skinmode_border_color]',
	'priority' => 250,
) ) );





// Submenu Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_submenu_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_submenu_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_background_color]',
) ) );

// Submenu Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_submenu_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_submenu_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_text_color]',
) ) );

// Submenu Accent Color.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_submenu_accent_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_submenu_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_accent_color]',
) ) );

// Skinmode heading.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_skinmode_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_header_submenu_skinmode_header', array(
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_skinmode_header]',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Dark or Light Skin Mode', 'bimber' ) . '</h2>',
) ) );


// Skinmode Submenu Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_skinmode_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_submenu_skinmode_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_submenu_skinmode_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_skinmode_background_color]',
) ) );

// Skinmode Submenu Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_skinmode_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_submenu_skinmode_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_submenu_skinmode_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_skinmode_text_color]',
) ) );

// Skinmode Submenu Accent Color.
$wp_customize->add_setting( $bimber_option_name . '[header_submenu_skinmode_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_submenu_skinmode_accent_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_submenu_skinmode_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_submenus',
	'settings' => $bimber_option_name . '[header_submenu_skinmode_accent_color]',
) ) );




// Canvas.
$wp_customize->add_setting( $bimber_option_name . '[canvas_sticky]', array(
	'default'           => $bimber_customizer_defaults['canvas_sticky'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_canvas_sticky', array(
	'label'    => __( 'Sticky', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[canvas_sticky]',
	'choices'  => array(
		'all'       => __( 'All', 'bimber' ),
		'home'      => __( 'Home', 'bimber' ),
	),
) ) );




// Colors heading.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_colors_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_header_builder_canvas_colors_header', array(
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_colors_header]',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Colors', 'bimber' ) . '</h2>',
) ) );



// Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_text_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_text_color]',
) ) );
// Acces Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_accent_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_accent_color]',
) ) );


// Button Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_button_background]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_button_background'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_button_background', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_button_background]',
) ) );
// Button Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_button_text]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_button_text'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_button_text', array(
	'label'    => __( 'Button', 'bimber' ) . ': ' . __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_button_text]',
) ) );



// Background.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_background_color]',
) ) );
// Optional Gradient Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_gradient_color]',
) ) );

// Background Image.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_background_image]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_background_image'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_header_builder_canvas_background_image', array(
	'label'    => __( 'Background Image', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_background_image]',
) ) );
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_background_repeat]', array(
	'default'               => $bimber_customizer_defaults['header_builder_canvas_background_repeat'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );

$wp_customize->add_control( 'bimber_header_builder_canvas_background_repeat', array(
	'label'    => __( 'Background Image Repeat', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_background_repeat]',
	'type'     => 'select',
	'choices'  => array(
		'no-repeat'      	=> _x( 'no repeat', 'background repeat option', 'bimber' ),
		'repeat'      		=> _x( 'repeat', 'background repeat option', 'bimber' ),
		'repeat-x'      	=> _x( 'repeat x', 'background repeat option', 'bimber' ),
		'repeat-y'      	=> _x( 'repeat y', 'background repeat option', 'bimber' ),
	),
) );
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_background_size]', array(
	'default'               => $bimber_customizer_defaults['header_builder_canvas_background_size'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_builder_canvas_background_size', array(
	'label'    => __( 'Background Image Size', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_background_size]',
	'type'     => 'select',
	'choices'  => array(
		'auto'      	=> _x( 'auto', 'background size option', 'bimber' ),
		'cover'      	=> _x( 'cover', 'background size option', 'bimber' ),
	),
) );
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_background_position]', array(
	'default'               => $bimber_customizer_defaults['header_builder_canvas_background_position'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );

$wp_customize->add_control( 'bimber_header_builder_canvas_background_position', array(
	'label'    => __( 'Background Image Position', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_background_position]',
	'type'     => 'select',
	'choices'  => array(
		'top left'      	=> _x( 'top left',      'background position option', 'bimber' ),
		'top center'      	=> _x( 'top center',    'background position option', 'bimber' ),
		'top right'      	=> _x( 'top right',     'background position option', 'bimber' ),
		'center left'      	=> _x( 'center left',   'background position option', 'bimber' ),
		'center center'     => _x( 'center center', 'background position option', 'bimber' ),
		'center right'      => _x( 'center right',  'background position option', 'bimber' ),
		'bottom left'      	=> _x( 'bottom left',   'background position option', 'bimber' ),
		'bottom center'     => _x( 'bottom center', 'background position option', 'bimber' ),
		'bottom right'      => _x( 'bottom right',  'background position option', 'bimber' ),
	),
) );
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_background_opacity]', array(
	'default'               => $bimber_customizer_defaults['header_builder_canvas_background_opacity'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Custom_Range_Control( $wp_customize, 'bimber_header_builder_canvas_background_opacity', array(
	'label'    => __( 'Background Image Opacity', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_background_opacity]',
	'input_attrs' => array(
		'min'   => 0,
		'max'   => 100,
	),
) ) );


// Skinmode heading.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_skinmode_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_header_builder_canvas_skinmode_header', array(
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_skinmode_header]',
	'priority' => 200,
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Dark or Light Skin Mode', 'bimber' ) . '</h2>',
) ) );

// Skinmode Text Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_skinmode_text_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_skinmode_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_skinmode_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_skinmode_text_color]',
	'priority' => 210,
) ) );

// Skinmode Accent Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_skinmode_accent_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_skinmode_accent_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_skinmode_accent_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_skinmode_accent_color]',
	'priority' => 220,
) ) );

// Skinmode Background Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_skinmode_background_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_skinmode_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_skinmode_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_skinmode_background_color]',
	'priority' => 240,
) ) );

// Skinmode Border Color.
$wp_customize->add_setting( $bimber_option_name . '[header_builder_canvas_skinmode_border_color]', array(
	'default'           => $bimber_customizer_defaults['header_builder_canvas_skinmode_border_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_header_builder_canvas_skinmode_border_color', array(
	'label'    => __( 'Border Color', 'bimber' ),
	'section'  => 'bimber_header_builder_colors_section_canvas',
	'settings' => $bimber_option_name . '[header_builder_canvas_skinmode_border_color]',
	'priority' => 250,
) ) );

