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

$wp_customize->add_section( 'bimber_header_builder_section_elements_quick_nav', array(
	'title'    => sprintf(  __( 'Element: %s', 'bimber' ), __( 'Quick Nav', 'bimber' ) ),
	'panel'    => 'bimber_header_panel',
) );

// Quick Nav Margin Top.
$wp_customize->add_setting( $bimber_option_name . '[header_quicknav_margin_top]', array(
	'default'           => $bimber_customizer_defaults['header_quicknav_margin_top'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_quicknav_margin_top', array(
	'label'    => __( 'Margin Top', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_quick_nav',
	'settings' => $bimber_option_name . '[header_quicknav_margin_top]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Quick Nav Margin Bottom.
$wp_customize->add_setting( $bimber_option_name . '[header_quicknav_margin_bottom]', array(
	'default'           => $bimber_customizer_defaults['header_quicknav_margin_bottom'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_quicknav_margin_bottom', array(
	'label'    => __( 'Margin Bottom', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_quick_nav',
	'settings' => $bimber_option_name . '[header_quicknav_margin_bottom]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Quick Nav Labels Visibility.
$wp_customize->add_setting( $bimber_option_name . '[header_quicknav_labels]', array(
	'default'           => $bimber_customizer_defaults['header_quicknav_labels'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_header_quicknav_labels', array(
	'label'    => __( 'Labels', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_quick_nav',
	'settings' => $bimber_option_name . '[header_quicknav_labels]',
	'type'     => 'select',
	'choices'     => array(
		'standard'      => __( 'Show', 'bimber' ),
		'none'          => __( 'Hide', 'bimber' ),
	),
) );


