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
		'name' 		=> __( 'Bimber Title', 'bimber' ),
		'base'	 	=> 'bimber_title',
		'category' 	=> 'Bimber',
		'params' 	=> apply_filters( 'bimber_vc_title_params', array(
			// General > Text.
			array(
				'type' 			=> 'textfield',
				'heading' 		=> __( 'Text', 'bimber' ),
				'param_name'	=> 'content',
				'value' 		=> '',
			),
			// General > Size.
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Size', 'bimber' ),
				'param_name'	=> 'size',
				'value' 		=> array(
					__( 'Giga Heading', 'bimber' ) 		=> 'giga',
					__( 'Mega Heading', 'bimber' ) 		=> 'mega',
					__( 'H1 Heading', 'bimber' ) => 'h1',
					__( 'H2 Heading', 'bimber' ) => 'h2',
					__( 'H3 Heading', 'bimber' ) => 'h3',
					__( 'H4 Heading', 'bimber' ) => 'h4',
					__( 'H5 Heading', 'bimber' ) => 'h5',
					__( 'H6 Heading', 'bimber' ) => 'h6',
				),
				'std' 			=> 'h4',
			),
			// General > Size.
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Align', 'bimber' ),
				'param_name'	=> 'align',
				'value' 		=> array(
					__( 'Default', 'bimber' )	=> '',
					__( 'Center', 'bimber' ) 	=> 'center',
				),
				'std' 			=> '',
			),
			// General > HTML Id.
			array(
				'type' 			=> 'textfield',
				'heading' 		=> __( 'HTML id', 'bimber' ),
				'param_name'	=> 'html_class',
				'value' 		=> '',
			),
			// General > HTML class.
			array(
				'type' 			=> 'textfield',
				'heading' 		=> __( 'HTML class', 'bimber' ),
				'param_name'	=> 'html_id',
				'value' 		=> '',
			),
		) ),
	) );
}
