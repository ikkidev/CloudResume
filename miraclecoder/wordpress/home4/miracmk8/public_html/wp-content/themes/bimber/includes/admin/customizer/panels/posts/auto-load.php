<?php
/**
 * WP Customizer panel section to handle post posts_auto_load options
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

// Header.
$wp_customize->add_setting( 'bimber_post_auto_load_header', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Bimber_Customize_HTML_Control( $wp_customize, 'bimber_post_auto_load_header', array(
	'section'  => 'bimber_posts_single_section',
	'settings' => 'bimber_post_auto_load_header',
	'priority' => 650,
	'html'     =>
		'<hr />
		<h2>' . __( 'Autoload Next Post', 'bimber' ) . '</h2>',
) ) );

// Enable Auto load.
$wp_customize->add_setting( $bimber_option_name . '[posts_auto_load_enable]', array(
	'default'           => $bimber_customizer_defaults['posts_auto_load_enable'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_auto_load_enable', array(
	'label'    => __( 'Enabled?', 'bimber' ),
	'section'  => 'bimber_posts_single_section',
	'settings' => $bimber_option_name . '[posts_auto_load_enable]',
	'type'     => 'checkbox',
	'priority' 	  => 660,
) );

// Load posts from the same category?
$wp_customize->add_setting( $bimber_option_name . '[posts_auto_load_in_same_category]', array(
	'default'           => $bimber_customizer_defaults['posts_auto_load_in_same_category'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_posts_auto_load_in_same_category', array(
	'label'    => __( 'From the same category?', 'bimber' ),
	'section'  => 'bimber_posts_single_section',
	'settings' => $bimber_option_name . '[posts_auto_load_in_same_category]',
	'type'     => 'checkbox',
	'priority' 	  => 665,
) );

// Max posts.
$wp_customize->add_setting( $bimber_option_name . '[posts_auto_load_max_posts]', array(
	'default'           => $bimber_customizer_defaults['posts_auto_load_max_posts'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( 'bimber_posts_auto_load_max_posts', array(
	'label'       => __( 'Max posts to load (0 for infinite)', 'bimber' ),
	'section'     => 'bimber_posts_single_section',
	'settings'    => $bimber_option_name . '[posts_auto_load_max_posts]',
	'type'        => 'number',
	'priority' 	  => 670,
	'input_attrs' => array(
		'class' => 'small-text',
	),
) );
