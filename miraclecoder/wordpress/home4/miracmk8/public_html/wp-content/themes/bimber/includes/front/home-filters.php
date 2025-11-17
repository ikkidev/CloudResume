<?php
/**
 * Home filters
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

add_action( 'bimber_before_title',                  'bimber_render_home_filters_menu', 10, 1 );
add_action( 'bimber_before_no_results',             'bimber_render_home_filters_menu', 10, 1 );
add_filter( 'nav_menu_item_args',                   'bimber_home_filters_menu_item_args', 10, 3 );
add_filter( 'nav_menu_css_class',                   'bimber_home_filters_menu_css_class', 11, 4 );
add_filter( 'nav_menu_link_attributes',             'bimber_home_filters_menu_link_attributes', 10, 4 );
add_action( 'pre_get_posts', 	                    'bimber_apply_home_filters' );

/**
 * Render the menu on home
 *
 * @param string $archive_type      Type of archive (home | archive).
 */
function bimber_render_home_filters_menu( $archive_type ) {
	if ( 'home' !== $archive_type ) {
		return;
	}

	if ( apply_filters( 'bimber_home_filters_disabled', false ) ) {
		return;
	}

	if ( has_nav_menu( 'bimber_home_filters' ) ) {
		add_filter( 'bimber_render_title', '__return_false' );
		add_filter( 'bimber_show_archive_no_results', '__return_true' );

		wp_nav_menu( array(
			'theme_location'  => 'bimber_home_filters',
			'container'       => 'div',
			'container_class' => 'g1-tabs',
			'container_id'    => 'g1-home-filters',
			'menu_class'      => 'g1-tab-items',
			'menu_id'         => 'g1-home-filters-menu',
			'depth'           => 0,
		) );

		wp_enqueue_script( 'bimber-tabs' );
	}
}

/**
 * Modify menu item url to use filters
 *
 * @param array    $args        Menu args.
 * @param stdClass $item        Item object.
 * @param int      $depth       Menu level.
 *
 * @return array
 */
function bimber_home_filters_menu_item_args( $args, $item, $depth ) {
	// Only for the Home Filters menu.
	if ( ! isset( $args->theme_location ) || $args->theme_location !== 'bimber_home_filters' ) {
		return $args;
	}

	// Latest posts page.
	if ( $item->url === bimber_get_latest_page_url() ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'latest',
		) );
	}

	// Popular posts page.
	if ( $item->url === bimber_get_popular_page_url() ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'popular',
		) );
	}

	// Hot posts page.
	if ( $item->url === bimber_get_hot_page_url() ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'hot',
		) );
	}

	// Trending posts page.
	if ( $item->url === bimber_get_trending_page_url() ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'trending',
		) );
	}

    // Random posts page.
    if ( $item->url === bimber_get_random_posts_url() ) {
        $item->url = bimber_get_home_filter_url( array(
            'filter-by' => 'random',
            'gen' => time(), // Prevent caching.
        ) );
    }

	// Taxonomy.
	if ( 'taxonomy' === $item->type ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'taxonomy',
			'obj'       => $item->object,
			'obj-id'    => $item->object_id,
		) );
	}

	if ( 'post_type_archive' === $item->type ) {
		$item->url = bimber_get_home_filter_url( array(
			'filter-by' => 'post-type-archive',
			'obj'       => $item->object,
		) );
	}

	return apply_filters( 'bimber_home_filters_menu_item_args', $args, $item );
}

/**
 * Highlight the current menu item filter
 *
 * @param array    $classes     Current classes.
 * @param stdClass $item        Item object.
 * @param array    $args        Menu args.
 * @param int      $depth       Current menu item level.
 *
 * @return array
 */
function bimber_home_filters_menu_css_class( $classes, $item, $args, $depth = 0 ) {
	// Only for the Home Filters menu.
	if ( ! isset( $args->theme_location ) || $args->theme_location !== 'bimber_home_filters' ) {
		return $classes;
	}

	if ( 0 === $depth ) {
		$classes[] = 'g1-tab-item';
	}

	$filter = bimber_get_home_current_filter();

	if ( ! $filter ) {
		$filter = array(
			'by' => 'latest'
		);
	}

	// Reset current item selection.
	$found_key = array_search( 'current-menu-item', $classes );

	if ( $found_key ) {
		unset( $classes[ $found_key ] );
	}

	if ( false !== strpos( $item->url, 'filter-by=' . $filter['by'] ) ) {
		$obj    = isset( $filter['obj'] ) ? $filter['obj'] : '';
		$obj_id = isset( $filter['obj_id'] ) ? $filter['obj_id'] : '';

		// Latest / Hot / Popular / Trending.
		if ( empty( $obj ) && empty( $obj_id ) ) {
			$classes[] = 'g1-tab-item-current';


		}

		// Taxonomy.
		if ( 'taxonomy' === $item->type && $obj === $item->object && $obj_id === $item->object_id ) {
			$classes[] = 'g1-tab-item-current';
		}

		// Custom post type.
		if ( 'post_type_archive' === $item->type && $obj === $item->object ) {
			$classes[] = 'g1-tab-item-current';
		}
	}

	return apply_filters( 'bimber_home_filters_menu_css_class', $classes, $item, $args );
}

/**
 * Add CSS class to menu item link
 *
 * @param array    $atts            Link attributes.
 * @param sdtClass $item            Menu item object.
 * @param array    $args            Menu args.
 * @param int      $depth           Menu level.
 *
 * @return mixed
 */
function bimber_home_filters_menu_link_attributes( $atts, $item, $args, $depth ) {
	// Only for the Home Filters menu.
	if ( !isset( $args->theme_location ) || $args->theme_location !== 'bimber_home_filters' ) {
		return $atts;
	}

	if ( ! isset( $atts['class'] ) ) {
		$atts['class'] = 'g1-tab';
	} else {
		$atts['class'] .= ' g1-tab';
	}

	return $atts;
}

/**
 * Build filter url
 *
 * @param array $query_args     Query args.
 *
 * @return string
 */
function bimber_get_home_filter_url( $query_args ) {
    $home_url = home_url();

    $show_on_front = get_option( 'show_on_front' );

    if ( 'page' === $show_on_front ) {
        $page_for_posts = (int) get_option( 'page_for_posts' );

        if ( $page_for_posts > 0 ) {
            $home_url = get_permalink( $page_for_posts );
        }
    }

	$home_url = apply_filters( 'bimber_home_filter_base_url', $home_url );
	$args = $query_args;

	return add_query_arg( $args, $home_url );
}

/**
 * Apply the home filters
 *
 * @param WP_Query $query Home main query.
 */
function bimber_apply_home_filters( $query ) {
	if ( ! $query->is_main_query() || is_feed() ) {
		return;
	}

	if ( ! is_home() ) {
		return;
	}

	if ( apply_filters( 'bimber_home_filters_disabled', false ) ) {
		return;
	}

	$filter = bimber_get_home_current_filter();

	if ( ! $filter ) {
		return;
	}

	global $bimber_home_filter;

	$bimber_home_filter = $filter;

	switch ( $filter['by'] ) {
		case 'hot':
			$post_ids = bimber_get_hot_post_ids( bimber_get_hot_posts_index_limit() );

			$query->set('post__in', $post_ids);
			$query->set('orderby', 'post__in');
			$query->set('ignore_sticky_posts', true );
			$query->set('post_type', 'any' );
			break;

		case 'trending':
			$post_ids = bimber_get_trending_post_ids( bimber_get_trending_posts_index_limit() );

			$query->set('post__in', $post_ids);
			$query->set('orderby', 'post__in');
			$query->set('ignore_sticky_posts', true );
			$query->set('post_type', 'any' );
			break;

		case 'popular':
			$post_ids = bimber_get_popular_post_ids( bimber_get_popular_posts_index_limit() );

			$query->set('post__in', $post_ids);
			$query->set('orderby', 'post__in');
			$query->set('ignore_sticky_posts', true );
			$query->set('post_type', 'any' );
			break;

        case 'random':
            $query->set('orderby', 'rand');
            $query->set('ignore_sticky_posts', true );
            $query->set('post_type', array( 'post' ) );
            break;

		case 'taxonomy':
			$term = get_term_by( 'id', $filter['obj_id'], $filter['obj'] );

			// Valid term?
			if ( false !== $term ) {
				$query->set('tax_query', array(
					array(
						'taxonomy' => $filter['obj'],
						'field'    => 'term_id',
						'terms'    => $filter['obj_id'],
					),
				));
			}
			break;

		case 'post-type-archive':
			$query->set('post_type', $filter['obj'] );
			break;

		default:
			do_action( 'bimber_filter_home_query', $query, $filter['by'] );
	}

    do_action( 'bimber_after_home_filters_applied', $query, $filter['by'] );
}

/**
 * Get current home filter
 *
 * @return mixed            Array with filter data, false if failed.
 */
function bimber_get_home_current_filter() {
	$filter_by  = bimber_htmlspecialchars( filter_input( INPUT_GET, 'filter-by' ) );
	$obj        = bimber_htmlspecialchars( filter_input( INPUT_GET, 'obj' ) );
	$obj_id     = filter_input( INPUT_GET, 'obj-id', FILTER_SANITIZE_NUMBER_INT );

	// Is the filter set explicitly?
	if ( $filter_by ) {
		return array(
			'by'     => $filter_by,
			'obj'    => $obj,
			'obj_id' => $obj_id,
		);
	}

	// If filter not set, check if the Home Filters menu assigned and if so, get the first filter.
	$menu_location = 'bimber_home_filters';

	if ( has_nav_menu( $menu_location ) ) {
		$locations = get_nav_menu_locations();

		if ( isset( $locations[ $menu_location ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_location ] );

			$items = wp_get_nav_menu_items( $menu );

			if ( ! empty( $items ) ) {
				$filter_arr = bimber_menu_item_to_home_filter( $items[0] );

				if ( $filter_arr ) {
					return $filter_arr;
				}
			}
		}
	}

	return false;
}

/**
 * Map menu item to home filter
 *
 * @param stdClass $item    Menu item objectl
 *
 * @return mixed            Array with filter data, false if failed.
 */
function bimber_menu_item_to_home_filter( $item ) {
	// Latest posts.
	if ( $item->url === bimber_get_latest_page_url() ) {
		return array(
			'by' => 'latest'
		);
	}

	// Popular posts.
	if ( $item->url === bimber_get_popular_page_url() ) {
		return array(
			'by' => 'popular'
		);
	}

	// Hot posts.
	if ( $item->url === bimber_get_hot_page_url() ) {
		return array(
			'by' => 'hot'
		);
	}

	// Trending posts.
	if ( $item->url === bimber_get_trending_page_url() ) {
		return array(
			'by' => 'trending'
		);
	}

    // Random posts.
    if ( $item->url === bimber_get_random_posts_url() ) {
        return array(
            'by' => 'random'
        );
    }

	// Taxonomy.
	if ( 'taxonomy' === $item->type ) {
		return array(
			'by'     => 'taxonomy',
			'obj'    => $item->object,
			'obj_id' => $item->object_id
		);
	}

	// Custom Post Type.
	if ( 'post_type_archive' === $item->type ) {
		return array(
			'by'     => 'post-type-archive',
			'obj'    => $item->object,
		);
	}

	return apply_filters( 'bimber_menu_item_to_home_filter', false, $item );
}
