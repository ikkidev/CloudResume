<?php
/**
 * Snax Customizer integration
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

add_filter( 'bimber_customizer_defaults',       'bimber_wyr_add_customizer_defaults' );
add_action( 'bimber_after_customize_register',   'bimber_wyr_register_customizer_options', 10, 1 );

/**
 * Register plugin defaults
 *
 * @param array $defaults       Default values.
 *
 * @return array
 */
function bimber_wyr_add_customizer_defaults( $defaults ) {
	$defaults['wyr_show_reactions_in_header']       = 'standard';
	$defaults['wyr_show_entry_reactions']           = 'standard';
	$defaults['wyr_show_entry_reactions_single']    = 'standard';

	return $defaults;
}

/**
 * Add plugin panel
 *
 * @param WP_Customize_Manager $wp_customize        Customizer instance.
 */
function bimber_wyr_register_customizer_options( $wp_customize ) {

	$defaults    = bimber_get_customizer_defaults();
	$option_name = bimber_get_theme_id();

	/**
	 * Plugin main panel
	 */

	$wp_customize->add_panel( 'bimber_wyr_panel', array(
		'title'    => __( 'What\'s Your Reaction Plugin', 'bimber' ),
		'priority' => 800,
	) );

	/**
	 * General (section)
	 */

	$wp_customize->add_section( 'bimber_wyr_general_section', array(
		'title'    => __( 'General', 'bimber' ),
		'priority' => 10,
		'panel'    => 'bimber_wyr_panel',
	) );

	/**
	 * General (controls)
	 */

	// Hide reactions in header.

	$wp_customize->add_setting( $option_name . '[wyr_show_reactions_in_header]', array(
		'default'           => $defaults['wyr_show_reactions_in_header'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_wyr_show_reactions_in_header', array(
		'label'    => __( 'Show reactions in header', 'bimber' ),
		'section'  => 'bimber_wyr_general_section',
		'settings' => $option_name . '[wyr_show_reactions_in_header]',
		'type'     => 'select',
		'choices'  => array(
			'standard'	=> __( 'Yes', 'bimber' ),
			'none'		=> __( 'No', 'bimber' ),
		),
	) );

	// Hide entry reactions.

	$wp_customize->add_setting( $option_name . '[wyr_show_entry_reactions]', array(
		'default'           => $defaults['wyr_show_entry_reactions'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_wyr_show_entry_reactions', array(
		'label'    => __( 'Show entry reactions in collections', 'bimber' ),
		'section'  => 'bimber_wyr_general_section',
		'settings' => $option_name . '[wyr_show_entry_reactions]',
		'type'     => 'select',
		'choices'  => array(
			'standard'	=> __( 'Yes', 'bimber' ),
			'none'		=> __( 'No', 'bimber' ),
		),
	) );

	// Hide entry reactions.

	$wp_customize->add_setting( $option_name . '[wyr_show_entry_reactions_single]', array(
		'default'           => $defaults['wyr_show_entry_reactions_single'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_wyr_show_entry_reactions_single', array(
		'label'    => __( 'Show entry reactions on single post', 'bimber' ),
		'section'  => 'bimber_wyr_general_section',
		'settings' => $option_name . '[wyr_show_entry_reactions_single]',
		'type'     => 'select',
		'choices'  => array(
			'standard'	=> __( 'Yes', 'bimber' ),
			'none'		=> __( 'No', 'bimber' ),
		),
	) );

}
