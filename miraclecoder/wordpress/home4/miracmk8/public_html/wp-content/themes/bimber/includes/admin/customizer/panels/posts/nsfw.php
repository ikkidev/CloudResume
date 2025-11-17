<?php
/**
 * WP Customizer panel section to handle featured entries options
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

$wp_customize->add_section( 'bimber_nsfw_section', array(
	'title'         => __( 'NSFW', 'bimber' ),
	'priority'      => 350,
	'panel'         => 'bimber_posts_panel',
	'description'   =>
		__( 'Don\'t know what NSFW is?', 'bimber' ) . ' ' .
		sprintf(
			str_replace(
				'<a href="%1$s">',
				'<a href="%1$s" target="_blank" rel="noopener">',
				__( 'Read more about it in our <a href="%1$s">online documentation</a>.', 'bimber' ) ),
			esc_url( bimber_get_documentation_link( 'nsfw' ) )
		),
) );


// Enabled?
$wp_customize->add_setting( $bimber_option_name . '[nsfw_enabled]', array(
	'default'           => $bimber_customizer_defaults['nsfw_enabled'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'bimber_nsfw_enabled', array(
	'label'    => __( 'Enabled?', 'bimber' ),
	'section'  => 'bimber_nsfw_section',
	'settings' => $bimber_option_name . '[nsfw_enabled]',
	'type'     => 'checkbox',
) );

// Categories ids.
$wp_customize->add_setting( $bimber_option_name . '[nsfw_categories_ids]', array(
	'default'           => $bimber_customizer_defaults['nsfw_categories_ids'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'bimber_sanitize_multi_choice',
) );

$wp_customize->add_control( new Bimber_Customize_Multi_Checkbox_Control( $wp_customize, 'bimber_nsfw_categories_ids', array(
	'label'           => __( 'NSFW Categories', 'bimber' ),
	'section'         => 'bimber_nsfw_section',
	'settings'        => $bimber_option_name . '[nsfw_categories_ids]',
	'choices'         => bimber_customizer_get_category_choices(),
	'active_callback' => 'bimber_customizer_nsfw_enabled',
) ) );

/**
 * Check whether NSFW is enabled
 *
 * @param WP_Customize_Control $control     Control instance for which this callback is executed.
 *
 * @return bool
 */
function bimber_customizer_nsfw_enabled( $control ) {
	$type = $control->manager->get_setting( bimber_get_theme_id() . '[nsfw_enabled]' )->value();

	return (bool) $type;
}
