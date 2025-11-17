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

$wp_customize->add_section( 'bimber_design_general_section', array(
	'title'    => __( 'General', 'bimber' ),
	'priority' => 10,
	'panel'    => 'bimber_design_panel',
) );
$wp_customize->add_section( 'bimber_design_colors_section', array(
	'title'    => __( 'Colors', 'bimber' ),
	'priority' => 50,
	'panel'    => 'bimber_design_panel',
) );

$wp_customize->add_section( 'bimber_design_colors_flags_section', array(
	'title'    => __( 'Colors', 'bimber' ) . ' - ' . __( 'Flags', 'bimber' ),
	'priority' => 60,
	'panel'    => 'bimber_design_panel',
) );


// Stack.
$wp_customize->add_setting( $bimber_option_name . '[global_stack]', array(
	'default'           => $bimber_customizer_defaults['global_stack'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$the_choices = array(
	'app' 			=> 'app',
	'bunchy'        => 'bunchy',
	'cards'         => 'cards',
	'cards-2019'    => 'cards 2019',
	'carmania'		=> 'carmania',
	'fashion'  		=> 'fashion',
	'food'  		=> 'food',
	'hardcore'      => 'hardcore',
	'miami'         => 'miami',
	'minimal'       => 'minimal',
	'music'         => 'music',
	'news'          => 'news',
	'original'      => 'original',
	'original-2018' => 'original 2018',
	'system'        => 'system',
	'video'         => 'video',
);


$wp_customize->add_control( 'bimber_global_stack', array(
	'label'    => __( 'Style', 'bimber' ),
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[global_stack]',
	'type'     => 'select',
	'choices'  => $the_choices,
) );


// Skin.
$wp_customize->add_setting( $bimber_option_name . '[global_skin]', array(
	'default'           => $bimber_customizer_defaults['global_skin'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_global_skin', array(
	'label'         => __( 'Skin', 'bimber' ),
	'description'   => 'Add the skin switcher via <a href="#" class="bimber-test">the Header Builder</a>',
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[global_skin]',
	'type'     => 'select',
	'choices'  => array(
		'light'  => _x( 'Light', 'skin', 'bimber' ),
		'dark'   => _x( 'Dark',  'skin', 'bimber' ),
	),
) );

// Page layout.
$wp_customize->add_setting( $bimber_option_name . '[global_layout]', array(
	'default'           => $bimber_customizer_defaults['global_layout'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	'transport'         => 'postMessage',
) );

$wp_customize->add_control( 'bimber_global_layout', array(
	'label'    => __( 'Layout', 'bimber' ),
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[global_layout]',
	'type'     => 'select',
	'choices'  => array(
		'boxed'     => _x( 'Boxed', 'layout', 'bimber' ),
		'stretched' => _x( 'Stretched', 'layout', 'bimber' ),
	),
) );



// Icon style.
$wp_customize->add_setting( $bimber_option_name . '[global_icon_style]', array(
	'default'           => $bimber_customizer_defaults['global_icon_style'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_global_icon_style', array(
	'label'    => __( 'Icon Style', 'bimber' ),
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[global_icon_style]',
	'type'     => 'select',
	'choices'  => array(
		'default'   => __( 'Default', 'bimber' ),
		'solid'  	=> _x( 'Solid', 'icon style', 'bimber' ),
		'line'     	=> _x( 'Line',  'icon style', 'bimber' ),
	),
) );


// Breadcrumbs.
$wp_customize->add_setting( $bimber_option_name . '[breadcrumbs]', array(
	'default'           => $bimber_customizer_defaults['breadcrumbs'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_breadcrumbs', array(
	'label'    => __( 'Breadcrumbs', 'bimber' ),
	'section'  => 'bimber_design_general_section',
	'settings' => $bimber_option_name . '[breadcrumbs]',
	'type'     => 'select',
	'choices'  => array(
		'standard'  => __( 'Show', 'bimber' ),
		'none'      => __( 'Hide', 'bimber' ),
	),
) );
