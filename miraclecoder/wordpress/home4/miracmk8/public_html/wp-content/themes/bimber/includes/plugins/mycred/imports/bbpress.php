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

add_action( 'bimber_mycred_import_bbpress', 'bimber_mycred_import_bbpress' );

/**
 * Import MyCred settings for Bimber
 */
function bimber_mycred_import_bbpress() {

	$badges = [
		'forum' => [
			'name' 		 => 'Forum Buddy',
			'manual' 	 => '0',
			'excerpt'	 => '',
			'main_image' => 'badge-forum-0.svg',
			'importslug' => 'badge-forum',
			'menu_order' => 6,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-forum-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'new_forum_reply',
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
					'attachment_id'	=> 'badge-forum-2.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'new_forum_reply',
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
					'attachment_id'	=> 'badge-forum-3.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'new_forum_reply',
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
					'attachment_id'	=> 'badge-forum-4.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'new_forum_reply',
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
					'attachment_id'	=> 'badge-forum-5.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'new_forum_reply',
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

	$hook_prefs = json_decode( '{"hook_bbpress":{"new_forum":{"creds":"1","limit":"0\/x","log":"%plural% for new forum"},"delete_forum":{"creds":"0","log":"%singular% deduction for deleted forum"},"new_topic":{"creds":"1","limit":"0\/x","log":"%plural% for new forum topic","author":1},"delete_topic":{"creds":"0","log":"%singular% deduction for deleted topic"},"fav_topic":{"creds":"1","limit":"0\/x","log":"%plural% for someone favorited your forum topic"},"new_reply":{"creds":"1","limit":"0\/x","log":"%plural% for new forum reply","author":1},"delete_reply":{"creds":"0","log":"%singular% deduction for deleted reply"},"show_points_in_reply":0,"show_points_in_profile":0}}', true );
	$active = json_decode( '["hook_bbpress"]', true );

	bimber_mycred_add_hooks( $active, $hook_prefs );
	bimber_mycred_add_badge( $badges['forum'] );
}
