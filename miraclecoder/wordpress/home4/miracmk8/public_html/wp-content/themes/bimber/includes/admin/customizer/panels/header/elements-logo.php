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

$wp_customize->add_section( 'bimber_header_builder_section_elements_logo', array(
	'title'    => sprintf(  __( 'Element: %s', 'bimber' ), __( 'Logo', 'bimber' ) ),
	'panel'    => 'bimber_header_panel',
) );

// Logo Margin Top.
$wp_customize->add_setting( $bimber_option_name . '[header_logo_margin_top]', array(
	'default'           => $bimber_customizer_defaults['header_logo_margin_top'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_logo_margin_top', array(
	'label'    => __( 'Margin Top', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_logo',
	'settings' => $bimber_option_name . '[header_logo_margin_top]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );

// Logo Margin Bottom.
$wp_customize->add_setting( $bimber_option_name . '[header_logo_margin_bottom]', array(
	'default'           => $bimber_customizer_defaults['header_logo_margin_bottom'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_logo_margin_bottom', array(
	'label'    => __( 'Margin Bottom', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_logo',
	'settings' => $bimber_option_name . '[header_logo_margin_bottom]',
	'type'     => 'number',
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );
