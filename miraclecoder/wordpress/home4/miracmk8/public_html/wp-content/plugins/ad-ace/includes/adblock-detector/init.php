<?php
/**
 * Init Ads
 *
 * @package AdAce.
 * @subpackage Sponsors.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Load common parts.
require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/adblock-detector/functions.php' );

if ( is_admin() ) {
	require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/options-page-adblock-detector.php' );

	add_filter( 'display_post_states', 'adace_add_display_post_states', 10, 2 );
}

require_once( trailingslashit( adace_get_plugin_dir() ) . 'includes/adblock-detector/functions.php' );


add_filter( 'adace_options_defaults', 'adace_options_add_adblock_detector_defaults' );
add_action( 'admin_notices',            'adace_scripts_blocked_error_notice' );
add_action( 'admin_enqueue_scripts',    'adace_scripts_blocked_detection_scripts' );

/**
 * Add Shop The Post Defaults.
 *
 * @param array $option_key Key to get default for.
 * @return mixed Default value or false.
 */
function adace_options_add_adblock_detector_defaults( $defaults ) {
	$defaults = array_merge( $defaults, array(
		'adace_adblock_detector_enabled' => 'none',
		'adace_adblock_detector_title' => 'Ad Blocker Detected!',
		'adace_adblock_detector_description' => 'Advertisements fund this website. Please disable your adblocking software or whitelist our website.<br>Thank You!',
		'adace_adblock_detector_page' => '-1',
	) );
	return $defaults;
}


/**
 * Render the Scripts Blocked error notice
 */
function adace_scripts_blocked_error_notice() {
	?>
	<div id="adace-scripts-blocked" class="notice notice-error" style="display: none !important;">
		<p>
			<strong><?php _ex( 'AdAce plugin name rewrite required', 'AdBlocker detection status', 'adace' ); ?></strong>
		</p>
		<p>
			<?php _ex( 'The AdAce plugin\'s scripts have been blocked by an adblocker extension running in your browser. To use the AdAce plugin and all its modules, you have to rewrite plugin name. ', 'AdBlocker detection status', 'adace' ); ?>
		</p>
		<p>
			<a href="<?php echo esc_url( admin_url( 'options-general.php?page=' . adace_options_page_slug() . '&tab=adace_advanced' ) ); ?>" class="button-primary"><?php _ex( 'Rewrite plugin name', 'AdBlocker detection status', 'adace' ); ?></a>
			<a href="https://bimber.bringthepixel.com/docs/adace-scripts-blocked/" class="button-secondary" target="_blank"><?php _ex( 'Learn more about the problem', 'AdBlocker detection status', 'adace' ); ?></a>
		</p>
	</div>
	<script>
		// The error is hidden by default. Show it only if necessary.
		if (!window.adaceScriptsLoaded) {
			document.getElementById('adace-scripts-blocked').style.display = 'block';
		}
	</script>
	<?php
}

/**
 * Load script that removes the Scripts Blocked error notice if scripts are not blocked
 */
function adace_scripts_blocked_detection_scripts() {
	// Load on all admin pages.
	wp_enqueue_script( 'adace-scripts-blocked-detection', adace_get_plugin_url() . 'assets/js/scripts-blocked-detection.js', array(), adace_get_plugin_version(), false );
}
