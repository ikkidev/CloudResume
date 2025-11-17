<?php
/**
 * WP Customizer panel section to handle general design options
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

$wp_customize->add_section( 'bimber_design_breadcrumbs_section', array(
	'title'    => __( 'Breadcrumbs', 'bimber' ),
	'priority' => 100,
	'panel'    => 'bimber_design_panel',
) );


// Enable.
$wp_customize->add_setting( $bimber_option_name . '[breadcrumbs]', array(
	'default'           => $bimber_customizer_defaults['breadcrumbs'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_breadcrumbs', array(
	'label'    => __( 'Enabled?', 'bimber' ),
	'section'  => 'bimber_design_breadcrumbs_section',
	'settings' => $bimber_option_name . '[breadcrumbs]',
	'type'     => 'select',
	'choices'  => array(
		'standard'  => __( 'Yes', 'bimber' ),
		'none'      => __( 'No', 'bimber' ),
	),
) );

// Ellipsis.
$wp_customize->add_setting( $bimber_option_name . '[breadcrumbs_ellipsis]', array(
	'default'           => $bimber_customizer_defaults['breadcrumbs_ellipsis'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport' => 'postMessage',
) );

$wp_customize->add_control( 'bimber_breadcrumbs_ellipsis', array(
	'label'     => __( 'Show an ellipsis for longer items', 'bimber' ),
	'section'   => 'bimber_design_breadcrumbs_section',
	'settings'  => $bimber_option_name . '[breadcrumbs_ellipsis]',
	'type'      => 'checkbox',
) );
