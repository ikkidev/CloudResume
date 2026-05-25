<?php
/**
 * Bimber Title VC Element
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

if ( function_exists( 'vc_map' ) ) {
	vc_map( array(
		'name' 		=> __( 'Bimber Newsletter Form', 'bimber' ),
		'base'	 	=> 'bimber_mc4wp_form',
		'category' 	=> 'Bimber',
		'params' 	=> apply_filters( 'bimber_vc_mc4wp_form_params', array(
			// Template.
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Template', 'bimber' ),
				'param_name'	=> 'template',
				'value' 		=> array_flip( bimber_mc4wp_customizer_get_template_choices() ),
				'std' 			=> 'box-vertical',
			),
			// Title.
			array(
				'type' 			=> 'textfield',
				'heading' 		=> __( 'Title', 'bimber' ),
				'param_name'	=> 'title',
				'value' 		=> esc_html__( 'Get the best viral stories straight into your inbox!', 'bimber' ),
			),
			// Subtitle.
			array(
				'type' 			=> 'textfield',
				'heading' 		=> __( 'Subtitle', 'bimber' ),
				'param_name'	=> 'subtitle',
				'value' 		=> '',
			),
			// Avatar.
			array(
				'type' 			=> 'attach_image',
				'heading' 		=> __( 'Avatar', 'bimber' ),
				'param_name'	=> 'avatar_id',
				'value' 		=> '',
			),
			// Background Image.
			array(
				'type' 			=> 'attach_image',
				'heading' 		=> __( 'Background Image', 'bimber' ),
				'param_name'	=> 'background_image_id',
				'value' 		=> '',
			),
		) ),
	) );
}
