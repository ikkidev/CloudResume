<?php
/**
 * WP Customizer panel section to customize footer design options
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

$wp_customize->add_section( 'bimber_footer_colors_section', array(
	'title'    => __( 'Colors', 'bimber' ),
	'priority' => 11,
	'panel'    => 'bimber_footer_panel',
) );

// Text 1 (cs1).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_text1]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_text1'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_1_text1', array(
	'label'    => __( 'Headings &amp; Titles', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_text1]',
) ) );


// Text 2 (cs1).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_text2]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_text2'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_1_text2', array(
	'label'    => __( 'Regular Text', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_text2]',
) ) );


// Text 3 (cs1).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_text3]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_text3'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_1_text3', array(
	'label'    => __( 'Small Text Descriptions', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_text3]',
) ) );


// Accent 1 (cs1).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_accent1]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_accent1'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_1_accent1', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_accent1]',
) ) );


// Background Color (cs1).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_background_color]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_1_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_background_color]',
) ) );

// Background Color (cs1).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_1_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_gradient_color]',
) ) );

$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_background_image]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_1_background_image'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_footer_cs_1_background_image', array(
	'label'    => __( 'Background Image', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_background_image]',
) ) );
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_background_repeat]', array(
	'default'               => $bimber_customizer_defaults['footer_cs_1_background_repeat'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );

$wp_customize->add_control( 'bimber_footer_cs_1_background_repeat', array(
	'label'    => __( 'Background Image Repeat', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_background_repeat]',
	'type'     => 'select',
	'choices'  => array(
		'no-repeat'      	=> _x( 'no repeat', 'background repeat option', 'bimber' ),
		'repeat'      		=> _x( 'repeat', 'background repeat option', 'bimber' ),
		'repeat-x'      	=> _x( 'repeat x', 'background repeat option', 'bimber' ),
		'repeat-y'      	=> _x( 'repeat y', 'background repeat option', 'bimber' ),
	),
) );
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_background_size]', array(
	'default'               => $bimber_customizer_defaults['footer_cs_1_background_size'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_footer_cs_1_background_size', array(
	'label'    => __( 'Background Image Size', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_background_size]',
	'type'     => 'select',
	'choices'  => array(
		'auto'      	=> _x( 'auto', 'background size option', 'bimber' ),
		'cover'      	=> _x( 'cover', 'background size option', 'bimber' ),
	),
) );
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_background_position]', array(
	'default'               => $bimber_customizer_defaults['footer_cs_1_background_position'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );

$wp_customize->add_control( 'bimber_footer_cs_1_background_position', array(
	'label'    => __( 'Background Image Position', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_background_position]',
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
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_1_background_opacity]', array(
	'default'               => $bimber_customizer_defaults['footer_cs_1_background_opacity'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );

$wp_customize->add_control( new Bimber_Customize_Custom_Range_Control( $wp_customize, 'bimber_footer_cs_1_background_opacity', array(
	'label'    => __( 'Background Image Opacity', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_1_background_opacity]',
	'input_attrs' => array(
		'min'   => 0,
		'max'   => 100,
	),
) ) );

// Divider.
$wp_customize->add_setting( 'bimber_footer_cs_2_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_footer_cs_2_divider', array(
	'section'  => 'bimber_footer_colors_section',
	'settings' => 'bimber_footer_cs_2_divider',
	'html'     =>
		'<hr />
		<h2>' . esc_html__( 'Secondary Color Scheme', 'bimber' ) . '</h2>
		<p>' . esc_html__( 'Will be applied to buttons, badges &amp; flags.', 'bimber' ) . '</p>',
) ) );


// Background Color (cs2).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_2_background_color]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_2_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_2_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_2_background_color]',
) ) );


// Text 1 (cs2).
$wp_customize->add_setting( $bimber_option_name . '[footer_cs_2_text1]', array(
	'default'           => $bimber_customizer_defaults['footer_cs_2_text1'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_cs_2_text1', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_cs_2_text1]',
) ) );





// Skinmode heading.
$wp_customize->add_setting( $bimber_option_name . '[footer_skinmode_header]', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_footer_skinmode_header', array(
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_skinmode_header]',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Dark or Light Skin Mode', 'bimber' ) . '</h2>',
) ) );

// Skinmode: Important Text.
$wp_customize->add_setting( $bimber_option_name . '[footer_skinmode_itxt_color]', array(
	'default'           => $bimber_customizer_defaults['footer_skinmode_itxt_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_skinmode_itxt_color', array(
	'label'    => __( 'Headings &amp; Titles', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_skinmode_itxt_color]',
) ) );

// Skinmode: Regular Text.
$wp_customize->add_setting( $bimber_option_name . '[footer_skinmode_rtxt_color]', array(
	'default'           => $bimber_customizer_defaults['footer_skinmode_rtxt_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_skinmode_rtxt_color', array(
	'label'    => __( 'Regular Text', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_skinmode_rtxt_color]',
) ) );

// Skinmode: Meta Text.
$wp_customize->add_setting( $bimber_option_name . '[footer_skinmode_mtxt_color]', array(
	'default'           => $bimber_customizer_defaults['footer_skinmode_mtxt_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_skinmode_mtxt_color', array(
	'label'    => __( 'Small Text Descriptions', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_skinmode_mtxt_color]',
) ) );

// Skinmode: Accent Text.
$wp_customize->add_setting( $bimber_option_name . '[footer_skinmode_atxt_color]', array(
	'default'           => $bimber_customizer_defaults['footer_skinmode_atxt_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_skinmode_atxt_color', array(
	'label'    => __( 'Accent Color', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_skinmode_atxt_color]',
) ) );

// Skinmode: Background Color.
$wp_customize->add_setting( $bimber_option_name . '[footer_skinmode_bg_color]', array(
	'default'           => $bimber_customizer_defaults['footer_skinmode_bg_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'  		=> 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_footer_skinmode_bg_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_footer_colors_section',
	'settings' => $bimber_option_name . '[footer_skinmode_bg_color]',
) ) );


