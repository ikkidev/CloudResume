<?php
/**
 * WP Customizer panel section to handle Design > Cards options
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

// Cards Section.
$wp_customize->add_section( 'bimber_design_cards_section', array(
	'title'    => __( 'Cards', 'bimber' ) . ' (BETA)',
	'priority' => 20,
	'panel'    => 'bimber_design_panel',
) );

$bimber_card_style_choices = array(
	'none'      => __( 'None', 'bimber' ),
	'solid'     => __( 'Solid', 'bimber' ),
	'simple'    => __( 'Simple', 'bimber' ),
	'subtle'    => __( 'Subtle', 'bimber' ),
);


// Home Content.
$wp_customize->add_setting( $bimber_option_name . '[cards_home_content]', array(
	'default'           => $bimber_customizer_defaults['cards_home_content'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_home_content', array(
	'label'    => __( 'Home Content', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_home_content]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );

// Home Sidebars.
$wp_customize->add_setting( $bimber_option_name . '[cards_home_sidebar]', array(
	'default'           => $bimber_customizer_defaults['cards_home_sidebar'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_home_sidebar', array(
	'label'    => __( 'Home Sidebars', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_home_sidebar]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );



// Archive Content.
$wp_customize->add_setting( $bimber_option_name . '[cards_archive_content]', array(
	'default'           => $bimber_customizer_defaults['cards_archive_content'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_archive_content', array(
	'label'    => __( 'Archive Content', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_archive_content]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );

// Archive Sidebars.
$wp_customize->add_setting( $bimber_option_name . '[cards_archive_sidebar]', array(
	'default'           => $bimber_customizer_defaults['cards_archive_sidebar'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_archive_sidebar', array(
	'label'    => __( 'Archive Sidebars', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_archive_sidebar]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );


// Search Page Content.
$wp_customize->add_setting( $bimber_option_name . '[cards_search_content]', array(
	'default'           => $bimber_customizer_defaults['cards_search_content'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_search_content', array(
	'label'    => __( 'Search Page Content', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_search_content]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );

// Search Page Sidebars.
$wp_customize->add_setting( $bimber_option_name . '[cards_search_sidebar]', array(
	'default'           => $bimber_customizer_defaults['cards_search_sidebar'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_search_sidebar', array(
	'label'    => __( 'Search Page Sidebars', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_search_sidebar]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );


// Single Content.
$wp_customize->add_setting( $bimber_option_name . '[cards_single_content]', array(
	'default'           => $bimber_customizer_defaults['cards_single_content'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_single_content', array(
	'label'    => __( 'Single Content', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_single_content]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );

// Single Sidebars.
$wp_customize->add_setting( $bimber_option_name . '[cards_single_sidebar]', array(
	'default'           => $bimber_customizer_defaults['cards_single_sidebar'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_single_sidebar', array(
	'label'    => __( 'Single Sidebars', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_single_sidebar]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );

// Single Comments.
$wp_customize->add_setting( $bimber_option_name . '[cards_single_comments]', array(
	'default'           => $bimber_customizer_defaults['cards_single_comments'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_cards_single_comments', array(
	'label'    => __( 'Single Comments', 'bimber' ),
	'section'  => 'bimber_design_cards_section',
	'settings' => $bimber_option_name . '[cards_single_comments]',
	'type'     => 'select',
	'choices'  => $bimber_card_style_choices,
) );



