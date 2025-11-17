<?php
/**
 * WP Customizer panel section to handle the Video post format options
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

$wp_customize->add_section( 'bimber_posts_gallery_section', array(
	'title'    => __( 'Gallery Post Format', 'bimber' ),
	'priority' => 32,
	'panel'    => 'bimber_posts_panel',
) );


// Frame icon.
$wp_customize->add_setting( $bimber_option_name . '[post_gallery_frame_icon]', array(
	'default'           => $bimber_customizer_defaults['post_gallery_frame_icon'],
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'bimber_post_gallery_frame_icon', array(
	'label'    => __( 'Show Featured Image Icon', 'bimber' ),
	'section'  => 'bimber_posts_gallery_section',
	'settings' => $bimber_option_name . '[post_gallery_frame_icon]',
	'type'     => 'checkbox',
) );
