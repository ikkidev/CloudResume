<?php

namespace NewfoldLabs\WP\Module\Performance;

use NewfoldLabs\WP\Module\Installer\Services\PluginInstaller;

use function NewfoldLabs\WP\ModuleLoader\container;

if ( function_exists( 'add_filter' ) ) {
	add_filter(
		'newfold/features/filter/register',
		function ( $features ) {
			return array_merge( $features, array( PerformanceFeature::class ) );
		}
	);
}

// Activate Jetpack Boost on fresh installation.
if ( function_exists( 'add_action' ) ) {
	add_action(
		'activated_plugin',
		function ( $plugin ) {
			if ( container()->plugin()->basename === $plugin &&
				container()->has( 'isFreshInstallation' ) &&
				container()->get( 'isFreshInstallation' ) &&
				isset( $_REQUEST['action'] ) && // phpcs:ignore WordPress.Security.NonceVerification
				'activate' === $_REQUEST['action'] // phpcs:ignore WordPress.Security.NonceVerification
			) {
				add_action(
					'activate_jetpack-boost/jetpack-boost.php',
					function () {
						remove_action( 'activated_plugin', 'Automattic\\Jetpack_Boost\\jetpack_boost_plugin_activation' );
					}
				);
				PluginInstaller::install( 'jetpack-boost', true );
			}
		}
	);
}

new PerformanceLifecycleHooks();

require_once __DIR__ . '/includes/BurstSafetyMode/init.php';
