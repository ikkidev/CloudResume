<?php

/**
 * Class NFD_CLI
 */
final class NFD_CLI {

	/**
	 * Returns the file path to save the site capabilities.
	 *
	 * @return string
	 */
	private static function capabilities_file_path() {
		return WP_CONTENT_DIR . '/mu-plugins/site-capability-override.php';
	}

	/**
	 * Enables and disables AI and AI Sitegen Capabilities.
	 *
	 * ## EXAMPLES
	 *
	 * wp newfold ai enable <Hiive Token>
	 * wp newfold ai disable🌞
	 *
	 * @when after_wp_load
	 *
	 * @param string $args arguments passed to this from the command line.
	 */
	public function ai( $args ) {

		switch ( $args[0] ) {
			case 'enable':
				self::enable( $args[1] );
				break;
			case 'disable':
				self::disable();
				break;
			default:
				\WP_CLI::warning( __( 'No action provided', 'wp-module-ai' ) );
				\WP_CLI::log( __( 'Usage: wp newfold ai enable <Hiive Token>', 'wp-module-ai' ) );
				\WP_CLI::log( __( 'wp newfold ai disable', 'wp-module-ai' ) );
				break;
		}
	}

	/**
	 * Enable AI and AI Sitegen capability.
	 *
	 * @param string $token Hiive token to be saved as an option.
	 * @return null
	 */
	private static function enable( $token ) {

		if ( empty( $token ) ) {
			\WP_CLI::error( __( 'Hiive token not provided. Cannot enable AI capabilities.', 'wp-module-ai' ) );
			return;
		}

		$enable_ai_filter = '<?php 
		add_filter(
			"pre_transient_nfd_site_capabilities", 
			function () { 
				return [ 
					"canAccessAI" 			=> true, 
					"hasAISiteGen" 			=> true, 
				];
			}
		);';

		// Initialize the WP Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			WP_CLI::error( __( 'Failed to initialize the WP Filesystem.', 'wp-module-ai' ) );
		}

		// create a php file that overrides the AI capabilities
		if ( $wp_filesystem->put_contents( self::capabilities_file_path(), $enable_ai_filter ) === false ) {
			\WP_CLI::error( __( 'Could not enable AI capabilities.', 'wp-module-ai' ) );
		}
		\WP_CLI::success( __( 'AI Capabilities Enabled.', 'wp-module-ai' ) );

		// Update the hiive token in the option, it gets automatically encrypted while saving and decrypted while reading
		if ( update_option( 'nfd_data_token', $token ) ) {
			\WP_CLI::success( __( 'Hiive token encrypted and saved.', 'wp-module-ai' ) );
		}
	}

	/**
	 * Disable AI and AI Sitegen capability.
	 */
	private static function disable() {

		// Initialize the WP Filesystem
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			WP_CLI::error( __( 'Failed to initialize the WP Filesystem.', 'wp-module-ai' ) );
		}

		// Check if the file exists
		if ( ! $wp_filesystem->exists( self::capabilities_file_path() ) ) {
			WP_CLI::error( __( 'Could not disable AI capabilities.', 'wp-module-ai' ) );
		}

		// delete the php file that overrides the AI capabilities
		if ( ! $wp_filesystem->delete( self::capabilities_file_path() ) ) {
			\WP_CLI::error( __( 'Could not disable AI capabilities.', 'wp-module-ai' ) );
		}
		\WP_CLI::success( __( 'AI Capabilities Disabled.', 'wp-module-ai' ) );

		// delete the Hiive token
		if ( delete_option( 'nfd_data_token' ) ) {
			\WP_CLI::success( __( 'Hiive token deleted.', 'wp-module-ai' ) );
		}
	}
}
