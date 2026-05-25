<?php
/**
 * Positions
 *
 * @package Bimber
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Return all available share positions
 *
 * @return array
 */
function bimber_get_share_positions() {
	$positions = array(
		'microshares' => array(
			'name'           => _x( 'Post Content Images', 'Shares', 'bimber' ),
			'type'           => _x( 'built-in', 'Shares > Position Type', 'bimber' ),
			'networks'       => array( 'pinterest' ,'facebook', 'twitter' ),
			'networks_order' => 'pinterest,facebook,twitter',
			'availability' => array(
				'check' => '__return_true',
				'info'  => '',
			),
			'is_editable' => true,
		),
		'mycred_badge' => array(
			'name'           => _x( 'New Badge Popup', 'Shares', 'bimber' ),
			'type'           => _x( 'myCRED plugin integration', 'Shares > Position Type', 'bimber' ),
			'networks'       => array( 'facebook', 'twitter' ),
			'networks_order' => 'facebook,twitter',
			'availability' => array(
				'check' => bimber_can_use_plugin( 'mycred/mycred.php' ),
				'info'  => sprintf( esc_html__( '%s plugin is not activated', 'bimber' ), 'myCRED' ),
			),
			'is_editable' => true,
		),
		'mycred_rank' => array(
			'name'           => _x( 'New Rank Popup', 'Shares', 'bimber' ),
			'type'           => _x( 'myCRED plugin integration', 'Shares > Position Type', 'bimber' ),
			'networks'       => array( 'facebook', 'twitter' ),
			'networks_order' => 'facebook,twitter',
			'availability' => array(
				'check' => bimber_can_use_plugin( 'mycred/mycred.php' ),
				'info'  => sprintf( esc_html__( '%s plugin is not activated', 'bimber' ), 'myCRED' ),
			),
			'is_editable' => true,
		),
		'mace_gallery' => array(
			'name'           => _x( 'Gallery Lightbox', 'Shares', 'bimber' ),
			'type'           => _x( 'MediaAce plugin integration', 'Shares > Position Type', 'bimber' ),
			'networks'       => array( 'pinterest' ,'facebook', 'twitter' ),
			'networks_order' => 'pinterest,facebook,twitter',
			'availability' => array(
				'check' => bimber_can_use_plugin( 'media-ace/media-ace.php' ),
				'info'  => sprintf( esc_html__( '%s plugin is not activated', 'bimber' ), 'MediaAce' ),
			),
			'is_editable' => true,
		),
		'snax' => array(
			'name'           => _x( 'Snax', 'Shares', 'bimber' ),
			'type'           => _x( 'plugin', 'Shares > Position Type', 'bimber' ),
			'availability' => array(
				'check' => bimber_can_use_plugin( 'snax/snax.php' ),
				'info'  => sprintf( esc_html__( '%s plugin is not activated', 'bimber' ),  'Snax' ),
			),
			'is_editable'   => false,
			'edit_page_url' => admin_url( 'admin.php?page=snax-shares-positions-settings' ),
		),
		'essb' => array(
			'name'           => _x( 'Easy Social Share Buttons for WordPress', 'Shares', 'bimber' ),
			'type'           => _x( 'plugin', 'Shares > Position Type', 'bimber' ),
			'availability' => array(
				'check' => bimber_can_use_plugin( 'easy-social-share-buttons3/easy-social-share-buttons3.php' ),
				'info'  => sprintf( esc_html__( '%s plugin is not activated', 'bimber' ), 'Easy Social Share Button for WordPress' ),
			),
			'is_editable'   => false,
			'edit_page_url' => admin_url( 'admin.php?page=essb_redirect_where' ),
		),
		'mashshare' => array(
			'name'           => _x( 'Mashshare Share Buttons', 'Shares', 'bimber' ),
			'type'           => _x( 'plugin', 'Shares > Position Type', 'bimber' ),
			'availability' => array(
				'check' => bimber_can_use_plugin( 'mashsharer/mashshare.php' ),
				'info'  => sprintf( esc_html__( '%s plugin is not activated', 'bimber' ), 'MashShare Share Buttons' ),
			),
			'is_editable'   => false,
			'edit_page_url' => admin_url( 'admin.php?page=mashsb-settings#mashsb_settingslocation_header' ),
		),
	);

	return apply_filters( 'bimber_share_positions', $positions );
}

/**
 * Return active share positions
 *
 * @return array
 */
function bimber_get_active_share_positions() {
	if ( ! bimber_shares_enabled() ) {
		return array();
	}

	$positions = bimber_get_theme_option( 'shares', 'positions' );

	// Option not set.
	if ( false === $positions ) {
		// Load default.
		$positions = bimber_get_share_positions();

        // Legacy.
        if ( ! bimber_microshares_enabled() && isset( $positions['microshares'] ) ) {
            unset( $positions['microshares'] );
        }

		return array_keys( $positions );
	}

	return ! empty( $positions['active'] ) ? $positions['active'] : array();
}

/**
 * Check whether the position is active
 *
 * @param string $position      Position id.
 *
 * @return bool
 */
function bimber_is_active_share_position( $position ) {
	$active_positions = bimber_get_active_share_positions();

	return in_array( $position, $active_positions, true );
}

/**
 * Return share position active networks (ordered)
 *
 * @param string $position      Position id.
 *
 * @return array|WP_Error
 */
function bimber_get_share_position_active_networks( $position ) {
	$positions = bimber_get_theme_option( 'shares', 'positions' );

	// Option not set.
	if ( false === $positions ) {
		// Load default.
		$positions = bimber_get_share_positions();
	}

	if ( isset( $positions[ $position ] ) && isset( $positions[ $position ]['networks'] ) ) {
		// Sort.
		$order = bimber_get_share_position_networks_order( $position );
		$active_networks = $positions[ $position ]['networks'];

		foreach ( $order as $index => $network ) {
			if ( ! in_array( $network, $active_networks ) ) {
				unset( $order[ $index ] );
			}
		}

		return $order;
	}

	return array();
}

/**
 * Return share position networks order
 *
 * @param string $position      Position id.
 *
 * @return array|WP_Error
 */
function bimber_get_share_position_networks_order( $position ) {
	$positions = bimber_get_theme_option( 'shares', 'positions' );

	// Option not set.
	if ( false === $positions ) {
		// Load default.
		$positions = bimber_get_share_positions();
	}

	if ( isset( $positions[ $position ] ) && isset( $positions[ $position ]['networks_order'] ) ) {
		$order_str =  $positions[ $position ]['networks_order'];
		return explode( ',', $order_str );
	}

	return array();
}
