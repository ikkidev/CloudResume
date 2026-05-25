<?php
/**
 * Search Everything plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

add_action( 'wp_loaded', 'bimber_search_everything_ajax_initialization', 1 );

/**
 * Initialize SE object with $ajax_request=true
 *
 * @return void
 */
function bimber_search_everything_ajax_initialization() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && 'bimber_search' === $_REQUEST['action']  ) {
		remove_action( 'wp_loaded','se_initialize_plugin' );
		new SearchEverything( true );
	}
}
