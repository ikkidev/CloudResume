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

// -------
// SECTION
// -------

$wp_customize->add_section( 'bimber_footer_modules_section', array(
	'title'    => __( 'Modules', 'bimber' ),
	'priority' => 75,
	'panel'    => 'bimber_footer_panel',
) );

$bimber_curr_module_order                = 0;
$bimber_sortable_elements_start_priority = 1000;

$bimber_order_controls = array(
//	'footer_podcast_order'           => esc_html__( 'Podcast', 'bimber' ),
	'footer_newsletter_order'        => __( 'Newsletter', 'bimber' ),
	'footer_patreon_order'           => 'Patreon',
	'footer_links_order'             => __( 'Links', 'bimber' ),
	'footer_instagram_order'         => 'Instagram',
	'footer_social_order'            => __( 'Social Media Profile Links', 'bimber' ),
	'footer_promoted_products_order' => __( 'Promoted Products', 'bimber' ),
	'footer_promoted_product_order'  => __( 'Promoted Single Product', 'bimber' ),
);

// ---
// Elements order
// ---

$wp_customize->add_setting( 'bimber_footer_settings_order', array(
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new Bimber_Customize_Sortable_Control( $wp_customize, 'bimber_footer_settings_order', array(
	'label'             => __( 'Sections order', 'bimber' ),
	'description'       => __( 'Drag and drop to reorder.', 'bimber' ),
	'section'           => 'bimber_footer_modules_section',
	'settings'          => 'bimber_footer_settings_order',
	'priority'          => $bimber_sortable_elements_start_priority,
	'sortable_controls' => $bimber_order_controls,
) ) );

// ---
// Podcast
// ---
//$bimber_curr_module_order += 10;
//
//// Visibility.
//$wp_customize->add_setting( $bimber_option_name . '[podcast_above_footer]', array(
//	'default'           => $bimber_customizer_defaults['podcast_above_footer'],
//	'type'              => 'option',
//	'capability'        => 'edit_theme_options',
//	'sanitize_callback' => 'sanitize_text_field',
//) );
//$wp_customize->add_control( 'podcast_above_footer', array(
//	'label'    => esc_html__( 'Show Podcast', 'bimber' ),
//	'section'  => 'bimber_footer_modules_section',
//	'settings' => $bimber_option_name . '[podcast_above_footer]',
//	'type'     => 'checkbox',
//	'priority' => $bimber_curr_module_order,
//) );
//
//// Color scheme.
//$wp_customize->add_setting( $bimber_option_name . '[podcast_above_footer_color_scheme]', array(
//	'default'           => $bimber_customizer_defaults['podcast_above_footer_color_scheme'],
//	'type'              => 'option',
//	'capability'        => 'edit_theme_options',
//	'sanitize_callback' => 'sanitize_text_field',
//) );
//$wp_customize->add_control( 'podcast_above_footer_color_scheme', array(
//	'label'           => esc_html__( 'Podcast Color Scheme', 'bimber' ),
//	'section'         => 'bimber_footer_modules_section',
//	'settings'        => $bimber_option_name . '[podcast_above_footer_color_scheme]',
//	'type'            => 'select',
//	'choices'         => array(
//		'light' => esc_html__( 'light', 'bimber' ),
//		'dark'  => esc_html__( 'dark', 'bimber' ),
//	),
//	'priority'        => $bimber_curr_module_order + 5,
//	'active_callback' => 'bimber_customizer_podcast_above_footer_is_active',
//) );
//
//// Background color.
//$wp_customize->add_setting( $bimber_option_name . '[podcast_above_footer_background_color]', array(
//	'default'           => $bimber_customizer_defaults['podcast_above_footer_background_color'],
//	'type'              => 'option',
//	'capability'        => 'edit_theme_options',
//	'sanitize_callback' => 'sanitize_text_field',
//) );
//$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_podcast_above_footer_background_color', array(
//	'label'           => esc_html__( 'Podcast Background Color', 'bimber' ),
//	'section'         => 'bimber_footer_modules_section',
//	'settings'        => $bimber_option_name . '[podcast_above_footer_background_color]',
//	'priority'        => $bimber_curr_module_order + 7,
//	'active_callback' => 'bimber_customizer_podcast_above_footer_is_active',
//) ) );
//
//// Order.
//$wp_customize->add_setting( $bimber_option_name . '[footer_podcast_order]', array(
//	'default'           => $bimber_customizer_defaults['footer_podcast_order'],
//	'type'              => 'option',
//	'capability'        => 'edit_theme_options',
//	'sanitize_callback' => 'absint',
//) );
//$wp_customize->add_control( 'bimber_footer_podcast_order', array(
//	'label'    => $bimber_order_controls['footer_podcast_order'],
//	'section'  => 'bimber_footer_modules_section',
//	'settings' => $bimber_option_name . '[footer_podcast_order]',
//	'type'     => 'text',
//	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
//) );
//
//function bimber_customizer_podcast_above_footer_is_active( $control ) {
//	$value = $control->manager->get_setting( bimber_get_theme_id() . '[podcast_above_footer]' )->value();
//
//	return ! empty( $value );
//}


// ---
// Newsletter
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[newsletter_before_footer]', array(
	'default'           => $bimber_customizer_defaults['newsletter_before_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'newsletter_before_footer', array(
	'label'       => __( 'Show Newsletter', 'bimber' ),
	'description' => bimber_can_use_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'Mailchimp for WordPress' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[newsletter_before_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Color scheme.
$wp_customize->add_setting( $bimber_option_name . '[newsletter_before_footer_color_scheme]', array(
	'default'           => $bimber_customizer_defaults['newsletter_before_footer_color_scheme'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'newsletter_before_footer_color_scheme', array(
	'label'           => __( 'Newsletter Color Scheme', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[newsletter_before_footer_color_scheme]',
	'type'            => 'select',
	'choices'         => array(
		'light' => __( 'Light', 'bimber' ),
		'dark'  => __( 'Dark', 'bimber' ),
	),
	'priority'        => $bimber_curr_module_order + 5,
	'active_callback' => 'bimber_customizer_newsletter_before_footer_is_active',
) );

// Background color.
$wp_customize->add_setting( $bimber_option_name . '[newsletter_before_footer_background_color]', array(
	'default'           => $bimber_customizer_defaults['newsletter_before_footer_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_newsletter_before_footer_background_color', array(
	'label'           => __( 'Newsletter', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[newsletter_before_footer_background_color]',
	'priority'        => $bimber_curr_module_order + 7,
	'active_callback' => 'bimber_customizer_newsletter_before_footer_is_active',
) ) );

// Title.
$wp_customize->add_setting( $bimber_option_name . '[newsletter_before_footer_title]', array(
	'default'           => $bimber_customizer_defaults['newsletter_before_footer_title'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'bimber_sanitize_section_title_allowed_tags',
) );
$wp_customize->add_control( 'bimber_newsletter_before_footer_title', array(
	'label'           => __( 'Newsletter Title', 'bimber' ),
	'description'     => '',
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[newsletter_before_footer_title]',
	'type'            => 'textarea',
	'priority'        => $bimber_curr_module_order + 8,
	'active_callback' => 'bimber_customizer_newsletter_before_footer_is_active',
) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_newsletter_order]', array(
	'default'           => $bimber_customizer_defaults['footer_newsletter_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_newsletter_order', array(
	'label'    => $bimber_order_controls['footer_newsletter_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_newsletter_order]',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );

function bimber_customizer_newsletter_before_footer_is_active( $control ) {
	$value = $control->manager->get_setting( bimber_get_theme_id() . '[newsletter_before_footer]' )->value();

	return ! empty( $value );
}


// ---
// Patreon
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[patreon_above_footer]', array(
	'default'           => $bimber_customizer_defaults['patreon_above_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'patreon_above_footer', array(
	'label'       => __( 'Show Patreon', 'bimber' ),
	'description' => bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'AdAce' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[patreon_above_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Color scheme.
$wp_customize->add_setting( $bimber_option_name . '[patreon_above_footer_color_scheme]', array(
	'default'           => $bimber_customizer_defaults['patreon_above_footer_color_scheme'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'patreon_above_footer_color_scheme', array(
	'label'           => __( 'Patreon Color Scheme', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[patreon_above_footer_color_scheme]',
	'type'            => 'select',
	'choices'         => array(
		'light' => __( 'Light', 'bimber' ),
		'dark'  => __( 'Dark', 'bimber' ),
	),
	'priority'        => $bimber_curr_module_order + 5,
	'active_callback' => 'bimber_customizer_patreon_above_footer_is_active',
) );

// Background color.
$wp_customize->add_setting( $bimber_option_name . '[patreon_above_footer_background_color]', array(
	'default'           => $bimber_customizer_defaults['patreon_above_footer_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_patreon_above_footer_background_color', array(
	'label'           => 'Patreon' . ': ' . __( 'Background Color', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[patreon_above_footer_background_color]',
	'priority'        => $bimber_curr_module_order + 7,
	'active_callback' => 'bimber_customizer_patreon_above_footer_is_active',
) ) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_patreon_order]', array(
	'default'           => $bimber_customizer_defaults['footer_patreon_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_patreon_order', array(
	'label'    => $bimber_order_controls['footer_patreon_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_patreon_order]',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );

function bimber_customizer_patreon_above_footer_is_active( $control ) {
	$value = $control->manager->get_setting( bimber_get_theme_id() . '[patreon_above_footer]' )->value();

	return ! empty( $value );
}


// ---
// Links
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[links_above_footer]', array(
	'default'           => $bimber_customizer_defaults['links_above_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_links_above_footer', array(
	'label'       => __( 'Show Links', 'bimber' ),
	'description' => bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'AdAce' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[links_above_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Color scheme.
$wp_customize->add_setting( $bimber_option_name . '[links_above_footer_color_scheme]', array(
	'default'           => $bimber_customizer_defaults['links_above_footer_color_scheme'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'links_above_footer_color_scheme', array(
	'label'           => __( 'Links Color Scheme', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[links_above_footer_color_scheme]',
	'type'            => 'select',
	'choices'         => array(
		'light' => __( 'Light', 'bimber' ),
		'dark'  => __( 'Dark', 'bimber' ),
	),
	'priority'        => $bimber_curr_module_order + 5,
	'active_callback' => 'bimber_customizer_links_above_footer_is_active',
) );

// Background color.
$wp_customize->add_setting( $bimber_option_name . '[links_above_footer_background_color]', array(
	'default'           => $bimber_customizer_defaults['links_above_footer_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_links_above_footer_background_color', array(
	'label'           => __( 'Links', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[links_above_footer_background_color]',
	'priority'        => $bimber_curr_module_order + 7,
	'active_callback' => 'bimber_customizer_links_above_footer_is_active',
) ) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_links_order]', array(
	'default'           => $bimber_customizer_defaults['footer_links_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_links_order', array(
	'label'    => $bimber_order_controls['footer_links_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_links_order]',
	//'active_callback' => 'bimber_customizer_links_above_footer_is_active',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );

function bimber_customizer_links_above_footer_is_active( $control ) {
	$value = $control->manager->get_setting( bimber_get_theme_id() . '[links_above_footer]' )->value();

	return ! empty( $value );
}


// ---
// Instagram
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[instagram_above_footer]', array(
	'default'           => $bimber_customizer_defaults['instagram_above_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_instagram_above_footer', array(
	'label'       => __( 'Show Instagram', 'bimber' ),
	'description' => bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'G1 Socials' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[instagram_above_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Color scheme.
$wp_customize->add_setting( $bimber_option_name . '[instagram_above_footer_color_scheme]', array(
	'default'           => $bimber_customizer_defaults['instagram_above_footer_color_scheme'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'instagram_above_footer_color_scheme', array(
	'label'           => __( 'Instagram Color Scheme', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[instagram_above_footer_color_scheme]',
	'type'            => 'select',
	'choices'         => array(
		'light' => __( 'Light', 'bimber' ),
		'dark'  => __( 'Dark', 'bimber' ),
	),
	'priority'        => $bimber_curr_module_order + 5,
	'active_callback' => 'bimber_customizer_instagram_above_footer_is_active',
) );

// Background color.
$wp_customize->add_setting( $bimber_option_name . '[instagram_above_footer_background_color]', array(
	'default'           => $bimber_customizer_defaults['instagram_above_footer_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_instagram_above_footer_background_color', array(
	'label'           => 'Instagram' . ': ' . __( 'Background Color', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[instagram_above_footer_background_color]',
	'priority'        => $bimber_curr_module_order + 7,
	'active_callback' => 'bimber_customizer_instagram_above_footer_is_active',
) ) );

// Signle row.
$wp_customize->add_setting( $bimber_option_name . '[instagram_above_footer_single_row]', array(
	'default'           => $bimber_customizer_defaults['instagram_above_footer_single_row'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_instagram_above_footer_single_row', array(
	'label'       => __( 'Instagram - single row', 'bimber' ),
	'description' => bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'G1 Socials'),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[instagram_above_footer_single_row]',
	'type'        => 'checkbox',
	'priority'        => $bimber_curr_module_order + 8,
	'active_callback' => 'bimber_customizer_instagram_above_footer_is_active',
) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_instagram_order]', array(
	'default'           => $bimber_customizer_defaults['footer_instagram_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_instagram_order', array(
	'label'    => $bimber_order_controls['footer_instagram_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_instagram_order]',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );

function bimber_customizer_instagram_above_footer_is_active( $control ) {
	$value = $control->manager->get_setting( bimber_get_theme_id() . '[instagram_above_footer]' )->value();

	return ! empty( $value );
}


// ---
// Social Media Profile Links
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[social_above_footer]', array(
	'default'           => $bimber_customizer_defaults['social_above_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'social_above_footer', array(
	'label'       => __( 'Show Social Media Profile Links', 'bimber' ),
	'description' => bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'G1 Socials' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[social_above_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Color scheme.
$wp_customize->add_setting( $bimber_option_name . '[social_above_footer_color_scheme]', array(
	'default'           => $bimber_customizer_defaults['social_above_footer_color_scheme'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'social_above_footer_color_scheme', array(
	'label'           => __( 'Social Media Links Color Scheme', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[social_above_footer_color_scheme]',
	'type'            => 'select',
	'choices'         => array(
		'light' => __( 'Light', 'bimber' ),
		'dark'  => __( 'Dark', 'bimber' ),
	),
	'priority'        => $bimber_curr_module_order + 5,
	'active_callback' => 'bimber_customizer_social_above_footer_is_active',
) );

// Background color.
$wp_customize->add_setting( $bimber_option_name . '[social_above_footer_background_color]', array(
	'default'           => $bimber_customizer_defaults['social_above_footer_background_color'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bimber_social_above_footer_background_color', array(
	'label'           => __( 'Social Media Links', 'bimber' ) . ': ' . __( 'Background Color', 'bimber' ),
	'section'         => 'bimber_footer_modules_section',
	'settings'        => $bimber_option_name . '[social_above_footer_background_color]',
	'priority'        => $bimber_curr_module_order + 7,
	'active_callback' => 'bimber_customizer_social_above_footer_is_active',
) ) );

// Show in footer.
$wp_customize->add_setting( $bimber_option_name . '[social_in_footer]', array(
	'default'           => $bimber_customizer_defaults['social_in_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'social_in_footer', array(
	'label'       => __( 'Show Social Media Profile Links in the footer', 'bimber' ),
	'description' => bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'G1 Socials' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[social_in_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order + 8,
) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_social_order]', array(
	'default'           => $bimber_customizer_defaults['footer_social_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_social_order', array(
	'label'    => $bimber_order_controls['footer_social_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_social_order]',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );

function bimber_customizer_social_above_footer_is_active( $control ) {
	$value = $control->manager->get_setting( bimber_get_theme_id() . '[social_above_footer]' )->value();

	return ! empty( $value );
}


// ---
// Featured Products
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[promoted_products_above_footer]', array(
	'default'           => $bimber_customizer_defaults['promoted_products_above_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_promoted_products_above_footer', array(
	'label'       => __( 'Show Promoted Products', 'bimber' ),
	'description' => bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'AdAce'  ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[promoted_products_above_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_promoted_products_order]', array(
	'default'           => $bimber_customizer_defaults['footer_promoted_products_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_promoted_products_order', array(
	'label'    => $bimber_order_controls['footer_promoted_products_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_promoted_products_order]',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );


// ---
// Featured Single Product
// ---
$bimber_curr_module_order += 10;

// Visibility.
$wp_customize->add_setting( $bimber_option_name . '[promoted_product_above_footer]', array(
	'default'           => $bimber_customizer_defaults['promoted_product_above_footer'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_promoted_product_above_footer', array(
	'label'       => __( 'Show Promoted Single Product', 'bimber' ),
	'description' => bimber_can_use_plugin( 'ad-ace/ad-ace.php' ) ? '' : sprintf( __( 'Activate the %s plugin to use this option', 'bimber' ), 'AdAce' ),
	'section'     => 'bimber_footer_modules_section',
	'settings'    => $bimber_option_name . '[promoted_product_above_footer]',
	'type'        => 'checkbox',
	'priority'    => $bimber_curr_module_order,
) );

// Order.
$wp_customize->add_setting( $bimber_option_name . '[footer_promoted_product_order]', array(
	'default'           => $bimber_customizer_defaults['footer_promoted_product_order'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
) );
$wp_customize->add_control( 'bimber_footer_promoted_product_order', array(
	'label'    => $bimber_order_controls['footer_promoted_product_order'],
	'section'  => 'bimber_footer_modules_section',
	'settings' => $bimber_option_name . '[footer_promoted_product_order]',
	'type'     => 'text',
	'priority' => $bimber_sortable_elements_start_priority + $bimber_curr_module_order,
) );
