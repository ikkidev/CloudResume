<?php
/**
 * Shares Core Functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber
**/

/**
 * Check whether the module is enabled
 *
 * @return bool
 */
function bimber_shares_enabled() {
	$enabled = 'standard' === bimber_get_theme_option( 'shares', 'enabled' );

	return apply_filters( 'bimber_shares_enabled', $enabled );
}

/**
 * Check whether the debug mode is enabled
 *
 * @return bool
 */
function bimber_shares_debug_mode_enabled() {
	$enabled = 'standard' === bimber_get_theme_option( 'shares', 'debug_mode' );

	return apply_filters( 'bimber_shares_debug_mode_enabled', $enabled );
}

/**
 * Check whether to share URLs in shortlink form
 *
 * @return bool
 */
function bimber_shares_use_shortlinks() {
	return apply_filters( 'bimber_shares_use_shortlinks', false );
}

/**
 * Return post share URL
 *
 * @param int $post         Optional. Post object or id.
 *
 * @return string
 */
function bimber_get_share_url( $post = 0 ) {
	$post = get_post( $post );

	if ( bimber_shares_use_shortlinks() ) {
		$url = wp_get_shortlink( $post );
	} else {
		$url = get_permalink( $post );
	}

	return $url;
}

/**
 * Check whether the Microshare are enabled
 *
 * @return string
 */
function bimber_microshares_enabled() {
	if ( ! bimber_shares_enabled() ) {
		return false;
	}

	$positions = bimber_get_theme_option( 'shares', 'positions' );

	// Option not set, use legacy.
	if ( false === $positions ) {
		$theme_options = get_option( bimber_get_theme_id() );

		$legacy_value = ! empty( $theme_options['post_microshare'] ) ? $theme_options['post_microshare'] : '';

		$enabled = 'standard' === $legacy_value;
	} else {
		$enabled = bimber_is_active_share_position( 'microshares' );
	}

	return apply_filters( 'bimber_microshares_enabled', $enabled );
}
