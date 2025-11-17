<?php
/**
 * Bimber Categories VC Element
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

$bimber_vc_categories_params = apply_filters( 'bimber_vc_categories_params', array(

	/**
	 * GENERAL
	 */

	// Template.
	10 => array(
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Template', 'bimber' ),
		'param_name' 	=> 'template',
		'value'         => array(
			'tiles'  => 'tiles',
		),
		'std' 			=> 'grid-standard',
		'description' 	=> __( 'Select display style for items.', 'bimber' ),
	),
//	// Columns.
//	20 => array(
//		'type' 			=> 'dropdown',
//		'heading' 		=> __( 'Columns', 'bimber' ),
//		'param_name' 	=> 'columns',
//		'value' 		=> array(
//			'1' => 1,
//			'2' => 2,
//			'3' => 3,
//			'4' => 4,
//		),
//		'std' => 3,
//		'description' 	=> __( 'Number of columns to use for grid template.', 'bimber' ),
//	),

	/**
	 * TITLE
	 */

	// Title.
	30 => array(
		'group' 		=> __( 'Title', 'bimber' ),
		'type' 			=> 'textfield',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Title', 'bimber' ),
		'param_name'	=> 'title',
		'value' 		=> '',
	),
	40 => array(
		'group' 		=> __( 'Title', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Size', 'bimber' ),
		'param_name'	=> 'title_size',
		'value' 		=> array(
			__( 'H1 Heading', 'bimber' ) => 'h1',
			__( 'H2 Heading', 'bimber' ) => 'h2',
			__( 'H3 Heading', 'bimber' ) => 'h3',
			__( 'H4 Heading', 'bimber' ) => 'h4',
			__( 'H5 Heading', 'bimber' ) => 'h5',
			__( 'H6 Heading', 'bimber' ) => 'h6',
			__( 'Giga Heading', 'bimber' ) 		=> 'giga',
			__( 'Mega Heading', 'bimber' ) 		=> 'mega',
		),
		'std' 		=> 'h4',
	),
	50 => array(
		'group' 		=> __( 'Title', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Align', 'bimber' ),
		'param_name'	=> 'title_align',
		'value' 		=> array(
			__( 'Default', 'bimber' ) => '',
			__( 'Center', 'bimber' ) => 'center',
		),
		'std' 		=> '',
	),
	60 => array(
		'group' 		=> __( 'Title', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Show', 'bimber' ),
		'param_name'	=> 'title_show',
		'value' 		=> array(
			__( 'None', 'bimber' )      => 'none',
			__( 'Standard', 'bimber' )  => 'standard',
		),
		'std' 		=> 'standard',
	),


	/**
	 * DATA
	 */

	// Include.
	105 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'textfield',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Include', 'bimber' ),
		'param_name'	=> 'include',
		'value' 		=> '',
		'description' 	=> __( 'A comma-separated list of category ids or slugs.', 'bimber' ),
	),


	// Orderby.
	110 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'dropdown',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Order by', 'bimber' ),
		'param_name'	=> 'orderby',
		'value' 		=> array(
			__( 'Name', 'bimber' )  => 'name',
			__( 'Number of Entries', 'bimber' ) => 'count',
		),
		'std' 		=> 'name',
	),

	// Total items.
	120 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'textfield',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Maximum items', 'bimber' ),
		'param_name'	=> 'max',
		'value' 		=> 6,
		'description' 	=> __( 'Set max limit for items or enter -1 to display all.', 'bimber' ),
	),


	/**
	 * ITEM DESIGN
	 */
	// Icon.
	300 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Icon', 'bimber' ),
		'param_name' 	=> 'show_icon',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Number of Entries.
	310 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Number of Entries', 'bimber' ),
		'param_name' 	=> 'show_count',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
) );

// Sort params by key.
ksort( $bimber_vc_categories_params );

if ( function_exists( 'vc_map' ) ) {
	vc_map( array(
		'name' 		=> __( 'Bimber Categories', 'bimber' ),
		'base'	 	=> 'bimber_categories',
		'category'  => 'Bimber',
		'params' 	=> $bimber_vc_categories_params,
	) );
}
