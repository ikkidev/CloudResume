<?php
/**
 * Widgets
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
 * Init widgets
 */
function bimber_widgets_init() {
	register_widget( 'Bimber_Widget_Posts' );
	register_widget( 'Bimber_Widget_Sticky_Start_Point' );
	register_widget( 'Bimber_Widget_Taxonomy_Filter' );
}

function bimber_sticky_widgets( $params ) {
	if ( is_admin() ) {
		return $params;
	}

	// State vars.
	global $bimber_current_sidebar_id;
	global $bimber_make_next_widget_sticky;
	global $bimber_sticky_widgets_count;
	global $bimber_close_sticky_combined_wrapper;

	$sidebar_id = $params[0]['id'];
	$widget_id  = $params[0]['widget_id'];

	// Reset state on a next sidebar.
	if ( $bimber_current_sidebar_id !== $sidebar_id ) {
		$bimber_make_next_widget_sticky         = false;
		$bimber_sticky_widgets_count            = 0;
		$bimber_close_sticky_combined_wrapper   = false;

		$bimber_current_sidebar_id = $sidebar_id;
	}

	$is_sticky_start_point = false !== strpos( $widget_id, 'bimber_sticky_start_point_widget' );

	if ( $is_sticky_start_point ) {
		$widget_base  = _get_widget_id_base( $widget_id );
		$widget_index = absint( str_replace( $widget_base . '-', '', $widget_id ) );

		$widget_group_data = get_option( 'widget_' . $widget_base );
		$widget_data = $widget_group_data[ $widget_index ];

		$widget_data = wp_parse_args( $widget_data, Bimber_Widget_Sticky_Start_Point::$defaults );

		$bimber_make_next_widget_sticky = $widget_data;
		$bimber_sticky_widgets_count = 0;

		// Config stored, stop processing.
		return $params;
	}

	if ( $bimber_make_next_widget_sticky ) {
		$widgets_nb = absint( $bimber_make_next_widget_sticky['widgets_nb'] );

		// 0 means that all subsequent widgets should be sticky.
		if ( $widgets_nb > 0 && $bimber_sticky_widgets_count > $widgets_nb ) {
			$bimber_make_next_widget_sticky = false;
		}
	}

	if ( $bimber_make_next_widget_sticky && ! $bimber_close_sticky_combined_wrapper ) {
		$wrapper_height = absint( $bimber_make_next_widget_sticky['height'] );

		$wrapper_style = $wrapper_height > 0 ? sprintf( ' style="height: %dpx;"', $wrapper_height ) : '';

		$params[0]['before_widget'] =
			'<div class="g1-sticky-widget-wrapper"'. $wrapper_style .'>'.
				'<div class="g1-sticky-widget" style="top: '. absint( $bimber_make_next_widget_sticky['offset'] ) .'px">' .
				$params[0]['before_widget'];

		// Every widget has its onw sticky wrapper.
		if ( $wrapper_height > 0 ) {
			$params[0]['after_widget']  .= '</div></div><!-- End of sticky widget wrapper -->';

			$bimber_sticky_widgets_count++;
		// All widgets should be in one container.
		} else {
			// Do not wrap next widget into sticky container.
			$bimber_make_next_widget_sticky = false;

			// Close combined container at sidebar end.
			$bimber_close_sticky_combined_wrapper = true;
		}
	}

	return $params;
}

function bimber_sticky_close_combined_wrapper() {
	global $bimber_close_sticky_combined_wrapper;

	if ( $bimber_close_sticky_combined_wrapper ) {
		echo '</div></div><!-- End of sticky widgets combined wrapper -->';

		$bimber_close_sticky_combined_wrapper = false;
	}
}


add_action( 'admin_enqueue_scripts', 'bimber_widgets_enqueue_admin_assets' );

/**
 * Enqueue assets for widget admin.
 */
function bimber_widgets_enqueue_admin_assets() {
	$screen = get_current_screen();
	if ( 'post' === $screen->base ) {
		return;
	}
	wp_enqueue_script( 'bimber-post-widget-admin', BIMBER_ADMIN_DIR_URI . 'js/posts-widget.js', array( 'tags-box', 'jquery-ui-core', 'jquery-ui-autocomplete' ), bimber_get_theme_version(), true );
}
