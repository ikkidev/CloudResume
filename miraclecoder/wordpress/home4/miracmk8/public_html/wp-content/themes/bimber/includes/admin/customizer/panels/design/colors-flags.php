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

// Divider.
$wp_customize->add_setting( 'bimber_colors_flags_trending_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_colors_flags_trending_divider', array(
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => 'bimber_colors_flags_trending_divider',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Trending', 'bimber' ) . '</h2>',
) ) );


// Trending Background Color.
$wp_customize->add_setting( $bimber_option_name . '[trending_background_color]', array(
	'default'           => $bimber_customizer_defaults['trending_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_trending_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[trending_background_color]',
) ) );

// Trending Optional Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[trending_optional_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['trending_optional_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_trending_optional_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[trending_optional_gradient_color]',
) ) );

// Trending Text.
$wp_customize->add_setting( $bimber_option_name . '[trending_text_color]', array(
	'default'           => $bimber_customizer_defaults['trending_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_trending_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[trending_text_color]',
) ) );

// Divider.
$wp_customize->add_setting( 'bimber_colors_flags_hot_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_colors_flags_hot_divider', array(
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => 'bimber_colors_flags_hot_divider',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Hot', 'bimber' ) . '</h2>',
) ) );


// Hot Background Color.
$wp_customize->add_setting( $bimber_option_name . '[hot_background_color]', array(
	'default'           => $bimber_customizer_defaults['hot_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_hot_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[hot_background_color]',
) ) );

// Hot Optional Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[hot_optional_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['hot_optional_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_hot_optional_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[hot_optional_gradient_color]',
) ) );

// Hot Text.
$wp_customize->add_setting( $bimber_option_name . '[hot_text_color]', array(
	'default'           => $bimber_customizer_defaults['hot_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_hot_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[hot_text_color]',
) ) );



// Divider.
$wp_customize->add_setting( 'bimber_colors_flags_popular_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_colors_flags_popular_divider', array(
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => 'bimber_colors_flags_popular_divider',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Popular', 'bimber' ) . '</h2>',
) ) );

// Popular Background Color.
$wp_customize->add_setting( $bimber_option_name . '[popular_background_color]', array(
	'default'           => $bimber_customizer_defaults['popular_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_popular_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[popular_background_color]',
) ) );


// Popular Optional Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[popular_optional_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['popular_optional_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_popular_optional_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[popular_optional_gradient_color]',
) ) );

// Popular Text.
$wp_customize->add_setting( $bimber_option_name . '[popular_text_color]', array(
	'default'           => $bimber_customizer_defaults['popular_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_popular_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[popular_text_color]',
) ) );


if ( bimber_can_use_plugin( 'restrict-content-pro/restrict-content-pro.php' ) ) {
	// Divider.
	$wp_customize->add_setting( 'bimber_colors_flags_members_only_divider', array(
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_colors_flags_members_only_divider', array(
		'section'  => 'bimber_design_colors_flags_section',
		'settings' => 'bimber_colors_flags_members_only_divider',
		'html'     =>
			'<hr /><h2>' . esc_html__( 'Members Only', 'bimber' ) . '</h2>',
	) ) );


	// Members Only Background Color.
	$wp_customize->add_setting( $bimber_option_name . '[members_only_background_color]', array(
		'default'           => $bimber_customizer_defaults['members_only_background_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_members_only_background_color', array(
		'label'    => __( 'Background Color', 'bimber' ),
		'section'  => 'bimber_design_colors_flags_section',
		'settings' => $bimber_option_name . '[members_only_background_color]',
	) ) );


	// Members Only Optional Background Gradient.
	$wp_customize->add_setting( $bimber_option_name . '[members_only_optional_gradient_color]', array(
		'default'           => $bimber_customizer_defaults['members_only_optional_gradient_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_members_only_optional_gradient_color', array(
		'label'    => __( 'Optional Background Gradient', 'bimber' ),
		'section'  => 'bimber_design_colors_flags_section',
		'settings' => $bimber_option_name . '[members_only_optional_gradient_color]',
	) ) );

	// Members Only Text.
	$wp_customize->add_setting( $bimber_option_name . '[members_only_text_color]', array(
		'default'           => $bimber_customizer_defaults['members_only_text_color'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_members_only_text_color', array(
		'label'    => __( 'Text Color', 'bimber' ),
		'section'  => 'bimber_design_colors_flags_section',
		'settings' => $bimber_option_name . '[members_only_text_color]',
	) ) );
}


// Divider.
$wp_customize->add_setting( 'bimber_colors_flags_coupon_inside_divider', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_colors_flags_coupon_inside_divider', array(
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => 'bimber_colors_flags_coupon_inside_divider',
	'html'     =>
		'<hr /><h2>' . esc_html__( 'Coupon Inside', 'bimber' ) . '</h2>',
) ) );

// Coupon Inside Background Color.
$wp_customize->add_setting( $bimber_option_name . '[coupon_inside_background_color]', array(
	'default'           => $bimber_customizer_defaults['coupon_inside_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_coupon_inside_background_color', array(
	'label'    => __( 'Background Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[coupon_inside_background_color]',
) ) );


// Coupon Inside Optional Background Gradient.
$wp_customize->add_setting( $bimber_option_name . '[coupon_inside_optional_gradient_color]', array(
	'default'           => $bimber_customizer_defaults['coupon_inside_optional_gradient_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_coupon_inside_optional_gradient_color', array(
	'label'    => __( 'Optional Background Gradient', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[coupon_inside_optional_gradient_color]',
) ) );

// Coupon Inside Text.
$wp_customize->add_setting( $bimber_option_name . '[coupon_inside_text_color]', array(
	'default'           => $bimber_customizer_defaults['coupon_inside_text_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_hex_color',
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_coupon_inside_text_color', array(
	'label'    => __( 'Text Color', 'bimber' ),
	'section'  => 'bimber_design_colors_flags_section',
	'settings' => $bimber_option_name . '[coupon_inside_text_color]',
) ) );
