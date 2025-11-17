<?php
/**
 * TGM Configuration
 *
 * @package       TGM-Plugin-Activation
 * @subpackage Example
 * @version       2.3.6
 * @author       Thomas Griffin <thomas@thomasgriffinmedia.com>, Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license       http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

define( 'TGM_THEME_DOMAIN', 'bimber' );
$bimber_tgmpa_prefix = ( defined( 'WP_NETWORK_ADMIN' ) && WP_NETWORK_ADMIN ) ? 'network_admin_' : '';

require_once BIMBER_ADMIN_DIR . 'lib/class-tgm-plugin-activation.php';

add_filter( 'tgmpa_' . $bimber_tgmpa_prefix . 'plugin_action_links', 'bimber_tgmpa_filter_action_links', 10, 4 );

/**
 * Filter TGMPA action links.
 *
 * @param array  $action_links			Actions.
 * @param string $item_slug				Item slug.
 * @param array  $item					Item config.
 * @param string $view_context			Context.
 *
 * @return array
 */
function bimber_tgmpa_filter_action_links( $action_links, $item_slug, $item, $view_context ) {
	$source = ! empty( $item['source'] ) ? $item['source'] : '';

	// Prevent installing theme's premium plugins.
	if ( 'Pre-Packaged' === $source && ! bimber_is_theme_registered() ) {
		$action_links = array(
			'bimber_registration_required' => sprintf( __( '<a style="color: #ff0000;" href="%s">Register theme to unlock</a>', 'bimber' ), esc_url( admin_url( 'themes.php?page=theme-options&group=registration' ) ) ),
		);
	}

	return $action_links;
}

/**
 * Return theme plugins configuration
 *
 * @return array
 */
function bimber_get_theme_plugins_config() {
	$theme_dir = trailingslashit( get_template_directory() );

	$config = array(
		array(
			'name'              => 'WordPress Importer',
			'slug'              => 'wordpress-importer',
			'required'          => false,
			'version'           => '',
			'install_with_demo' => 'all',
		),
		array(
			'name'     => 'MailChimp for WordPress',
			// The plugin name.
			'slug'     => 'mailchimp-for-wp',
			// The plugin slug (typically the folder name).
			'required' => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'  => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'install_with_demo' => 'all',
		),
		array(
			'name'     => 'Wordpress Popular Posts',
			// The plugin name.
			'slug'     => 'wordpress-popular-posts',
			// The plugin slug (typically the folder name).
			'required' => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'  => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'install_with_demo' => 'all',
		),
		array(
			'name'     => 'WP Subtitle',
			// The plugin name.
			'slug'     => 'wp-subtitle',
			// The plugin slug (typically the folder name).
			'required' => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'  => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'install_with_demo' => 'all',
		),
		array(
			'name'               => 'Snax',
			// The plugin name.
			'slug'               => 'snax',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Viral Front-End Uploader with Open Lists', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/snax.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '1.94',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'local_plugin'		 => true,
			'install_with_demo'  => 'all',
		),
		array(
			'name'               => 'G1 Socials',
			// The plugin name.
			'slug'               => 'g1-socials',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Social media profile icons', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/g1-socials.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '1.2.28',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'local_plugin'		 => true,
			'install_with_demo'  => 'all',
		),
		array(
			'name'               => 'What\'s Your Reaction?',
			// The plugin name.
			'slug'               => 'whats-your-reaction',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Share your reaction to a post, using nice looking badges', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/whats-your-reaction.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '1.3.20',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'local_plugin'		 => true,
			'install_with_demo'  => 'all',
		),
		array(
			'name'               => 'Media Ace',
			// The plugin name.
			'slug'               => 'media-ace',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Your media assistant', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/media-ace.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '1.4.13',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'local_plugin'		 => true,
			'install_with_demo'  => 'all',
		),
		array(
			'name'               => 'Ad Ace',
			// The plugin name.
			'slug'               => 'ad-ace',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Ad manager', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/ad-ace.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '1.3.28',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'local_plugin'		 => true,
			'install_with_demo'  => 'all',
		),
        array(
            'name'               => 'Comment Ace',
            // The plugin name.
            'slug'               => 'comment-ace',
            // The plugin slug (typically the folder name).
            'description'        => esc_html__( 'Comments manager', 'bimber' ),
            'source'             => $theme_dir . 'includes/plugins/zip/comment-ace.zip',
            // The plugin source.
            'required'           => false,
            // If false, the plugin is only 'recommended' instead of required.
            'version'            => '1.0.8',
            // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
            'force_activation'   => false,
            // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false,
            // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '',
            // If set, overrides default API URL and points to an external URL.
            'local_plugin'		 => true,
            'install_with_demo'  => 'all',
        ),
		array(
			'name'               => 'PhotoMix',
			// The plugin name.
			'slug'               => 'photomix',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Mix photos without a graphics editor', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/photomix.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '1.0.3',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'local_plugin'		 => true,
			'install_with_demo'  => 'all',
		),
		array(
			'name'               => 'WPBakery Page Builder',
			// The plugin name.
			'slug'               => 'js_composer',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Frontend and backend page builder', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/js_composer.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '6.10.0',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'install_with_demo'  => 'all',
		),
		array(
			'name'               => 'Envato Market',
			// The plugin name.
			'slug'               => 'envato-market',
			// The plugin slug (typically the folder name).
			'description'        => esc_html__( 'Automatic theme updates', 'bimber' ),
			'source'             => $theme_dir . 'includes/plugins/zip/envato-market.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '2.0.8',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'install_with_demo'  => 'all',
		),
		array(
			'name'              => 'WP GDPR Compliance',
			// The plugin name.
			'slug'              => 'wp-gdpr-compliance',
			// The plugin slug (typically the folder name).
			'required'          => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'  => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			// If set, plugin will not be installed during demo data installation.
			'install_with_demo' => 'none',
		),
		array(
			'name'               => 'Easy Social Share Buttons',
			// The plugin name.
			'slug'               => 'easy-social-share-buttons3',
			// The plugin slug (typically the folder name).
			'source'             => $theme_dir . 'includes/plugins/zip/easy-social-share-buttons3.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '8.8',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'install_with_demo' => 'none',
		),
		array(
			'name'               => 'Youzer',
			// The plugin name.
			'slug'               => 'youzer',
			// The plugin slug (typically the folder name).
			'source'             => $theme_dir . 'includes/plugins/zip/youzer.zip',
			// The plugin source.
			'required'           => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '2.6.2',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'install_with_demo' => 'none',
		),
		array(
			'name'     => 'Elementor',
			// The plugin name.
			'slug'     => 'elementor',
			// The plugin slug (typically the folder name).
			'required' => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'  => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			'install_with_demo' => array( 'affiliate', 'news', 'adsmania' ),
		),

		// @todo - Needs to be last entry so setup wizard won't affect other plugins.
		array(
			'name'              => 'WooCommerce',
			// The plugin name.
			'slug'              => 'woocommerce',
			// The plugin slug (typically the folder name).
			'required' => false,
			// If false, the plugin is only 'recommended' instead of required.
			'version'           => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented.
			// If set, plugin will not be installed during demo data installation.
			'install_with_demo' => array( 'affiliate' ),
		),
	);

	return apply_filters( 'bimber_tgm_plugins_config', $config );
}

/**
 * Register TGM plugins
 */
function bimber_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = bimber_get_theme_plugins_config();

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'           => 'bimber',                    // Text domain - likely want to be the same as your theme.
		'default_path'     => '',                            // Default absolute path to pre-packaged plugins
		'parent_slug'      => 'themes.php',                // Default parent menu slug
		'menu'             => 'install-required-plugins',    // Menu slug
		'has_notices'      => true,                        // Show admin notices or not
		'is_automatic'     => true,                        // Automatically activate plugins after installation or not
		'message'          => '',                            // Message to output right before the plugins table.
		'strings'          => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'bimber' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'bimber' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'bimber' ),
			// %1$s = plugin name
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'bimber' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'bimber' ),
			// %1$s = plugin name(s)
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'bimber' ),
			// %1$s = plugin name(s)
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'bimber' ),
			// %1$s = plugin name(s)
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'bimber' ),
			// %1$s = plugin name(s)
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'bimber' ),
			// %1$s = plugin name(s)
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'bimber' ),
			// %1$s = plugin name(s)
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'bimber' ),
			// %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'bimber' ),
			// %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'bimber' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'bimber' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'bimber' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'bimber' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'bimber' ),
			// %1$s = dashboard link .
			'nag_type'                        => esc_html__( 'updated', 'bimber' ),
			// Determines admin notice type - can only be 'updated' or 'error'.
		),
	);

	tgmpa( $plugins, $config );
}
