<?php
/**
 * East Social Share Buttons plugin integration
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
	=== WHAT'S LEFT? ===
	For now all below positions work. All has own "Facebook App Id" setting.

	1) Bimber (WP Admin > Appearance > Customize > Posts > General > Facebook App Id):
		- Rank (myCRED integration)         - Bimber integration? Custom position?
		- Badge (myCRED integration)        - Bimber integration? Custom position?
		- Microshares                       - Bimber integration? Custom position?
			- turn ON Microshares, based on theme's option (default: ON)
			- remove the option "WP Customizer > Post > Single > Microshares", ESSB will handle it.

	2) Snax (WP Admin > Settings > Snax >  Facebook App Id)::
		- Poll (share results)              - Bimber integration? Custom position?
		- Quiz (share results)              - Bimber integration? Custom position?
		- Snax Item (on a list)             - Bimber integration? Custom position?

	3) MediaAce (has own settings to provide Facebook App Id):
		- Gallery                           - Bimber integration? Custom position?
*/

require_once BIMBER_PLUGINS_DIR . 'easy-social-share-buttons/migrations.php';
require_once BIMBER_PLUGINS_DIR . 'easy-social-share-buttons/debug.php';

// Setup.
add_action( 'init',                                     'bimber_essb_register_positions', 99 );
add_filter( 'essb4_custom_method_list',                 'bimber_essb_register_positions_settings' );
add_filter( 'essb4_button_positions',                   'bimber_essb_setup_positions' );
add_filter( 'essb4_button_positions_mobile',            'bimber_essb_setup_positions' );

// Render positions.
add_action( 'bimber_render_top_share_buttons',          'bimber_essb_render_top_share_buttons' );
add_action( 'bimber_render_bottom_share_buttons',       'bimber_essb_render_bottom_share_buttons' );
add_action( 'bimber_render_compact_share_buttons',      'bimber_essb_render_compact_share_buttons' );
add_action( 'bimber_render_mini_share_buttons',         'bimber_essb_render_mini_share_buttons' );
add_action( 'bimber_render_side_share_buttons',         'bimber_essb_render_side_share_buttons' );

// Share count and visibility threshold.
add_filter( 'bimber_entry_share_count',                 'bimber_essb_entry_share_count' );
add_filter( 'bimber_show_entry_share_count',            'bimber_essb_show_entry_share_count', 10, 2 );

// Most shared query.
add_filter( 'bimber_most_shared_query_args',            'bimber_essb_most_shared_query_args', 10, 2 );

// Archive filter.
add_filter( 'bimber_archive_filters',                   'bimber_essb_add_most_shares_filter', 10, 1 );
add_action( 'bimber_apply_archive_filter_most_shares',  'bimber_essb_apply_archive_filter_most_shares', 10, 1 );

// Customize > Single Post > Share Buttons.
add_filter( 'post_bottom_share_buttons_active',         'bimber_essb_post_bottom_share_buttons_active' );

// Templates.
add_filter( 'essb4_templates',          'bimber_essb_template_initialze' );
add_filter( 'essb4_templates_class',    'bimber_essb_template_class', 10, 2 );
add_action( 'wp_enqueue_scripts',       'bimber_essb_enqueue_head_styles', 20 );

// Migrations.
add_action( 'after_setup_theme',                        'bimber_essb_run_migrations' );

// Debug.
add_action( 'after_setup_theme',                        'bimber_essb_display_debug_log' );

/**
 * Register custom positions.
 */
function bimber_essb_register_positions() {
	if ( ! is_admin() ) {
		return;
	}

	if ( class_exists( 'ESSBOptionsStructureHelper' ) ) {
		essb_prepare_location_advanced_customization( 'where', 'positions|display-38', 'bimber_top' );
		essb_prepare_location_advanced_customization( 'where', 'positions|display-39', 'bimber_bottom' );
		essb_prepare_location_advanced_customization( 'where', 'positions|display-40', 'bimber_compact' );
		essb_prepare_location_advanced_customization( 'where', 'positions|display-41', 'bimber_mini' );
		essb_prepare_location_advanced_customization( 'where', 'positions|display-42', 'bimber_side' );
	}
}

/**
 * Register Bimber position under ESSB > Where to Display > Position Settings
 *
 * @param array $methods    Methods.
 *
 * @return array
 */
function bimber_essb_register_positions_settings( $methods ) {
	$methods['display-38'] = _x( 'Bimber: Before Content', 'Easy Social Share Buttons integration', 'bimber' );
	$methods['display-39'] = _x( 'Bimber: After Content', 'Easy Social Share Buttons integration', 'bimber' );
	$methods['display-40'] = _x( 'Bimber: Compact', 'Easy Social Share Buttons integration', 'bimber' );
	$methods['display-41'] = _x( 'Bimber: Mini', 'Easy Social Share Buttons integration', 'bimber' );
	$methods['display-42'] = _x( 'Bimber: Content Side', 'Easy Social Share Buttons integration', 'bimber' );

	return $methods;
}

/**
 * Setup position (image, label etc)
 *
 * @param array $positions      Position list.
 *
 * @return array
 */
function bimber_essb_setup_positions( $positions ) {
	$positions['bimber_top'] = array (
		'image' => 'assets/images/display-positions-09.png', 
		'label' => _x( 'Bimber: Before Content', 'Easy Social Share Buttons integration', 'bimber' )
	);
	
	$positions['bimber_bottom'] = array (
		'image' => 'assets/images/display-positions-09.png', 
		'label' => _x( 'Bimber: After Content', 'Easy Social Share Buttons integration', 'bimber' )
	);
	
	$positions['bimber_compact'] = array (
		'image' => 'assets/images/display-positions-09.png', 
		'label' => _x( 'Bimber: Compact', 'Easy Social Share Buttons integration', 'bimber' )
	);

	$positions['bimber_mini'] = array (
		'image' => 'assets/images/display-positions-09.png',
		'label' => _x( 'Bimber: Mini', 'Easy Social Share Buttons integration', 'bimber' )
	);

	$positions['bimber_side'] = array (
		'image' => 'assets/images/display-positions-09.png',
		'label' => _x( 'Bimber: Side', 'Easy Social Share Buttons integration', 'bimber' )
	);
	
	return $positions;
}

// Define helper renderer.
if ( ! function_exists( 'essb_draw_custom_position' ) ) {
	function essb_draw_custom_position( $position ) {
		if ( function_exists( 'essb_core' ) ) {
			$general_options = essb_core()->get_general_options();

			if ( is_array( $general_options ) ) {
				if ( in_array( $position, $general_options['button_position'] ) ) {
					echo essb_core()->generate_share_buttons( $position );
				}
			}
		}
	}
}

/**
 * Return post types to display share buttons on
 *
 * @return array
 */
function bimber_essb_get_display_post_types() {
    $post_types = essb_option_value('display_in_types');

    if ( ! is_array( $post_types ) ) {
        $post_types = array();
    }

    return $post_types;
}

/**
 * Render "top" buttons.
 */
function bimber_essb_render_top_share_buttons() {
    if ( ! function_exists( 'essb_manager' ) ) {
        return;
    }

    $display_key = 'bimber_top';
    $post_types = bimber_essb_get_display_post_types();

    if ( essb_manager()->essb()->check_applicability( $post_types, $display_key ) ) {
        essb_draw_custom_position( $display_key );
    }
}

/**
 * Render "bottom" buttons.
 */
function bimber_essb_render_bottom_share_buttons() {
    if ( ! function_exists( 'essb_manager' ) ) {
        return;
    }

    $display_key = 'bimber_bottom';
    $post_types = bimber_essb_get_display_post_types();

    if ( essb_manager()->essb()->check_applicability( $post_types, $display_key ) ) {
        essb_draw_custom_position( $display_key );
    }
}

/**
 * Render "compact" buttons.
 */
function bimber_essb_render_compact_share_buttons() {
    if ( ! function_exists( 'essb_manager' ) ) {
        return;
    }

    $display_key = 'bimber_compact';
    $post_types = bimber_essb_get_display_post_types();

    if ( essb_manager()->essb()->check_applicability( $post_types, $display_key ) ) {
        essb_draw_custom_position( $display_key );
    }
}

/**
 * Render "mini" buttons.
 */
function bimber_essb_render_mini_share_buttons() {
    if ( ! function_exists( 'essb_manager' ) ) {
        return;
    }

    $display_key = 'bimber_mini';
    $post_types = bimber_essb_get_display_post_types();

    if ( essb_manager()->essb()->check_applicability( $post_types, $display_key ) ) {
        essb_draw_custom_position( $display_key );
    }
}

/**
 * Render "side" buttons.
 */
function bimber_essb_render_side_share_buttons() {
    if ( ! function_exists( 'essb_manager' ) ) {
        return;
    }

    $display_key = 'bimber_side';
    $post_types = bimber_essb_get_display_post_types();

    if ( essb_manager()->essb()->check_applicability( $post_types, $display_key ) ) {
        essb_draw_custom_position( $display_key );
    }
}

/**
 * Return total number of shares for the current post
 *
 * @return int
 */
function bimber_essb_entry_share_count() {
    $counters = bimber_essb_get_sharecount();

    $number = isset( $counters['total'] ) ? $counters['total'] : 0;

    return $number;

//	return bimber_essb_get_post_sharecount();
}

/**
 * Get share counters for a post
 *
 * @param WP_Post $post     Optional. Post object.
 *
 * @return int            Counters data.
 */
//function bimber_essb_get_post_sharecount( $post = null ) {
function bimber_essb_get_sharecount( $post = null ) {
    $post = get_post( $post );

    $cached_counters = array();
    $cached_counters['total'] = 0;

    if ( isset( $post ) ) {
        $networks = essb_available_social_networks();

        foreach ( $networks as $network => $network_data ) {
            $cached_counters[ $network ] = get_post_meta( $post->ID, 'essb_c_' . $network, true );
            $cached_counters['total'] += intval( $cached_counters[ $network ] );
        }

        if ( has_filter( 'essb4_get_cached_counters' ) ) {
            $cached_counters = apply_filters( 'essb4_get_cached_counters', $cached_counters );
        }
    }

    return $cached_counters;

//	$post = get_post( $post );
//
//    $networks = essb_available_social_networks();
//    $networks = array_keys( $networks );
//
//    $cached_counter_networks = ESSBCachedCounters::prepare_list_of_networks_with_counter($networks, $networks);
//
//    $share_details = essb_get_post_share_details('');
//    $share_details['full_url'] = $share_details['url'];
//
//    $networks = essb_option_value('networks'); // ??? check instead of $cached_counter_networks
//    $networks = ESSBCachedCounters::all_socaial_networks();
//
//    // Check the post ID 194, it has 1 share, essb_pc_facebook
//    $cached_counters = ESSBCachedCounters::get_counters($post->ID, $share_details, $cached_counter_networks);
//
//    $total = isset($cached_counters['total']) ? $cached_counters['total'] : '0';
//
//    $total = essb_kilomega_format( $total, 'total' );
//
//	return $total;
}

/**
 * Check whether to show the share count
 *
 * @param bool $show            Determines whether to show or not the counter.
 * @param int  $share_count     Current shares number.
 *
 * @return bool
 */
function bimber_essb_show_entry_share_count( $show, $share_count ) {
	// WP Admin > ESSB > Social Sharing > Share Counters Setup > Avoid Social Negative Proof.
	$social_proof_enable = essb_option_bool_value('social_proof_enable');

	if ( $social_proof_enable ) {
		// WP Admin > ESSB > Social Sharing > Share Counters Setup > Avoid Social Negative Proof > Display total counter after this value of shares is reached.
		$share_count_threshold = essb_option_value( 'total_counter_hidden_till' );

		if ( absint( $share_count ) < absint( $share_count_threshold ) ) {
			$show = false;
		}
	}

	return $show;
}

/**
 * Override query to fetch shared posts
 *
 * @param array $query_args     WP Query args.
 *
 * @return array
 */
function bimber_essb_most_shared_query_args( $query_args ) {
	$defaults = array(
		'posts_per_page'      => 10,
		'ignore_sticky_posts' => true,
		'meta_key'            => 'essb_c_total',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		// This way we can be sure that only "shared" posts will be used.
		'meta_query'          => array(
			array(
				'key'     => 'essb_c_total',
				'compare' => '>',
				'value'   => 0,
			),
		),
	);

	$query_args = wp_parse_args( $query_args, $defaults );

	return $query_args;
}

/**
 * Add most shared archive filter
 *
 * @param  array $archive_filters  Archive filters.
 *
 * @return array
 */
function bimber_essb_add_most_shares_filter( $archive_filters ) {
	$archive_filters['most_shares'] =__( 'Most Shared', 'bimber' );

	return $archive_filters;
}

/**
 * Apply the archive filter to the query.
 *
 * @param WP_Query $query Archive main query.
 */
function bimber_essb_apply_archive_filter_most_shares( $query ) {
	$query->set( 'orderby','meta_value_num' );
	$query->set( 'order','DESC' );
	$query->set( 'meta_key','essb_c_total' );
}

/**
 * Enable WP Customizer > Posts > Single > Elements Order > Share Buttons
 *
 * @param bool $active          Is active?
 *
 * @return bool
 */
function bimber_essb_post_bottom_share_buttons_active( $active ) {
	$options = get_option( ESSB3_OPTIONS_NAME );

	if ( isset( $options['button_position'] ) && in_array( 'bimber_bottom', $options['button_position'] ) ) {
		$active = true;
	}

	return $active;
}

function bimber_essb_template_initialze($templates) {
	$templates['bimber'] = 'Bimber';

	return $templates;
}

function bimber_essb_template_class($class, $template_id) {
	if ($template_id == 'bimber') {
		$class = 'bimber-template';
	}

	return $class;
}

/**
 * Enqueue WooCommerce Plugin integration assets.
 */
function bimber_essb_enqueue_head_styles() {
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );

	// Global styles.
	wp_enqueue_style( 'bimber-essb', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/essb-' . $skin . '.min.css', array(), $version );
	wp_style_add_data( 'bimber-essb', 'rtl', 'replace' );
}


