<?php
namespace WPGDPRC\WordPress\Admin\Pages;

use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Debug;
use WPGDPRC\Utils\Redirect;
use WPGDPRC\Utils\Request;
use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Plugin;

/**
 * Class PageRequests
 * @package WPGDPRC\WordPress\PageAdmin\Pages
 */
class PageRequests extends AbstractPage {

	/**
	 * Returns the page slug for this page
	 * @return string
	 */
	public static function getPageSlug() {
		return Plugin::PLUGIN_SLUG . '-requests';
	}

	/**
	 * @return string
	 */
	protected static function getParentSlug() {
		return Plugin::PLUGIN_SLUG;
	}

	/**
	 * @return string
	 */
	protected static function getPageTitle() {
		return _x( 'Requests', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	protected static function getMenuTitle() {
		$count = self::getRequestCount();
		if ( empty( $count ) ) {
			return parent::getMenuTitle();
		}

		$append = Template::get(
			'Admin/Elements/updatecount',
			[
				'count' => $count,
				/* translators: %1s: Number of requests */
				'text'  => sprintf( _nx( '%1s request to process', '%1s requests to process', $count, 'admin', 'wp-gdpr-compliance' ), $count ),
			]
		);
		if ( empty( $append ) ) {
			return parent::getMenuTitle();
		}

		return implode( ' ', [ parent::getMenuTitle(), $append ] );
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public static function getDeleteUrl( $id = 0 ) {
		return add_query_arg( [ static::KEY_DELETE => $id ], static::getPageUrl() );
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public static function getEditUrl( $id = 0 ) {
		return add_query_arg( [ static::KEY_ID => $id ], static::getPageUrl() );
	}

	/**
	 * @return bool
	 */
	protected static function isSubMenu() {
		return true;
	}

	/**
	 * @return int
	 */
	public static function getRequestCount() {
		return RequestDelete::getTotalToProcess();
	}

	/**
	 * Generates the page content
	 */
	public static function generate() {
		if ( ! empty( $_GET[ static::KEY_ID ] ) ) {
			static::generateEdit( (int) $_GET[ static::KEY_ID ] );
			return;
		}

		do_action( Plugin::PREFIX . '_before_page' );

		static::handlePost();

		Template::render(
			'Admin/Pages/Requests/main',
			[
				'admin_url' => static::getPageUrl(),
			]
		);

		do_action( Plugin::PREFIX . '_after_page' );
	}

	/**
	 * Handles form post data
	 */
	public static function handlePost() {
		if ( isset( $_GET[ static::KEY_DELETE ] ) ) {
			$id = (int) $_GET[ static::KEY_DELETE ];

			if ( ! empty( $id ) ) {
				RequestAccess::deleteById( $id );
			}
		}

		if ( isset( $_POST['update'] ) || isset( $_POST['delete'] ) ) {
			if ( empty( $_POST['request_id'] ) ) {
				return;
			}

			$id = (int) $_POST['request_id'];
			if ( empty( $id ) ) {
				return;
			}

			if ( isset( $_POST['delete'] ) ) {
				RequestAccess::deleteById( $id );
                AdminHelper::wrapNotice( _x( 'Request cancelled.', 'admin', 'wp-gdpr-compliance' ) );
				Redirect::goToUrl( remove_query_arg( [ static::KEY_ID ], static::getPageUrl() ) );
				return;
			}

			$object = new RequestAccess( $id );
			$output = RequestDelete::anonymizeByAccessId( $object->getId() );
			if ( ! empty( $output['error'] ) && empty( $output['processed'] ) ) {
				Debug::log( $output, Plugin::PLUGIN_SLUG );
				return;
			}

			$success = RequestDelete::sendNotification( $object, $output );
			if ( empty( $success ) ) {
				Debug::log( $output, Plugin::PLUGIN_SLUG );
				return;
			}

            AdminHelper::wrapNotice( $success );
		}
	}

	/**
	 * @param int $id
	 */
	public static function generateEdit( $id = 0 ) {
		if ( empty( $id ) ) {
            AdminHelper::wrapNotice( _x( 'Unable to locate request.', 'admin', 'wp-gdpr-compliance' ) );
			Redirect::goToUrl( remove_query_arg( [ static::KEY_ID ], static::getPageUrl() ) );
			return;
		}

		$object = new RequestAccess( $id );
		if ( empty( $object ) ) {
            AdminHelper::wrapNotice( _x( 'Unable to locate request.', 'admin', 'wp-gdpr-compliance' ) );
			Redirect::goToUrl( remove_query_arg( [ static::KEY_ID ], static::getPageUrl() ) );
			return;
		}

		static::handlePost();

		do_action( Plugin::PREFIX . '_before_page' );

		$created = Time::formatLocalDateTime( 'd-m-Y', strtotime( $object->getDateCreated() ) );

		Template::render(
			'Admin/Pages/Requests/Edit/main',
			[
				'admin_url' => static::getPageUrl(),
				'email'     => $object->getEmailAddress(),
				'created'   => $created,
				'offset'    => Request::getProccessOffset( $object ),
				'object_id' => $object->getId(),
				'request'   => $object,
				'results'   => Request::getData( $object, false ),
			]
		);

		do_action( Plugin::PREFIX . '_after_page' );
	}

}
