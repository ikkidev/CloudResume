<?php
/**
 * Auto Load Next Post plugin functions
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

// This is required to make everything work.
add_theme_support( 'auto-load-next-post' );

/**
 * When the 'partial' endpoint is used on a post, retrieve only the post content.
 **/
function bimber_auto_load_next_post_template_redirect() {
	global $wp_query;

	// If this is not a request for partial or a singular object then bail.
	if ( ! isset( $wp_query->query_vars['partial'] ) || ! is_singular() ) {
		return;
	}

	/**
	 * Load the template file from theme (child or main) if one exists.
	 * If theme does not have a template file, load default from the plugin.
	 */
	$located = locate_template( trailingslashit( AUTO_LOAD_NEXT_POST_TEMPLATE_PATH ) . 'content-partial.php' );

	if ( ! empty( $located ) ) {
		require_once $located;
	} else {
		require_once trailingslashit( AUTO_LOAD_NEXT_POST_FILE_PATH ) . 'template/content-partial.php';
	}

	exit;
}

/**
 * Hide options that were set by the theme
 *
 * @param array $settings		Settings.
 *
 * @return array
 */
function bimber_auto_load_next_post_general_settings( $settings ) {
	$hide_settings = array(
		'auto_load_next_post_content_container',
		'auto_load_next_post_title_selector',
		'auto_load_next_post_navigation_container',
		'auto_load_next_post_comments_container',
	);

	foreach ( $settings as $index => $setting ) {
		if ( in_array( $setting['id'], $hide_settings, true ) ) {
			unset( $settings[ $index ] );
		}
	}

	return $settings;
}

/**
 * Return theme content selector
 *
 * @return string
 */
function bimber_auto_load_next_post_content_container() {
	return '#content';
}

/**
 * Return theme title selector
 *
 * @return string
 */
function bimber_auto_load_next_post_title_selector() {
	return '#content > article > header .entry-title';
}

/**
 * Return theme navigation selector
 *
 * @return string
 */
function bimber_auto_load_next_post_navigation_container() {
	return '.g1-nav-single';
}

/**
 * Return theme comments selecotor
 *
 * @return string
 */
function bimber_auto_load_next_post_comments_container() {
	return '#comments';
}
