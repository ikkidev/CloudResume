<?php
/**
 * Theme common functions
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
 * Get theme identificator
 *
 * @return string
 */
function bimber_get_theme_id() {
	return 'bimber_theme';
}

/**
 * Get the id of the option where we store all theme options
 *
 * @return string
 */
function bimber_get_theme_options_id() {
	return 'bimber_theme_options';
}

/**
 * Get theme name
 *
 * @return string
 */
function bimber_get_theme_name() {
	return 'bimber';
}

/**
 * Get theme version
 *
 * @return string
 */
function bimber_get_theme_version() {
	$current_theme = wp_get_theme(get_template());

	return $current_theme->exists() ? $current_theme->get( 'Version' ) : '1.0';
}

/**
 * Check whether the theme is in developer mode
 *
 * @return bool
 */
function bimber_in_dev_mode() {
	return defined( 'BIMBER_DEVELOPER_MODE' ) ? constant( 'BIMBER_DEVELOPER_MODE' ) : false;
}

/**
 * Get scripts version
 *
 * @return string
 */
function bimber_get_scripts_version() {
	return bimber_in_dev_mode() ? '' : '.min';
}

/**
 * Get theme options prefixes
 *
 * @return array
 */
function bimber_get_theme_options_vars_prefixes() {
	return array(
		'theme_update',
		'advanced',
		'tracking_code',
		'shares',
		'facebook',
	);
}

/**
 * Get default theme option values
 *
 * @return array
 */
function bimber_get_defaults() {
	static $defaults;

	// Load only once.
	if ( ! $defaults ) {
		$storage_name = bimber_get_theme_id();

		$defaults = array(
			$storage_name              => bimber_get_customizer_defaults(),
			$storage_name . '_options' => bimber_get_theme_options_defaults(),
		);
	}

	return apply_filters( 'bimber_defaults', $defaults );
}

function bimber_get_theme_options_defaults() {
	require BIMBER_ADMIN_DIR . 'theme-options/theme-defaults.php';

	/**
	 * Vars from included file
	 *
	 * @var array $bimber_theme_options_defaults
	 */
	return apply_filters( 'bimber_theme_options_defaults', $bimber_theme_options_defaults );
}

function bimber_get_customizer_defaults() {
	require BIMBER_ADMIN_DIR . 'customizer/customizer-defaults.php';

	/**
	 * Vars from included file
	 *
	 * @var array $bimber_customizer_defaults
	 */
	return apply_filters( 'bimber_customizer_defaults', $bimber_customizer_defaults );
}

/**
 * Return current page URL
 *
 * @return string
 */
function bimber_get_current_url() {
	global $wp;
	$current_url = home_url( add_query_arg( array(), $wp->request ) );

	return $current_url;
}

/**
 * Returns WordPress uploads dir base URL.
 *
 * @return string
 */
function bimber_get_uploads_url() {
	$dir = wp_upload_dir();

	// Fix SSL.
	$url = is_ssl() ? str_replace( 'http:', 'https:', $dir['baseurl'] ) : $dir['baseurl'];

	return trailingslashit( $url );
}

/**
 * Returns WordPress uploads dir.
 *
 * @return string
 */
function bimber_get_uploads_dir() {
    $dir = wp_upload_dir();

    return trailingslashit( $dir['basedir']);
}

function bimber_get_iso_8601_utc_offset() {
	$offset  = get_option( 'gmt_offset' );
	$hours   = (int) $offset;
	$minutes = abs( ( $offset - (int) $offset ) * 60 );

	return sprintf( '%+03d:%02d', $hours, $minutes );
}

/**
 * Get theme options about image paths
 *
 * @return array
 */
function bimber_get_theme_image_options() {
	return array(
		'branding_logo',                        // Logo.
		'branding_logo_hdpi',                   // Logo HDPI.
		'branding_logo_inverted',               // Logo inverted.
		'branding_logo_inverted_hdpi',          // Logo inverted HDPI.
		'branding_logo_small',                  // Mobile logo.
		'branding_logo_small_hdpi',             // Mobile logo HDPI.
		'branding_logo_small_inverted',         // Mobile logo inverted.
		'branding_logo_small_inverted_hdpi',    // Mobile logo inverted HDPI.
		'footer_stamp',
		'footer_stamp_hdpi',
		'header_builder_canvas_background_image',
		// @todo - do it via filter (Plugins > Mailchimp)
		'newsletter_popup_background_image',
		'newsletter_slideup_avatar',
		'newsletter_slideup_background_image',
		'newsletter_before_collection_avatar',
		'newsletter_before_collection_background_image',
		'newsletter_in_collection_avatar',
		'newsletter_in_collection_background_image',
		'newsletter_after_post_content_avatar',
		'newsletter_after_post_content_background_image',
		'newsletter_before_footer_avatar',
		'newsletter_before_footer_background_image',
		'newsletter_other_avatar',
	);
}

/**
 * Return demos config array
 *
 * @return array
 */
function bimber_get_demos() {
	$demo_images_dir_uri = BIMBER_ADMIN_DIR_URI . 'images/demos/';

	$demos = array(

		// Main.
		'main' => array(
			'name'          => 'Main',
			'preview_url'   => 'https://bimber.bringthepixel.com/main/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-original.jpg',
		),

		// Relink.
		'relink' => array(
			'name'          => 'Relink',
			'preview_url'   => 'https://bimber.bringthepixel.com/relink/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-relink.jpg',
		),

		// AdsMania.
		'adsmania' => array(
			'name'          => 'AdsMania',
			'preview_url'   => 'https://bimber.bringthepixel.com/adsmania/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-adsmania.jpg',
		),

		// Gags.
		'gags' => array(
			'name'          => 'Gags',
			'preview_url'   => 'https://bimber.bringthepixel.com/gags/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-gags.jpg',
		),

        // News.
        'news' => array(
            'name'          => 'News',
            'preview_url'   => 'https://bimber.bringthepixel.com/news/',
            'preview_img'   => $demo_images_dir_uri . 'bimber-demo-news.jpg',
        ),

        // Freebies.
        'freebies' => array(
            'name'          => 'Freebies',
            'preview_url'   => 'https://bimber.bringthepixel.com/freebies/',
            'preview_img'   => $demo_images_dir_uri . 'bimber-demo-freebies.jpg',
        ),

		// Video.
		'video' => array(
			'name'          => 'Video',
			'preview_url'   => 'https://bimber.bringthepixel.com/video/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-video.jpg',
		),

		// BuzzFreak.
		'buzzfreak' => array(
			'name'          => 'BuzzFreak',
			'preview_url'   => 'https://bimber.bringthepixel.com/buzzfreak/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-buzzfreak.jpg',
		),

        // Gagster.
        'gagster' => array(
            'name'          => 'Gagster',
            'preview_url'   => 'https://bimber.bringthepixel.com/gagster/',
            'preview_img'   => $demo_images_dir_uri . 'bimber-demo-gagster.jpg',
        ),

        // Affiliate.
        'affiliate' => array(
            'name'          => 'Affiliate',
            'preview_url'   => 'https://bimber.bringthepixel.com/affiliate/',
            'preview_img'   => $demo_images_dir_uri . 'bimber-demo-affiliate.jpg',
        ),

		// Food.
		'food' => array(
			'name'          => 'Food',
			'preview_url'   => 'https://bimber.bringthepixel.com/food/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-food.jpg',
		),

        // Wall.
        'wall' => array(
            'name'          => 'Wall',
            'preview_url'   => 'https://bimber.bringthepixel.com/wall/',
            'preview_img'   => $demo_images_dir_uri . 'bimber-demo-wall.jpg',
        ),

		// Music.
		'music' => array(
			'name'          => 'Music',
			'preview_url'   => 'https://bimber.bringthepixel.com/music/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-music.jpg',
		),

		// Fashion.
		'fashion' => array(
			'name'          => 'Fashion',
			'preview_url'   => 'https://bimber.bringthepixel.com/fashion/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-fashion.jpg',
		),

		// Celebrities.
		'celebrities' => array(
			'name'          => 'Celebrities',
			'preview_url'   => 'https://bimber.bringthepixel.com/celebrities/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-celebrities.jpg',
		),


		// Smiley.
		'smiley' => array(
			'name'          => 'Smiley',
			'preview_url'   => 'https://bimber.bringthepixel.com/smiley/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-smiley.jpg',
		),

        // CarMania.
        'carmania' => array(
            'name'          => 'Cars',
            'preview_url'   => 'https://bimber.bringthepixel.com/carmania/',
            'preview_img'   => $demo_images_dir_uri . 'bimber-demo-carmania.jpg',
        ),

		// Bad Boy.
		'badboy' => array(
			'name'          => 'Bad Boy',
			'preview_url'   => 'https://bimber.bringthepixel.com/badboy/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-badboy.jpg',
		),

		// Minimal.
		'minimal' => array(
			'name'          => 'Minimal',
			'default'       => true,
			'preview_url'   => 'https://bimber.bringthepixel.com/minimal/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-minimal.jpg',
		),

		// Geeky.
		'geeky' => array(
			'name'          => 'Geeky',
			'preview_url'   => 'https://bimber.bringthepixel.com/geeky/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-geeky.jpg',
		),

		// Bunchy.
		'bunchy' => array(
			'name'          => 'Bunchy',
			'preview_url'   => 'https://bimber.bringthepixel.com/bunchy/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-bunchy.jpg',
		),

		// Community.
		'community' => array(
			'name'          => 'Community',
			'preview_url'   => 'https://bimber.bringthepixel.com/community/',
			'preview_img'   => $demo_images_dir_uri . 'bimber-demo-community.jpg',
		),



	);

	return apply_filters( 'bimber_demos', $demos );
}

/**
 * Get theme option value
 *
 * @param string $base 				Base.
 * @param string $key 				Key.
 * @param bool   $force_global		If set to true, global value will always be returned.
 *
 * @return mixed
 */
function bimber_get_theme_option( $base, $key, $force_global = false ) {
	/*
	 * Single category.
	 */

	if ( ! $force_global && 'archive' === $base && is_category() ) {
		$term = get_queried_object();

		if ( $term && is_a( $term, 'WP_Term' ) ) {
            $term_meta_prefix 	= 'bimber_';
            $term_setting 		= get_term_meta( $term->term_id, $term_meta_prefix . $key, true );

            // Valid setting is not empty, empty string is reserved for "inherit" value.
            if ( $term_setting ) {
                return $term_setting;
            }
        }
	}

	/*
	 * Single post tag.
	 */

	if ( ! $force_global && 'archive' === $base && is_tag() ) {
		$term = get_queried_object();

		if ( $term && is_a( $term, 'WP_Term' ) ) {
            $term_meta_prefix 	= 'bimber_';
            $term_setting 		= get_term_meta( $term->term_id, $term_meta_prefix . $key, true );

            // Valid setting is not empty, empty string is reserved for "inherit" value.
            if ( $term_setting ) {
                return $term_setting;
            }
        }
	}

	/*
	 * Global.
	 */

	$storage_name = bimber_get_theme_id();

	// Use different storage for WP Admin > Appearance > Theme Options values.
	if ( in_array( $base, bimber_get_theme_options_vars_prefixes(), true ) ) {
		$storage_name .= '_options';
	}

	$storage_values = get_option( $storage_name, array() );

	$option_name = $base;

	if ( strlen( $key ) > 0 ) {
		$option_name .= '_' . $key;
	}

	$defaults = bimber_get_defaults();

	if ( isset( $defaults[ $storage_name ][ $option_name ] ) ) {
		$result = isset( $storage_values[ $option_name ] ) ? $storage_values[ $option_name ] : $defaults[ $storage_name ][ $option_name ];
	} else {
		$result = null;
	}

	// Fix image absolute paths stored in Customizer.
    $image_options = bimber_get_theme_image_options();

	// Is a theme image?
    if ( $result && in_array( $option_name, $image_options ) ) {
        // SSL is ON but image is http://.
        if ( is_ssl() && false !== strpos( $result, 'http:' ) ) {
            $result = str_replace( 'http:', 'https:', $result );
        }
    }

	return $result;
}

/**
 * Set theme option value
 *
 * @param string $base Base.
 * @param string $key Key.
 * @param mixed  $value Value.
 */
function bimber_set_theme_option( $base, $key, $value ) {
	$storage_name = bimber_get_theme_id();

	// Use different storage for WP Admin > Appearance > Theme Options values.
	if ( in_array( $base, bimber_get_theme_options_vars_prefixes(), true ) ) {
		$storage_name .= '_options';
	}

	$storage_values = get_option( $storage_name, array() );

	$option_name = $base;

	if ( strlen( $key ) > 0 ) {
		$option_name .= '_' . $key;
	}

	$storage_values[ $option_name ] = $value;

	update_option( $storage_name, $storage_values );
}

/**
 * Set template part data.
 *
 * @param mixed|void $data Data.
 */
function bimber_set_template_part_data( $data ) {
	global $bimber_template_data;
	global $bimber_template_data_stack;

	if ( ! isset( $bimber_template_data_stack ) ) {
		$bimber_template_data_stack = array();
	}

	// Push into the stack.
	array_push( $bimber_template_data_stack, $bimber_template_data );

	// Override current data.
	$bimber_template_data      = $data;
}

/**
 * Get template part data.
 *
 * @return mixed
 */
function bimber_get_template_part_data() {
	global $bimber_template_data;

	return $bimber_template_data;
}

/**
 * Reset template part data
 */
function bimber_reset_template_part_data() {
	global $bimber_template_data;
	global $bimber_template_data_stack;

	// Restore from the stack.
	$bimber_template_data = array_pop( $bimber_template_data_stack );
}

/**
 * Return query args for most shared posts
 *
 * @param array $query_args         Arguments.
 *
 * @return array
 */
function bimber_get_most_shared_query_args( $query_args ) {
	if ( isset( $query_args['time_range'] ) ) {
		$query_args = bimber_time_range_to_date_query( $query_args['time_range'], $query_args );
	}

	return apply_filters( 'bimber_most_shared_query_args', $query_args );
}

/**
 * Return query args for most viewed posts
 *
 * @param array $query_args     Arguments.
 *
 * @return array
 */
function bimber_get_most_viewed_query_args( $query_args, $type = '' ) {
	if ( isset( $query_args['time_range'] ) ) {
		$query_args = bimber_time_range_to_date_query( $query_args['time_range'], $query_args );
	}

	// By default there are no most viewed posts,
	// so to make sure that no posts will be returned we use none existing post id.
	$query_args['post__in'] = array( -1 );

	return apply_filters( 'bimber_most_viewed_query_args', $query_args, $type );
}

/**
 * Return query args for most voted posts
 *
 * @param array $query_args     Arguments.
 *
 * @return array
 */
function bimber_get_most_voted_query_args( $query_args, $type = '' ) {
	if ( isset( $query_args['time_range'] ) ) {
		$query_args = bimber_time_range_to_date_query( $query_args['time_range'], $query_args );
	}

	return apply_filters( 'bimber_most_voted_query_args', $query_args, $type );
}

/**
 * Return list of most voted posts (ids)
 *
 * @param string $date_range        Date range.
 * @param int    $limit             Max. number of posts to fetch.
 * @param string $type              List type.
 *
 * @return array
 */
function bimber_get_most_voted_posts( $date_range, $limit, $type ) {
	$ids = array();

	return apply_filters( 'bimber_most_voted_posts', $ids, $date_range, $limit, $type );
}

/**
 * Get the maximum number of hot posts to display
 *
 * @return int
 */
function bimber_get_hot_posts_limit() {
	return apply_filters( 'bimber_hot_posts_limit', 10 );
}

/**
 * Get the maximum number of hot posts to index
 *
 * @return int
 */
function bimber_get_hot_posts_index_limit() {
	return apply_filters( 'bimber_hot_posts_index_limit', 100 );
}

/**
 * Get the maximum number of popular posts to display
 *
 * @return int
 */
function bimber_get_popular_posts_limit() {
	return apply_filters( 'bimber_popular_posts_limit', 10 );
}

/**
 * Get the maximum number of popular posts to index
 *
 * @return int
 */
function bimber_get_popular_posts_index_limit() {
	return apply_filters( 'bimber_popular_posts_index_limit', 100 );
}

/**
 * Get the maximum number of trending posts to display
 *
 * @return int
 */
function bimber_get_trending_posts_limit() {
	return apply_filters( 'bimber_trending_posts_limit', 10 );
}

/**
 * Get the maximum number of trending posts to index
 *
 * @return int
 */
function bimber_get_trending_posts_index_limit() {
	return apply_filters( 'bimber_trending_posts_index_limit', 100 );
}

/**
 * Get the maximum number of related posts
 *
 * @return int
 */
function bimber_get_related_posts_limit() {
	return apply_filters( 'bimber_related_posts_limit', bimber_get_theme_option( 'post', 'related_max_posts' ) );
}

/**
 * Get the maximum number of "More From" posts
 *
 * @return int
 */
function bimber_get_more_from_posts_limit() {
	return apply_filters( 'bimber_more_from_posts_limit', bimber_get_theme_option( 'post', 'more_from_max_posts' ) );
}

/**
 * Get the maximum number of "Don't Miss" posts
 *
 * @return int
 */
function bimber_get_dont_miss_posts_limit() {
	return apply_filters( 'bimber_dont_miss_posts_limit', bimber_get_theme_option( 'post', 'dont_miss_max_posts' ) );
}

/**
 * Convert custom time range to date query args
 *
 * @param string $time_range      Time range type.
 * @param array  $query_args       Arguments.
 *
 * @return array
 */
function bimber_time_range_to_date_query( $time_range, $query_args ) {
	switch ( $time_range ) {
		case 'day':
			$date_ago = '1 day ago';
			break;

		case 'week':
			$date_ago = '1 week ago';
			break;

		case 'month':
			$date_ago = '1 month ago';
			break;
	}

	// Keep it for further use (eg. for 3rd party plugins like WPP).
	$query_args['time_range'] = $time_range;

	if ( isset( $date_ago ) ) {
		$query_args['date_query'] = array(
			array(
				'after' => $date_ago,
			),
		);
	}

	return $query_args;
}

/**
 * Get predefined sidebars
 *
 * @return array
 */
function bimber_get_predefined_sidebars() {
	return array(
		'primary'      => array(
			'label' => esc_html_x( 'Primary', 'sidebar name', 'bimber' ),
		),
		'secondary'      => array(
			'label' => esc_html_x( 'Secondary', 'sidebar name', 'bimber' ),
		),
		'home'         => array(
			'label'       => esc_html_x( 'Home', 'sidebar name', 'bimber' ),
			'description' => esc_html__( 'Leave empty to use the Primary sidebar', 'bimber' ),
		),
		'home_2nd'         => array(
			'label'       => esc_html__( 'Home 2nd', 'bimber' ),
			'description' => esc_html__( 'Leave empty to use the Secondary sidebar', 'bimber' ),
		),
		'post_single'  => array(
			'label'       => esc_html_x( 'Single Post', 'sidebar name', 'bimber' ),
			'description' => esc_html__( 'Leave empty to use the Primary sidebar', 'bimber' ),
		),
		'post_archive' => array(
			'label'       => esc_html_x( 'Post Archives', 'sidebar name', 'bimber' ),
			'description' => esc_html__( 'For posts archive pages (categories, tags). Leave empty to use the Primary sidebar', 'bimber' ),
		),
		'post_archive_2nd' => array(
			'label'       => esc_html__( 'Post Archives 2nd', 'bimber' ),
			'description' => esc_html__( 'For posts archive pages (categories, tags). Leave empty to use the Secondary sidebar', 'bimber' ),
		),
		'page'         => array(
			'label'       => esc_html_x( 'Pages', 'sidebar name', 'bimber' ),
			'description' => esc_html__( 'Leave empty to use the Primary sidebar', 'bimber' ),
		),
		'footer-1'     => array(
			'label' => esc_html( sprintf( _x( 'Footer %d', 'sidebar name', 'bimber' ), 1) ),
		),
		'footer-2'     => array(
			'label' => esc_html( sprintf( _x( 'Footer %d', 'sidebar name', 'bimber' ), 2) ),
		),
		'footer-3'     => array(
			'label' => esc_html( sprintf( _x( 'Footer %d', 'sidebar name', 'bimber' ), 3) ),
		),
		'footer-4'     => array(
			'label' => esc_html( sprintf( _x( 'Footer %d', 'sidebar name', 'bimber' ), 4) ),
		),
	);
}

/**
 * Get nice name of a sidebar
 *
 * @param string $sidebar_id Sidebar identificator.
 *
 * @return mixed|string
 */
function bimber_get_nice_sidebar_name( $sidebar_id ) {
	$sidebar_name = str_replace( '-', ' ', $sidebar_id );

	// Split to single words.
	$parts = explode( ' ', $sidebar_name );

	// Each word with first letter capitalized.
	$parts = array_map( 'ucfirst', $parts );

	// Join to one string.
	$sidebar_name = implode( ' ', $parts );

	return $sidebar_name;
}

/**
 * Check whether the plugin is active and theme can rely on it
 *
 * @param string $plugin        Base plugin path.
 * @return bool
 */
function bimber_can_use_plugin( $plugin ) {
	// Detect plugin. For use on Front End only.
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	return is_plugin_active( $plugin );
}

function bimber_htmlspecialchars( $input ) {
    if ( $input ) {
        $input = htmlspecialchars( $input );
    }

    return $input;
}

/**
 * Empty theme related transients.
 */
function bimber_delete_transients() {
	delete_transient( 'bimber_featured_entries_query' );
	delete_transient( 'bimber_dont_miss_query' );
}

/**
 * Calculate hot posts.
 *
 * The list position is stored in the "_bimber_hot" post meta.
 *
 * @return array            Calculated post ids.
 */
function bimber_calculate_hot_posts() {
	delete_post_meta_by_key( '_bimber_hot' );

	$ids = array();

	$by = bimber_get_theme_option( 'posts', 'lists_ordered_by' );

	if ( 'views' === $by ) {
		$query_args = bimber_get_most_viewed_query_args( array(
			'posts_per_page' => bimber_get_hot_posts_index_limit(),
			'time_range'     => 'month',
		), 'hot' );

		foreach ( $query_args['post__in'] as $index => $post_id ) {
			$ids[] = $post_id;
		}
	}

	if ( 'votes' === $by ) {
		$ids = bimber_get_most_voted_posts( 'month', bimber_get_hot_posts_index_limit(), 'hot' );
	}

	// Update.
	foreach ( $ids as $index => $post_id ) {
		update_post_meta( $post_id, '_bimber_hot', $index + 1 );
	}

	return $ids;
}

/**
 * If list empty, calculate
 *
 * @param array $ids                    Current list of ids.
 * @param int   $limit                  Limit.
 *
 * @return array                        Calculated list.
 */
function bimber_calculate_hot_post_ids_if_empty( $ids, $limit ) {
	if ( empty( $ids ) ) {
		$ids = bimber_calculate_hot_posts();
	}

	return $ids;
}

/**
 * Calculate popular posts.
 *
 * The list position is stored in the "_bimber_popular" post meta.
 *
 * @return array    Calculated post ids.
 */
function bimber_calculate_popular_posts() {
	delete_post_meta_by_key( '_bimber_popular' );

	$ids = array();

	$by = bimber_get_theme_option( 'posts', 'lists_ordered_by' );

	if ( 'views' === $by ) {
		$query_args = bimber_get_most_viewed_query_args( array(
			'posts_per_page' => bimber_get_popular_posts_index_limit(),
		), 'popular' );

		foreach ( $query_args['post__in'] as $index => $post_id ) {
			$ids[] = $post_id;
		}
	}

	if ( 'votes' === $by ) {
		$ids = bimber_get_most_voted_posts( 'all', bimber_get_popular_posts_index_limit(), 'popular' );
	}

	// Update.
	foreach ( $ids as $index => $post_id ) {
		update_post_meta( $post_id, '_bimber_popular', $index + 1 );
	}

	return $ids;
}

/**
 * If list empty, calculate
 *
 * @param array $ids                    Current list of ids.
 * @param int   $limit                  Limit.
 *
 * @return array                        Calculated list.
 */
function bimber_calculate_popular_post_ids_if_empty( $ids, $limit ) {
	if ( empty( $ids ) ) {
		$ids = bimber_calculate_popular_posts();
	}

	return $ids;
}

/**
 * Calculate trending posts.
 *
 * The list position is stored in the "_bimber_popular" post meta.
 *
 * @return array    Calculated post ids.
 */
function bimber_calculate_trending_posts() {
	delete_post_meta_by_key( '_bimber_trending' );

	$ids = array();

	$by = bimber_get_theme_option( 'posts', 'lists_ordered_by' );

	if ( 'views' === $by ) {
		$query_args = bimber_get_most_viewed_query_args( array(
			'posts_per_page' => bimber_get_trending_posts_index_limit(),
			'time_range'     => 'day',
		), 'trending' );

		foreach ( $query_args['post__in'] as $index => $post_id ) {
			$ids[] = $post_id;
		}
	}

	if ( 'votes' === $by ) {
		$ids = bimber_get_most_voted_posts( 'day', bimber_get_trending_posts_index_limit(), 'trending' );
	}

	// Update.
	foreach ( $ids as $index => $post_id ) {
		update_post_meta( $post_id, '_bimber_trending', $index + 1 );
	}

	return $ids;
}

/**
 * If list empty, calculate
 *
 * @param array $ids                    Current list of ids.
 * @param int   $limit                  Limit.
 *
 * @return array                        Calculated list.
 */
function bimber_calculate_trending_post_ids_if_empty( $ids, $limit ) {
	if ( empty( $ids ) ) {
		$ids = bimber_calculate_trending_posts();
	}

	return $ids;
}

/**
 * Convers string (opt1,opt2,opt3) into bool array (array( opt1 => true ))
 *
 * @param string $string        Comma-separated list of elements.
 * @param array  $array         All elements.
 *
 * @return array
 */
function bimber_conver_string_to_bool_array( $string, $array ) {
	$string_arr = explode( ',', $string );

	foreach ( $array as $key => $value ) {
		if ( in_array( $key, $string_arr, true ) ) {
			$array[ $key ] = false;
		}
	}

	return $array;
}

/**
 * Returns list of avaliable templates for single post
 *
 * @return array
 */
function bimber_get_post_templates() {
	$uri = BIMBER_ADMIN_DIR_URI . 'images/templates/post/';

	return apply_filters( 'bimber_single_post_templates', array(
		'classic' => array(
			'label' => 'Classic',
			'path'  => $uri . 'classic.png',
			'image_sizes' => array( 'bimber-grid-2of3' ),
		),
		'classic-no-sidebar' => array(
			'label' => 'Classic, no sidebar',
			'path' => $uri . 'classic-no-sidebar.png',
			'image_sizes' => array( 'bimber-classic-1of1' ),
		),
		'classic-v2' => array(
			'label' => 'Classic v2',
			'path' => $uri . 'classic-v2.png',
			'image_sizes' => array( 'bimber-grid-2of3' ),
		),
		'classic-v3' => array(
			'label' => 'Classic v3',
			'path' => $uri . 'classic-v3.png',
			'image_sizes' => array( 'bimber-grid-2of3' ),
		),
		'classic-v3-no-sidebar' => array(
			'label' => 'Classic v3, no sidebar',
			'path' => $uri . 'classic-v3-no-sidebar.png',
			'image_sizes' => array( 'bimber-classic-1of1' ),
		),
		'media' => array(
			'label' => 'Media',
			'path'  => $uri . 'media.png',
			'image_sizes' => array( 'bimber-grid-2of3' ),
		),
		'media-no-sidebar' => array(
			'label' => 'Media, no sidebar',
			'path'  => $uri . 'media-no-sidebar.png',
			'image_sizes' => array( 'bimber-classic-1of1' ),
		),
		'media-v2' => array(
			'label' => 'Media v2',
			'path'  => $uri . 'media-v2.png',
			'image_sizes' => array( 'bimber-grid-2of3' ),
		),
		'background-stretched' => array(
			'label' => 'Background stretched',
			'path'  => $uri . 'background-stretched.png',
			'image_sizes' => array( 'full' ),
		),
		'background-stretched-no-sidebar' => array(
			'label' => 'Background stretched, no sidebar',
			'path'  => $uri . 'background-stretched-no-sidebar.png',
			'image_sizes' => array( 'full' ),
		),
		'background-stretched-v2' => array(
			'label' => 'Background stretched, v2',
			'path'  => $uri . 'background-stretched-v2.png',
			'image_sizes' => array( 'full' ),
		),
		'background-boxed' => array(
			'label' => 'Background boxed',
			'path'  => $uri . 'background-boxed.png',
			'image_sizes' => array( 'full' ),
		),
		'background-boxed-v2' => array(
			'label' => 'Background boxed, v2',
			'path'  => $uri . 'background-boxed-v2.png',
			'image_sizes' => array( 'full' ),
		),
		'overlay-stretched' => array(
			'label' => 'Overlay stretched',
			'path'  => $uri . 'overlay-stretched.png',
			'image_sizes' => array( 'full' ),
		),
		'overlay-stretched-no-sidebar' => array(
			'label' => 'Overlay stretched, no sidebar',
			'path'  => $uri . 'overlay-stretched-no-sidebar.png',
			'image_sizes' => array( 'full' ),
		),
		'overlay-stretched-v2' => array(
			'label' => 'Overlay stretched, v2',
			'path'  => $uri . 'overlay-stretched-v2.png',
			'image_sizes' => array( 'full' ),
		),
		'overlay-boxed' => array(
			'label' => 'Overlay boxed',
			'path'  => $uri . 'overlay-boxed.png',
			'image_sizes' => array( 'full' ),
		),
		'overlay-boxed-v2' => array(
			'label' => 'Overlay boxed, v2',
			'path'  => $uri . 'overlay-boxed-v2.png',
			'image_sizes' => array( 'full' ),
		),
	) );
}

/**
 * Return number of featured entries for passed template
 *
 * @param string $template_name		Template name.
 * @param int 	 $default			Default value.
 *
 * @return int
 */
function bimber_get_post_per_page_from_template( $template_name, $default = 3 ) {
	$result = $default;

	switch ( $template_name ) {
		case 'module-01':
		case '3-3v-3v-3v-3v-stretched':
		case '3-3v-3v-3v-3v-boxed':
			$result = 5;
			break;

		case '4-4-4-4-stretched':
		case '4-4-4-4-boxed':
		case 'todo-music':
			$result = 4;
			break;

		case 'todo-fashion':
			$result = 3;
			break;

		case '2-2-stretched':
		case '2-2-boxed':
			$result = 2;
			break;

		case '1-sidebar':
		case '1-sidebar-bunchy':
			$result = 1;
			break;
	}

	return $result;
}

/**
 * Render the featured media of the current post.
 *
 * @param array $args Arguments.
 */
function bimber_render_entry_featured_media( $args = array() ) {
	do_action( 'bimber_before_render_entry_featured_media', $args );

	add_filter( 'the_permalink', 'bimber_the_permalink' );

	if ( apply_filters( 'bimber_render_entry_featured_media', true, $args ) ) {
		echo bimber_capture_entry_featured_media( $args );
	}

	remove_filter( 'the_permalink', 'bimber_the_permalink' );

	do_action( 'bimber_after_render_entry_featured_media', $args );
}

/**
 * Capture the featured media of the current post.
 *
 * @param array $args Arguments.
 *
 * @return string       Escaped HTML
 */
function bimber_capture_entry_featured_media( $args ) {
	if ( post_password_required() || is_attachment( get_the_ID() ) ) {
		return '';
	}

	global $post;
	$post_format = get_post_format();

	$args = wp_parse_args( $args, array(
		'size'              => 'post-thumbnail',
		'class'				=> '',
		'use_microdata'     => false,
		'use_ellipsis'      => false,
		'use_sizer'         => true,
		'use_nsfw'          => true,
		'apply_link'        => true,
		'force_placeholder' => false,
		'show_caption'      => false,
		'allow_video'       => false,
		'allow_gif'		    => false,
		'thumbnail_id'		=> false,
	) );


	$args = apply_filters( 'bimber_entry_featured_media_args', $args );

	$media = '';
	$media_type = 'image';

	$container_style_attr = '';
	$container_extra_atts = array();

	//$inner_style_escaped = '';

	$use_ellipsis = false;
	$has_nsfw_link = false;

	$final_class = array(
		'entry-featured-media',
	);

	// Handle NSFW.
	$has_nsfw = $args['use_nsfw'] && bimber_is_nsfw() && ! is_category( bimber_get_nsfw_categories() );
	if ( $has_nsfw ) {
		$final_class[] = 'entry-media-nsfw';
	}

	// Use embed as media.
	$embed_supported_format = in_array( $post_format, array( 'audio', 'video' ) );
	$embed_supported_format = apply_filters( 'bimber_featured_media_embeds_supported_format', $embed_supported_format );

	$allow_embed = $args['allow_video'] && $embed_supported_format;
	$allow_embed = apply_filters( 'bimber_capture_entry_featured_media_embeds', $allow_embed );

	if ( $allow_embed ) {
		// Save $content_width value for later.
		global $content_width;
		$old_content_width = $content_width;

		// Make the $content_width equal to the image size width.
		global $_wp_additional_image_sizes;
		if ( isset( $_wp_additional_image_sizes[ $args['size'] ] ) ) {
			$content_width = $_wp_additional_image_sizes[ $args['size']]['width'];
		}

		// Get embed.
		$media = bimber_get_the_post_embed( $args['size'] );

		// Revert $content_width value.
		$content_width = $old_content_width;

		if ( ! empty( $media ) ) {
			$media_type = 'embed';

			if ( $has_nsfw ) {
				$has_nsfw_link = true;
				$final_class[] = 'entry-media-nsfw';
				$final_class[] = 'entry-media-nsfw-embed';
			}

			if ( $args['use_microdata'] ) {
				//$container_extra_atts['itemprop'] = 'video';
				//$container_extra_atts['itemscope'] = '';
				//$container_extra_atts['itemtype'] = 'http://schema.org/VideoObject';
			}
		}
	}

	// Use image as media.
	if ( 'image' == $media_type ) {
		$has_post_thumbnail = $args['thumbnail_id'] || has_post_thumbnail();

		if ( $has_post_thumbnail ) {
			if ( $args['use_microdata'] ) {
				$media .= get_the_post_thumbnail( null, $args['size'], array( 'itemprop' => 'contentUrl' ) );
				$container_extra_atts['itemprop'] = 'image';
				$container_extra_atts['itemscope'] = '';
				$container_extra_atts['itemtype'] = 'http://schema.org/ImageObject';
			} else {
				$media .= get_the_post_thumbnail( null, $args['size'] );
			}
		}
	}


	if ( empty( $media ) ) {
		if ( $args['force_placeholder'] ) {
			$media_type = 'placeholder';
		} else {
			return '';
		}
	}


	if ( 'image' === $media_type ) {
		// Get thumbnail to display.
		$post_thumbnail_id = $args['thumbnail_id'] ? $args['thumbnail_id'] : get_post_thumbnail_id( get_the_ID() );

		$thumb = wp_get_attachment_image_src( $post_thumbnail_id, $args['size'] );

		if ( is_array( $thumb ) ) {
			$thumb_width  = absint( $thumb[1] );
			$thumb_height = absint( $thumb[2] );

			if ( $thumb_width > 0 ) {
				// Use ellipsis.
				if ( $args['use_ellipsis'] ) {
					$ratio = 0 !== $thumb_width ? $thumb_height / $thumb_width : 0;
					$use_ellipsis = $ratio > ( 3 / 1 ) ? true : false;
				}

				//// Use sizer. Prevent image loading jump.
				//if ( true === $args['use_sizer'] ) {
				//	$inner_style_escaped = ' style="padding-bottom: ' . sprintf( "%.8F", $thumb_height / $thumb_width * 100 ) . '%;"';
				//}
			}
		}

		if ( $use_ellipsis ) {
			$final_class[] = 'entry-media-with-ellipsis';
		}
	}

	if ( 'placeholder' === $media_type ) {
		global $_wp_additional_image_sizes;

		$thumb_width = 1600;
		$thumb_height = 900;

		if ( isset( $_wp_additional_image_sizes[$args['size'] ] ) ) {
			//// 9999 means infinite height :)
			if ( 9999 !== $_wp_additional_image_sizes[$args['size']]['height'] ) {
				$thumb_width = $_wp_additional_image_sizes[$args['size']]['width'];
				$thumb_height = $_wp_additional_image_sizes[$args['size']]['height'];
			}
		}

		// Use sizer. Prevent image loading jump.
		//if ( $args['use_sizer'] ) {
			//$inner_style_escaped = ' style="padding-bottom: ' . sprintf( "%.8F", $thumb_height / $thumb_width * 100 ) . '%;"';
		//}
	}

	// Merge all classes.
	$final_class = array_merge( $final_class, explode( ' ', $args['class'] ) );

//	if ( $args['allow_gif'] ) {
//		// GIF has to be served in original size so GIF player can be loaded on it.
//		if ( is_array( $image ) && false !== strpos( $image[0], '.gif' )) {
//			$args['size'] = 'full';
//		}
//	}

	do_action( 'bimber_before_capture_entry_featured_media', $args );

	// Start rendering.
	$out_escaped = '';

	// Workaround for Elementor Page Builder with its lousy figure margins.
	$container_tag = in_array( 'entry-feature-media-main', $final_class, true ) ? 'figure' : 'div';

	// Open container.
	$out_escaped .= '<' . $container_tag;
	$out_escaped .= ' class="' . implode( ' ', array_map( 'sanitize_html_class', $final_class ) ) . '"';

	// Render style attribute;
	$out_escaped .= ' ' . $container_style_attr;

	// Render extra figure attributes.
	foreach ( $container_extra_atts as $attr_name => $attr_value ) {
		$out_escaped .= ' ' . $attr_name . '="' . esc_attr( $attr_value ) . '" ';
	}
	$out_escaped .= '>';

	if ( in_array( $media_type, array( 'image', 'placeholder'), true ) ) {
		// Open .g1-frame container.
		if ( $args['apply_link'] ) {
			$link_data = apply_filters( 'bimber_entry_featured_media_link_data', array(
				'classes' => array(
					'g1-frame',
				),
				'target' => '',
			), $post );

			$target = '';

			if ( ! empty( $link_data['target'] ) ) {
				$target = sprintf( ' target="%s"', $link_data['target'] );
			}

			$out_escaped .= '<a title="' . the_title_attribute( 'echo=0' ) . '" class="' . implode( ' ', array_map( 'sanitize_html_class', $link_data['classes'] ) ) . '" href="' . esc_url( apply_filters( 'the_permalink', get_permalink( $post ), $post ) ) . '"'.  $target.'>';
		} else {
			$out_escaped .= '<div class="g1-frame">';
		}

		// Open .g1-frame-inner container.
		$out_escaped .= '<div class="g1-frame-inner">';
	}

	// Render media.
	$out_escaped .= $media;

	// Render frame icon.
	if ( 'image' === $media_type ) {
		$has_frame_icon = true;

		switch ( $post_format ) {
			case 'video':
				$has_frame_icon = (bool) bimber_get_theme_option( 'post_video', 'frame_icon' );
				break;

			case 'link':
				$has_frame_icon = (bool) bimber_get_theme_option( 'post_link', 'frame_icon' );
				break;

			case 'gallery':
				$has_frame_icon = (bool) bimber_get_theme_option( 'post_gallery', 'frame_icon' );
				break;

			case 'audio':
				$has_frame_icon = true;
				break;

			default:
				break;
		}

		if ( $has_frame_icon ) {
			$post_format = apply_filters( 'bimber_get_post_format_for_icon', $post_format, $post );

			$out_escaped .= '<span class="g1-frame-icon g1-frame-icon-' . sanitize_html_class( $post_format ) .'">';
			if ( 'gallery' === $post_format ) {
				$out_escaped .= bimber_get_post_gallery_media_count( $post );
			}

			$out_escaped .= '</span>';
		}
	}

	// Render video length.
	if ( 'image' === $media_type && 'video' === $post_format ) {
		$video_length = bimber_get_post_video_length( $post );

		if ( ! empty( $video_length ) ) {
			$out_escaped .= '<span class="mace-video-duration">';
			$out_escaped .= $video_length;
			$out_escaped .= '</span>';
		}
	}

	// Render NSFW.
	if ( $has_nsfw ) {
		if ( $has_nsfw_link ) {
			$out_escaped.= '<a class="g1-nsfw" href="' . esc_url( apply_filters( 'the_permalink', get_permalink( $post ), $post ) ) . '">';
		} else {
			$out_escaped .= '<div class="g1-nsfw">';
		}

		$out_escaped .= '<div class="g1-nsfw-inner">';
		$out_escaped .= '<i class="g1-nsfw-icon"></i>';
		$out_escaped .= '<div class="g1-nsfw-title g1-delta g1-delta-1st">' . __( 'Not Safe For Work', 'bimber' ) .  '</div>';
		$out_escaped .= '<div class="g1-nsfw-desc g1-meta">' . __( 'Click to view this post', 'bimber' ) .  '</div>';
		$out_escaped .= '</div>';

		if ( $has_nsfw_link ) {
			$out_escaped .= '</a>';
		} else {
			$out_escaped .= '</div>';
		}
	}

	if ( in_array( $media_type, array( 'image', 'placeholder'), true ) ) {
		// Close .g1-frame-inner container.
		$out_escaped .= '</div>';

		// Close .g1-frame container.
		$out_escaped .= $args['apply_link'] ? '</a>' : '</div>';
	}

	// Render microdata.
	if ( $args['use_microdata'] ) {
	    $post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );

	    if ( $post_thumbnail_id ) {
            // Get image to use in microdata.
            $image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );

            if ( ! empty( $image ) ) {
                $out_escaped .= '<meta itemprop="url" content="' . esc_url( $image[0] ) .  '" />';
                $out_escaped .= '<meta itemprop="width" content="' . intval( $image[1] ) .  '" />';
                $out_escaped .= '<meta itemprop="height" content="' . intval( $image[2] ) .  '" />';
            }
        }
	}

	// Render ellipsis.
	if ( $use_ellipsis ) {
		$out_escaped .= '<div class="entry-media-ellipsis">';
		$out_escaped .= '<a class="g1-button g1-button-xs g1-button-solid" href="' .  esc_url( apply_filters( 'the_permalink', get_permalink( $post ), $post ) ) . '">'  . esc_html__( 'View full post', 'bimber' ) .  '</a>';
		$out_escaped .= '</div>';
	}

	// Render caption.
	if ( $args['show_caption'] ) {
		$thumb_id = get_post_thumbnail_id( get_the_ID() );
		$thumb_img = get_post( $thumb_id );
		$thumb_caption = isset( $thumb_img->post_excerpt ) ? $thumb_img->post_excerpt : '';

		if ( strlen( $thumb_caption ) ) {
			$out_escaped .= '<figcaption class="wp-caption-text">';
			$out_escaped .= wp_kses_post( $thumb_caption );
			$out_escaped .= '</figcaption>';
		}
	}

	// Close figure container.
	$out_escaped .= '</' . $container_tag  . '>';

	do_action( 'bimber_after_capture_entry_featured_media', $args );

	return apply_filters( 'bimber_capture_entry_featured_media', $out_escaped, $args, $post );
}


function bimber_get_the_post_embed( $poster_size, $post = null ) {
	$post = get_post( $post );

	$url = bimber_get_first_url_in_content( $post );

	$html = apply_filters( 'bimber_the_post_embed', '', $url, $post );

	// Short circuit.
	if ( ! empty( $html ) ) {
	    return $html;
    }

    /**
     * Find first Gutenberg embed block and check if it contains the first URL.
     *
     * Regexp modifiers:
     * i - case insensitive
     * s - include new lines
     * U - ungreedy, stop on first match. Don't look to the end of string.
     */
    if ( preg_match( '/<!-- wp:core-embed.*<!-- \/wp:core-embed[^\s]+ -->/isU', $post->post_content, $matches ) ) {
        $wp_embed_block = $matches[0];

        // Remove entire block if contains url.
        if ( false !== strpos( $wp_embed_block, $url ) ) {
            // Strip comments to get the <figure> only.
            $html = bimber_strip_html_comments( $wp_embed_block );

            $html = str_replace( array(
                '<figure',
                '</figure>'
            ), array(
                '<div',
                '</div>'
            ), $html );

            global $wp_embed;

            $embed = $wp_embed->shortcode( array(), $url );

            // Check if valid string, may return false.
            if ( ! empty( $embed ) ) {
                $html = str_replace( $url, $embed, $html );
            }

            return $html;
        }
    }

    // If the first URL is not wrapped into the Gutenbergs embed block, process it like an embed URL.śś
	if ( ! empty( $url ) ) {
		global $wp_embed;

		$embed = $wp_embed->shortcode( array(), $url );

		// Check if valid string, may return false.
		if ( ! empty( $embed ) ) {
			// Converted to shortcode ([audio], [video])?
			if ( false !== strpos( $embed, '[' ) ) {
				if ( has_post_thumbnail( $post ) ) {
					$poster_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), $poster_size );
					$poster_url  = isset( $poster_data[0] ) ? $poster_data[0] : '';

					$embed = str_replace(']', ' poster="' . $poster_url . '"]', $embed);
				}

				$embed = do_shortcode( $embed );
			}

			$html = $embed;
		}
	}

	return $html;
}

/**
 * Checks whether the post is NSFW
 *
 * @return bool
 */
function bimber_is_nsfw() {
	$bool = false;

	if ( bimber_get_theme_option( 'nsfw', 'enabled' ) ) {
		$nsfw_categories = bimber_get_nsfw_categories();

		if ( ! empty( $nsfw_categories ) && has_category( $nsfw_categories ) ) {
			$bool = true;
		}
	}

	return apply_filters( 'bimber_is_nsfw', $bool );
}

/**
 * Return ids of categories for NSFW posts.
 *
 * @return array		Array of ids.
 */
function bimber_get_nsfw_categories() {
	$ids = array();
	$slugs = explode( ',', bimber_get_theme_option( 'nsfw', 'categories_ids' ) );

	foreach ( $slugs as $slug ) {
		$category = get_category_by_slug( $slug );

		if ( $category ) {
			$ids[] = $category->term_id;
		}
	}

	return $ids;
}

/**
 * Return list of registered comment types
 *
 * @return array
 */
function bimber_get_comment_types() {
	return apply_filters( 'bimber_comment_types', array() );
}

// @DEBUG
add_action( 'in_admin_footer', 'bimber_render_error_log_in_footer' );

function bimber_render_error_log_in_footer() {
	$log_enabled = filter_input( INPUT_GET, 'bimber-show-log' );

	if ( is_null( $log_enabled ) ) {
		return;
	}

	$log = get_transient( 'bimber_theme_register_error_log' );

	var_dump($log);
}

function bimber_register_theme( $purchase_code ) {
	// Register without checking.
	$referer = wp_get_referer();

	if ( false !== strpos( $referer, 'bimber-skip-api' ) ) {
		update_option( 'envato_purchase_code_14493994', $purchase_code );
		return true;
	}

	$api_endpoint = 'https://api.bringthepixel.com/wp-json/bimber/v1/purchase/';

	//add_filter( 'https_ssl_verify', '__return_false' );

	$error_log = array(
		'test_run_at'       => date( 'Y-m-d H:i:s' ),
		'purchase_code'     => $purchase_code,
		'server_ip'         => $_SERVER['SERVER_ADDR'],
	);
	$get_response = false;

	// POST request.
	$response = wp_remote_post( $api_endpoint . $purchase_code, array(
		'method'    => 'POST',
		'timeout'   => 30,
		'body' => array(
			'domain' => home_url(),
		),
	) );

	// Log POST error.
	if ( is_wp_error( $response ) ) {
		$error_log['post_error'] = array(
			'code'      => $response->get_error_code(),
			'message'   => $response->get_error_message(),
		);
	}

	// If POST failed, try GET.
	if ( is_wp_error( $response ) ) {
		// GET request.
		$get_response = wp_remote_get( $api_endpoint . $purchase_code, array(
			'timeout'   => 30,
			'body' => array(
				'domain' => home_url(),
			),
		) );
	}

	// Log GET error.
	if ( is_wp_error( $get_response ) ) {
		$error_log['get_error'] = array(
			'code'      => $get_response->get_error_code(),
			'message'   => $get_response->get_error_message(),
		);
	}

	// Test connection.
	$file_out = file_get_contents( 'https://api.bringthepixel.com/license.txt' );
	$error_log['test_connection'] = $file_out ? 'succeed' : 'failed';

//	$file_out = file_get_contents( 'https://avadatheme.info/license.txt' );
//	$error_log['test_connection_2'] = $file_out ? 'succeed' : 'failed';

	// Log even if not errors.
	set_transient( 'bimber_theme_register_error_log', $error_log );

	if ( is_wp_error( $response ) || is_wp_error( $get_response ) ) {
		return new WP_Error( 'bringthepixel_api_error', "There is a problem contacting the BringThePixel server. Automatic registration is not possible. Please use the Token Registration form below." );
	}

	$response_code = wp_remote_retrieve_response_code( $response );

	if ( 200 !== $response_code ) {
		$response_data = json_decode( wp_remote_retrieve_body( $response ), true );

		return new WP_Error( $response_data['code'], $response_data['message'] . ' Automatic registration is not possible. Please use the Token Registration form below.' );
	}

	// Code is valid.
	update_option( 'envato_purchase_code_14493994', $purchase_code );

	return true;
}

function bimber_deregister_theme() {
	delete_option( 'envato_purchase_code_14493994' );

	return true;
}

function bimber_is_theme_registered() {
	$purchase_code =  bimber_get_registered_purchase_code();
	$registered_by_purchase_code =  ! empty( $purchase_code );

	// Purchase code entered correctly.
	if ( $registered_by_purchase_code ) {
		return true;
	}

	// Backward compatibility.
	$registered_by_token = get_transient( 'bimber_theme_registered' );

	// Token entered correctly.
	if ( $registered_by_token ) {
		return true;
	}

	// Can't verify.
	if ( ! bimber_can_use_plugin( 'envato-market/envato-market.php' ) ) {
		return false;
	}

	$purchased_themes = envato_market()->api()->themes();

	foreach ( $purchased_themes as $purchased_theme ) {
		if ( bimber_get_theme_name() === strtolower( $purchased_theme['name'] ) ) {
			$registered_by_token = true;
		}
	}

	if ( $registered_by_token ) {
		$expire_in_one_day = 60 * 60 * 24;

		// Theme is active for next 24h. Then next check will be performed (user eg. got a refund).
		set_transient( 'bimber_theme_registered', true, $expire_in_one_day );
	}

	return $registered_by_token;
}

function bimber_get_registered_purchase_code() {
	return get_option( 'envato_purchase_code_14493994' );
}

/**
 * Whether there is auto load template url var set
 *
 * @return bool
 */
function bimber_is_auto_load() {
	$auto_load_template = bimber_htmlspecialchars( filter_input( INPUT_GET, 'bimber_auto_load_next_post_template' ) );
	if ( $auto_load_template ) {
		return true;
	}
}

/**
 * Whether we're in autoload with no sidebar.
 *
 * @return bool
 */
function bimber_is_auto_load_no_sidebar() {
	$auto_load_template = bimber_htmlspecialchars( filter_input( INPUT_GET, 'bimber_auto_load_next_post_template' ) );
	if ( 'row' === $auto_load_template ) {
		return true;
	}
}

/**
 * Change video shortcode attributes to better match the content width
 *
 * @param string $out Markup.
 * @param array  $pairs Entire list of supported attributes and their defaults.
 * @param array  $atts User defined attributes in shortcode tag.
 * @param string $shortcode Shortcode name.
 *
 * @return mixed
 */
function bimber_wp_video_shortcode_atts( $out, $pairs, $atts ) {
	global $content_width;
	$width  = $out['width'];
	$height = $out['height'];

	$out['width']  = $content_width;
	if ( $width > 0 ) {
		$out['height'] = round( $content_width * $height / $width );
	}

	return $out;
}

/**
 * Render footer text
 */
function bimber_render_footer_text() {
	$bimber_footer_text = bimber_get_theme_option( 'footer', 'text' );

	// Automatic date (eg. usage @ %%Y%%).
	if ( preg_match( '/%%([^%]+)%%/', $bimber_footer_text, $date_matches ) ) {
		$bimber_footer_text = str_replace( $date_matches[0], date( $date_matches[1] ), $bimber_footer_text );
	}

	echo wp_kses_post( $bimber_footer_text );
}


/**
 * Renders microdata attributes.
 *
 * @param array $r Array of attributes
 */
function bimber_render_microdata( $r ) {
	if ( bimber_get_support( 'microdata' ) ) {
		foreach( $r as $key => $value ) {
			echo $key . '="' . esc_attr( $value ) . '" ';
		}
	}
}

function bimber_get_support( $feature ) {
	return apply_filters( 'bimber_get_support', $feature, true );
}



/**
 * Get attachment by id.
 *
 * @param string $url attachment url.
 * @return string|bool attachment id or false
 */
function bimber_get_attachment_id_by_url( $url ) {
	$attachment_id = false;

	$upload_dir_baseurl = bimber_get_uploads_url();

	if ( false !== strpos( $url, $upload_dir_baseurl ) ) {
		// Strip upload dir part, leaving path like this 2018/03/file.jpg.
		$file = str_replace( $upload_dir_baseurl, '', $url );

		global $wpdb;
		$post = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%s';", '%' . $file ));

		if ( ! empty( $post ) ) {
			$attachment_id = $post[0];
		}
	}

	return $attachment_id;
}

/**
 * Get current global style.
 *
 * @return string
 */
function bimber_get_current_stack() {
	return bimber_get_theme_option( 'global', 'stack' );
}

/**
 * On theme activation (after updates too), show all TGMPA notices.
 */
function bimber_reset_tgm_notices() {
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_snax', null, true ); 	// Snax.
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_tgmpa', null, true );	// Bimber.
}

/**
 * Hide elements that should be visible only for highlighted items
 *
 * @param array $elements       List of elements.
 *
 * @return array
 */
function bimber_hide_highlighted_elements( $elements ) {
	foreach ( $elements as $element_id => $visibility ) {
		if ( 'highlighted' === $visibility ) {
			$elements[ $element_id ] = false;
		}
	}

	return $elements;
}
