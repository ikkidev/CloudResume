<?php
/**
 * Featured Collection shortcode
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

add_shortcode( 'bimber_featured_collection', 'bimber_featured_collection_shortcode' );

/**
 * Featured Collection shortcode
 *
 * @param array $atts			Shortcode attributes.
 *
 * @return string				Shortcode output.
 */
function bimber_featured_collection_shortcode( $atts ) {
	$default_atts = array(
		'title' 				=> '',
		'template' 				=> '2-2-boxed',
		'type' 					=> 'recent',
		'time_range'			=> 'all',
		'category' 				=> '',
		'post_tag' 				=> '',
		'post_format' 			=> '',
		'snax_format' 			=> '',

		// Elements visibility.
		'show_categories' 		=> 'standard',
		'show_shares' 			=> 'standard',
		'show_views' 			=> 'standard',
		'show_comments_link'	=> 'standard',
	);

	$atts = shortcode_atts( $default_atts, $atts, 'bimber_featured_collection' );

	$item_settings = array(
		'featured_entries_template'		=> $atts['template'],
		'featured_entries_gutter'		=> false,
		'featured_entries_title'		=> $atts['title'],
		'featured_entries_title_hide'	=> false,
		'featured_entries' => array(
			'type'          => $atts['type'],
			'time_range'    => $atts['time_range'],
			'elements'      => array(
				'featured_media' => true,
				'categories'     => 'standard' === $atts['show_categories'],
				'title'          => true,
				'summary'        => true,
				'author'         => true,
				'avatar'         => true,
				'date'           => true,
				'shares'         => 'standard' === $atts['show_shares'],
				'views'          => 'standard' === $atts['show_views'],
				'comments_link'  => 'standard' === $atts['show_comments_link'],
			),
			'category_name' => $atts['category'],
			'tag'  			=> $atts['post_tag'],
			'post_format'	=> $atts['post_format'],
			'snax_format'	=> $atts['snax_format'],
		),
	);

	bimber_set_template_part_data( $item_settings );

	ob_start();
	if ( in_array( $atts['template'], array( '1-sidebar', '1-sidebar-bunchy' ), true )) {
		get_template_part( 'template-parts/featured/with-sidebar/' . $atts['template'] );
	} else {
		get_template_part( 'template-parts/featured/' . $atts['template'] );
	}

	$out = ob_get_clean();

	bimber_reset_template_part_data();

	return $out;
}
