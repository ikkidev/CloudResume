<?php
/**
 * Plugin migrations
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'init', 'cace_run_migrations' );

/**
 * Run migration script if needed
 */
function cace_run_migrations() {
	if ( ! get_option( 'cace_init_migration' ) ) {
		cace_init_migration();
	}
}

/**
 * Init migration
 */
function cace_init_migration() {
    // Don't get options using bimber_bet_theme_option().
    $bimber_options = get_option( bimber_get_theme_id() );

    if ( ! empty( $bimber_options['post_hide_elements'] ) ) {
         $hide_elements = explode( ',', $bimber_options['post_hide_elements'] );

        // Disable WordPress Comments.
         if ( in_array( 'native_comments', $hide_elements ) ) {
             update_option( 'cace_wp_enabled', 'none' );
         }
    }

    // FB Comments.
    $fb_options = get_option('fbcomments');

    if ( ! empty( $fb_options['appID'] ) ) {
        update_option( 'cace_fb_app_id', $fb_options['appID'] );
    }

    if ( ! empty( $fb_options['num'] ) ) {
        update_option( 'cace_fb_comments_number', $fb_options['num'] );
    }

    // Disqus Comments.
    $dsq_shortname = get_option('disqus_forum_url');

    if ( ! empty( $dsq_shortname ) ) {
        update_option( 'cace_dsq_shortname', $dsq_shortname );
    }

    // Update state.
    update_option( 'cace_init_migration', 'done' );
}
