<?php
/**
 * Categories shortcode
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

add_shortcode( 'bimber_categories', 'bimber_categories_shortcode' );

/**
 * Collection shortcode
 *
 * @param array $atts			Shortcode attributes.
 *
 * @return string				Shortcode output.
 */
function bimber_categories_shortcode( $atts ) {
	$default_atts = array(
		'title' 				=> '',
		'title_size' 			=> 'h4',
		'title_align' 			=> '',
		'title_show'            => 'standard',
		'template' 				=> 'tiles',
		'columns' 				=> 4,
		'max' 					=> 8,
		'orderby'               => 'name',
		'include'               => '',
		'more_url'              => 'https://www.google.com',

		// Elements visibility.
		'show_icon'             => 'standard',
		'show_count'            => 'standard',
	);

	$atts = shortcode_atts( $default_atts, $atts, 'bimber_categories' );

	// Map 'orderby' to 'order'.
	$orderby_mapping = array(
		'name'      => 'ASC',
		'count'     => 'DESC',
		'include'   => 'ASC',
	);
	// Normalize 'orderby' value';
	$atts['orderby'] = array_key_exists( $atts['orderby'], $orderby_mapping ) ? $atts['orderby'] : 'name';
	// Get 'order' value based on the mapping.
	$atts['order'] = $orderby_mapping[ $atts['orderby'] ];

	// Normalize 'include' value.
    $include_parts = array_filter( explode( ',', $atts['include'] ) );
    $include_ids = array();

    if ( ! empty( $include_parts ) ) {
        foreach ( $include_parts as $include_part ) {
            // Id?
            if ( is_numeric( $include_part ) ) {
                $include_ids[] = (int) $include_part;
            // Slug?
            } else {
                $category = get_category_by_slug( $include_part );

                if ( $category ) {
                    $include_ids[] = $category->term_id;
                }
            }
        }
    }


	// Normalize 'max' value.
	$atts['max'] = (int) $atts['max'];

	// Include only top-level categories if no IDs specified.
	$parent = count( $include_ids ) ? '' : 0;

	$query_args = array(
		'taxonomy'      => 'category',
		'parent'        => $parent,
		'include'       => $include_ids,
		'orderby'       => $atts['orderby'],
		'order'         => $atts['order'],
		'hide_empty'    => true,
		'fields'        => 'count',
	);

	$query_args = apply_filters( 'bimber_categories_shortcode_query_args', $query_args );

	$terms_more = (int) get_terms( $query_args );
	$terms_more -= $atts['max'];

	$query_args['number'] = $atts['max'];
	$query_args['fields'] = 'all';

	$terms = get_terms( $query_args );

	ob_start();
	if ( ! is_wp_error( $terms ) ) {
		bimber_set_template_part_data( array(
			'title'         => $atts['title'],
			'title_size'    => $atts['title_size'],
			'title_align'   => $atts['title_align'],
			'title_show'    => $atts['title_show'],
			'terms'         => $terms,
			'columns'       => $atts['columns'],
			'more'          => $terms_more,
			'more_url'      => $atts['more_url'],
			'elements'      => array(
				'icon'  => 'none' === $atts['show_icon'] ? false : $atts['show_icon'],
				'count' => 'none' === $atts['show_count'] ? false : $atts['show_count'],
			),
		) );
		get_template_part( 'template-parts/categories/templates/' . $atts['template'] );
		bimber_reset_template_part_data();
	}
	$out = ob_get_clean();

	return $out;
}
