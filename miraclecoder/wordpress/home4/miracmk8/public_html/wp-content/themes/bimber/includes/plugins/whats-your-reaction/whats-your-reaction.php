<?php
/**
 * What's Your Reaction? plugin functions
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

require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/whats-your-reaction/customizer.php';

remove_filter( 'the_content',                           'wyr_load_post_voting_box' );
add_action( 'wp_loaded',                                'bimber_wyr_apply_voting_box_order' );
add_filter( 'bimber_show_reactions_in_header',          'bimber_wyr_show_reactions_in_header' );
add_filter( 'bimber_show_entry_reactions',      		'bimber_wyr_show_entry_reactions' );
add_filter( 'bimber_show_entry_reactions_single',      	'bimber_wyr_show_entry_reactions_single' );

// Bimber + WYR + Snax integration.
add_filter( 'wyr_supported_post_types', 'bimber_wyr_snax_supported_post_types', 10, 1 );

// Snax integration.
add_action( 'snax_post_added',                          'bimber_wyr_snax_disable_fake_reactions', 10, 2 );

function bimber_wyr_show_reactions_in_header( $show ) {
	$show = 'standard' === bimber_get_theme_option( 'wyr', 'show_reactions_in_header' );

	return $show;
}

function bimber_wyr_show_entry_reactions( $show ) {
	$show = 'standard' === bimber_get_theme_option( 'wyr', 'show_entry_reactions' );

	return $show;
}

function bimber_wyr_show_entry_reactions_single( $show ) {
	$show = 'standard' === bimber_get_theme_option( 'wyr', 'show_entry_reactions_single' );

	return $show;
}

function bimber_wyr_apply_voting_box_order() {
	add_action( 'bimber_after_single_content',      'bimber_wyr_load_post_voting_box', bimber_get_theme_option( 'post', 'reactions_order' ) );
}

function bimber_wyr_load_post_voting_box() {
	$load = is_single() && 'publish' === get_post_status( get_the_ID() );

	if ( function_exists( 'wyr_voting_get_post_types' ) ) {
        $post_type 			= get_post_type();
        $allowed_post_types	= wyr_voting_get_post_types();

        if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
            return;
        }
    }

	if ( apply_filters( 'bimber_wyr_load_post_voting_box', $load ) ) {
		wyr_render_voting_box();
	}
}

add_filter( 'bp_nav_menu', 'bimber_wyr_reactions_nav_current', 100, 2 );

/**
 * Fix the navigation current tab style.
 *
 * @return string
 */
function bimber_wyr_reactions_nav_current( $nav_menu, $args  ) {
	$nav_menu = explode( '<li', $nav_menu );
	foreach ( $nav_menu as $index => $item ) {
		if ( 'reactions' === bp_current_component() && strpos( $item, 'reactions-personal-li' ) > -1 ) {
			$nav_menu[ $index ] = str_replace( 'g1-tab-item', 'g1-tab-item g1-tab-item-current', $item );
		}
	}
	$nav_menu = implode( '<li', $nav_menu );
	return $nav_menu;
}

function bimber_wyr_snax_disable_fake_reactions( $post_id, $type ) {
	if ( 'standard' === get_option( 'wyr_fake_reactions_disable_for_new', 'none' ) ) {
		$terms = wyr_get_reactions();
		$values = array();

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$slug 	= $term->slug;
				$values[ $slug ] = true;
			}

			update_post_meta( $post_id, '_wyr_disable_fakes_for_reactions', $values );
		}
	}
}

function bimber_wyr_snax_supported_post_types( $post_types ) {
    if ( bimber_can_use_plugin( 'snax/snax.php' ) && ! is_network_admin() ) {
        $post_types[] = snax_get_quiz_post_type();
        $post_types[] = snax_get_poll_post_type();
    }

    return $post_types;
}