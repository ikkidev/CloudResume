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

$wp_customize->add_section( 'bimber_footer_general_section', array(
	'title'    => __( 'General', 'bimber' ),
	'priority' => 10,
	'panel'    => 'bimber_footer_panel',
) );

// composition.
$wp_customize->add_setting( $bimber_option_name . '[footer_composition]', array(
	'default'           => $bimber_customizer_defaults['footer_composition'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_footer_composition', array(
	'label'    => __( 'Composition', 'bimber' ),
	'section'  => 'bimber_footer_general_section',
	'settings' => $bimber_option_name . '[footer_composition]',
	'type'     => 'select',
	'choices'  => bimber_get_footer_compositions(),
) );

// Back to Top Button.
$wp_customize->add_setting( $bimber_option_name . '[back_to_top]', array(
	'default'           => $bimber_customizer_defaults['back_to_top'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_back_to_top', array(
	'label'    => __( 'Show "Back to Top" Button?', 'bimber' ),
	'section'  => 'bimber_footer_general_section',
	'settings' => $bimber_option_name . '[back_to_top]',
	'type'     => 'select',
	'choices'  => array(
		'standard'  => __( 'Yes', 'bimber' ),
		'none'      => __( 'No', 'bimber' ),
	),
) );

