<?php
namespace WPGDPRC\WordPress\Front;

use WP_Query;
use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Debug;
use WPGDPRC\Utils\Request;
use WPGDPRC\Utils\Session;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Cron;
use WPGDPRC\WordPress\Cron\DeactivateRequest;
use WPGDPRC\WordPress\Cron\ProcessRequest;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;
use WPGDPRC\WordPress\Shortcodes\RequestAccessForm;

/**
 * Class Actions
 * @package WPGDPRC\WordPress\Front
 */
class Actions {

	/**
	 * Admin constructor
	 */
	public static function init() {
		if ( Settings::isPremium() ) {
			return;
		}

		add_action( 'wp', [ self::class, 'checkSession' ] );

		if ( DataProcessor::isActive() ) {
			add_action( 'wp_footer', [ DataProcessor::class, 'renderBar' ], 1 );
			add_action( 'wp_footer', [ DataProcessor::class, 'renderModal' ], 999 );
		}

		add_action( 'update_option_' . Settings::getKey( Settings::KEY_ACCESS_ENABLE ), [ self::class, 'handleAccessRequestToggle' ] );

		if ( Settings::canRequest() ) {
			DeactivateRequest::schedule( time() );
			ProcessRequest::schedule( time() );

		} else {
			DeactivateRequest::clear();
			ProcessRequest::clear();
		}
	}

	/**
	 * Renders the HTML before the page
	 */
	public static function checkSession() {
		$status = Request::getDataAccessStatus();
		$list   = Request::getDataAccessIDs();

		if ( $status !== 'done' && empty( $list ) ) {
			$list = RequestAccessForm::getPublishedPosts();
			Request::setDataAccessIDs( $list );
		}
		if ( ! is_array( $list ) ) {
			$list = [];
		}

		global $post;
		if ( empty( $post ) ) {
			return;
		}
		if ( ! in_array( $post->ID, $list, true ) ) {
			return;
		}

		Session::start();
	}

	/**
	 * Creates (or updates the status of) a page with the Request Access form
	 */
	public static function handleAccessRequestToggle() {
		$enabled = Settings::get( Settings::KEY_ACCESS_ENABLE );
		$status  = $enabled ? 'private' : 'draft';
		$page    = Settings::getAccessRequestPage();

		if ( ! empty( $page ) ) {
			$page = wp_update_post(
				[
					'ID'          => $page,
					'post_status' => $status,
				]
			);
			if ( is_wp_error( $page ) ) {
				Debug::log( $page, __METHOD__ );
			}
			return;
		}

		$page = wp_insert_post(
			[
				'post_type'    => 'page',
				'post_content' => '[' . RequestAccessForm::getShortcode() . ']',
				'post_author'  => get_current_user_id(),
				'post_status'  => $status,
			]
		);

		if ( is_wp_error( $page ) ) {
			Debug::log( $page, __METHOD__ );
			return;
		}
		Settings::saveSetting( Settings::KEY_ACCESS_PAGE, (int) $page );
	}
}
