<?php
/**
 * Cache functions
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

/**
 * Print dynamic styles and exit
 */
function bimber_load_dynamic_styles() {
	$print = filter_input( INPUT_GET, 'bimber-dynamic-style', FILTER_SANITIZE_NUMBER_INT );

	if ( 1 === intval( $print ) ) {
		bimber_print_dynamic_styles();
		exit;
	}
}

/**
 * Return dynamic styles content
 *
 * @return array
 */
function bimber_get_dynamic_styles() {
	require_once BIMBER_FRONT_DIR . 'lib/class-bimber-color.php';
	require_once BIMBER_FRONT_DIR . 'lib/class-bimber-color-generator.php';

	ob_start();
	require_once BIMBER_THEME_DIR . 'css/dynamic-style-global.php';
	require_once BIMBER_THEME_DIR . 'css/dynamic-style-header.php';
	require_once BIMBER_THEME_DIR . 'css/dynamic-style-footer.php';
	require_once BIMBER_THEME_DIR . 'css/dynamic-style-premade.php';

	$size    = ob_get_length();
	$content = ob_get_contents();
	$content = apply_filters( 'bimber_dynamic_styles', $content );
	ob_end_clean();

	return array(
		'content' => $content,
		'size'    => $size,
	);
}

/**
 * Render dynamic styles content
 *
 * @param bool $with_headers        If headers should be sent.
 */
function bimber_print_dynamic_styles( $with_headers = true ) {
	$dynamic_styles = bimber_get_dynamic_styles();

	if ( $with_headers ) {
		$bimber_cache_timeout = apply_filters( 'bimber_dynamic_style_cache_timeout', 0 );

		header( 'Pragma: public' ); // HTTP 1.0 .
		header( 'Cache-Control: public' );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $bimber_cache_timeout ) . ' GMT' );
		header( 'Content-Type: text/css' );
		header( 'Content-Length: ' . intval( $dynamic_styles['size'] ) );
	}

	echo ! empty( $dynamic_styles['content'] ) ? $dynamic_styles['content'] : '';
}

/**
 * Render dynamic styles based on theme options
 */
function bimber_internal_dynamic_styles() {
	$bimber_stack 	 = bimber_get_current_stack();
	$icon_style		 = bimber_get_theme_option( 'global', 'icon_style' );
	if ( 'default' === $icon_style && 'bunchy' === $bimber_stack ) {
		$icon_style = 'line';
	} elseif ( 'default' === $icon_style ) {
		$icon_style = 'solid';
	}
	?>
	<style>
	@font-face {
		font-family: "bimber";
		<?php if ( 'line' === $icon_style ) : ?>
			src:url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bunchy/fonts/bimber.eot");
			src:url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bunchy/fonts/bimber.eot?#iefix") format("embedded-opentype"),
			url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bunchy/fonts/bimber.woff") format("woff"),
			url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bunchy/fonts/bimber.ttf") format("truetype"),
			url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bunchy/fonts/bimber.svg#bimber") format("svg");
		<?php endif; ?>
		<?php if ( 'solid' === $icon_style ) : ?>
			src:url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bimber/fonts/bimber.eot");
			src:url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bimber/fonts/bimber.eot?#iefix") format("embedded-opentype"),
			url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bimber/fonts/bimber.woff") format("woff"),
			url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bimber/fonts/bimber.ttf") format("truetype"),
			url("<?php echo esc_url( BIMBER_THEME_DIR_URI );?>css/<?php echo esc_attr( bimber_get_css_theme_ver_directory() )?>/bimber/fonts/bimber.svg#bimber") format("svg");
		<?php endif; ?>
		font-weight: normal;
		font-style: normal;
		font-display: block;
	}
	</style>
	<?php
	if ( bimber_use_external_dynamic_style() ) {
		return;
	}
	?>
	<style type="text/css" media="screen" id="g1-dynamic-styles">
		<?php
		bimber_print_dynamic_styles( false );
		?>
	</style>
	<?php
}

/**
 * Change dynamic styles cache method after saving theme options
 *
 * @param string $old_value Old state.
 * @param string $new_value New state.
 */
function bimber_dynamic_style_theme_option_changed( $old_value, $new_value ) {
	// Cache options changed from inactive to active state.
	if ( 'external_css' !== $old_value['advanced_dynamic_style'] && 'external_css' === $new_value['advanced_dynamic_style'] ) {
		bimber_dynamic_style_mark_cache_as_stale();
	}
}

/**
 * Mark cache as stale
 */
function bimber_dynamic_style_mark_cache_as_stale() {
	$option_base = bimber_get_theme_id();
	$option_name = $option_base . '_cache_dynamic_style';

	if ( bimber_dynamic_style_is_cache_enabled() && bimber_dynamic_style_is_cache_dir_writable() ) {
		update_option( $option_name, true );
	} else {
		delete_option( $option_name );

		$use_cache_option_name = $option_base . '_use_dynamic_style_cache';
		delete_option( $use_cache_option_name );
	}
}

/**
 * Whether or not caching of dynamic styles is enabled.
 *
 * @return bool
 */
function bimber_dynamic_style_is_cache_enabled() {
	return 'external_css' === bimber_get_dynamic_style_type();
}

/**
 * Whether or not caching directory of dynamic styles is enabled.
 *
 * @return bool
 */
function bimber_dynamic_style_is_cache_dir_writable() {
	return wp_is_writable( bimber_dynamic_style_get_cache_dir() );
}

/**
 * Get the cache directory of dynamic styles.
 *
 * @return mixed|void
 */
function bimber_dynamic_style_get_cache_dir() {
	return apply_filters( 'bimber_dynamic_style_cache_dir', bimber_get_uploads_dir() );
}

/**
 * Get the URL of the dynamic styles file
 *
 * @return null|string
 */
function bimber_dynamic_style_get_file_url() {
	$query_var = apply_filters( 'bimber_dynamic_styles_query_var', 'bimber-dynamic-style' );

	// By default it's a php script (not cached).
	$url = home_url( '?'. $query_var .'=1' );

	if ( bimber_dynamic_style_is_cache_enabled() ) {
		// Reload cache.
		bimber_dynamic_style_rebuild_cache();
		$cached_file_url = bimber_dynamic_style_get_cached_file_url();

		if ( $cached_file_url ) {
			$url = $cached_file_url;
		}
	}

	return $url;
}

/**
 * Get the URL of the dynamic styles cached file
 *
 * @return null|string
 */
function bimber_dynamic_style_get_cached_file_url() {
	$option_base                   = bimber_get_theme_id();
	$use_dynamic_style_option_name = $option_base . '_use_dynamic_style_cache';
	$use_dynamic_style             = (bool) get_option( $use_dynamic_style_option_name );

	if ( $use_dynamic_style ) {
		return bimber_get_uploads_url() . bimber_get_dynamic_style_filename();
	} else {
		return null;
	}
}

/**
 * Rebuild cache of dynamic styles.
 */
function bimber_dynamic_style_rebuild_cache() {
	$option_base             = bimber_get_theme_id();
	$force_cache_option_name = $option_base . '_cache_dynamic_style';
	$force_cache             = (bool) get_option( $force_cache_option_name );
	if ( $force_cache ) {
		$file_cached = bimber_dynamic_style_cache_file();

		// Flag that indicates if we can use cached version.
		$use_cache_option_name = $option_base . '_use_dynamic_style_cache';

		if ( $file_cached ) {
			update_option( $use_cache_option_name, true );

			bimber_dynamic_style_log( __( 'Cache file was successfully saved on disk.', 'bimber' ), 'success' );
		} else {
			delete_option( $use_cache_option_name );

			bimber_dynamic_style_log(
				esc_html__( 'Caching process failed. Cache file was not saved on disk.', 'bimber' ) .
				'<br />' .
				esc_html__( 'Please check if the directory "wp-content/uploads" is writable by your web server.', 'bimber' ) .
                '<br />' .
                esc_html__( 'When fixed, please apply some changes in WP Customizer and publish them to rebuild cache file. Back here then to see if it works.', 'bimber' ),
				'error'
			);
		}

		// Regardless of whether caching was successful or not,
		// we need to remove this flag.
		// If options will be saved next time, this flag will be set again
		// and caching process will be repeated.
		delete_option( $force_cache_option_name );
	}
}

/**
 * Cache dynamic styles
 *
 * @return bool
 */
function bimber_dynamic_style_cache_file() {
	require_once ABSPATH . 'wp-admin/includes/file.php';

	WP_Filesystem();

	/**
	 * Safe way to use filesystem
	 *
	 * @var WP_Filesystem_Base $wp_filesystem
	 */
	global $wp_filesystem;

	if ( ! $wp_filesystem ) {
		return false;
	}

	// Fetch styles content.
	$dynamic_styles = bimber_get_dynamic_styles();

	$old_file = trailingslashit( bimber_dynamic_style_get_cache_dir() ) . bimber_get_dynamic_style_filename();

	// If save correctly, use cached version.
	if ( $wp_filesystem->exists( $old_file ) ) {
		$wp_filesystem->delete( $old_file );
	}

	bimber_generate_dynamic_style_token();
	$file = trailingslashit( bimber_dynamic_style_get_cache_dir() ) . bimber_get_dynamic_style_filename();

	if ( $wp_filesystem->put_contents( $file, $dynamic_styles['content'], FS_CHMOD_FILE ) ) {
		return true;
	}

	return false;
}

function bimber_get_dynamic_style_filename() {
	$token = bimber_get_dynamic_style_token();

	if ( $token ) {
		$filename = sprintf( 'dynamic-style-%s.css', $token );
	} else {
		$filename = 'dynamic-style.css';
	}

	return $filename;
}

function bimber_get_dynamic_style_token() {
	$option_base = bimber_get_theme_id();
	$option_name = $option_base . '_dynamic_style_token';

	return get_option( $option_name );
}

function bimber_generate_dynamic_style_token() {
	$option_base = bimber_get_theme_id();
	$option_name = $option_base . '_dynamic_style_token';

	update_option( $option_name, time() );
}

/**
 * Log a message about the current state of dynamic styles
 *
 * @param string $message A message to log.
 * @param string $type Type.
 */
function bimber_dynamic_style_log( $message, $type ) {
	$expire_after_one_hour = 60 * 60 * 1;

	$log_entry = array(
		'type'    => $type,
		'message' => $message,
		'date'    => date( 'F j, Y, g:i a' ),
	);

	set_transient( 'bimber_dynamic_style_cache_log', $log_entry, $expire_after_one_hour );
}

/**
 * Whether or not we use external file for dynamic styles
 *
 * @return bool
 */
function bimber_use_external_dynamic_style() {
	return 'internal' !== bimber_get_dynamic_style_type();
}

/**
 * Get the type of dynamic styles
 *
 * @return mixed|void
 */
function bimber_get_dynamic_style_type() {
	$type = bimber_get_theme_option( 'advanced', 'dynamic_style' );

	return apply_filters( 'bimber_dynamic_style_type', $type );
}
