<?php
/**
 * Share Buttons
 *
 * @package Bimber
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'wp_enqueue_scripts', 'bimber_shares_enqueue_assets' );

/**
 * Load JS/CSS resources
 */
function bimber_shares_enqueue_assets() {
	$parent_uri      = trailingslashit( get_template_directory_uri() );
	$version         = bimber_get_theme_version();
	$scripts_version = bimber_get_scripts_version();

	// Register script here, it will be loaded on demand only when needed.
	wp_register_script( 'bimber-shares', $parent_uri . 'js/shares'. $scripts_version .'.js', array( 'jquery' ), $version, true );

	// Microshares.
	if ( bimber_microshares_enabled() && is_singular() ) {
		ob_start();
		get_template_part( 'template-parts/post/microshare' );
		$microshares_tpl = ob_get_clean();
	} else {
		$microshares_tpl = '';
	}

	$config = array(
		'debug_mode'    => bimber_shares_debug_mode_enabled(),
		'facebook_sdk'  => array(
			'url'               => sprintf( 'https://connect.facebook.net/%s/sdk.js', get_locale() ),
			'app_id'	        => bimber_get_facebook_app_id(),
			'version'	        => 'v5.0',
		),
		'microshares'   => array(
			'tpl'       => $microshares_tpl,
			'domain'    => trailingslashit( home_url() ),
		),
		'i18n' => array(
			'fb_app_id_not_set' => _x( 'Facebook App Id not set in Theme Options > Shares', 'Shares', 'bimber' ),
		),
	);

	$config  = apply_filters( 'bimber_shares_config', $config );

	wp_localize_script( 'bimber-shares', 'bimber_shares_config', $config );
}

/**
 * Return Facebook share button
 *
 * @param array $args       Buttons config.
 *
 * @return string           Button HTML.
 */
function bimber_facebook_share_button( $args ) {
	// Load dependencies.
	wp_enqueue_script( 'bimber-shares' );

	$args = wp_parse_args( $args, array(
		'share_url'     => '',
		'share_text'    => '',
		'classes'       => array(),
		'label'         => esc_html_x( 'Share on Facebook', 'Shares', 'bimber' ),
		'on_share'      => '',
	));

	// Add class for JS logic.
	$args['classes'][] = 'bimber-share-facebook';

	// When JS fails, share still be possible.
	$share_fallback_url = sprintf( 'https://www.facebook.com/dialog/share?app_id=%s&display=popup&href=%s&quote=%s', bimber_get_facebook_app_id(), $args['share_url'], $args['share_text'] );

	// Format HTML.
	$button = sprintf( '<a class="%s" href="%s" title="%s" data-share-url="%s" data-share-text="%s" data-on-share-callback="%s" target="_blank" rel="nofollow">%s</a>',
		implode( ' ', array_map( 'sanitize_html_class', $args['classes'] ) ),
		esc_url( $share_fallback_url ),
		esc_html_x( 'Share on Facebook', 'Shares', 'bimber' ),
		strpos( $args['share_url'], 'http' ) === 0 ? esc_url( $args['share_url'] ) : esc_attr( $args['share_url'] ),
		esc_attr( $args['share_text'] ),
		esc_js( $args['on_share'] ),
		esc_html( $args['label'] )
	);

	return apply_filters( 'bimber_facebook_share_button_html', $button, $args );
}

/**
 * Render Facebook share button
 *
 * @param array $args       Buttons config.
 */
function bimber_render_facebook_share_button( $args ) {
	echo bimber_facebook_share_button( $args );
}

/**
 * Return Twitter share button
 *
 * @param array $args       Buttons config.
 *
 * @return string           Button HTML.
 */
function bimber_twitter_share_button( $args ) {
	// Load dependencies.
	wp_enqueue_script( 'bimber-shares' );

	$args = wp_parse_args( $args, array(
		'share_url'     => '',
		'share_text'    => '',
		'classes'       => array(),
		'label'         => esc_html_x( 'Share on Twitter', 'Shares', 'bimber' ),
	));

	// Add class for JS logic.
	$args['classes'][] = 'bimber-share-twitter';

	$twitter_url = add_query_arg( array(
		'url'  => $args['share_url'],
		'text' => $args['share_text'] . ' ', // Without the space at the end, WP trims the "?" from the end of the text.
	), '//twitter.com/intent/tweet' );

	// Format HTML.
	$button = sprintf( '<a class="%s" href="%s" title="%s" target="_blank" rel="nofollow">%s</a>',
		implode( ' ', array_map( 'sanitize_html_class', $args['classes'] ) ),
		esc_url( $twitter_url ),
		esc_html_x( 'Share on Twitter', 'Shares', 'bimber' ),
		esc_attr( $args['label'] )
	);

	return apply_filters( 'bimber_facebook_share_button_html', $button, $args );
}

/**
 * Render Twitter share button
 *
 * @param array $args       Buttons config.
 */
function bimber_render_twitter_share_button( $args ) {
	echo bimber_twitter_share_button( $args );
}

/**
 * Return Pinterest share button
 *
 * @param array $args       Buttons config.
 *
 * @return string           Button HTML.
 */
function bimber_pinterest_share_button( $args ) {
	// Load dependencies.
	wp_enqueue_script( 'bimber-shares' );

	$args = wp_parse_args( $args, array(
		'share_url'     => '',
		'share_text'    => '',
		'share_media'   => '',
		'classes'       => array(),
		'label'         => esc_html_x( 'Share on Pinterest', 'Shares', 'bimber' ),
	));

	// Add class for JS logic.
	$args['classes'][] = 'bimber-share-pinterest';

	$pinterest_url = add_query_arg( array(
		'url'           => $args['share_url'],
		'description'   => $args['share_text'],
		'media'         => $args['share_media'],
	), 'https://pinterest.com/pin/create/button' );

	// Format HTML.
	$button = sprintf( '<a class="%s" href="%s" title="%s" target="_blank" rel="nofollow">%s</a>',
		implode( ' ', array_map( 'sanitize_html_class', $args['classes'] ) ),
		esc_url( $pinterest_url ),
		esc_html_x( 'Share on Pinterest', 'Shares', 'bimber' ),
		esc_attr( $args['label'] )
	);

	return apply_filters( 'bimber_facebook_share_button_html', $button, $args );
}

/**
 * Render Pinterest share button
 *
 * @param array $args       Buttons config.
 */
function bimber_render_pinterest_share_button( $args ) {
	echo bimber_pinterest_share_button( $args );
}
