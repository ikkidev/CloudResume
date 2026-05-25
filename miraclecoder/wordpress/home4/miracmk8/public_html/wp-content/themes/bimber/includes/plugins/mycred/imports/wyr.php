<?php
/**
 * MyCred wyr import
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

add_action( 'bimber_mycred_import_wyr', 'bimber_mycred_import_wyr' );

/**
 * Import MyCred settings for Bimber
 */
function bimber_mycred_import_wyr() {

	$badges = [
		'reaction' => [
			'name' 		 => 'Emoji Addict',
			'manual' 	 => '0',
			'excerpt'	 => '',
			'main_image' => 'badge-reaction-0.svg',
			'importslug' => 'badge-reaction',
			'menu_order' => 5,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-reaction-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'whats-your-reaction',
						'amount' => 50,
						'by' => 'sum',
						],
					),
					'reward' => [
						'type' => 'mycred_default',
						'log' => '',
						'amount' => 0,
						],
				],
				1 => [
					'attachment_id'	=> 'badge-reaction-2.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'whats-your-reaction',
						'amount' => 100,
						'by' => 'sum',
						],
					),
					'reward' => [
						'type' => 'mycred_default',
						'log' => '',
						'amount' => 0,
						],
				],
				2 => [
					'attachment_id'	=> 'badge-reaction-3.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'whats-your-reaction',
						'amount' => 200,
						'by' => 'sum',
						],
					),
					'reward' => [
						'type' => 'mycred_default',
						'log' => '',
						'amount' => 0,
						],
				],
				3 => [
					'attachment_id'	=> 'badge-reaction-4.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'whats-your-reaction',
						'amount' => 500,
						'by' => 'sum',
						],
					),
					'reward' => [
						'type' => 'mycred_default',
						'log' => '',
						'amount' => 0,
						],
				],
				4 => [
					'attachment_id'	=> 'badge-reaction-5.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'whats-your-reaction',
						'amount' => 1000,
						'by' => 'sum',
						],
					),
					'reward' => [
						'type' => 'mycred_default',
						'log' => '',
						'amount' => 0,
						],
				],
			],
		],
	];

	$hook_prefs = json_decode( '{"whats-your-reaction":{"creds":"1","log":"Reacted to %post_title% with: %reaction%"}}', true );
	$active = json_decode( '["whats-your-reaction"]', true );

	bimber_mycred_add_hooks( $active, $hook_prefs );
	bimber_mycred_add_badge( $badges['reaction'] );
}
