<?php
/**
 * Mashshare plugin functions
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

/*
 * Mashshare.
 */

// Only core loaded.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) ) {
	add_action( 'bimber_render_top_share_buttons', 		'bimber_mashsharer_render_top_share_buttons' );
	add_action( 'bimber_render_bottom_share_buttons',	'bimber_mashsharer_render_bottom_share_buttons' );
	add_action( 'bimber_render_side_share_buttons', 	'bimber_mashsharer_render_side_share_buttons' );
	add_action( 'bimber_render_compact_share_buttons', 	'bimber_mashsharer_render_compact_share_buttons' );
	add_action( 'bimber_render_mini_share_buttons', 	'bimber_mashsharer_render_mini_share_buttons' );

	$mashsharer_execution_order = 1000;

	if ( function_exists( 'getExecutionOrder' ) ) {
		$mashsharer_execution_order = getExecutionOrder();
	}

	remove_filter( 'the_content', 'mashshare_filter_content', $mashsharer_execution_order, 1 );

	add_filter( 'bimber_most_shared_query_args',    'bimber_mashsharer_get_most_shared_query_args', 10, 2 );
	add_filter( 'bimber_entry_share_count',         'bimber_mashsharer_get_share_count' );
	add_filter( 'bimber_show_entry_share_count',    'bimber_mashsharer_show_share_count', 10, 2 );
	add_filter( 'mashsb_opengraph_meta' , 'bimber_mashsharer_fix_empty_og_description' );
	add_action( 'bimber_after_import_content',      'bimber_mashsharer_set_defaults' );

	add_filter( 'mashsb_opengraph_meta', 'bimber_mashsharer_gif_opengraph' ,100,1 );

	// Custom caching rules to not refresh counters on archives.
	// Curl requests coast too much, so reload cache only on a single page.
	if ( ! is_admin() ) {
		add_action( 'init',         'bimber_mashsharer_init_custom_caching_rules' );
		add_filter( 'the_content',  'bimber_mashsharer_activate_curl', 1 );
		add_filter( 'the_content',  'bimber_mashsharer_deactivate_curl', 9999 );
	}

	add_filter( 'bimber_archive_filters', 'bimber_mashsharer_add_most_shares_filter', 10, 1 );
	add_action( 'bimber_apply_archive_filter_most_shares', 'bimber_mashsharer_apply_archive_filter_most_shares', 10, 1 );

	add_action( 'woocommerce_share', 'bimber_mashsharer_add_shares_to_single_product' );

	add_filter( 'post_bottom_share_buttons_active', '__return_true' );
	add_filter( 'bimber_post_sharebar', '__return_true' );
	add_filter( 'bimber_show_sharebar', '__return_true' );
}

// Core loaded but not Networks addon.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) && ! bimber_can_use_plugin( 'mashshare-networks/mashshare-networks.php' ) ) {
	add_filter( 'mashsb_array_networks',    'bimber_mashsharer_array_networks' );
	add_action( 'init',                     'bimber_mashsharer_register_new_networks' );
	add_action( 'plugins_loaded',           'bimber_mashsharer_add_networks_class' );
}

// Core and Networks addon loaded.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) && bimber_can_use_plugin( 'mashshare-networks/mashshare-networks.php' ) ) {
	add_action( 'init', 'bimber_mashsharer_deregister_new_networks' );
}

// Core and ShareBar addon loaded.
if ( bimber_can_use_plugin( 'mashsharer/mashshare.php' ) && bimber_can_use_plugin( 'mashshare-sharebar/mashshare-sharebar.php' ) ) {
	// Disable our built-in bar.
	add_filter( 'bimber_show_sharebar', '__return_false', 99 );
}

add_action( 'after_setup_theme', 'bimber_mashsharer_disable_plugin_welcome_redirect' );

function bimber_mashsharer_disable_plugin_welcome_redirect() {
	if ( get_transient( '_bimber_demo_import_started' ) ) {
		delete_transient( '_mashsb_activation_redirect' );
	}
}

add_action( 'wp', 'bimber_mashsharer_amp_hooks' );

/**
 * AMP specific hooks.
 */
function bimber_mashsharer_amp_hooks() {
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		// Remove empty style attribute.
		add_filter( 'mashsb_output_buttons', 'bimber_amp_remove_empty_style_attribute', 99 );
	}
}

/**
 * Return share count from Mashsharer
 *
 * @return int
 */
function bimber_mashsharer_get_share_count() {
	$url    = mashsb_get_url();
	$number = getSharedcount( $url );

	return $number;
}

/**
 * Check whether to show or now share count
 *
 * @param bool $show            Current state.
 * @param int  $share_count     Number of shares.
 *
 * @return bool
 */
function bimber_mashsharer_show_share_count( $show, $share_count ) {
	$settings = mashsb_get_settings();

	$share_count_threshold = isset( $settings['hide_sharecount'] ) ? $settings['hide_sharecount'] : 0;

	if ( absint( $share_count ) < absint( $share_count_threshold ) ) {
		$show = false;
	}

	return $show;
}


/**
 * Get query arguments for the most shared collection
 *
 * @param array $query_args Query arguments.
 *
 * @return array
 */
function bimber_mashsharer_get_most_shared_query_args( $query_args ) {
	$defaults = array(
		'posts_per_page'      => 10,
		'ignore_sticky_posts' => true,
		'meta_key'            => 'mashsb_shares',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		// This way we can be sure that only "shared" posts will be used.
		'meta_query'          => array(
			array(
				'key'     => 'mashsb_shares',
				'compare' => '>',
				'value'   => 0,
			),
		),
	);

	$query_args = wp_parse_args( $query_args, $defaults );

	return $query_args;
}

/**
 * Add custom newtworks for Mashsharer
 *
 * @param array $networks       Available network list.
 *
 * @return array
 */
function bimber_mashsharer_array_networks( $networks ) {
	$image     = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
	$image_url = $image ? $image[0] : '';

	if ( ! isset( $networks['pinterest'] ) ) {
		$networks['pinterest'] = 'https://www.pinterest.com/pin/create/button/?url=' . $networks['url'] . '&media=' . $image_url . '&description=' . $networks['title'];
	}

	if ( ! isset( $networks['google'] ) ) {
		$networks['google'] = 'https://plus.google.com/share?url=' . $networks['url'];
	}

	return $networks;
}

/**
 * Init custom caching rules.
 */
function bimber_mashsharer_init_custom_caching_rules() {
	if ( false === apply_filters( 'bimber_mashsharer_custom_cache', true ) ) {
		return;
	}

	global $mashsb_options;

	if ( isset( $mashsb_options ) ) {
		$default_cache = isset( $mashsb_options['mashsharer_cache'] ) ? $mashsb_options['mashsharer_cache'] : 21600; // 6h, from theme defaults.

		// Store default and new values for further use.
		$mashsb_options['mashsharer_default_cache'] = $default_cache;
		$mashsb_options['mashsharer_custom_cache'] = 2592000; // One month.

		bimber_mashsharer_enable_custom_caching_rules();
	}
}

/**
 * Enable custom caching rules.
 */
function bimber_mashsharer_enable_custom_caching_rules() {
	bimber_mashsharer_switch_cache( 'mashsharer_custom_cache' );
}

/**
 * Enable custom caching rules.
 */
function bimber_mashsharer_disable_custom_caching_rules() {
	bimber_mashsharer_switch_cache( 'mashsharer_default_cache' );
}

/**
 * Change cache value by type
 *
 * @param string $type         Cache type: mashsharer_custom_cache | mashsharer_default_cache.
 */
function bimber_mashsharer_switch_cache( $type ) {
	global $mashsb_options;

	if ( isset( $mashsb_options ) && isset( $mashsb_options[ $type ] ) ) {
		$mashsb_options['mashsharer_cache'] = $mashsb_options[ $type ];
	}
}

/**
 * Activate remote calls (for fetching current shares count) when entering singe post content.
 *
 * @param string $content           Post content.
 *
 * @return string
 */
function bimber_mashsharer_activate_curl( $content ) {
	if ( apply_filters( 'bimber_mashsharer_custom_cache', true ) && is_single() ) {
		bimber_mashsharer_disable_custom_caching_rules();
	}

	return $content;
}

/**
 * Deactivate remote calls when leaving singe post content.
 *
 * @param string $content           Post content.
 *
 * @return string
 */
function bimber_mashsharer_deactivate_curl( $content ) {
	if ( apply_filters( 'bimber_mashsharer_custom_cache', true ) && is_single() ) {
		bimber_mashsharer_enable_custom_caching_rules();
	}

	return $content;
}

/**
 * Register custom networks
 */
function bimber_mashsharer_register_new_networks() {
	$networks = get_option( 'mashsb_networks' );

	// These networks can be already added by addon.
	if ( ! in_array( 'Pinterest', $networks, true ) ) {
		$networks[] = 'Pinterest';
	}

	if ( ! in_array( 'Google', $networks, true ) ) {
		$networks[] = 'Google';
	}

	update_option( 'mashsb_networks', $networks );
}

/**
 * Deregister custom networks
 */
function bimber_mashsharer_deregister_new_networks() {
	$networks = get_option( 'mashsb_networks' );

	$custom_pinterest 	= isset( $networks[3] ) && 'Pinterest' === $networks[3];
	$custom_google 		= isset( $networks[4] ) && 'Google' === $networks[4];

	if ( $custom_pinterest && $custom_google ) {
		unset( $networks[3] );
		unset( $networks[4] );

		// Now should be only 3 networks loaded: FB, Twitter, Subscirbe.
		// But during Mashshare Networks addon activation new networks (e.g. Telegram) are added unconditionally
		// so we need to remove it here. It'll be added again after the call MashshareNetworks::mashnet_during_activation();

		// Telegram.
		$telegram_added = isset( $networks[5] ) && 'Telegram' === $networks[5];

		if ( $telegram_added ) {
			unset( $networks[5] ); // Telegram.
		}

		// Flipboard, overrides Telegram so index is also 5 here.
		$flipboard_added = isset( $networks[5] ) && 'Flipboard' === $networks[5];

		if ( $flipboard_added ) {
			unset( $networks[5] ); // Flipboard.
		}

		// Hackernews.
		$hackernews_added = isset( $networks[6] ) && 'Hackernews' === $networks[6];

		if ( $hackernews_added ) {
			unset( $networks[6] ); // Hackernews.
		}

		// Update db.
		update_option( 'mashsb_networks', $networks );

		// Run activation function again to load addon networks.
		MashshareNetworks::mashnet_during_activation();
	}
}

function bimber_mashsharer_add_networks_class() {
	if ( ! class_exists( 'MashshareNetworks' ) ) {
		/**
		 * Class MashshareNetworks
		 */
		class MashshareNetworks {
			// This class is required to enable custom networks counting.
			// It won't be used if "MashshareNetworks" add-on is installed.
		}
	}
}

/**
 * Set default option values for fresh MashShare plugin installations.
 */
function bimber_mashsharer_set_defaults() {
	$settings = get_option( 'mashsb_settings', array() );

	// Skip if already set.
	if ( ! empty( $settings ) ) {
		return;
	}

	$defaults = array(
		'mashsharer_cache'    => '21600', // 6 hours.
		'hide_sharecount'     => '1',
		'text_align_center'   => '1',
		'mashsharer_round'    => '1',
		'subscribe_behavior'  => 'link',
		'mashsharer_position' => 'both',
		'post_types'          => array(
			'post'      => 'post',
			'snax_item' => 'snax_item',
			'snax_quiz' => 'snax_quiz',
			'snax_poll' => 'snax_poll',
		),
		'visible_services'    => '1',
		'networks'            => array(
			// Facebook.
			array(
				'id'     => 'facebook',
				'status' => '1',
				'name'   => '',
			),
			// Twitter.
			array(
				'id'     => 'twitter',
				'status' => '1',
				'name'   => '',
			),
			// Subscribe.
			array(
				'id'     => 'subscribe',
				'status' => '1',
				'name'   => '',
			),
			// Pinterest.
			array(
				'id'     => 'pinterest',
				'status' => '1',
				'name'   => '',
			),
			// Google.
			array(
				'id'     => 'google',
				'status' => '1',
				'name'   => '',
			),
		),
	);

	update_option( 'mashsb_settings', $defaults );
}

/**
 * Check whether we can display share buttons on the post type ($post_id)
 *
 * @param int $post_id          Post id or object.
 *
 * @return bool
 */
function bimber_mashsharer_post_type_supported( $post_id = 0 ) {
    global $mashsb_options;

    $supported_post_types = ! empty( $mashsb_options['post_types'] ) ? $mashsb_options['post_types'] : array();

    $post_type = get_post_type( $post_id );

    return in_array( $post_type, $supported_post_types );
}

/**
 * Render social sharing buttons before the content.
 */
function bimber_mashsharer_render_top_share_buttons() {
	global $mashsb_options;

	// Check post type support.
    if ( ! bimber_mashsharer_post_type_supported() ) {
        return;
    }

	// Default position.
	$position = ! empty( $mashsb_options['mashsharer_position'] ) ? $mashsb_options['mashsharer_position'] : '';

	// Check if we have a post meta setting which overrides the global position than we use that one instead.
	if ( true === ( $position_meta = mashsb_get_post_meta_position() ) ) {
		$position = $position_meta;
	}

	if ( in_array( $position, array( 'before', 'both' ), true ) ) {
		echo str_replace( 'mashsb-main', 'mashsb-main mashsb-stretched', do_shortcode( '[mashshare]' ) );
	}
}

/**
 * Render social sharing buttons after the content.
 */
function bimber_mashsharer_render_bottom_share_buttons() {
	global $mashsb_options;

    // Check post type support.
    if ( ! bimber_mashsharer_post_type_supported() ) {
        return;
    }

	// Default position.
	$position = ! empty( $mashsb_options['mashsharer_position'] ) ? $mashsb_options['mashsharer_position'] : '';

	// Check if we have a post meta setting which overrides the global position than we use that one instead.
	if ( true === ($position_meta = mashsb_get_post_meta_position() ) ) {
		$position = $position_meta;
	}

	if ( in_array( $position, array( 'after', 'both' ), true ) ) {
		echo str_replace( 'mashsb-main', 'mashsb-main mashsb-stretched', do_shortcode( '[mashshare]' ) );
	}
}

/**
 * Render social sharing buttons next to the content.
 */
function bimber_mashsharer_render_side_share_buttons() {
    // Check post type support.
    if ( ! bimber_mashsharer_post_type_supported() ) {
        return;
    }

	echo str_replace( 'mashsb-main', 'mashsb-side', do_shortcode( '[mashshare]' ) );
}

/**
 * Render compact social sharing buttons.
 */
function bimber_mashsharer_render_compact_share_buttons() {
    // Check post type support.
    if ( ! bimber_mashsharer_post_type_supported() ) {
        return;
    }

	add_filter( 'mashsb_output_buttons', 'bimber_mashsharer_output_compact_buttons' );

	$post_title = addslashes( the_title_attribute( 'echo=0' ) );

	$post_title = str_replace(
	    array(
	        '[',
            ']'
        ),
        array(
            '&#91;',
            '&#93;'
        ),
        $post_title
    );

	echo do_shortcode( '[mashshare shares="false" text="' . $post_title . '"]' );

	remove_filter( 'mashsb_output_buttons', 'bimber_mashsharer_output_compact_buttons' );
}

/**
 * Render mini social sharing buttons.
 */
function bimber_mashsharer_render_mini_share_buttons() {
    // Check post type support.
    if ( ! bimber_mashsharer_post_type_supported() ) {
        return;
    }

	add_filter( 'mashsb_output_buttons', 'bimber_mashsharer_output_mini_buttons' );

	$post_title = addslashes( the_title_attribute( 'echo=0' ) );

    $post_title = str_replace(
        array(
            '[',
            ']'
        ),
        array(
            '&#91;',
            '&#93;'
        ),
        $post_title
    );

	echo do_shortcode( '[mashshare shares="false" text="' . $post_title . '"]' );

	remove_filter( 'mashsb_output_buttons', 'bimber_mashsharer_output_mini_buttons' );
}

/**
 * Replace share buttons main style to compact
 *
 * @param string $out			Share buttons HTML.
 * @return string
 */
function bimber_mashsharer_output_compact_buttons( $out ) {
	$out = str_replace( 'mashsb-main', 'mashsb-compact', $out );
	return $out;
}

/**
 * Replace share buttons main style to mini
 *
 * @param string $out			Share buttons HTML.
 * @return string
 */
function bimber_mashsharer_output_mini_buttons( $out ) {
	$out = str_replace( 'mashsb-main', 'mashsb-mini', $out );
	return $out;
}

/**
 * Set opengraph to share GIFs as images, not articles
 *
 * @param str $meta			Meta section HTML.
 */
function bimber_mashsharer_gif_opengraph( $meta ) {

	$page_id = get_queried_object_id();
	$post_thumbnail_id = get_post_thumbnail_id( $page_id );
	$featured_image_url = wp_get_attachment_url( $post_thumbnail_id );
	if ( strpos( $featured_image_url, '.gif' ) ) {
		$meta = preg_replace( '/<meta property=\"og:url\".*/', '<meta property="og:url" content="' . $featured_image_url . '">', $meta );
		$meta = str_replace( '<meta property="og:type" content="article" />', '<meta property="og:type" content="video.other">', $meta );
	}
	return $meta;
}

/**
 * Fix the empty OG desc issue
 *
 * @param str $meta	OG meta.
 * @return str
 */
function bimber_mashsharer_fix_empty_og_description( $meta ) {
	if ( false === strpos( $meta, 'og:description' ) ) {
		$meta .= '<meta property="og:description" content="' . get_bloginfo( 'description' ) . '" />';
	}
	return $meta;
}

/**
 * Add most voted archive filter
 *
 * @param  array $archive_filters  Archive filters.
 * @return array
 */
function bimber_mashsharer_add_most_shares_filter( $archive_filters ) {
	$archive_filters['most_shares'] =__( 'Most Shared', 'bimber' );

	return $archive_filters;
}

/**
 * Apply the archive filter to the query.
 *
 * @param WP_Query $query Archive main query.
 */
function bimber_mashsharer_apply_archive_filter_most_shares( $query ) {
	$query->set( 'orderby','meta_value_num' );
	$query->set( 'order','DESC' );
	$query->set( 'meta_key','mashsb_shares' );
}

/**
 * Add shares to single product.
 */
function bimber_mashsharer_add_shares_to_single_product() {
	$show = apply_filters( 'bimber_mashsharer_add_shares_to_single_product', false );
	if ( $show ) {
		ob_start();
		bimber_render_side_share_buttons();
		$html = ob_get_clean();
		$html = str_replace( 'mashsb-side', '', $html );

		echo $html;
	}
}




add_action( 'wp_enqueue_scripts', 'bimber_mashsharer_enqueue_head_styles', 20 );

/**
 * Enqueue WooCommerce Plugin integration assets.
 */
function bimber_mashsharer_enqueue_head_styles() {
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );

	// Global styles.
	wp_enqueue_style( 'bimber-mashshare', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/mashshare-' . $skin . '.min.css', array(), $version );
	wp_style_add_data( 'bimber-mashshare', 'rtl', 'replace' );
}



