<?php
/**
 * Post functions
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
 * Return single post supported post types
 * Those types are controlled by WP Dashboard > Appearance > Customize > Posts > Single (global)
 * and via Single Page metabox on post's edition screen
 *
 * @return array
 */
function bimber_get_single_post_supported_types() {
    return apply_filters( 'bimber_single_post_supported_types', array( 'post' ) );
}

/**
 * Get ids of popular posts
 *
 * @param int $limit Maximum number of ids to return.
 *
 * @return array
 */
function bimber_get_popular_post_ids( $limit = 10 ) {
	$ids = array();

	$posts = get_posts( array(
		'posts_per_page'        => $limit,
		'post_type'             => 'any',
		'ignore_sticky_posts'   => true,
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => '_bimber_popular',
				'compare' => 'EXISTS',
			),
		),
	) );

	foreach ( $posts as $post ) {
		$ids[] = $post->ID;
	}

	// Empty array in post__in clause results in returning all posts.
	if ( empty( $ids ) ) {
		$ids[] = -1;
	}

	return apply_filters( 'bimber_popular_post_ids', $ids, $limit );
}

/**
 * Get ids of hot posts
 *
 * @param int $limit Maximum number of ids to return.
 *
 * @return array
 */
function bimber_get_hot_post_ids( $limit = 10 ) {
	$ids = array();

	$posts = get_posts( array(
		'posts_per_page'        => $limit,
		'post_type'             => 'any',
		'ignore_sticky_posts'   => true,
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => '_bimber_hot',
				'compare' => 'EXISTS',
			),
		),
	) );

	foreach ( $posts as $post ) {
		$ids[] = $post->ID;
	}

	// Empty array in post__in clause results in returning all posts.
	if ( empty( $ids ) ) {
		$ids[] = -1;
	}

	return apply_filters( 'bimber_hot_post_ids', $ids, $limit );
}

/**
 * Get ids of trending posts
 *
 * @param int $limit Maximum numbef of ids to return.
 *
 * @return mixed|void
 */
function bimber_get_trending_post_ids( $limit = 10 ) {
	$ids = array();

	$posts = get_posts( array(
		'posts_per_page'        => $limit,
		'post_type'             => 'any',
		'ignore_sticky_posts'   => true,
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
		'meta_query' => array(
			array(
				'key'     => '_bimber_trending',
				'compare' => 'EXISTS',
			),
		),
	) );

	foreach ( $posts as $post ) {
		$ids[] = $post->ID;
	}

	// Empty array in post__in clause results in returning all posts.
	if ( empty( $ids ) ) {
		$ids[] = -1;
	}

	return apply_filters( 'bimber_trending_post_ids', $ids, $limit );
}

function bimber_get_post_call_to_action_buttons() {
	$choices = array(
		'read_more'     => esc_html__( 'Read More', 'bimber' ),
		'view_gallery'  => esc_html__( 'View Gallery', 'bimber' ),
	);

	return apply_filters( 'bimber_post_call_to_action_buttons', $choices );
}

/**
 * Check whether the current post should have the CTA button visible
 *
 * @param string $buttons_str       Comma separated list of allowed buttons
 *
 * @return bool
 */
function bimber_has_entry_call_to_action( $buttons_str ) {
	$buttons_arr = array_filter( explode( ',', $buttons_str ) );

	// By default all buttons are visible.
	if ( empty( $buttons_arr ) ) {
		return true;
	}

	$has = true;

	// Read More.
	if ( 'post' === get_post_type() ) {
		$has = ! in_array( 'read_more', $buttons_arr );
	}

	// View gallery.
	if ( 'gallery' === get_post_format() ) {
		$has = ! in_array( 'view_gallery', $buttons_arr );
	}

	return apply_filters( 'bimber_has_entry_call_to_action', $has, $buttons_arr );
}

/**
 * Check whether or not there are some entry action links
 *
 * @return bool
 */
function bimber_has_entry_action_links() {
	$links = bimber_get_entry_action_links();

	return ! empty( $links );
}

/**
 * Return action links for a post
 *
 * @return array
 */
function bimber_get_entry_action_links() {
	return apply_filters( 'bimber_entry_action_links', array() );
}

/**
 * Output action links for a post
 *
 * @param array $args See {@link bimber_get_entry_action_links()}.
 */
function bimber_render_entry_action_links( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'before' => '<div class="g1-drop g1-drop-icon g1-drop-before g1-drop-the-more">'.
		            '<button type="button" class="g1-button-none g1-drop-toggle"><i class="g1-drop-toggle-icon"></i><span class="g1-drop-toggle-text">' . esc_html__( 'More', 'bimber' ) . '</span><span class="g1-drop-toggle-arrow"></span></button>' .
		            '<div class="g1-drop-content">'.
		            '<ul class="sub-menu">'.
		            '<li class="menu-item">',
		'after'  =>             '</li>'.
		                        '</ul>'.
		                        '</div>'.
		                        '</div>',
		'sep'    => '</li><li class="menu-item">',
	) );

	$args = apply_filters( 'bimber_entry_action_links_args', $args );

	$links = bimber_get_entry_action_links();

	$out = '';

	if ( ! empty( $links ) ) {
		$links_str = implode( $args['sep'], array_filter( $links ) );

		$out = $args['before'] . $links_str . $args['after'];
	}

	$out = apply_filters( 'bimber_entry_action_links_html', $out, $args );

	echo filter_var( $out );
}

/**
 * Return post template name
 *
 * @param WP_Post $post     Optional. Post object or ID.
 *
 * @return string           Template name.
 */
function bimber_get_single_post_template( $post = null ) {
	$post           = get_post( $post );

	$single_post_options = get_post_meta( $post->ID, '_bimber_single_options', true );

	if ( ! empty( $single_post_options['template'] ) ) {
		$post_template = $single_post_options['template'];
	} else {
		$post_template = bimber_get_theme_option( 'post', 'template' );

		// Check for video specific template.
		if ( 'video' === get_post_format( $post->ID ) ) {
			$post_video_template = bimber_get_theme_option( 'post_video', 'template' );

			$post_template = ! empty( $post_video_template )  ? $post_video_template : $post_template;
		}
	}


	// Backward compatibility
	// @since 4.0.0
	switch ( $post_template ) {
		case 'classic-sidebar-right':
		case 'classic-sidebar':
			$post_template = 'classic';
			break;

		case 'no-sidebar':
			$post_template = 'classic-no-sidebar';
			break;

		case 'media-sidebar':
			$post_template = 'media';
			break;

		case 'media-v2-sidebar':
			$post_template = 'media-v2';
			break;
	}

	return $post_template;
}

/**
 * Alters single post template based on theme options
 *
 * @param  string $template Template.
 *
 * @return string
 */
function bimber_post_alter_single_template( $template ) {
	$object = get_queried_object();
	$auto_load_template = bimber_htmlspecialchars( filter_input( INPUT_GET, 'bimber_auto_load_next_post_template' ) );
	if ( 'row' === $auto_load_template ) {
		$auto_load_template = 'classic';
	}

	$supported_post_types = bimber_get_single_post_supported_types();

	if ( ! in_array( $object->post_type, $supported_post_types ) && ! $auto_load_template ) {
		return $template;
	}

	$post_template = bimber_get_single_post_template( $object );

	if ( $auto_load_template ) {
		$post_template = $auto_load_template;
	}

	$filename = sprintf( 'g1-template-post-%s', $post_template );

	$style_guide = bimber_htmlspecialchars( filter_input( INPUT_GET, 'bimber_style_guide' ) );
	if ( $style_guide ) {
		$filename = 'style-guide';
	}

	$templates = array();

	// Keep in mind the WordPress template hierarchy
	// Read more about it here https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post .
	array_unshift( $templates,
		"{$filename}-{$object->post_type}-{$object->post_name}.php",
		"{$filename}-{$object->post_type}.php",
		"{$filename}.php"
	);

	$templates = array_unique( $templates );
	if ( count( $templates ) ) {
		$new_template = locate_template( $templates );

		if ( ! empty( $new_template ) ) {
			return $new_template;
		}
	}

	return $template;
}

/**
 * Adjust post classes.
 *
 * @param array $classes Post classes.
 *
 * @return array
 */
function bimber_post_class( $classes ) {
	// Remove classes.
	return array_diff( $classes, array(
		// We'll be using schema.org microdata instead of microformats.
		'hentry'
	) );
}

/**
 * Get default settings for a post
 *
 * @return mixed|void
 */
function bimber_get_post_default_settings() {
	return apply_filters( 'bimber_post_default_settings', array(
		'template' => 'classic',
		'elements' => array(
			'featured_media'  => true,
			'subtitle'  => true,
			'categories'      => true,
			'author'          => true,
			'avatar'          => true,
			'date'            => true,
			'comments_link'   => true,
			'shares_top'      => true,
			'tags'            => true,
			'shares_bottom'   => true,
			'newsletter'      => true,
			'navigation'      => true,
			'author_info'     => true,
			'related_entries' => true,
			'more_from'       => true,
			'dont_miss'       => true,
			'comments'        => true,
			'native_comments' => true,
			'views'           => true,
			'views'           => true,
			'downloads'       => true,
			'votes'           => true,
			'voting_box'      => true,
			'call_to_action'  => true,
		),
		'call_to_action_hide_buttons'  => '',
	) );
}

/**
 * Get post settings
 *
 * @return mixed|void
 */
function bimber_get_post_settings() {
	return apply_filters( 'bimber_post_settings', array(
		'template' => bimber_get_theme_option( 'post', 'template' ),
		'elements' => bimber_get_post_elements_visibility_arr( bimber_get_theme_option( 'post', 'hide_elements' ) ),
	) );
}

/**
 * Get the post elements visibility configuration
 *
 * @param string $elements_to_hide_str Comma-separated list of elements to hide.
 *
 * @return mixed
 */
function bimber_get_post_elements_visibility_arr( $elements_to_hide_str ) {
	$elements_to_hide_arr = explode( ',', $elements_to_hide_str );
	$defaults             = bimber_get_post_default_settings();
	$all_elements         = $defaults['elements'];

	foreach ( $all_elements as $elem_id => $is_visible ) {
		if ( in_array( $elem_id, $elements_to_hide_arr, true ) ) {
			$all_elements[ $elem_id ] = false;
		}
	}

	return $all_elements;
}

/**
 * Get ids of related posts
 *
 * @param int $post_id Post id.
 * @param int $limit Maximum number of ids to return.
 * @param int $min_entries Minimum number of ids to return.
 *
 * @return array
 */
function bimber_get_related_posts_ids( $post_id = 0, $limit = 10, $min_entries = 0 ) {
	return bimber_get_related_entries_ids( $post_id, 'post', $limit, $min_entries );
}

/**
 * Get ids of related entries
 *
 * @param int    $post_id Post id.
 * @param string $post_type Post type.
 * @param int    $limit Limit.
 * @param int    $min_entries Minimum entries.
 *
 * @return array
 */
function bimber_get_related_entries_ids( $post_id = 0, $post_type = 'post', $limit = 10, $min_entries = 0 ) {
	if ( ! $post_id ) {
		global $post;

		$post_id = $post ? $post->ID : 0;
	}

	$min_entries = min( $min_entries, $limit );

	$post_id = absint( $post_id );

	if ( $post_id <= 0 ) {
		return array();
	}

	$related_ids = array();

	$tags = get_the_terms( $post_id, 'post_tag' );

	if ( ! empty( $tags ) ) {
		if ( apply_filters( 'bimber_related_entries_backward_compat_mode', false ) ) {
			$tag_ids = wp_list_pluck( $tags, 'term_id' );
		} else {
			$tag_ids = wp_list_pluck( $tags, 'term_taxonomy_id' );
		}

		global $wpdb;

		$tag_ids = implode( ', ', array_map( 'intval', $tag_ids ) );

		// We have to use in SQL query the term taxonomy ids instead of term ids. Both fields haven't to be the same since WP separated them.
		$term_taxonomies = $wpdb->get_results( "SELECT t_r.term_taxonomy_id FROM {$wpdb->term_taxonomy} AS t_r WHERE t_r.term_id IN( $tag_ids )" );

		$term_taxonomy_ids = wp_list_pluck( $term_taxonomies, 'term_taxonomy_id' );

		if ( ! empty( $term_taxonomy_ids ) ) {
			$term_taxonomy_ids = implode( ', ', array_map( 'intval', $term_taxonomy_ids ) );

			$post_types = apply_filters( 'bimber_related_entries_post_types', array( 'post' ) );

			// Custom SQL query.
			// Standard query_posts function doesn't have enough power to produce results we need.
			$bimber_query = $wpdb->prepare(
				"
				SELECT p.ID, COUNT(t_r.object_id) AS cnt
	            FROM {$wpdb->term_relationships} AS t_r, {$wpdb->posts} AS p
	            WHERE t_r.object_id = p.ID
	                AND t_r.term_taxonomy_id IN( $term_taxonomy_ids )
	                AND p.post_type IN ('" . implode( "', '", $post_types ) . "')
	                AND p.ID != %d
	                AND p.post_status= %s
	            GROUP BY t_r.object_id
	            ORDER BY cnt DESC, p.post_date_gmt DESC
			",
				$post_id,
				'publish'
			);

			if ( $limit > 0 ) {
				$bimber_query .= $wpdb->prepare( ' LIMIT %d', $limit );
			}

			// Run the query.
			$posts = $wpdb->get_results( $bimber_query );

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $p ) {
					$related_ids[] = (int) $p->ID;
				}
			}
		}
	}

	// Complement entries.
	if ( $min_entries > 0 && count( $related_ids ) < $min_entries ) {
		$entires_to_add = $min_entries - count( $related_ids );

		$query_args = array(
			'posts_per_page'        => $entires_to_add,
			'post_type'             => $post_type,
			'post_status'           => 'publish',
			'post__not_in'          => array_merge( $related_ids, array( $post_id ) ),
			'ignore_sticky_posts'   => true,
		);

		$query = new WP_Query();
		$posts = $query->query( $query_args );

		foreach ( $posts as $post ) {
			$related_ids[] = $post->ID;
		}
	}

	return $related_ids;
}

/**
 * Get post taxonomies
 *
 * @param int  $post_id Post id.
 * @param bool $hierarchical Whether or not to return hierarchical taxonomies.
 *
 * @return mixed|void
 */
function bimber_get_post_taxonomies( $post_id, $hierarchical = true ) {
	$post_obj           = get_post( $post_id );
	$taxonomy_objects   = get_object_taxonomies( $post_obj, 'objects' );

	// Remove taxonomies.
	foreach ( $taxonomy_objects as $name => $object ) {
		// Non-public.
		if ( ! $object->query_var ) {
			unset( $taxonomy_objects[ $name ] );
		}

		// None hierarchical, if hierarchical requested.
		if ( $hierarchical && ! $object->hierarchical ) {
			unset( $taxonomy_objects[ $name ] );
		}

		// Hierarchical, if none hierarchical requested.
		if ( ! $hierarchical && $object->hierarchical ) {
			unset( $taxonomy_objects[ $name ] );
		}
	}

	return apply_filters( 'bimber_post_taxonomies', $taxonomy_objects );
}

/**
 * Get post terms
 *
 * @param int  $post_id Post id.
 * @param bool $hierarchical_taxonomies Whether or not include hierarchical terms.
 *
 * @return array
 */
function bimber_get_post_terms( $post_id, $hierarchical_taxonomies = true ) {
	$taxonomies = bimber_get_post_taxonomies( $post_id, $hierarchical_taxonomies );

	$taxonomy_terms = array();

	foreach ( $taxonomies as $object ) {
		$terms = apply_filters( 'bimber_post_terms', get_the_terms( $post_id, $object->name ) );

		if ( ! empty( $terms ) ) {
			$taxonomy_terms[ $object->name ] = $terms;
		}
	}

	return $taxonomy_terms;
}

/**
 * Get the first category assigned to post
 *
 * @param int $post_id Post id.
 *
 * @return mixed|null
 */
function bimber_get_post_first_category( $post_id ) {
	$terms = bimber_get_post_terms( $post_id, true );

	if ( empty( $terms ) ) {
		return null;
	}

	$first_taxonomy_terms = array_shift( $terms );
	$first_term           = array_shift( $first_taxonomy_terms );

	return $first_term;
}

/**
 * Whether a post is popular.
 *
 * @param int|WP_Post $p Optional. Post ID or WP_Post object. Default is global `$post`.
 *
 * @return bool
 */
function bimber_is_post_popular( $p = null ) {
	$post_obj = get_post( $p );

	$index = (int) get_post_meta( $post_obj->ID, '_bimber_popular', true );

	// Post is popular if it's indexed and belongs to the Popular collection (based on display limit).
	$is_popular = ( $index > 0 && $index <= bimber_get_popular_posts_limit() );

	return apply_filters( 'bimber_is_post_popular', $is_popular, $post_obj->ID );
}

/**
 * Whether the post is hot.
 *
 * @param int|WP_Post $p Optional. Post ID or WP_Post object. Default is global `$post`.
 *
 * @return bool
 */
function bimber_is_post_hot( $p = null ) {
	$post_obj = get_post( $p );

	$index = (int) get_post_meta( $post_obj->ID, '_bimber_hot', true );

	// Post is hot if it's indexed and belongs to the Hot collection (based on display limit).
	$is_hot = ( $index > 0 && $index <= bimber_get_hot_posts_limit() );

	return apply_filters( 'bimber_is_post_hot', $is_hot, $post_obj->ID );
}

/**
 * Whether the post is trending.
 *
 * @param int|WP_Post $p Optional. Post ID or WP_Post object. Default is global `$post`.
 *
 * @return bool
 */
function bimber_is_post_trending( $p = null ) {
	$post_obj = get_post( $p );

	$index = (int) get_post_meta( $post_obj->ID, '_bimber_trending', true );

	// Post is trending if it's indexed and belongs to the Trending collection (based on display limit).
	$is_trending = ( $index > 0 && $index <= bimber_get_trending_posts_limit() );

	return apply_filters( 'bimber_is_post_trending', $is_trending, $post_obj->ID );
}

/**
 * Check whether the Popular collection is enabled
 *
 * @return bool
 */
function bimber_has_popular_collection() {
	return (bool) bimber_get_theme_option( 'posts', 'popular_enable' );
}

/**
 * Check whether the Hot collection is enabled
 *
 * @return bool
 */
function bimber_has_hot_collection() {
	return (bool) bimber_get_theme_option( 'posts', 'hot_enable' );
}

/**
 * Check whether the Trending collection is enabled
 *
 * @return bool
 */
function bimber_has_trending_collection() {
	return (bool) bimber_get_theme_option( 'posts', 'trending_enable' );
}


/**
 * Get ids of featured posts
 *
 * @param array $query_args Query arguments.
 *
 * @return array
 */
function bimber_get_featured_posts_ids( $query_args ) {
	// Static var as a simple cache
	// in one request, it's enough to calculate featured ids just once.
	static $featured_ids;

	if ( isset( $featured_ids ) ) {
		return $featured_ids;
	}

	// WP_Query args.
	$defaults = array(
		'posts_per_page'      => 10,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'category__in'        => array(),
		'tag__in'             => array(),
		// Custom args.
		'type'                => 'recent',
		'time_range'          => 'all',
		'post_format'         => '',
	);

	$query_args = wp_parse_args( $query_args, $defaults );

	if ( bimber_show_global_featured_entries() && bimber_global_featured_entries_exclude_from_main_loop() ) {
		$global_featured_ids = bimber_get_global_featured_posts_ids();

		if ( ! empty( $global_featured_ids ) ) {
			$query_args['post__not_in'] = $global_featured_ids;
		}
	}

	// Remove custom args form $args.
	$type        = $query_args['type'];
	$time_range  = $query_args['time_range'];
	$post_format = $query_args['post_format'];

	unset( $query_args['type'] );
	unset( $query_args['time_range'] );
	unset( $query_args['post_format'] );

	// Map custom args to WP_Query args.
	$query_args = bimber_time_range_to_date_query( $time_range, $query_args );

	if ( is_post_type_archive() ) {
	    $query_args['post_type'] = get_queried_object()->name;
    }

	if ( is_category() ) {
		$main_cat = get_queried_object()->term_id;
		$cats = get_term_children( $main_cat, 'category' );
		$cats[] = $main_cat;
		$query_args['category__in'] = array_merge( $query_args['category__in'], $cats );
	}

	if ( is_tag() ) {
		$query_args['tag__in'][] = get_queried_object()->term_id;
	}

	if ( is_tax() ) {
		$taxonomy = get_queried_object()->taxonomy;
		$term_id  = get_queried_object()->term_id;

		$query_args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $term_id,
			),
		);
	}

	// Filter by author.
	if ( is_author() ) {
		$author = get_user_by( 'id', get_query_var( 'author' ) );

		// Try to get author by slug if ID not set.
		if ( false === $author ) {
			$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
		}

		if ( false !== $author ) {
			$query_args['author'] = $author->ID;
		}
	}

	switch ( $type ) {
		case 'recent':
			$query_args['orderby'] = 'date';
			break;

		case 'most_shared':
			$query_args = bimber_get_most_shared_query_args( $query_args );
			break;

		case 'most_viewed':
			$query_args = bimber_get_most_viewed_query_args( $query_args, 'featured_posts_ids' );
			break;
	}

	// Post format.
	if ( ! empty( $post_format ) ) {
		$post_format_terms = explode( ',', $post_format );

		foreach ( $post_format_terms as $index => $term ) {
			$post_format_terms[ $index ] = 'post-format-' . $term;
		}

		if ( ! isset( $query_args['tax_query'] ) ) {
			$query_args['tax_query'] = array();
		}

		$query_args['tax_query'][] = array(
			'taxonomy' 	=> 'post_format',
			'field' 	=> 'slug',
			'terms' 	=> $post_format_terms,
		);
	}

	$query_args = apply_filters( 'bimber_featured_posts_query_args', $query_args );
	$query = new WP_Query();
	$posts = $query->query( $query_args );

	$featured_ids = array();

	foreach ( $posts as $post ) {
		$featured_ids[] = $post->ID;
	}

	return $featured_ids;
}

/**
 * Format a number to a more compact form
 *
 * @param int $number Number.
 *
 * @return string
 */
function bimber_format_number( $number ) {
	$number_formatted = $number;

	if ( $number > 1000000 ) {
		$number_formatted = round( $number / 1000000, 1 ) . esc_html_x( 'M', 'formatted number suffix', 'bimber' );
	} elseif ( $number > 1000 ) {
		$number_formatted = round( $number / 1000, 1 ) . esc_html_x( 'k', 'formatted number suffix', 'bimber' );
	}

	return $number_formatted;
}

/**
 * Return query object for global featured entries
 *
 * @return WP_Query
 */
function bimber_get_global_featured_entries_query() {
	// Get built query from cache if available.
	$use_cache = apply_filters( 'bimber_featured_entries_use_cache', true );

	$bimber_query = $use_cache ? get_transient( 'bimber_featured_entries_query' ) : false;
	// Build cache if not set.
	if ( false === $bimber_query ) {

		$bimber_template 	= bimber_get_theme_option( 'featured_entries', 'template' );
		$bimber_type 		= bimber_get_theme_option( 'featured_entries', 'type' );
		$bimber_time_range 	= bimber_get_theme_option( 'featured_entries', 'time_range' );


		$posts_per_page = 'bunchy' === bimber_get_theme_option( 'featured_entries', 'template' ) ? bimber_get_theme_option( 'featured_entries', 'number_bunchy' ) : bimber_get_theme_option( 'featured_entries', 'number' );

		// Common args.
		$bimber_query_args = array(
			'posts_per_page'      => $posts_per_page,
			'ignore_sticky_posts' => true,
		);

		// Category.
		$bimber_query_args['category_name'] = bimber_get_theme_option( 'featured_entries', 'category' );

		if ( is_array( $bimber_query_args['category_name'] ) ) {
			$bimber_query_args['category_name'] = implode( ',', $bimber_query_args['category_name'] );
		}

		if ( ( count( get_categories() ) === substr_count( $bimber_query_args['category_name'], "," ) )){
			unset( $bimber_query_args['category_name'] );
		}

		// Tag.
		$bimber_tags = bimber_get_featured_entries_tags(); // array_filter removes empty values.

		if ( ! empty( $bimber_tags ) ) {
			$bimber_query_args['tag_slug__in'] = $bimber_tags;
		}

		// Time range.
		$bimber_query_args = bimber_time_range_to_date_query( $bimber_time_range, $bimber_query_args );

		// Type.
		switch ( $bimber_type ) {
			case 'recent':
				$bimber_query_args['orderby'] = 'post_date';
				break;

			case 'most_viewed':
				$bimber_query_args = bimber_get_most_viewed_query_args( $bimber_query_args, 'featured' );
				break;

			case 'most_shared':
				$bimber_query_args = bimber_get_most_shared_query_args( $bimber_query_args );
				break;
		}

		$bimber_query_args = apply_filters( 'bimber_global_featured_entries_query_args', $bimber_query_args );

		$bimber_query = new WP_Query( $bimber_query_args );

		set_transient( 'bimber_featured_entries_query', $bimber_query );
	}

	return $bimber_query;
}

/**
 * Return global featured entries tags
 *
 * @return array
 */
function bimber_get_featured_entries_tags() {
	$tags = bimber_get_theme_option( 'featured_entries', 'tag' );

	if ( ! is_array( $tags ) ) {
		$tags = explode( ',', $tags );
	}

	return array_filter( $tags );
}

/**
 * Return global featured posts ids
 *
 * @return array
 */
function bimber_get_global_featured_posts_ids() {
	$ids = array();

	if ( 'none' === bimber_get_theme_option( 'featured_entries', 'type' ) ) {
		return $ids;
	}

	$query = bimber_get_global_featured_entries_query();

	if ( $query->have_posts() ) {
		$posts = $query->get_posts();

		foreach ( $posts as $post ) {
			$ids[] = $post->ID;
		}
	}

	return $ids;
}

/**
 * Returns media ids for the first [gallery] shortcode in post content
 *
 * @param WP_Post $p	Post object.
 *
 * @return array		List of ids.
 */
function bimber_get_post_gallery_media_ids($p ) {
	$ids = array();

	if ( $p = get_post( $p ) ) {
		if ( has_shortcode( $p->post_content, 'gallery' ) ) {
			if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $p->post_content, $matches, PREG_SET_ORDER ) ) {
				// Get first [gallery] shortcode.
				foreach ( $matches as $shortcode ) {
					if ( 'gallery' === $shortcode[2] ) {
						// Ids set explicitly.
						if ( preg_match( '/ids="([^"]+)"/', $shortcode[0], $ids_matches ) ) {
							$ids = explode( ',', trim( $ids_matches[1] ) );
							// Ids not get, fetch number of post image attachments.
						} else {
							$ids = array_keys( get_attached_media( 'image', $p->ID ) );
						}
						break;
					}
				}
			}
		}
	}

	return $ids;
}

/**
 * Returns number of media items for the first [gallery] shortcode in post content
 *
 * @param WP_Post $post		Post object.
 *
 * @return int				Number of gallery images.
 */
function bimber_get_post_gallery_media_count( $post ) {
	return apply_filters( 'bimber_get_post_gallery_media_count', count( bimber_get_post_gallery_media_ids( $post ) ), $post );
}

/**
 * Returns the formatted length of the video.
 *
 * @param WP_Post $post		        Post object.
 * @param bool    $formatted		If returned value should be in seconds or in H:m:s format
 *
 * @return int|string               Seconds or H:m:s string.
 */
function bimber_get_post_video_length( $post, $formatted = true ) {
    if ( ! is_object( $post ) ) {
        return 0;
    }

	$video_length = (int) get_post_meta( $post->ID, '_bimber_post_video_length', true );

	$seconds_count = apply_filters( 'bimber_post_video_length', $video_length, $post );

	if ( $seconds_count <= 0 ) {
		return 0;
	}

	$hms = bimber_convert_seconds_into_hms( $seconds_count );

	if ( $formatted ) {
		return $hms;
	} else {
		return $seconds_count;
	}
}

/**
 * Convert length in seconds into HOURS:MINUTES:SECONDS format
 *
 * @param int    $length        Length in seconds.
 * @param string $delimiter     Delimiter.
 *
 * @return string
 */
function bimber_convert_seconds_into_hms( $length, $delimiter = ':' ) {
	$seconds = $length % 60;
	$minutes = floor( $length / 60 ) % 60;
	$hours   = floor( $length / 3600 );

	if( $hours ) {
		$seconds = str_pad( $seconds, 2, "0", STR_PAD_LEFT );
		$minutes = str_pad( $minutes, 2, "0", STR_PAD_LEFT );
		$hours = str_pad( $hours, 2, "0", STR_PAD_LEFT);

		return $hours . $delimiter. $minutes . $delimiter . $seconds;
	} else if ( $minutes ) {
		$seconds = str_pad( $seconds, 2, "0", STR_PAD_LEFT );

		return $minutes . $delimiter . $seconds;
	} else {
		return '0' . $delimiter. $seconds;
	}
}

/**
 * Generate post pagination using built-in WP page links
 *
 * @param array    $posts           Array of posts.
 * @param WP_Query $wp_query        WP Query.
 *
 * @return array
 */
function bimber_post_pagination( $posts, $wp_query ) {
	/**
	 * Check if query is an instance of WP_Query.
	 * Some plugins, like BuddyPress may change it.
	 */
	if ( ! ( $wp_query instanceof WP_Query ) ) {
		return $posts;
	}

	// Apply only for the_content on a single post.
	if ( ! ( $wp_query->is_main_query() && $wp_query->is_singular() ) ) {
		return $posts;
	}

	if ( bimber_can_use_plugin( 'amp/amp.php' ) && function_exists( 'amp_get_slug' ) && false !== get_query_var( amp_get_slug(), false ) ) {
		return $posts;
	}

	foreach ( $posts as $post ) {
		$post_format = get_post_format( $post );

		if ( ! in_array( $post_format, array( 'gallery' ), true ) ) {
			continue;
		}

		if ( ! has_shortcode( $post->post_content, 'gallery' ) ) {
			continue;
		}

		if ( strpos( $post->post_content, 'mace_type="lightbox"') > -1 ) {
			continue;
		}

		// One gallery item per page.
		$gallery_ids = bimber_get_post_gallery_media_ids( $post );
		$pages = count( $gallery_ids );

		if ( $pages < 2 ) {
			continue;
		}

		// Remove first [gallery] shortcode.
		$post->post_content = preg_replace( '/\[gallery[^\[]*\]/s', '', $post->post_content, 1 );

		// Build pages.
		foreach ( $gallery_ids as $index => $media_id ) {

			$attachment = get_post( $media_id );

			$post->post_content .= '[caption width="600" align="aligncenter"]';
			$post->post_content .= wp_get_attachment_image( $media_id, 'large' );

			if ( trim( $attachment->post_excerpt ) ) {
				$post->post_content .= ' ' . $attachment->post_excerpt;
			}

			$post->post_content .= '[/caption]';

			// @todo ???
			//$post->post_content .= $attachment->post_content;

			// The <!--nextpage--> tag is a divider between two pages. Number of dividers = pages - 1.
			if ( $index < $pages - 1 ) {
				$post->post_content .= '<!--nextpage-->';
			}
		}
	}

	return $posts;
}

/**
 * Check whether the post fly-in navigation is enabled.
 *
 * @return bool
 */
function bimber_is_post_flyin_nav_enabled() {
	$bool = (bool) bimber_get_theme_option( 'post', 'flyin_nav' );
	if ( bimber_is_auto_load() ) {
		$bool = false;
	}

	return apply_filters( 'post_flyin_nav', $bool );
}

/**
 * Set final elements order on a single post page
 */
function bimber_post_set_elements_order() {
	add_action( 'bimber_after_single_content', 'bimber_render_pagination_single',       bimber_get_theme_option( 'post', 'pagination_single_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_bottom_share_buttons',    bimber_get_theme_option( 'post', 'bottom_share_buttons_order' ) );

	add_action( 'bimber_after_single_content', 'bimber_render_entry_tags', 	            bimber_get_theme_option( 'post', 'tags_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_newsletter', 	            bimber_get_theme_option( 'post', 'newsletter_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_nav_single',              bimber_get_theme_option( 'post', 'nav_single_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_author_info',             bimber_get_theme_option( 'post', 'author_info_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_comments', 		    	bimber_get_theme_option( 'post', 'comments_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_related_entries', 	    bimber_get_theme_option( 'post', 'related_entries_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_more_from', 		    	bimber_get_theme_option( 'post', 'more_from_order' ) );
	add_action( 'bimber_after_single_content', 'bimber_render_dont_miss', 		    	bimber_get_theme_option( 'post', 'dont_miss_order' ) );

	add_action( 'bimber_after_single_content', 'bimber_render_missing_metadata',        9998 );
	add_action( 'bimber_after_single_content', 'bimber_render_next_post_button',        9999 );
}

/**
 * Register WP comments type
 *
 * @param array $types			List of types.
 *
 * @return array
 */
function bimber_register_wp_comment_type( $types ) {
	$post_elements = bimber_get_post_elements_visibility_arr( bimber_get_theme_option( 'post', 'hide_elements' ) );

	if ( ! $post_elements['native_comments'] ) {
		return $types;
	}

	$count = bimber_get_wp_comment_count();

	$native_label = bimber_get_theme_option( 'post', 'native_comments_label' );

	if ( $count > 0 ) {
		if ( ! empty( $native_label ) ) {
			$native_label = $native_label . '<span class="count">%d</span>';
		} else {
			$native_label = _x( 'Our site <span class="count">%d</span>', 'Type of comments', 'bimber' );
		}
		$types['wp'] = sprintf( $native_label, $count );
	} else {
		! empty( $native_label ) || $native_label = _x( 'Our site', 'Type of comments', 'bimber' );
		$types['wp'] = $native_label;
	}

	return $types;
}

/**
 * Return number of WP comments
 *
 * @param WP_Post $post		Optional. Post object or id.
 *
 * @return int
 */
function bimber_get_wp_comment_count( $post = null ) {
	$post = get_post( $post );

	return apply_filters( 'bimber_wp_comment_count', get_comments_number( $post ) );
}

/**
 * Add the "read more" link to the excerpt.
 *
 * @param string $excerpt Post excerpt.
 * @return string
 */
function bimber_excerpt_more( $excerpt ) {
	if ( is_feed() ) {
		return $excerpt;
	}

	if ( apply_filters( 'bimber_show_excerpt_more', strlen( $excerpt ) ) ) {
		$excerpt .= sprintf(
			' <a class="g1-link g1-link-more" href="%1$s">%2$s</a>',
			esc_url( get_permalink() ),
			__( 'More', 'bimber' )
		);
	}

	return $excerpt;
}

/**
 * Excerpt lenght.
 *
 * @param int $lenght  Excerpt lenght.
 * @return int
 */
function bimber_excerpt_length( $lenght ) {
	return bimber_get_theme_option( 'posts', 'excerpt_length' );
}

/**
 * Add the "read more" link to the excerpt.
 *
 * @param string $excerpt Post excerpt.
 * @return string
 */
function bimber_excerpt_strip_oembed( $excerpt ) {
	$url  = '';

	$the_post = get_post();
	$content = $the_post->post_content;
	// Find URLs on their own line.
	if ( preg_match( '|^(\s*)(https?://[^\s<>"]+)(\s*)$|im', $content, $matches ) ) {
		$url = $matches[2];
		// Find URLs in their own paragraph.
	} elseif ( preg_match( '|(<p(?: [^>]*)?>\s*)(https?://[^\s<>"]+)(\s*<\/p>)|i', $content, $matches ) ) {
		$url = $matches[2];
	}
	$excerpt = str_replace( $url, '', $excerpt );
	return $excerpt;
}

/**
 * Set post to random when url var is present
 */
function bimber_single_post_redirect_to_random_post() {
	$query_var = bimber_get_random_post_url_var();

	if ( isset( $_GET[ $query_var ] ) ) {
		$random_posts = get_posts( 'orderby=rand&posts_per_page=1' );

		if ( !empty( $random_posts ) ) {
			wp_safe_redirect( get_permalink( $random_posts[0]->ID ) );
			exit();
		}
	}
}

/**
 * Return random post url
 *
 * @return string
 */
function bimber_get_random_post_url() {
	$url = add_query_arg( array(
		bimber_get_random_post_url_var() => 'true',
	), trailingslashit( get_home_url() ) );

	return apply_filters( 'bimber_random_post_url', $url );
}

/**
 * Return random post url variable
 *
 * @return string
 */
function bimber_get_random_post_url_var() {
	return apply_filters( 'bimber_random_post_url_var', 'bimber_random_post' );
}

/**
 * Return random post url
 *
 * @return string
 */
function bimber_get_random_posts_url() {
    $url = add_query_arg( array(
        bimber_get_random_posts_url_var() => 'true',
    ), trailingslashit( get_home_url() ) );

    return apply_filters( 'bimber_random_posts_url', $url );
}

/**
 * Return random post url variable
 *
 * @return string
 */
function bimber_get_random_posts_url_var() {
    return apply_filters( 'bimber_random_posts_url_var', 'bimber_random_posts' );
}
