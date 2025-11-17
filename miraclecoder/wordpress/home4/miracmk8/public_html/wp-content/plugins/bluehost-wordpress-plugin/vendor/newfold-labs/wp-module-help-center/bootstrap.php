<?php

namespace NewfoldLabs\WP\Module\HelpCenter;

// Define global constants
define( 'NFD_HELPCENTER_DIR', __DIR__ );
define( 'NFD_HELPCENTER_BUILD_DIR', __DIR__ . '/build/' );

// Register the HelpCenterFeature class in the features filter
if ( function_exists( 'add_filter' ) ) {
	add_filter(
		'newfold/features/filter/register',
		function ( $features ) {
			return array_merge( $features, array( HelpCenterFeature::class ) );
		}
	);
}

new HelpCenterFeatureHooks();
