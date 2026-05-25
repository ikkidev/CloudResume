<?php

namespace NewfoldLabs\WP\Module\Staging;

if ( ! defined( 'NFD_STAGING_DIR' ) ) {
	define( 'NFD_STAGING_DIR', __DIR__ );
}

if ( function_exists( 'add_filter' ) ) {
	add_filter(
		'newfold/features/filter/register',
		function ( $features ) {
			return array_merge( $features, array( StagingFeature::class ) );
		}
	);
}
