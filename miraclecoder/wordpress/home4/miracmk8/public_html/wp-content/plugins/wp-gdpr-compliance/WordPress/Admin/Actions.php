<?php

namespace WPGDPRC\WordPress\Admin;

use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\FormHandler;
use WPGDPRC\Utils\Request;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Shortcodes\RequestAccessForm;

/**
 * Class Actions
 * @package WPGDPRC\WordPress\Admin
 */
class Actions {

	/**
	 * Admin constructor
	 */
	public static function init() {
		add_action( Plugin::PREFIX . '_before_page', [ self::class, 'pageBefore' ] );
		add_action( Plugin::PREFIX . '_after_page', [ self::class, 'pageAfter' ] );
		add_action( 'save_post', [ self::class, 'checkIfAccessRequestPage' ], 10, 3 );
	}

	/**
	 * Renders the HTML before the page
	 */
	public static function pageBefore() {
		Template::render( 'Admin/page-before', [] );

		// handle possible deletion requests
		if ( PageDashboard::getCurrentTab() === PageDashboard::TAB_PROCESSORS ) {
			if ( ! empty( $_GET['updated'] ) ) {
                AdminHelper::wrapNotice( _x( 'Consent updated.', 'admin', 'wp-gdpr-compliance' ) );
			} elseif ( ! empty( $_GET['delete'] ) ) {
				FormHandler::consentDeleteForm( (int) $_GET['delete'] );
			}
		}

		if ( ! empty( $_POST[ PageDashboard::TAB_PROCESSORS ] ) && is_iterable( $_POST[ PageDashboard::TAB_PROCESSORS ] ) ) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- is an array.
			$args = FormHandler::consentEditForm( wp_unslash( $_POST[ PageDashboard::TAB_PROCESSORS ] ) );
			FormHandler::consentEditFormRedirect( $args );
		}
	}

	/**
	 * Renders the HTML after the page
	 */
	public static function pageAfter() {
		Template::render( 'Admin/page-after', [] );
	}

	/**
	 * @param int $post_id
	 * @param \WP_Post $post
	 * @param          $update
	 */
	public static function checkIfAccessRequestPage( $post_id, \WP_Post $post, $update ) {
		// if this is a revision, get real post ID
		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$post_id = $parent_id;
		}

		$list = Request::getDataAccessIDs();
		if ( ! is_array( $list ) ) {
			$list = [];
		}

		// check for presence of shortcode in the content
		if ( has_shortcode( strtolower( $post->post_content ), RequestAccessForm::getShortcode() ) ) {
			if ( in_array( $post_id, $list, true ) ) {
				return;
			}

			$list[] = $post_id;
			Request::setDataAccessIDs( $list );

			return;
		}

		// no shortcode, so make sure it not in the list
		if ( ! in_array( $post_id, $list, true ) ) {
			return;
		}

		foreach ( $list as $id => $value ) {
			if ( $value !== $post_id ) {
				continue;
			}
			unset( $list[ $id ] );
		}
		Request::setDataAccessIDs( $list );
	}

}
