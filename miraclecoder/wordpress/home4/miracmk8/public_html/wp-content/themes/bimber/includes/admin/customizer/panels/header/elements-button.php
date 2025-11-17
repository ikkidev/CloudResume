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

$wp_customize->add_section( 'bimber_header_builder_section_elements_button', array(
	'title'    => sprintf(  __( 'Element: %s', 'bimber' ), __( 'Create Button', 'bimber' ) ),
	'panel'    => 'bimber_header_panel',
) );

// Header "Create" button visibility.
$wp_customize->add_setting( $bimber_option_name . '[snax_header_create_button_visibility]', array(
	'default'           => $bimber_customizer_defaults['snax_header_create_button_visibility'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
	$wp_customize->add_control( 'bimber_snax_header_create_button_visibility', array(
		'label'    => __( 'Show', 'bimber' ),
		'section'  => 'bimber_header_builder_section_elements_button',
		'settings' => $bimber_option_name . '[snax_header_create_button_visibility]',
		'type'     => 'select',
		'choices'  => array(
			'all'		=> __( 'for all', 'bimber' ),
			'logged_in'	=> __( 'for logged in users', 'bimber' ),
			'none'		=> __( 'no', 'bimber' ),
		),
	) );
}

$wp_customize->add_setting( $bimber_option_name . '[header_builder_element_size_create_button]', array(
	'default'               => $bimber_customizer_defaults['header_builder_element_size_create_button'],
	'type'                  => 'option',
	'capability'            => 'edit_theme_options',
	'sanitize_callback'     => 'sanitize_text_field',
	'transport'  			=> 'postMessage',
) );
if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
	$wp_customize->add_control( new Bimber_Customize_Custom_Radio_Control( $wp_customize, 'bimber_header_builder_element_size_create_button', array(
		'label'    => __( 'Size', 'bimber' ),
		'section'  => 'bimber_header_builder_section_elements_button',
		'settings' => $bimber_option_name . '[header_builder_element_size_create_button]',
		'type'     => 'radio',
		'input_attrs' => array(
			'row-class' => 'radio-single-line',
		),
		'choices'  => array(
			'g1-button-s'      	=> __( 'small', 'bimber' ),
			'g1-button-m'      => __( 'standard', 'bimber' ),
		),
	) ) );
}

// Header "Create" button type.
$wp_customize->add_setting( $bimber_option_name . '[snax_header_create_button_type]', array(
	'default'           => $bimber_customizer_defaults['snax_header_create_button_type'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
	$all_formats = snax_get_formats();
	$choices = array(
			'all'				=> __( 'Create (button)', 'bimber' ),
			'all_dropdown'		=> __( 'Create (dropdown)', 'bimber' ),
	);
	foreach ( $all_formats as $key => $format) {
		$choices[ $key ] = 'Create ' . $format['labels']['name'];
	}
	$wp_customize->add_control( 'bimber_snax_header_create_button_type', array(
		'label'    => __( 'Type', 'bimber' ),
		'section'  => 'bimber_header_builder_section_elements_button',
		'settings' => $bimber_option_name . '[snax_header_create_button_type]',
		'type'     => 'select',
		'choices'  => $choices,
	) );
}

// Header "Create" button label.
$wp_customize->add_setting( $bimber_option_name . '[snax_header_create_button_label]', array(
	'default'           => $bimber_customizer_defaults['snax_header_create_button_label'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
    'transport'         => 'postMessage',
) );
if ( bimber_can_use_plugin( 'snax/snax.php' ) ) {
	$wp_customize->add_control( 'bimber_snax_header_create_button_label', array(
		'label'       => __( 'Label', 'bimber' ),
		'section'     => 'bimber_header_builder_section_elements_button',
		'settings'    => $bimber_option_name . '[snax_header_create_button_label]',
		'type'        => 'text',
	) );
}
