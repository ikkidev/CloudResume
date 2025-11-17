<?php
/**
 * Options
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
 * Return possible templates for home pages
 *
 * @return array
 */
function bimber_get_home_templates() {
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/archive/';

	$choices = array(
		'grid-sidebar' => array(
			'label' => 'Grid with Sidebar',
			'path'  => $uri . 'grid-sidebar.png',
		),
		'grid' => array(
			'label' => 'Grid',
			'path'  => $uri . 'grid.png',
		),
		'masonry-stretched' => array(
			'label' => 'Masonry',
			'path'  => $uri . 'masonry-stretched.png',
		),
		'grid-l' => array(
			'label' => 'Grid Large',
			'path'  => $uri . 'grid-l.png',
		),
		'grid-l-sidebars' => array(
			'label' => 'Grid Large with Sidebars',
			'path'  => $uri . 'grid-l-sidebars.png',
		),
		'list-sidebar' => array(
			'label' => 'List with Sidebar',
			'path'  => $uri . 'list-sidebar.png',
		),
		'classic-sidebar' => array(
			'label' => 'Classic with Sidebar',
			'path'  => $uri . 'classic-sidebar.png',
		),
		'stream-sidebar' => array(
			'label' => 'Stream with Sidebar',
			'path'  => $uri . 'stream-sidebar.png',
		),
		'stream' => array(
			'label' => 'Stream',
			'path'  => $uri . 'stream.png',
		),
		'bunchy' => array(
			'label' => 'Bunchy',
			'path'  => $uri . 'bunchy.png',
		),
		'list-s-sidebar' => array(
			'label' => 'Small List with Sidebar',
			'path'  => $uri . 'list-s-sidebar.png',
		),
		'grid-s' => array(
			'label' => 'Small Grid',
			'path'  => $uri . 'grid-s.png',
		),
		'zigzag' => array(
			'label' => 'Zigzag',
			'path'  => $uri . 'zigzag.png',
		),
		'zigzag-s' => array(
			'label' => 'Zigzag Small',
			'path'  => $uri . 'zigzag-s.png',
		),
		'upvote-sidebar' => array(
			'label' => 'Upvote with Sidebar',
			'path'  => $uri . 'list-s-sidebar.png',
		),
	);

	return $choices;
}



/**
 * Return possible templates for collection shortcode
 *
 * All collection template files are located in the "bimber/template-parts/collection" folder.
 *
 * @return array
 */
function bimber_get_collection_templates() {
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/collection/';

	$choices = array(
		'grid-xxs-mod01' => array(
			'label' => 'Grid XXSmall Mod01',
			'path'  => $uri . 'grid-xxs-mod01.png',
		),
		'grid-xxs-mod11' => array(
			'label' => 'Grid XXSmall Mod11',
			'path'  => $uri . 'grid-xxs-mod11.png',
		),
		'list-xxs-mod01' => array(
			'label' => 'List XXSmall Mod01',
			'path'  => $uri . 'list-xxs-mod01.png',
		),
		'list-xxs-mod11' => array(
			'label' => 'List XXSmall Mod11',
			'path'  => $uri . 'list-xxs-mod11.png',
		),
		'txtlist' => array(
			'label' => 'Text List',
			'path'  => $uri . 'txtlist.png',
		),
		'txtlist-mod01' => array(
			'label' => 'Text List Mod01',
			'path'  => $uri . 'txtlist-mod01.png',
		),
		'txtlist-mod11' => array(
			'label' => 'Text List Mod11',
			'path'  => $uri . 'txtlist-mod11.png',
		),
		'list-xxs-mod02' => array(
			'label' => 'List XXSmall Mod02',
			'path'  => $uri . 'list-xxs-mod02.png',
		),
		'list-xxs-mod12' => array(
			'label' => 'List XXSmall Mod12',
			'path'  => $uri . 'list-xxs-mod12.png',
		),
		'list-xxs-mod21' => array(
			'label' => 'List XXSmall Mod21',
			'path'  => $uri . 'list-xxs-mod21.png',
		),
		'list-xxs-mod31' => array(
			'label' => 'List XXSmall Mod31',
			'path'  => $uri . 'list-xxs-mod31.png',
		),
		'txtlist-mod02' => array(
			'label' => 'Text List Mod02',
			'path'  => $uri . 'txtlist-mod02.png',
		),
		'10-2of3' => array(
			'label' => '10 2of3',
			'path'  => $uri . '10-2of3.png',
		),
		'index' => array(
			'label' => 'index',
			'path'  => $uri . 'index.png',
		),
		'list-xxs-mod03' => array(
			'label' => 'List XXSmall Mod03',
			'path'  => $uri . 'list-xxs-mod03.png',
		),
		'list-xxs-mod13' => array(
			'label' => 'List XXSmall Mod13',
			'path'  => $uri . 'list-xxs-mod13.png',
		),
		'list-xxs-mod23' => array(
			'label' => 'List XXSmall Mod23',
			'path'  => $uri . 'list-xxs-mod23.png',
		),
		'list-xxs-mod32' => array(
			'label' => 'List XXSmall Mod32',
			'path'  => $uri . 'list-xxs-mod32.png',
		),
		'txtlist-mod03' => array(
			'label' => 'Text List Mod03',
			'path'  => $uri . 'txtlist-mod03.png',
		),
		'list-s' => array(
			'label' => 'List Small',
			'path'  => $uri . 'list-s.png',
		),
		'upvote' => array(
			'label' => 'Upvote',
			'path'  => $uri . 'list-s.png', // @todo
		),
		'list-standard' => array(
			'label' => 'List',
			'path'  => $uri . 'list-standard.png',
		),
		'list-xxs' => array(
			'label' => 'List XXSmall',
			'path'  => $uri . 'list-xxs.png',
		),
		'grid-s-mod03' => array(
			'label' => 'Grid Small Mod03',
			'path'  => $uri . 'grid-s-mod03.png',
		),
		'grid-s' => array(
			'label' => 'Grid Small',
			'path'  => $uri . 'grid-s.png',
		),
		'grid-standard' => array(
			'label' => 'Grid',
			'path'  => $uri . 'grid-standard.png',
		),
		'grid-m-mod03' => array(
			'label' => 'Grid Mod03',
			'path'  => $uri . 'grid-m-mod03.png',
		),
		'grid-m-mod13' => array(
			'label' => 'Grid Mod13',
			'path'  => $uri . 'grid-m-mod13.png',
		),
		'grid-l' => array(
			'label' => 'Grid Large',
			'path'  => $uri . 'grid-l.png',
		),
		'zigzag' => array(
			'label' => 'Zigzag',
			'path'  => $uri . 'zigzag.png',
		),
		'zigzag-s' => array(
			'label' => 'Zigzag Small',
			'path'  => $uri . 'zigzag-s.png',
		),
		'tiles-m' => array(
			'label' => 'Tiles',
			'path'  => $uri . 'tiles-m.png',
		),
		'tiles-m-mod03' => array(
			'label' => 'Tiles Mod03',
			'path'  => $uri . 'tiles-m-mod03.png',
		),
		'tiles-m-mod02' => array(
			'label' => 'Tiles Mod02',
			'path'  => $uri . 'tiles-m-mod02.png',
		),
		'tiles-m-mod13' => array(
			'label' => 'Tiles Mod13',
			'path'  => $uri . 'tiles-m-mod13.png',
		),
		'tiles-l' => array(
			'label' => 'Tiles Large',
			'path'  => $uri . 'tiles-l.png',
		),
		'ticker' => array(
			'label' => 'Ticker',
			'path'  => $uri . 'ticker.png',
		),
	);

	return $choices;
}



/**
 * Return featured entries possible types for archive pages
 *
 * @return array
 */
function bimber_get_archive_featured_entries_types() {
	return array(
		'most_shared' => esc_html__( 'Most Shared', 'bimber' ),
		'most_viewed' => esc_html__( 'Most Viewed', 'bimber' ),
		'recent'      => esc_html__( 'Recent', 'bimber' ),
		'none'        => esc_html__( 'none', 'bimber' ),
	);
}

/**
 * Return featured entries possible templates for archive pages
 *
 * @return array
 */
function bimber_get_archive_featured_entries_templates() {
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/featured-entries/';
	$choices = array(
		'2-2-boxed' => array(
			'label' => '2-2-boxed',
			'path'  => $uri . '2-2-boxed.png',
		),
		'2-2-stretched' => array(
			'label' => '2-2-stretched',
			'path'  => $uri . '2-2-stretched.png',
		),
		'3-3-3-boxed' => array(
			'label' => '3-3-3-boxed',
			'path'  => $uri . '3-3-3-boxed.png',
		),
		'3-3-3-stretched' => array(
			'label' => '3-3-3-stretched',
			'path'  => $uri . '3-3-3-stretched.png',
		),
		'2-4-4-boxed' => array(
			'label' => '2-4-4-boxed',
			'path'  => $uri . '2-4-4-boxed.png',
		),
		'2-4-4-stretched' => array(
			'label' => '2-4-4-stretched',
			'path'  => $uri . '2-4-4-stretched.png',
		),
		'2of3-3v-3v-boxed' => array(
			'label' => '2of-3v-3v-boxed',
			'path'  => $uri . '2of3-3v-3v-boxed.png',
		),
		'2of3-3v-3v-stretched' => array(
			'label' => '2of-3v-3v-stretched',
			'path'  => $uri . '2of3-3v-3v-stretched.png',
		),
		'4-4-4-4-boxed' => array(
			'label' => '4-4-4-4-boxed',
			'path'  => $uri . '4-4-4-4-boxed.png',
		),
		'4-4-4-4-stretched' => array(
			'label' => '4-4-4-4-stretched',
			'path'  => $uri . '4-4-4-4-stretched.png',
		),
		'3-3v-3v-3v-3v-boxed' => array(
			'label' => '3-3v-3v-3v-3v-boxed',
			'path'  => $uri . '3-3v-3v-3v-3v-boxed.png',
		),
		'3-3v-3v-3v-3v-stretched' => array(
			'label' => '3-3v-3v-3v-3v-stretched',
			'path'  => $uri . '3-3v-3v-3v-3v-stretched.png',
		),
		'1-sidebar' => array(
			'label' => '1-sidebar',
			'path'  => $uri . '1-sidebar.png',
		),
		'1-sidebar-bunchy' => array(
			'label' => '1-sidebar-bunchy',
			'path'  => $uri . '1-sidebar-bunchy.png',
		),
		'todo-music' => array(
			'label' => 'todo-music',
			'path'  => $uri . 'todo-music.png',
		),
		'module-01' => array(
			'label' => 'module-01',
			'path'  => $uri . '1-sidebar-bunchy.png',
		),
		'todo-fashion' => array(
			'label' => 'todo-fashion',
			'path'  => $uri . 'todo-fashion.png',
		),
	);
	return $choices;
}

/**
 * Return featured entries possible time ranges for archive pages
 *
 * @return array
 */
function bimber_get_archive_featured_entries_time_ranges() {
	return array(
		'all'   => esc_html__( 'All time', 'bimber' ),
		'month' => esc_html__( 'Last 30 days', 'bimber' ),
		'week'  => esc_html__( 'Last 7 days', 'bimber' ),
		'day'   => esc_html__( 'Last 24 hours', 'bimber' ),
	);
}

/**
 * Return possible templates for archive pages
 *
 * @return array
 */
function bimber_get_archive_templates() {
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/archive/';

	$choices = array(
		'grid-sidebar' => array(
			'label' => 'Grid with Sidebar',
			'path'  => $uri . 'grid-sidebar.png',
		),
		'grid' => array(
			'label' => 'Grid',
			'path'  => $uri . 'grid.png',
		),
		'masonry-stretched' => array(
			'label' => 'Masonry, full width',
			'path'  => $uri . 'masonry-stretched.png',
		),
		'grid-l' => array(
			'label' => 'Grid Large',
			'path'  => $uri . 'grid-l.png',
		),
		'grid-l-sidebars' => array(
			'label' => 'Grid Large with Sidebars',
			'path'  => $uri . 'grid-l-sidebars.png',
		),
		'list-sidebar' => array(
			'label' => 'List with Sidebar',
			'path'  => $uri . 'list-sidebar.png',
		),
		'classic-sidebar' => array(
			'label' => 'Classic with Sidebar',
			'path'  => $uri . 'classic-sidebar.png',
		),
		'stream-sidebar' => array(
			'label' => 'Stream with Sidebar',
			'path'  => $uri . 'stream-sidebar.png',
		),
		'stream' => array(
			'label' => 'Stream',
			'path'  => $uri . 'stream.png',
		),
		'bunchy' => array(
			'label' => 'Bunchy',
			'path'  => $uri . 'bunchy.png',
		),
		'list-s-sidebar' => array(
			'label' => 'Small List with Sidebar',
			'path'  => $uri . 'list-s-sidebar.png',
		),
		'grid-s' => array(
			'label' => 'Small Grid',
			'path'  => $uri . 'grid-s.png',
		),
		'zigzag' => array(
			'label' => 'Zigzag',
			'path'  => $uri . 'zigzag.png',
		),
		'zigzag-s' => array(
			'label' => 'Zigzag Small',
			'path'  => $uri . 'zigzag-s.png',
		),
		'upvote-sidebar' => array(
			'label' => 'Upvote with Sidebar',
			'path'  => $uri . 'list-s-sidebar.png',
		),
	);

	return $choices;
}

/**
 * Return possible header compositions for archive pages
 *
 * @return array
 */
function bimber_get_archive_header_compositions() {
	return array(
		'01'      => '01',
		'02'      => '02',
		'03'      => '03',
	);
}

/**
 * Return possible pagination types for archive pages
 *
 * @return array
 */
function bimber_get_archive_pagination_types() {
	return array(
		'load-more'                 => esc_html__( 'Load More', 'bimber' ),
		'infinite-scroll'           => esc_html__( 'Infinite Scroll', 'bimber' ),
		'infinite-scroll-on-demand' => esc_html__( 'Infinite Scroll (first load via click)', 'bimber' ),
		'pages'                     => esc_html__( 'Prev/Next Pages', 'bimber' ),
	);
}

/**
 * Return possible to hide elements for archive pages
 *
 * @return array
 */
function bimber_get_archive_elements_to_hide() {
	return apply_filters( 'bimber_archive_hide_elements_choices', array(
		'featured_media' => esc_html__( 'Featured Media', 'bimber' ),
		'categories'     => esc_html__( 'Categories', 'bimber' ),
		'subtitle'       => esc_html__( 'Subtitle', 'bimber' ),
		'summary'        => esc_html__( 'Summary', 'bimber' ),
		'author'         => esc_html__( 'Author', 'bimber' ),
		'avatar'         => esc_html__( 'Avatar', 'bimber' ),
		'date'           => esc_html__( 'Date', 'bimber' ),
		'shares'         => esc_html__( 'Shares', 'bimber' ),
		'views'          => esc_html__( 'Views', 'bimber' ),
		'comments_link'  => esc_html__( 'Comments Link', 'bimber' ),
		'call_to_action' => esc_html__( 'Call to Action', 'bimber' ),
	) );
}

/**
 * Return possible to hide elements for archive pages
 *
 * @return array
 */
function bimber_get_archive_header_elements_to_hide() {
	return array(
		'taxonomy image' => esc_html__( 'Icon', 'bimber' ),
		'breadcrumbs'    => esc_html__( 'Breadcrumbs', 'bimber' ),
		'title'          => esc_html__( 'Title', 'bimber' ),
		'description'    => esc_html__( 'Description', 'bimber' ),
		'filters'        => esc_html__( 'Filters', 'bimber' ),
	);
}

/**
 * Return possible to hide elements for search pages
 *
 * @return array
 */
function bimber_get_search_elements_to_hide() {
	return apply_filters( 'bimber_search_hide_elements_choices', array(
		'featured_media' => esc_html__( 'Featured Media', 'bimber' ),
		'shares'         => esc_html__( 'Shares', 'bimber' ),
		'views'          => esc_html__( 'Views', 'bimber' ),
		'comments_link'  => esc_html__( 'Comments Link', 'bimber' ),
		'categories'     => esc_html__( 'Categories', 'bimber' ),
		'summary'        => esc_html__( 'Summary', 'bimber' ),
		'author'         => esc_html__( 'Author', 'bimber' ),
		'avatar'         => esc_html__( 'Avatar', 'bimber' ),
		'date'           => esc_html__( 'Date', 'bimber' ),
	) );
}

/**
 * Return possible newsletter options for archive pages
 *
 * @return array
 */
function bimber_get_archive_newsletter_options() {
	return array(
		'standard' => __( 'inject into post collection', 'bimber' ),
		'none'     => __( 'Hide', 'bimber' ),
	);
}

/**
 * Return possible ad options for archive pages
 *
 * @return array
 */
function bimber_get_archive_ad_options() {
	return array(
		'standard' => __( 'inject into post collection', 'bimber' ),
		'none'     => __( 'Hide', 'bimber' ),
	);
}

/**
 * Return possible product options for archive pages
 *
 * @return array
 */
function bimber_get_archive_product_options() {
	return array(
		'standard' => __( 'inject into post collection', 'bimber' ),
		'none'     => __( 'Hide', 'bimber' ),
	);
}

/**
 * Return possible hide title options
 *
 * @return array
 */
function bimber_get_yes_no_options() {
	return array(
		'standard' => __( 'Yes', 'bimber' ),
		'none'     => __( 'No', 'bimber' ),
	);
}

/**
 * Get archive filters.
 *
 * @return arrray
 */
function bimber_get_archive_filters() {
	$archive_filters = array(
		'newest' 			=> __( 'Latest', 'bimber' ),
		'oldest' 			=> __( 'Oldest', 'bimber' ),
		'most_commented' 	=> __( 'Most Discussed', 'bimber' ),
	);
	return apply_filters( 'bimber_archive_filters', $archive_filters );
}

/**
 * Return possible footer compositions
 *
 * @return array
 */
function bimber_get_footer_compositions() {
	return apply_filters( '', array(
		'3cols' => esc_html__( '3 columns', 'bimber' ),
		'4cols' => esc_html__( '4 columns', 'bimber' ),
	) );
}

/**
 * Return possible templates for archive pages
 *
 * @return array
 */
function bimber_get_sections_templates() {
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/archive/';

	$choices = array(
		'grid' => array(
			'label' => 'Grid',
			'path'  => $uri . 'grid.png',
		),
		'list' => array(
			'label' => 'List',
			'path'  => $uri . 'list-sidebar.png',
		),
		'list-s' => array(
			'label' => 'Small List',
			'path'  => $uri . 'list-s-sidebar.png',
		),
	);
	return $choices;
}

/**
 * Return ad slot id for a template
 *
 * @param string $tpl   Template name.
 *
 * @return string       Empty if match not found.
 */
function bimber_get_ad_slot_by_template( $tpl ) {
    $slot = '';

    $tpl_slot_map = array(
        'grid-sidebar'      => 'bimber_inside_grid',
        'grid'              => 'bimber_inside_grid',
        'masonry-stretched' => 'bimber_inside_grid',
        'grid-l'            => 'bimber_inside_grid',
        'grid-l-sidebars'   => 'bimber_inside_grid',
        'list-sidebar'      => 'bimber_inside_list',
        'classic-sidebar'   => 'bimber_inside_classic',
        'stream-sidebar'    => 'bimber_inside_stream',
        'stream'            => 'bimber_inside_stream',
        'bunchy'            => 'bimber_inside_stream',
        'list-s-sidebar'    => 'bimber_inside_list',
        'grid-s'            => 'bimber_inside_grid_s',
        'zigzag'            => 'bimber_inside_zigzag',
        'zigzag-s'          => 'bimber_inside_zigzag_s',
        'upvote-sidebar'    => 'bimber_inside_list',
    );

    if ( isset( $tpl_slot_map[ $tpl ] ) ) {
        $slot = $tpl_slot_map[ $tpl ];
    }

    return $slot;
}

/**
 * Return Facebook App ID
 *
 * @return string
 */
function bimber_get_facebook_app_id() {
    $customizer_options = get_option( bimber_get_theme_id() );
    $legacy_value = ! empty( $customizer_options['posts_fb_app_id'] ) ? $customizer_options['posts_fb_app_id'] : '';

    $theme_options = get_option( bimber_get_theme_options_id() );

    // Migrate old option from Customizer.
    if ( ! isset( $theme_options['facebook_app_id'] ) ) {
        $fb_app_id = $legacy_value;
    } else {
        $fb_app_id = $theme_options['facebook_app_id'];
    }

    return apply_filters( 'bimber_facebook_app_id', $fb_app_id );
}

/**
 * Return Facebook App Secret
 *
 * @return string
 */
function bimber_get_facebook_app_secret() {
    return bimber_get_theme_option( 'facebook', 'app_secret' );
}
