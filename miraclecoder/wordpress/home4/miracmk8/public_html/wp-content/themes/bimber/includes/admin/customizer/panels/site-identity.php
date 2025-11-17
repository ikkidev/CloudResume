<?php
/**
 * WP Customizer panel section to handle general side options (like logo, footer text)
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

// Show tagline.
$wp_customize->add_setting( $bimber_option_name . '[branding_show_tagline]', array(
	'default'           => $bimber_customizer_defaults['branding_show_tagline'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_branding_show_tagline', array(
	'label'    => __( 'Show Tagline', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_show_tagline]',
	'type'     => 'checkbox',
) );

// Logo.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo]', array(
	'default'           => $bimber_customizer_defaults['branding_logo'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo', array(
	'label'    => __( 'Logo', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo]',
) ) );


// Logo width.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_width]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_width'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_branding_logo_width', array(
	'label'    => __( 'Logo Width', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_width]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Logo height.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_height]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_height'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_branding_logo_height', array(
	'label'    => __( 'Logo Height', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_height]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Logo HDPI.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_hdpi]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_hdpi'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_hdpi', array(
	'label'       => __( 'Logo HDPI', 'bimber' ),
	'description' => __( 'An image for High DPI screen (like Retina) should be twice as big.', 'bimber' ),
	'section'     => 'title_tagline',
	'settings'    => $bimber_option_name . '[branding_logo_hdpi]',
) ) );


// Logo inverted.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_inverted]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_inverted'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_inverted', array(
	'label'    => __( 'Logo inverted', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_inverted]',
) ) );

// Logo inverted HDPI.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_inverted_hdpi]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_inverted_hdpi'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_inverted_hdpi', array(
	'label'       => __( 'Logo inverted HDPI', 'bimber' ),
	'description' => __( 'An image for High DPI screen (like Retina) should be twice as big.', 'bimber' ),
	'section'     => 'title_tagline',
	'settings'    => $bimber_option_name . '[branding_logo_inverted_hdpi]',
) ) );


$wp_customize->selective_refresh->add_partial( 'logo', array(
	'selector'              => '.g1-id',
	'settings'              => array(
		$bimber_option_name . '[branding_logo]',
		$bimber_option_name . '[branding_logo_width]',
		$bimber_option_name . '[branding_logo_height]',
		$bimber_option_name . '[branding_logo_hdpi]',
		$bimber_option_name . '[branding_logo_inverted]',
		$bimber_option_name . '[branding_logo_inverted_hdpi]',
	),
	'render_callback'       => 'bimber_render_logo',
	'container_inclusive'   => true,
) );


// Small Logo.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_small]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_small'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_small', array(
	'label'    => __( 'Mobile Logo', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_small]',
) ) );


// Logo width.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_small_width]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_small_width'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_branding_logo_small_width', array(
	'label'    => __( 'Mobile Logo Width', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_small_width]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Small Logo height.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_small_height]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_small_height'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_branding_logo_small_height', array(
	'label'    => __( 'Mobile Logo Height', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_small_height]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Small Logo HDPI.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_small_hdpi]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_small_hdpi'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_small_hdpi', array(
	'label'       => __( 'Mobile Logo HDPI', 'bimber' ),
	'description' => __( 'An image for High DPI screen (like Retina) should be twice as big.', 'bimber' ),
	'section'     => 'title_tagline',
	'settings'    => $bimber_option_name . '[branding_logo_small_hdpi]',
) ) );

// Small Logo inverted.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_small_inverted]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_small_inverted'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_small_inverted', array(
	'label'    => __( 'Mobile Logo inverted', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[branding_logo_small_inverted]',
) ) );

// Logo inverted HDPI.
$wp_customize->add_setting( $bimber_option_name . '[branding_logo_small_inverted_hdpi]', array(
	'default'           => $bimber_customizer_defaults['branding_logo_small_inverted_hdpi'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_branding_logo_small_inverted_hdpi', array(
	'label'       => __( 'Mobile Logo inverted HDPI', 'bimber' ),
	'description' => __( 'An image for High DPI screen (like Retina) should be twice as big.', 'bimber' ),
	'section'     => 'title_tagline',
	'settings'    => $bimber_option_name . '[branding_logo_small_inverted_hdpi]',
) ) );

$wp_customize->selective_refresh->add_partial( 'mobile_logo', array(
	'selector'              => '.g1-id-mobile',
	'settings'              => array(
		$bimber_option_name . '[branding_logo_small]',
		$bimber_option_name . '[branding_logo_small_width]',
		$bimber_option_name . '[branding_logo_small_height]',
		$bimber_option_name . '[branding_logo_small_hdpi]',
		$bimber_option_name . '[branding_logo_small_inverted]',
		$bimber_option_name . '[branding_logo_small_inverted_hdpi]',
	),
	'render_callback'       => 'bimber_render_mobile_logo',
	'container_inclusive'   => true,
) );






// Footer Text.
$wp_customize->add_setting( $bimber_option_name . '[footer_text]', array(
	'default'           => $bimber_customizer_defaults['footer_text'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'wp_filter_post_kses',
) );

$wp_customize->add_control( 'bimber_footer_text', array(
	'label'    => __( 'Footer Text', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[footer_text]',
	'type'     => 'text',
) );

// Footer Stamp.
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_footer_stamp', array(
	'label'    => __( 'Footer Stamp', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[footer_stamp]',
) ) );


// Footer Stamp Width.
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp_width]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp_width'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_footer_stamp_width', array(
	'label'    => __( 'Footer Stamp Width', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[footer_stamp_width]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Footer Stamp Height.
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp_height]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp_height'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_footer_stamp_height', array(
	'label'    => __( 'Footer Stamp Height', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[footer_stamp_height]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );


// Footer Stamp HDPI.
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp_hdpi]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp_hdpi'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bimber_footer_stamp_hdpi', array(
	'label'       => __( 'Footer Stamp HDPI', 'bimber' ),
	'description' => __( 'An image for High DPI screen (like Retina) should be twice as big.', 'bimber' ),
	'section'     => 'title_tagline',
	'settings'    => $bimber_option_name . '[footer_stamp_hdpi]',
) ) );


// Footer Stamp Label.
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp_label]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp_label'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_footer_stamp_label', array(
	'label'    => __( 'Footer Stamp Label', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[footer_stamp_label]',
	'type'     => 'text',
) );

// Hide Footer Stamp Label
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp_label_hide]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp_label_hide'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_footer_stamp_label_hide', array(
	'label'    => __( 'Hide footer stamp label', 'bimber' ),
	'section'  => 'title_tagline',
	'settings' => $bimber_option_name . '[footer_stamp_label_hide]',
	'type'     => 'checkbox',
) );


// Footer Stamp Url.
$wp_customize->add_setting( $bimber_option_name . '[footer_stamp_url]', array(
	'default'           => $bimber_customizer_defaults['footer_stamp_url'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_footer_stamp_url', array(
	'label'       => __( 'Footer Stamp URL', 'bimber' ),
	'section'     => 'title_tagline',
	'settings'    => $bimber_option_name . '[footer_stamp_url]',
	'type'        => 'text',
	'input_attrs' => array(
		'placeholder' => __( 'http://', 'bimber' ),
	),
) );
