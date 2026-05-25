<?php
/**
 * East Social Share Buttons migrations
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

function bimber_essb_run_migrations() {
	bimber_essb_init_setup();
}

/**
 * Initial setup
 */
function bimber_essb_init_setup() {
	// Run only once.
	if ( get_option( 'bimber_essb_init_setup' ) ) {
		return;
	}

	require_once BIMBER_PLUGINS_DIR . 'easy-social-share-buttons/migrations/init-setup.php';

	bimber_essb_run_init_setup();

	// Migration done.
	update_option( 'bimber_essb_init_setup', true );
}
