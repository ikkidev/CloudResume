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

$wp_customize->add_section( 'bimber_header_builder_section_elements_primary_nav', array(
	'title'    => sprintf(  __( 'Element: %s', 'bimber' ), __( 'Primary Nav', 'bimber' ) ),
	'panel'    => 'bimber_header_panel',
) );


// Primary Nav Icons.
$wp_customize->add_setting( $bimber_option_name . '[header_primary_nav_icons]', array(
	'default'           => $bimber_customizer_defaults['header_primary_nav_icons'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  		=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_primary_nav_icons', array(
	'label'    => __( 'Icons', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_primary_nav',
	'settings' => $bimber_option_name . '[header_primary_nav_icons]',
	'type'     => 'select',
	'choices'     => array(
		'none'      => __( 'Hide', 'bimber' ),
		'standard'  => __( 'Show', 'bimber' ),
	),
) );


// Primary Nav Margin Top.
$wp_customize->add_setting( $bimber_option_name . '[header_primary_nav_margin_top]', array(
	'default'           => $bimber_customizer_defaults['header_primary_nav_margin_top'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_primary_nav_margin_top', array(
	'label'    => __( 'Margin Top', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_primary_nav',
	'settings' => $bimber_option_name . '[header_primary_nav_margin_top]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Primary Nav Margin Bottom.
$wp_customize->add_setting( $bimber_option_name . '[header_primary_nav_margin_bottom]', array(
	'default'           => $bimber_customizer_defaults['header_primary_nav_margin_bottom'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_primary_nav_margin_bottom', array(
	'label'    => __( 'Margin Bottom', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_primary_nav',
	'settings' => $bimber_option_name . '[header_primary_nav_margin_bottom]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Primary Nav Layout.
$wp_customize->add_setting( $bimber_option_name . '[header_primarynav_layout]', array(
	'default'           => $bimber_customizer_defaults['header_primarynav_layout'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_header_primarynav_layout', array(
	'label'    => __( 'Layout', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_primary_nav',
	'settings' => $bimber_option_name . '[header_primarynav_layout]',
	'type'     => 'select',
	'choices'     => array(
		'standard'      => __( 'standard', 'bimber' ),
		'justified'          => __( 'justified', 'bimber' ),
	),
) );
