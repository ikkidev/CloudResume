<?php
/**
 * Archive functions
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

/**
 * Alters home template based on theme options
 *
 * @param string $template Template name.
 *
 * @return string
 */
function bimber_home_alter_template( $template ) {
	$home_settings = bimber_get_home_settings();

	$new_template = $home_settings['template'];
	$new_template = sprintf( 'g1-template-home-%s.php', $new_template );

	$new_template = locate_template( $new_template );

	if ( ! empty( $new_template ) ) {
		return $new_template;
	}

	return $template;
}

/**
 * Get home page settings.
 *
 * @return array
 */
function bimber_get_home_settings() {
	$featured_entries_category = bimber_get_theme_option( 'home', 'featured_entries_category' );

	if ( is_array( $featured_entries_category ) ) {
		$featured_entries_category = implode( ',', $featured_entries_category );
	}

	$main_collection_excluded_categories = bimber_get_theme_option( 'home', 'main_collection_excluded_categories' );

	if ( is_array( $main_collection_excluded_categories ) ) {
		$main_collection_excluded_categories = implode( ',', $main_collection_excluded_categories );
	}

	return apply_filters( 'bimber_home_settings', array(
		'template'         				=> bimber_get_theme_option( 'home', 'template' ),
		'card_style'                    => bimber_get_theme_option( 'cards', 'home_content' ),
		'title'            				=> bimber_get_home_title(),
		'pagination'       				=> bimber_get_theme_option( 'home', 'pagination' ),
		'highlight_items'               => 'standard' === bimber_get_theme_option( 'home', 'highlight_items' ),
		'highlight_items_offset'        => bimber_get_theme_option( 'home', 'highlight_items_offset' ),
		'highlight_items_repeat'        => bimber_get_theme_option( 'home', 'highlight_items_repeat' ) + 1,
		'elements'         				=> bimber_get_archive_elements_visibility_arr( bimber_get_theme_option( 'home', 'hide_elements' ) ),
		'call_to_action_hide_buttons'   => bimber_get_theme_option( 'home', 'call_to_action_hide_buttons' ),
		'featured_entries_template'		=> bimber_get_theme_option( 'home', 'featured_entries_template' ),
		'featured_entries_gutter'		=> 'standard' === bimber_get_theme_option( 'home', 'featured_entries_gutter' ),
		'featured_entries_title'		=> bimber_get_home_featured_entries_title(),
		'featured_entries_title_hide'	=> (bool) bimber_get_theme_option( 'home', 'featured_entries_title_hide' ),
		// Query args.
		'featured_entries' => array(
			'type'          => bimber_get_theme_option( 'home', 'featured_entries' ),
			'time_range'    => bimber_get_theme_option( 'home', 'featured_entries_time_range' ),
			'elements'      => bimber_get_archive_elements_visibility_arr( bimber_get_theme_option( 'home', 'featured_entries_hide_elements' ) ),
			'category_name' => $featured_entries_category,
			'tag_slug__in'  => bimber_get_home_featured_entries_tags(),
			'call_to_action_hide_buttons' => bimber_get_theme_option( 'home', 'featured_entries_call_to_action_hide_buttons' ),
		),
		'main_collection' => array(
			'category__not_in' => $main_collection_excluded_categories,
		),
	) );
}

/**
 * Return home featured entries tags
 *
 * @return array
 */
function bimber_get_home_featured_entries_tags() {
	$tags = bimber_get_theme_option( 'home', 'featured_entries_tag' );

	if ( ! is_array( $tags ) ) {
		$tags = explode( ',', $tags );
	}

	return array_filter( $tags );
}

/**
 * Get featured post ids.
 *
 * @return array
 */
function bimber_get_home_featured_posts_ids() {
	$home_settings    = bimber_get_home_settings();
	$featured_entries = $home_settings['featured_entries'];

	if ( 'none' === $featured_entries['type'] ) {
		return array();
	}

	$featured_entries['posts_per_page'] = bimber_get_post_per_page_from_template( $home_settings['featured_entries_template'] );

	return bimber_get_featured_posts_ids( $featured_entries );
}

/**
 * Exclude the featured content from the home main query.
 *
 * @param WP_Query $query Home main query.
 */
function bimber_home_exclude_featured( $query ) {
	if ( ! $query->is_main_query() || is_feed() ) {
		return;
	}

	if ( ! is_home() ) {
		return;
	}

	$excluded_ids = bimber_get_home_featured_posts_ids();

	if ( bimber_show_global_featured_entries() && bimber_global_featured_entries_exclude_from_main_loop() ) {
		$global_featured_ids = bimber_get_global_featured_posts_ids();

		if ( ! empty( $global_featured_ids ) ) {
			$excluded_ids = array_merge( $excluded_ids, $global_featured_ids );

			$excluded_ids = array_unique( $excluded_ids );
		}
	}

	if ( ! empty( $excluded_ids ) ) {
		$query->set( 'post__not_in', $excluded_ids );

		// When we exclude posts from main query, it can be left empty.
		// We don't want to show empty loop info because featured entries are there.
		add_filter( 'bimber_show_archive_no_results', '__return_false' );
	}
}

/**
 * Apply the set categories to the main collection.
 *
 * @param WP_Query $query Home main query.
 */
function bimber_home_apply_main_collection_query_args( $query ){
	if ( ! $query->is_main_query() || is_feed() ) {
		return;
	}

	if ( ! is_home() ) {
		return;
	}


	$home_settings 	= bimber_get_home_settings();
	$args			= $home_settings['main_collection'];

	if ( ! empty( $args['category__not_in'] ) ) {
		$category_slugs = explode( ',', $args['category__not_in'] );
		$category_ids   = array();

		foreach ( $category_slugs as $category_slug ) {
			$category_obj = get_category_by_slug( $category_slug );

			if ( false !== $category_obj ) {
				$category_ids[] = '-' . $category_obj->term_id;
			}
		}
		// 'category__not_in' is not reliable, so we merge it with 'cat' as recommended by Codex.
		if ( ! empty( $category_ids ) ) {
			if ( empty( $args['cat'] ) ) {
				$args['cat'] = implode( ',', $category_ids );
			} else {
				$args['cat'] = $args['cat'] . ',' . implode( ',', $category_ids );
			}
		}
	}

	foreach ( $args as $key => $value ){
		if ( ! empty( $value ) ) {
			$query->set( $key, $value );
		}
	}
}

/**
 * Get the title of the home collection.
 *
 * @return string
 */
function bimber_get_home_title() {
	$title = bimber_get_theme_option( 'home', 'title' );

	// Fallback to defaults.
	if ( ! strlen( $title ) ) {
		if ( 'recent' === bimber_get_theme_option( 'home', 'featured_entries' ) ) {
			$title = __( 'More stories', 'bimber' );
		} else {
			$title = __( 'Latest stories', 'bimber' );
		}
	}

	return $title;
}

/**
 * Get the title of the home featured entries.
 *
 * @return string
 */
function bimber_get_home_featured_entries_title() {
	$title = bimber_get_theme_option( 'home', 'featured_entries_title' );

	// Fallback to defaults.
	if ( ! strlen( $title ) ) {
		$type = bimber_get_theme_option( 'home', 'featured_entries' );

		switch ( $type ) {
			case 'most_viewed':
				$title = __( 'Most Viewed', 'bimber' );
				break;

			case 'most_shared':
				$title = __( 'Most Shared', 'bimber' );
				break;

			default:
				$title = __( 'Latest stories', 'bimber' );
		}
	}

	return $title;
}
