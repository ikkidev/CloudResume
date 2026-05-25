<?php
/**
 * Elementor Page Builder plugin functions
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

require_once BIMBER_PLUGINS_DIR . 'elementor/customizer.php';
require_once BIMBER_PLUGINS_DIR . 'elementor/extensions/class-elementor-bimber-extension.php';

add_action( 'bimber_home_before_main_collection', 'bimber_elementor_render_home_static' );
add_action( 'woocommerce_archive_description', 'bimber_elementor_render_woocommerce_home_static' );

// Admin page status.
add_filter( 'display_post_states',          'bimber_elementor_add_display_post_states', 10, 2 );

// Fix for Elementor 3.x
add_action( 'admin_menu', 'bimber_elementor_fix_snax_author_async_upload' );

/**
 * When the async upload from frontend is running, the Elementor throws some warning, breaking Snax image preview.
 * We have to disable the "fix_submenu" filter to fix it.
 */
function bimber_elementor_fix_snax_author_async_upload() {
    $is_snax_doing_ajax	= (bool) bimber_htmlspecialchars( filter_input( INPUT_POST, 'snax_media_upload_action' ) );

    if ( $is_snax_doing_ajax ) {
        $elementor = \Elementor\Plugin::instance();

        if ( is_object( $elementor ) ) {
            remove_filter( 'add_menu_classes', [ $elementor->app, 'fix_submenu' ] );
        }
    }
}

function bimber_elementor_get_home_page_id() {
	$page_id = bimber_get_theme_option( 'home', 'elementor_page_id' );
	$page_id = apply_filters( 'bimber_home_elementor_page_id', $page_id );

	$page = get_post( $page_id );

	$is_page = ( $page && 'page' === $page->post_type );

	return $is_page ? (int) $page_id : false;
}


/**
 * Render Elementor static page on the Homepage
 */
function bimber_elementor_render_home_static() {
	$page_id = bimber_elementor_get_home_page_id();
	if ( ! $page_id ) {
		return;
	}

	$page_template = get_post_meta( $page_id, '_wp_page_template', true );

	$template_name = ( 'g1-template-page-full.php' === $page_template ) ? 'full' : 'with-sidebar';

	global $post;
	$orig_post = $post;

	$post = get_post( $page_id );
	setup_postdata( $post );

	$quads_priority = function_exists( 'quads_get_load_priority' ) ? quads_get_load_priority() : 20;

	add_filter('the_content', 'bimber_elementor_disable_quads_content_ads', $quads_priority - 1 );

	get_template_part( 'template-parts/home-static/' . $template_name );

	remove_filter('the_content', 'bimber_elementor_disable_quads_content_ads', $quads_priority - 1 );

	$post = $orig_post;
	wp_reset_postdata();
}

function bimber_elementor_render_woocommerce_home_static() {
	if ( is_front_page() && is_shop() ) {
		bimber_elementor_render_home_static();
	}
}

add_action( 'wp_enqueue_scripts', 'bimber_elementor_enqueue_scripts', 500 );
function bimber_elementor_enqueue_scripts() {
	if ( ! class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
		return;
	}

	$page_id = bimber_elementor_get_home_page_id();

	if ( $page_id && is_front_page() ) {
		$css_file = new \Elementor\Core\Files\CSS\Post( $page_id );
		$css_file->enqueue();
	}
}


/**
 * Disable WP Quads content ads
 *
 * @param string $content
 *
 * @return string
 */
function bimber_elementor_disable_quads_content_ads( $content ) {
	$content .= '<!--OffAds-->';

	return $content;
}

/**
 * Add a post display state for Elementor special pages in the page list table
 *
 * @param array   $post_states  An array of post display states.
 * @param WP_Post $post         The current post object.
 *
 * @return array
 */
function bimber_elementor_add_display_post_states( $post_states, $post ) {
    if ( bimber_elementor_get_home_page_id() === $post->ID ) {
        $post_states['bimber_elementor_home_injected_page'] = _x( 'Bimber, Elementor Home Injected Page', 'Admin page label', 'bimber' );
    }

    return $post_states;
}
