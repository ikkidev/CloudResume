<?php
/**
 * Handles update for coming soon module version 1.1.18.
 *
 * Enable coming soon page on fresh installations.
 *
 * @package NewfoldLabs\WP\Module\ComingSoon
 */

use NewfoldLabs\WP\Module\ComingSoon\Service;
use NewfoldLabs\WP\ModuleLoader\Container;

add_action( 'newfold_container_set', function ( Container $container ) {
	$isFreshInstall = $container->has( 'isFreshInstallation' ) ? $container->get( 'isFreshInstallation' ) : false;
	$isFreshInstall = apply_filters( 'newfold/coming-soon/filter/default/fresh', $isFreshInstall );
	if ( $isFreshInstall ) {
		$comingSoonService = new Service();
		$comingSoonService->enable( false );

		// Initially set the `mm_coming_soon` option to true as well to keep things in sync.
		update_option( 'mm_coming_soon', true );
	}
} );
