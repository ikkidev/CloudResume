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


$wp_customize->add_section( 'bimber_header_builder_section_elements_hamburger', array(
	'title'    => sprintf(  __( 'Element: %s', 'bimber' ), __( 'Hamburger', 'bimber' ) ),
	'panel'    => 'bimber_header_panel',
) );

$wp_customize->add_setting( $bimber_option_name . '[header_builder_element_label_mobile_menu]', array(
	'default'               => $bimber_customizer_defaults['header_builder_element_label_mobile_menu'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( 'bimber_header_builder_element_label_mobile_menu', array(
	'label'    => __( 'Label', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_hamburger',
	'settings' => $bimber_option_name . '[header_builder_element_label_mobile_menu]',
	'type'     => 'select',
	'choices'  => array(
		'standard'                          => __( 'enable', 'bimber' ),
		'g1-hamburger-label-hidden'      	=> __( 'disable', 'bimber' ),
	),
) );

$wp_customize->add_setting( $bimber_option_name . '[header_builder_element_size_mobile_menu]', array(
	'default'               => $bimber_customizer_defaults['header_builder_element_size_mobile_menu'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
$wp_customize->add_control( new Bimber_Customize_Custom_Radio_Control( $wp_customize, 'bimber_header_builder_element_size_mobile_menu', array(
	'label'    => __( 'Size', 'bimber' ),
	'section'  => 'bimber_header_builder_section_elements_hamburger',
	'settings' => $bimber_option_name . '[header_builder_element_size_mobile_menu]',
	'type'     => 'radio',
	'input_attrs' => array(
		'row-class' => 'radio-single-line',
	),
	'choices'  => array(
		'g1-hamburger-s'  	=> '16px',
		'g1-hamburger-m'    => '24px',
		'standard'      	=> '32px',
	),
) ) );
