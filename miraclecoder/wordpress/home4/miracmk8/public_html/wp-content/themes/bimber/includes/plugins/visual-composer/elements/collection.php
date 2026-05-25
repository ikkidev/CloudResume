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

$bimber_post_cta_buttons = bimber_get_post_call_to_action_buttons();

$bimber_vc_collection_params = apply_filters( 'bimber_vc_collection_params', array(

	/**
	 * GENERAL
	 */

	// Template.
	10 => array(
		'type' 			=> 'image_radio',
		'heading' 		=> __( 'Template', 'bimber' ),
		'param_name' 	=> 'template',
		'value'         => bimber_get_collection_templates(),
		'std' 			=> 'grid-standard',
		'description' 	=> __( 'Select display style for items.', 'bimber' ),
	),
	// Card Style.
	12 => array(
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Card Style', 'bimber' ),
		'param_name' 	=> 'card_style',
		'value'         => array(
			__( 'None', 'bimber' )      => 'none',
			__( 'Solid', 'bimber' )     => 'solid',
			__( 'Simple', 'bimber' )    => 'simple',
			__( 'Subtle', 'bimber' )    => 'subtle',
		),
		'std' 			=> 'none',
	),
	// Columns.
	20 => array(
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Columns', 'bimber' ),
		'param_name' 	=> 'columns',
		'value' 		=> array(
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
		),
		'std' => 3,
		'description' 	=> __( 'Number of columns to use for grid template.', 'bimber' ),
	),

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
		'description' 	=> __( 'Leave empty to use the default value.', 'bimber' ),
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

	// Type.
	110 => array(
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
	120 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Time Range', 'bimber' ),
		'param_name' 	=> 'time_range',
		'value' 		=> array(
			__( 'All time', 'bimber' )		=> 'all',
			__( 'Last 30 days', 'bimber' ) 	=> 'month',
			__( 'Last 7 days', 'bimber' ) 	=> 'week',
			__( 'Last 24 hours', 'bimber' ) => 'day',
		),
		'std' 			=> 'all',
		'description' 	=> __( 'Narrow posts to a specific period of time.', 'bimber' ),
	),

	// Total items.
	130 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'textfield',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Total items', 'bimber' ),
		'param_name'	=> 'max',
		'value' 		=> 6,
		'description' 	=> __( 'Set max limit for items or enter -1 to display all.', 'bimber' ),
	),

	// Offset.
	140 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'textfield',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Offset', 'bimber' ),
		'param_name'	=> 'offset',
		'value' 		=> '',
		'description' 	=> __( 'Number of posts to displace or pass over.', 'bimber' ),
	),

	// Category.
	150 => array(
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
	160 => array(
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
	170 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'multi_checkbox',
		'heading' 		=> __( 'Filter by post format', 'bimber' ),
		'param_name' 	=> 'post_format',
		'value' 		=> bimber_vc_get_post_formats( 'bimber_collection' ),
		'std' 		    => '',
	),

	// Author.
	180 => array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'textfield',
		'holder' 		=> 'div',
		'class' 		=> '',
		'heading' 		=> __( 'Author', 'bimber' ),
		'param_name'	=> 'author',
		'value' 		=> '',
	),

	/**
	 * ITEM DESIGN
	 */

	// Featured Media.
	210 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Featured Media', 'bimber' ),
		'param_name' 	=> 'show_featured_media',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Shares.
	230 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Shares', 'bimber' ),
		'param_name' 	=> 'show_shares',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Views.
	240 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Views', 'bimber' ),
		'param_name' 	=> 'show_views',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Comments Link.
	250 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Comments Link', 'bimber' ),
		'param_name' 	=> 'show_comments_link',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Categories.
	260 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Categories', 'bimber' ),
		'param_name' 	=> 'show_categories',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Subtitle.
	265 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Subtitle', 'bimber' ),
		'param_name' 	=> 'show_subtitle',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),

	// Summary.
	270 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Summary', 'bimber' ),
		'param_name' 	=> 'show_summary',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Author.
	280 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Author', 'bimber' ),
		'param_name' 	=> 'show_author',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Avatar.
	290 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Avatar', 'bimber' ),
		'param_name' 	=> 'show_avatar',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Date.
	300 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Date', 'bimber' ),
		'param_name' 	=> 'show_date',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Call to action.
	310 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Call to Action', 'bimber' ),
		'param_name' 	=> 'show_call_to_action',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
	// Call to action buttons.
	320 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'multi_checkbox',
		'heading' 		=> __( 'Call to Action - Hide Buttons', 'bimber' ),
		'param_name' 	=> 'call_to_action_hide_buttons',
		'value' 		=> array_flip( $bimber_post_cta_buttons ),
		'std' 			=> '',
		'dependency'    => array(
			'element'   => 'show_call_to_action',
			'value'     => array( 'standard', 'highlighted' ),
		)
	),
	// Call to action.
	330 => array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Action Links', 'bimber' ),
		'param_name' 	=> 'show_action_links',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	),
) );

// Sort params by key.
ksort( $bimber_vc_collection_params );

if ( function_exists( 'vc_map' ) ) {
	vc_map( array(
		'name' 		=> __( 'Bimber Collection', 'bimber' ),
		'base'	 	=> 'bimber_collection',
		'category'  => 'Bimber',
		'params' 	=> $bimber_vc_collection_params,
	) );
}
