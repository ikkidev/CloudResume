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

add_action( 'bimber_mycred_import_snax', 'bimber_mycred_import_snax' );

/**
 * Import MyCred settings for Bimber
 */
function bimber_mycred_import_snax() {

	$badges = [
		'forum' => [
			'name' 		 => 'Up/Down Voter',
			'manual' 	 => '0',
			'excerpt'	 => '',
			'main_image' => 'badge-voting-0.svg',
			'importslug' => 'badge-voting',
			'menu_order' => 97,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-voting-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_vote',
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
					'attachment_id'	=> 'badge-voting-2.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_vote',
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
					'attachment_id'	=> 'badge-voting-3.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_vote',
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
					'attachment_id'	=> 'badge-voting-4.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_vote',
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
					'attachment_id'	=> 'badge-voting-5.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_vote',
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
		'submissions' => [
			'name' 		 => 'Content Author',
			'manual' 	 => '0',
			'excerpt'	 => '',
			'main_image' => 'badge-submission-0.svg',
			'importslug' => 'badge-submission',
			'menu_order' => 98,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-submission-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'publishing_content',
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
					'attachment_id'	=> 'badge-submission-2.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'publishing_content',
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
					'attachment_id'	=> 'badge-submission-3.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'publishing_content',
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
					'attachment_id'	=> 'badge-submission-4.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'publishing_content',
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
					'attachment_id'	=> 'badge-submission-5.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'publishing_content',
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
		'quizz_maker' => [
			'name' 		 => 'Quiz Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first quiz',
			'main_image' => 'badge-author-quiz-0.svg',
			'importslug' => 'badge-author-quiz',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-quiz-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_quiz',
						'amount' => 5,
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
		'poll_maker' => [
			'name' 		 => 'Poll Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first poll',
			'main_image' => 'badge-author-poll-0.svg',
			'importslug' => 'badge-author-poll',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-poll-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_poll',
						'amount' => 5,
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
		'text_maker' => [
			'name' 		 => 'Story Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first story',
			'main_image' => 'badge-author-story-0.svg',
			'importslug' => 'badge-author-story',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-story-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_text',
						'amount' => 5,
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
		'image_maker' => [
			'name' 		 => 'Image Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first image',
			'main_image' => 'badge-author-image-0.svg',
			'importslug' => 'badge-author-image',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-image-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_image',
						'amount' => 5,
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
		'audio_maker' => [
			'name' 		 => 'Audio Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first audio',
			'main_image' => 'badge-author-audio-0.svg',
			'importslug' => 'badge-author-audio',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-audio-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_audio',
						'amount' => 5,
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
		'video_maker' => [
			'name' 		 => 'Video Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first video',
			'main_image' => 'badge-author-video-0.svg',
			'importslug' => 'badge-author-video',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-video-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_video',
						'amount' => 5,
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
		'gallery_maker' => [
			'name' 		 => 'Gallery Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first gallery',
			'main_image' => 'badge-author-gallery-0.svg',
			'importslug' => 'badge-author-gallery',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-gallery-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_gallery',
						'amount' => 5,
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
		'embed_maker' => [
			'name' 		 => 'Embed Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first embed',
			'main_image' => 'badge-author-embed-0.svg',
			'importslug' => 'badge-author-embed',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-embed-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_embed',
						'amount' => 5,
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
		'list_maker' => [
			'name' 		 => 'List Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first list',
			'main_image' => 'badge-author-list-0.svg',
			'importslug' => 'badge-author-list',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-list-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_list',
						'amount' => 5,
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
		'meme_maker' => [
			'name' 		 => 'Meme Maker',
			'manual' 	 => '0',
			'excerpt'	 => 'Publish first meme',
			'main_image' => 'badge-author-meme-0.svg',
			'importslug' => 'badge-author-meme',
			'menu_order' => 99,
			'levels'	 => [
				0 => [
					'attachment_id'	=> 'badge-author-meme-1.svg',
					'image_url' => '',
					'label' => '',
					'compare' => 'AND',
					'requires' => array([
						'type' => 'mycred_default',
						'reference' => 'snax_format_meme',
						'amount' => 5,
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

	$hook_prefs = json_decode( '{
		"snax_vote":{"post_creds":"1","post_log":"Voted on %post_title%","item_creds":"1","item_log":"Voted on %post_title%"},
		"snax_format":{"text_creds":"5","text_log":"Published %snax_format%: %post_title%","image_creds":"5","image_log":"Published %snax_format%: %post_title%","audio_creds":"5","audio_log":"Published %snax_format%: %post_title%","video_creds":"5","video_log":"Published %snax_format%: %post_title%","gallery_creds":"5","gallery_log":"Published %snax_format%: %post_title%","embed_creds":"5","embed_log":"Published %snax_format%: %post_title%","list_creds":"5","list_log":"Published %snax_format%: %post_title%","ranked_list_creds":"5","ranked_list_log":"Published %snax_format%: %post_title%","classic_list_creds":"5","classic_list_log":"Published %snax_format%: %post_title%","meme_creds":"5","meme_log":"Published %snax_format%: %post_title%","trivia_quiz_creds":"5","trivia_quiz_log":"Published %snax_format%: %post_title%","personality_quiz_creds":"5","personality_quiz_log":"Published %snax_format%: %post_title%","classic_poll_creds":"5","classic_poll_log":"Published %snax_format%: %post_title%","versus_poll_creds":"5","versus_poll_log":"Published %snax_format%: %post_title%","binary_poll_creds":"5","binary_poll_log":"Published %snax_format%: %post_title%"},
		"publishing_content":{"post":{"creds":"5","limit":"0/x","log":"%plural% for new Post"},"page":{"creds":"0","limit":"0/x","log":"%plural% for new Page"}}
	}', true );
	$active = json_decode( '["snax_vote","snax_format","publishing_content"]', true );

	bimber_mycred_add_hooks( $active, $hook_prefs );
	foreach ( $badges as $badge ) {
		bimber_mycred_add_badge( $badge );
	}
}
