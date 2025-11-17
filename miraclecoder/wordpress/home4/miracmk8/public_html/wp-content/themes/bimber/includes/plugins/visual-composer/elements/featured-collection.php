<?php
/**
 * Bimber Collection VC Element
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
		'name' 		=> __( 'Bimber Featured Collection', 'bimber' ),
		'base'	 	=> 'bimber_featured_collection',
		'category' 	=> 'Bimber',
		'params' 	=> apply_filters( 'bimber_vc_featured_collection_params', array(

			/**
			 * GENERAL
			 */

			// Title.
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Title', 'bimber' ),
				'param_name'	=> 'title',
				'value' 		=> '',
			),
			// Template.
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Template', 'bimber' ),
				'param_name' 	=> 'template',
				'value' 		=> bimber_vc_get_archive_featured_entries_templates(), 	// First one is a default one.
				'description' 	=> __( 'Select display style for items.', 'bimber' ),
			),

			/**
			 * DATA
			 */

			// Type.
			array(
				'group' 		=> __( 'Data', 'bimber' ),
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Type', 'bimber' ),
				'param_name' 	=> 'type',
				'value' 		=> array(
					__( 'Recent', 'bimber' ) 		=> 'recent',
					__( 'Most Shared', 'bimber' ) 	=> 'most_shared',
					__( 'Most Viewed', 'bimber' ) 	=> 'most_viewed',
				),
				'std' 			=> 'recent',
			),
			// Time range.
			array(
				'group' 		=> __( 'Data', 'bimber' ),
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Time Range', 'bimber' ),
				'param_name' 	=> 'time_range',
				'value' 		=> array_flip( bimber_get_archive_featured_entries_time_ranges() ), // First one is a default one.
				'description' 	=> __( 'Narrow posts to a specific period of time.', 'bimber' ),
			),
			// Category.
			array(
				'group' 		=> __( 'Data', 'bimber' ),
				'type' 			=> 'autocomplete',
				'heading' 		=> __( 'Filter by category', 'bimber' ),
				'param_name' 	=> 'category',
				'settings' => array(
					'multiple' => true,
					'min_length' => 1,
					'groups' => false,
					// In UI show results grouped by groups, default false
					'unique_values' => true,
					// In UI show results except selected. NB! You should manually check values in backend, default false
					'display_inline' => true,
					// In UI show results inline view, default false (each value in own line)
					'delay' => 500,
					// delay for search. default 500
					'auto_focus' => true,
					// auto focus input, default true
				),
			),
			// Tag.
			array(
				'group' 		=> __( 'Data', 'bimber' ),
				'type' 			=> 'autocomplete',
				'heading' 		=> __( 'Filter by tag', 'bimber' ),
				'param_name' 	=> 'post_tag',
				'settings' => array(
					'multiple' => true,
					'min_length' => 1,
					'groups' => false,
					// In UI show results grouped by groups, default false
					'unique_values' => true,
					// In UI show results except selected. NB! You should manually check values in backend, default false
					'display_inline' => true,
					// In UI show results inline view, default false (each value in own line)
					'delay' => 500,
					// delay for search. default 500
					'auto_focus' => true,
					// auto focus input, default true
				),
			),
			// Post Format.
			array(
				'group' 		=> __( 'Data', 'bimber' ),
				'type' 			=> 'multi_checkbox',
				'heading' 		=> __( 'Filter by post format', 'bimber' ),
				'param_name' 	=> 'post_format',
				'value' 		=> bimber_vc_get_post_formats( 'bimber_featured_collection' ),
			),

			/**
			 * ITEM DESIGN
			 */

			// Categories.
			array(
				'group' 		=> __( 'Item Design', 'bimber' ),
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Categories', 'bimber' ),
				'param_name' 	=> 'show_categories',
				'value' 		=> array(
					__( 'Show', 'bimber' ) => 'standard',
					__( 'Hide', 'bimber' ) => 'none',
				),
				'std' 			=> 'standard',
			),
			// Shares.
			array(
				'group' 		=> __( 'Item Design', 'bimber' ),
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Shares', 'bimber' ),
				'param_name' 	=> 'show_shares',
				'value' 		=> array(
					__( 'Show', 'bimber' ) => 'standard',
					__( 'Hide', 'bimber' ) => 'none',
				),
				'std' 			=> 'standard',
			),
			// Views.
			array(
				'group' 		=> __( 'Item Design', 'bimber' ),
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Views', 'bimber' ),
				'param_name' 	=> 'show_views',
				'value' 		=> array(
					__( 'Show', 'bimber' ) => 'standard',
					__( 'Hide', 'bimber' ) => 'none',
				),
				'std' 			=> 'standard',
			),
			// Comments Link.
			array(
				'group' 		=> __( 'Item Design', 'bimber' ),
				'type' 			=> 'dropdown',
				'heading' 		=> __( 'Comments Link', 'bimber' ),
				'param_name' 	=> 'show_comments_link',
				'value' 		=> array(
					__( 'Show', 'bimber' ) => 'standard',
					__( 'Hide', 'bimber' ) => 'none',
				),
				'std' 			=> 'standard',
			),
		) ),
	) );
}
