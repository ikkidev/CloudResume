<?php
/**
 * MyCred core import
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

add_action( 'bimber_mycred_import_core', 'bimber_mycred_import_core' );

/**
 * Import MyCred settings for Bimber
 */
function bimber_mycred_import_core() {

	$ranks = [
		'cat_1' => [
			'name' 	=> 'Participant',
			'ctype' => 'mycred_default',
			'min' 	=> '0',
			'max' 	=> '49',
			'img'	=> 'badge-ranking-1a.svg',
			'importslug' => 'badge-ranking-1a.svg',
		],
		'cat_2' => [
			'name' 	=> 'Enthusiast',
			'ctype' => 'mycred_default',
			'min' 	=> '50',
			'max' 	=> '99',
			'img'	=> 'badge-ranking-1b.svg',
			'importslug' => 'badge-ranking-1b.svg',
		],
		'cat_3' => [
			'name' 	=> 'Advocate',
			'ctype' => 'mycred_default',
			'min' 	=> '100',
			'max' 	=> '199',
			'img'	=> 'badge-ranking-1c.svg',
			'importslug' => 'badge-ranking-1c.svg',
		],
		'cat_4' => [
			'name' 	=> 'Contributor',
			'ctype' => 'mycred_default',
			'min' 	=> '200',
			'max' 	=> '499',
			'img'	=> 'badge-ranking-1d.svg',
			'importslug' => 'badge-ranking-1d.svg',
		],
		'cat_5' => [
			'name' 	=> 'Veteran',
			'ctype' => 'mycred_default',
			'min' 	=> '500',
			'max' 	=> '999',
			'img'	=> 'badge-ranking-1e.svg',
			'importslug' => 'badge-ranking-1e.svg',
		],
		'cat_6' => [
			'name' 	=> 'Expert',
			'ctype' => 'mycred_default',
			'min' 	=> '1000',
			'max' 	=> '1999',
			'img'	=> 'badge-ranking-2.svg',
			'importslug' => 'badge-ranking-2.svg',
		],
		'cat_7' => [
			'name' 	=> 'Mentor',
			'ctype' => 'mycred_default',
			'min' 	=> '2000',
			'max' 	=> '4999',
			'img'	=> 'badge-ranking-3.svg',
			'importslug' => 'badge-ranking-3.svg',
		],
		'cat_8' => [
			'name' 	=> 'Hero',
			'ctype' => 'mycred_default',
			'min' 	=> '5000',
			'max' 	=> '9999',
			'img'	=> 'badge-ranking-4.svg',
			'importslug' => 'badge-ranking-4.svg',
		],
		'cat_9' => [
			'name' 	=> 'Legend',
			'ctype' => 'mycred_default',
			'min' 	=> '10000',
			'max' 	=> '999999',
			'img'	=> 'badge-ranking-5.svg',
			'importslug' => 'badge-ranking-5.svg',
		],
	];

	$badges = [
		'years_of_membership' => [
			'name' 		 => 'Years Of Membership',
			'manual' 	 => '1',
			'excerpt'	 => 'Awards for veteran members.',
			'main_image' => 'badge-years-0.svg',
			'importslug' => 'badge-years',
			'menu_order' => 2,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-years-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
					'attachment_id'	=> 'badge-years-2.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
					'attachment_id'	=> 'badge-years-3.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
					'attachment_id'	=> 'badge-years-4.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
					'attachment_id'	=> 'badge-years-5.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
		'community_moderator' => [
			'name' 		 => 'Community Moderator',
			'manual' 	 => '1',
			'excerpt'	 => 'Helps ensure that all content and communications in the community are appropriate.',
			'main_image' => 'badge-community-moderator-0.svg',
			'importslug' => 'badge-community-moderator',
			'menu_order' => 3,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-community-moderator-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
		'community_verified' => [
			'name' 		 => 'Verified User',
			'manual' 	 => '1',
			'excerpt'	 => 'A verified member of the community',
			'main_image' => 'badge-community-verified-0.svg',
			'importslug' => 'badge-community-verified',
			'menu_order' => 4,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-community-verified-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 0,
						'amount' => 0,
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
	foreach ( $ranks as $rank ) {
		bimber_mycred_add_rank( $rank );
	}
	foreach ( $badges as $badge ) {
		bimber_mycred_add_badge( $badge );
	}
}
