<?php
/**
 * bbPress plugin functions
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
 * Reigster bbPress specific sidebars
 *
 * @param array $sidebars		Registered sidebars.
 *
 * @return array
 */
function bimber_bbpress_setup_sidebars( $sidebars ) {
	$sidebars['bimber_bbpress'] = array(
		'label'       => 'bbPress',
		'description' => esc_html__( 'Leave empty to use the Primary sidebar', 'bimber' ),
	);

	return $sidebars;
}

/**
 * Load bbPress specific sidebar
 *
 * @param string $sidebar		Sidebar set.
 *
 * @return string
 */
function bimber_bbpress_sidebar( $sidebar ) {
	if ( is_bbpress() ) {
		if ( is_active_sidebar( 'bimber_bbpress' ) ) {
			$sidebar = 'bimber_bbpress';
		} else {
			$sidebar = 'primary'; // By default, Bimber fall backs to "single_post".
		}
	}

	return $sidebar;
}

/**
 * Remove Snax content from bbPress pages. It's there becasue bbPress prevent WP do_shortcode run.
 */
function bimber_bbpress_remove_snax_content( $excerpt ) {
	if ( is_bbpress() ) {
		remove_filter( 'get_the_excerpt', 'snax_restore_post_content', 15 );
	}
	return $excerpt;
}

/**
 * Disable default bbPress styles enqueue, since we load per stack.
 *
 * @param array $styles  Styles array.
 * @return array
 */
function bimber_disable_bbp_default_styles( $styles ) {
	return array();
}
