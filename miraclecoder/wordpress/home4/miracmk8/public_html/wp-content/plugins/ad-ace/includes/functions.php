<?php
/**
 * Common Functions
 *
 * @package AdAce.
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'plugins_loaded', 'adace_load_textdomain' );
/**
 * Load plugin textdomain.
 */
function adace_load_textdomain() {
	load_plugin_textdomain( 'adace', false, 'ad-ace/languages' );
}

add_action( 'admin_enqueue_scripts', 'adace_admin_enqueue_scripts' );
/**
 * Register Admin Scripts
 *
 * @param string $hook Current page.
 */
function adace_admin_enqueue_scripts( $hook ) {
    $supported_pages = apply_filters( 'adace_admin_scripts_supported_pages', array( 'post.php', 'post-new.php', 'edit-tags.php', 'term.php', 'customize.php', 'widgets.php', 'settings_page_adace_options' ) );

	if ( in_array( $hook, $supported_pages, true ) ) {
		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'adace-admin', adace_get_plugin_url() . 'assets/js/admin.js', array(), adace_get_plugin_version() );
		wp_localize_script( 'adace-admin', 'AdaceAdminVars',
			array(
			    'i18n' => array(
			        'clear_selection' => _x( 'Clear', 'Admin UI', 'adace' ),
			        'start_typing'    => _x( 'Start typing...', 'Admin UI', 'adace' ),
                ),
				'plugins' => array(
					'is_woocommerce' => adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ? true : false,
				),
			)
		);
	}
}

add_action( 'wp_enqueue_scripts', 'adace_front_enqueue_styles' );
/**
 * Register Front Styles
 */
function adace_front_enqueue_styles() {
	$ver = adace_get_plugin_version();

	wp_enqueue_style( 'adace-style', adace_get_plugin_url() . 'assets/css/style.min.css', array(), $ver );
	wp_style_add_data( 'adace-style', 'rtl', 'replace' );
}

add_action( 'style_loader_tag', 'adace_fix_rtl_styles', 10, 4 );
/**
 * Fix RTL styles.
 *
 * @param string $html   The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @param string $href   The stylesheet's source URL.
 * @param string $media  The stylesheet's media attribute.
 * @return string
 */
function adace_fix_rtl_styles( $html, $handle, $href, $media ){
	if ( strpos( $handle, 'adace-' ) > -1 ) {
		$html = str_replace( '.min-rtl', '-rtl.min', $html );
	}
	return $html;
}

add_action( 'wp_enqueue_scripts', 'adace_front_enqueue_scripts' );
/**
 * Register Front Styles
 */
function adace_front_enqueue_scripts() {
	$ver = adace_get_plugin_version();

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'adace-slot-slideup', adace_get_plugin_url() . 'assets/js/slot-slideup.js', array( 'jquery' ), $ver, false );

	wp_register_script( 'adace-slot-vignette', adace_get_plugin_url() . 'assets/js/slot-vignette.js', array(), $ver, false );
}

add_action( 'admin_enqueue_scripts', 'adace_admin_enqueue_styles' );
/**
 * Register Admin Scripts
 */
function adace_admin_enqueue_styles() {
	$ver = adace_get_plugin_version();

	wp_enqueue_style( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'adace-admin-style', adace_get_plugin_url() . 'assets/css/admin.css', array(), $ver );
}

/**
 * Replace first occurence of a string
 *
 * @param str $needle   Needle.
 * @param str $replace  Replacement.
 * @param str $haystack Haystack.
 * @return str
 */
function adace_str_replace_first( $needle, $replace, $haystack ) {
	$newstring = $haystack;
	$pos = strpos( $haystack, $needle );
	if ( false !== $pos ) {
		$newstring = substr_replace( $haystack, $replace, $pos, strlen( $needle ) );
	}
	return $newstring;
}

/**
 * Replaces regexp matches with uniqe temporary tags.
 *
 * @param str $regexp  Regular expression.
 * @param str $string  Haystack.
 * @return array   New string and array with old values to revert
 */
function adace_preg_make_unique( $regexp, $string ) {
	$replacements = array();
	preg_match_all( $regexp, $string, $matches );
	foreach ( $matches[0] as $match ) {
		$replace = '<!--UNIQUEMATCH' . uniqid() . '-->';
		$replacements[ $replace ] = $match;
		$string = adace_str_replace_first( $match, $replace, $string );
	}
	return array(
		'string'       => $string,
		'replacements' => $replacements,
	);
}

/**
 * Reverts adace_preg_make_unique() using it's own return value.
 *
 * @param array $args Exactly as return of adace_preg_make_unique().
 * @return str
 */
function adace_preg_make_unique_revert( $args ) {
	$string = $args['string'];
	$replacements = $args['replacements'];

	foreach ( $replacements as $key => $value ) {
		$string = str_replace( $key, $value, $string );
	}

	return $string;
}

/**
 * Shuffle array by string seed
 *
 * @param array  $array  Array.
 * @param string $seed   Randomization seed.
 * @return array
 */
function adace_seed_shuffle_array( $array, $seed ) {
	mt_srand( crc32( $seed ) );
	$order = array_map( function( $val ) {
		return mt_rand();
	}, range( 1, count( $array ) ) );
	array_multisort( $order, $array );
	return $array;
}

add_filter( 'plugin_action_links', 'adace_add_plugin_settings_link', 10, 2 );

function adace_add_plugin_settings_link( $links, $file ) {
    $basename = adace_get_plugin_basename();

    if ( is_plugin_active( $basename ) && $basename === $file ) {
        $links[] = '<a href="' . esc_url( admin_url( add_query_arg( array( 'page' => 'adace_options' ), 'admin.php' ) ) ) . '">'. esc_html__( 'Settings', 'adace' ) .'</a>';
    }

    return $links;
}
