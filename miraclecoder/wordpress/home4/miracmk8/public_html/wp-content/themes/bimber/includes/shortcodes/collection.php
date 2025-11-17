<?php
/**
 * Collection shortcode
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

add_shortcode( 'bimber_collection', 'bimber_collection_shortcode' );

/**
 * Collection shortcode
 *
 * @param array $atts			Shortcode attributes.
 *
 * @return string				Shortcode output.
 */
function bimber_collection_shortcode( $atts ) {
	$default_atts = apply_filters( 'bimber_collection_shortcode_default_atts', array(
		'title' 				=> '',
		'title_size' 			=> 'h4',
		'title_align' 			=> '',
		'title_show'            => 'standard',
		'template' 				=> 'grid-standard',
		'card_style'            => 'none',
		'columns' 				=> 3,
		'type' 					=> 'recent',
		'time_range'			=> 'all',
		'max' 					=> 6,
		'offset' 				=> '',
		'category' 				=> '',
		'post_tag' 				=> '',
		'post_format'			=> '',
		'snax_format'			=> '',
		'author'                => '',

		// Elements visibility.
		'show_featured_media'	=> 'standard',
		'show_subtitle'	        => 'standard',
		'show_shares' 			=> 'standard',
		'show_votes'			=> 'none',
		'show_views' 			=> 'standard',
		'show_downloads'		=> 'standard',
		'show_comments_link'	=> 'standard',
		'show_categories' 		=> 'standard',
		'show_summary' 			=> 'standard',
		'show_author' 			=> 'standard',
		'show_avatar' 			=> 'standard',
		'show_date' 			=> 'standard',
		'show_voting_box'		=> 'none',
		'show_call_to_action'   => 'standard',
		'show_action_links'     => 'standard',

		// CTA buttons visibility.
		'call_to_action_hide_buttons'=> '',          // Empty values means show all.
	) );

	$atts = shortcode_atts( $default_atts, $atts, 'bimber_collection' );

	// Query args.
	// ----------

	// Common.
	$query_args = array(
		'post_type'           	=> 'post',
		'post_status'         	=> 'publish',
		'ignore_sticky_posts' 	=> true,
		'posts_per_page'		=> $atts['max'],
		'snax_format'			=> $atts['snax_format'],
	);

	// Time range.
	$query_args = bimber_time_range_to_date_query( $atts['time_range'], $query_args );

	// Type.
	switch ( $atts['type'] ) {
		case 'recent':
			$query_args['orderby'] = 'date';
			break;

		case 'most_shared':
			$query_args = bimber_get_most_shared_query_args( $query_args );
			break;

		case 'most_viewed':
			$query_args = bimber_get_most_viewed_query_args( $query_args, 'collection_shortcode' );
			break;
	}

	// Offset.
	if ( ! empty( $atts['offset'] ) ) {
		$query_args['offset'] = $atts['offset'];
	}

	// Category.
	if ( ! empty( $atts['category'] ) ) {
		$query_args['category_name'] = $atts['category'];
	}

	// Tag.
	if ( ! empty( $atts['post_tag'] ) ) {
		$query_args['tag'] = $atts['post_tag'];
	}

	// Post format.
	if ( ! empty( $atts['post_format'] ) ) {
		$post_format_terms = explode( ',', $atts['post_format'] );

		foreach ( $post_format_terms as $index => $term ) {
			$post_format_terms[ $index ] = 'post-format-' . $term;
		}

		$query_args['tax_query'] = array(
			array(
				'taxonomy' 	=> 'post_format',
				'field' 	=> 'slug',
				'terms' 	=> $post_format_terms,
			)
		);
	}

	// Author.
	if ( ! empty( $atts['author'] ) ) {
		$query_args['author_name'] = $atts['author'];
	}

	// ------------------
	// End of Query args.


	// Loop posts.
	// -----------

	global $post;
	$current_post = $post;

	$query_args = apply_filters( 'bimber_collection_shortcode_query_args', $query_args, $atts );

	$query = new WP_Query( $query_args );

	$item_settings = array(
		'elements' => array(
			'featured_media' => 'none' === $atts['show_featured_media'] ? false : $atts['show_featured_media'],
			'subtitle'       => 'none' === $atts['show_subtitle'] ? false : $atts['show_subtitle'],
			'shares'         => 'none' === $atts['show_shares'] ? false : $atts['show_shares'],
			'views'          => 'none' === $atts['show_views'] ? false : $atts['show_views'],
			'votes'          => 'none' === $atts['show_votes'] ? false : $atts['show_votes'],
			'downloads'      => 'none' === $atts['show_downloads'] ? false : $atts['show_downloads'],
			'comments_link'  => 'none' === $atts['show_comments_link'] ? false : $atts['show_comments_link'],
			'categories'     => 'none' === $atts['show_categories'] ? false : $atts['show_categories'],
			'summary'        => 'none' === $atts['show_summary'] ? false : $atts['show_summary'],
			'author'         => 'none' === $atts['show_author'] ? false : $atts['show_author'],
			'avatar'         => 'none' === $atts['show_avatar'] ? false : $atts['show_avatar'],
			'date'           => 'none' === $atts['show_date'] ? false : $atts['show_date'],
			'voting_box'     => 'none' === $atts['show_voting_box'] ? false : $atts['show_voting_box'],
			'call_to_action' => 'none' === $atts['show_call_to_action'] ? false : $atts['show_call_to_action'],
			'action_links'   => 'none' === $atts['show_action_links'] ? false : $atts['show_action_links'],
		),
		'query'                 => $query,
		'title'                 => $atts['title'],
		'title_size'            => $atts['title_size'],
		'title_align'           => $atts['title_align'],
		'title_show'            => $atts['title_show'],
		'card_style'            => $atts['card_style'],
		'columns'               => $atts['columns'],
		'call_to_action_hide_buttons'=> $atts['call_to_action_hide_buttons'],
	);

	bimber_set_template_part_data( $item_settings );

	ob_start();
	get_template_part( 'template-parts/collection/' . $atts['template'] );
	$out = ob_get_clean();

	bimber_reset_template_part_data();

	$post = $current_post;
	wp_reset_postdata();

	// ---------
	// Loop end.

	return $out;
}
