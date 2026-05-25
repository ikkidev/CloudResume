<?php

use NewfoldLabs\WP\Module\LinkTracker\LinkTracker;

// Do not allow multiple copies of the module to be activated.
if ( defined( 'NFD_LINK_TRACKER_MODULE_VERSION' ) ) {
	return;
}

define( 'NFD_LINK_TRACKER_MODULE_VERSION', '1.1.1' );

if ( function_exists( 'add_action' ) ) {
	add_action(
		'newfold_container_set',
		function ( $container ) {
			if ( ! defined( 'NFD_LINK_TRACKER_BUILD_URL' ) ) {
				define( 'NFD_LINK_TRACKER_BUILD_URL', $container->plugin()->url . 'vendor/newfold-labs/wp-module-link-tracker/build' );
			}
			if ( ! defined( 'NFD_LINK_TRACKER_BUILD_DIR' ) ) {
				define( 'NFD_LINK_TRACKER_BUILD_DIR', $container->plugin()->dir . 'vendor/newfold-labs/wp-module-link-tracker/build' );
			}
			$link_tracker = new LinkTracker( $container );
			$link_tracker->add_hooks();
		}
	);
}
