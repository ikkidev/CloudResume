<?php
/**
 * BuddyPress Customizer integration
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

add_filter( 'bimber_customizer_defaults',       'bimber_bp_add_customizer_defaults' );
add_action( 'bimber_after_customize_register',  'bimber_bp_register_customizer_options', 10, 1 );

/**
 * Register plugin defaults
 *
 * @param array $defaults       Default values.
 *
 * @return array
 */
function bimber_bp_add_customizer_defaults( $defaults ) {
	return array_merge( $defaults, array(
		'bp_enable_sidebar'     => 'standard',
		'bp_archive_template'   => 'grid-m',
	) );
}

/**
 * Add plugin panel
 *
 * @param WP_Customize_Manager $wp_customize        Customizer instance.
 */
function bimber_bp_register_customizer_options( $wp_customize ) {

	$defaults    = bimber_get_customizer_defaults();
	$option_name = bimber_get_theme_id();

	/**
	 * Sections
	 */

	$wp_customize->add_section( 'bimber_bp_general_section', array(
		'title'    => esc_html__( 'BuddyPress Plugin', 'bimber' ),
		'priority' => 520,
	) );

	/**
	 * Controls
	 */

	// Enable sidebar.
	$wp_customize->add_setting( $option_name . '[bp_enable_sidebar]', array(
		'default'           => $defaults['bp_enable_sidebar'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'bimber_bp_enable_sidebar', array(
		'label'    => esc_html__( 'Show sidebar on BuddyPress pages', 'bimber' ),
		'section'  => 'bimber_bp_general_section',
		'settings' => $option_name . '[bp_enable_sidebar]',
		'type'     => 'select',
		'choices'  => array(
			'standard'	=> __( 'Yes', 'bimber' ),
			'none'		=> __( 'No', 'bimber' ),
		),
	) );

	// Archive template.
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/archive/';
	$bimber_choices = array(
		'grid-m' => array(
			'label' => 'Grid',
			'path'  => $uri . 'grid.png',
		),
		'stream' => array(
			'label' => 'Stream',
			'path'  => $uri . 'stream.png',
		),
		'upvote' => array(
			'label' => 'Upvote with Sidebar',
			'path'  => $uri . 'list-s-sidebar.png',
		),
	);

	$wp_customize->add_setting( $option_name . '[bp_archive_template]', array(
		'default'           => $defaults['bp_archive_template'],
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Bimber_Customize_Multi_Radio_Control( $wp_customize, 'bimber_bp_archive_template', array(
		'label'    => __( 'Archive Template', 'bimber' ),
		'section'  => 'bimber_bp_general_section',
		'settings' => $option_name . '[bp_archive_template]',
		'type'     => 'select',
		'choices'  => $bimber_choices,
		'columns'  => 3,
	) ) );
}
