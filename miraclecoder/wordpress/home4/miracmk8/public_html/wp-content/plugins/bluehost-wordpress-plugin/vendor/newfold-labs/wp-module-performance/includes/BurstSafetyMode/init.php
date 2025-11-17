<?php

use NewfoldLabs\WP\Module\Performance\BurstSafetyMode\Skip404 as BurstSkip404;
use NewfoldLabs\WP\Module\Performance\BurstSafetyMode\Browser as BurstBrowser;
use NewfoldLabs\WP\Module\Performance\Cache\Types\Browser as CacheBrowser;
use NewfoldLabs\WP\Module\Performance\Cache\ResponseHeaderManager;

$newfold_burst_safety_mode = function_exists( 'get_option' ) ? (bool) get_option( 'newfold_burst_safety_mode', false ) : false;
$newfold_cache_level       = function_exists( 'newfold_cache_level' ) ? (int) get_option( 'newfold_cache_level', 0 ) : 0;

// Check if Performance feature is enabled and it's necessary reset the cache options
if ( class_exists( 'NewfoldLabs\WP\Module\Performance\Performance' ) ) {
	if ( $newfold_burst_safety_mode ) {
		$browser = new CacheBrowser();
		$browser::maybeAddRules( $newfold_cache_level );
		delete_option( 'newfold_burst_safety_mode' );
	}
} elseif ( ! $newfold_burst_safety_mode && defined( 'BLUEHOST_PLUGIN_DIR' ) ) {
	$files_to_include = array(
		'wp-htaccess_api'                        => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-htaccess/includes/Api.php',
		'wp_htaccess_fragment'                   => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-htaccess/includes/Fragment.php',
		'wp_htaccess_options'                    => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-htaccess/includes/Options.php',
		'wp_htaccess_context'                    => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-htaccess/includes/Context.php',
		'wp_performance_cache_type_fragment'     => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-performance/includes/Cache/Types/Fragments/BrowserCacheFragment.php',
		'wp_forge_htaccess'                      => BLUEHOST_PLUGIN_DIR . 'vendor/wp-forge/wp-htaccess-manager/includes/htaccess.php',
		'wp_forge_htaccess_functions'            => BLUEHOST_PLUGIN_DIR . 'vendor/wp-forge/wp-htaccess-manager/includes/functions.php',
		'wp_performance_skip404_fragment'        => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-performance/includes/Skip404/Fragments/Skip404Fragment.php',
		'wp_performance_skip404'                 => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-performance/includes/BurstSafetyMode/Skip404.php',
		'wp_performance_browser'                 => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-performance/includes/BurstSafetyMode/Browser.php',
		'wp_performance_response_header_manager' => BLUEHOST_PLUGIN_DIR . 'vendor/newfold-labs/wp-module-performance/includes/BurstSafetyMode/ResponseHeaderManager.php',
	);

	foreach ( $files_to_include as $file_path ) {
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}

	define( 'BURST_SAFETY_CACHE_LEVEL', 3 );

	$skip404 = new BurstSkip404();

	if ( BURST_SAFETY_CACHE_LEVEL !== $newfold_cache_level && class_exists( BurstBrowser::class ) ) {
		$browser = new BurstBrowser();
	}

	update_option( 'newfold_burst_safety_mode', true );
}
