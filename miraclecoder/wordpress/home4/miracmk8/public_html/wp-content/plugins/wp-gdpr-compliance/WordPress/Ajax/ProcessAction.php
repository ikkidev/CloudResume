<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Objects\AbstractRequest;
use WPGDPRC\Objects\Data\Data;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\IpAddress;
use WPGDPRC\Utils\Request;
use WPGDPRC\Utils\Session;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageRequests;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class ProcessAction
 * @package WPGDPRC\WordPress\Ajax
 */
class ProcessAction extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_process_action';
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return true;
	}

    public static function hasData()
    {
        return true;
    }

	/**
	 * @return array
	 */
	public static function requiredData() {
		return [ 'type' ];
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		if ( ! Settings::canRequest() ) {
			static::returnError( '' ); // @TODO Better error message?
		}

		switch ( $data['type'] ) {
			case 'access_request':
				static::handleAccessRequest( $data );
				break;

			case 'delete_request':
				static::handleDeleteRequest( $data );
				break;

			default:
				static::returnError( __( 'Missing type.', 'wp-gdpr-compliance' ) );
		}
	}

	/**
	 * @param array $data
	 */
	public static function handleAccessRequest( $data = [] ) {
		$email = ! empty( $data['email'] ) ? esc_attr( str_replace( ' ', '+', $data['email'] ) ) : false;
		if ( empty( $email ) || ! is_email( $email ) ) {
			static::returnError( __( 'Missing or incorrect email address.', 'wp-gdpr-compliance' ) );
		}

		$consent = ! empty( $data['consent'] ) ? filter_var( $data['consent'], FILTER_VALIDATE_BOOLEAN ) : false;
		if ( empty( $consent ) ) {
			static::returnError( __( 'You need to accept the privacy checkbox.', 'wp-gdpr-compliance' ) );
		}

		$request = RequestAccess::getByEmail( $email, true );
		if ( ! empty( $request ) ) {
			static::returnError( __( 'You have already requested your data. Please check your mailbox. After 24 hours you can put in a new request.', 'wp-gdpr-compliance' ) );
		}

		$hash = md5( openssl_random_pseudo_bytes( 20 ) );

		$object = new RequestAccess();
		$object->setSiteId( get_current_blog_id() );
		$object->setEmailAddress( $email );
		$object->setSessionId( Session::getId() );
		$object->setIpAddress( IpAddress::getClientIp() );
		$object->setToken( substr( $hash, -32 ) );
		$id = $object->save();

		if ( empty( $id ) ) {
			static::returnError( __( 'Something went wrong while saving the request. Please try again.', 'wp-gdpr-compliance' ) );
		}

		$page = Request::getAccessPage();
		if ( empty( $page ) ) {
			static::returnError( 'Something went wrong. Please contact the website owner.' ); // @TODO Better error message?
		}

		$site_info  = AdminHelper::getSiteInfo( $object->getSiteId() );
		$page_title = wp_strip_all_tags( apply_filters( Plugin::PREFIX . '_the_title', get_the_title( $page ) ) );

		/* translators: %1s: Email subject site name. */
		$subject = esc_attr( sprintf( __( '%1s - Your data request', 'wp-gdpr-compliance' ), $site_info['name'] ) );
		$subject = apply_filters( Plugin::PREFIX . '_access_request_mail_subject', $subject, $object, $site_info['name'] );

		$delete_url  = add_query_arg( [ Plugin::PREFIX => urlencode( $object->getToken() ) ], get_permalink( $page ) );
		$delete_link = Elements::getLink( $delete_url, __( 'page', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ], true );

		$message = Template::get(
			'Email/access_request',
			[
				'email'        => $email,
				'site_link'    => Elements::getLink( $site_info['url'], $site_info['name'], [ 'target' => '_blank' ], true ),
				'delete_link'  => $delete_link,
				'request_link' => Elements::getLink( get_permalink( $page ), $page_title, [ 'target' => '_blank' ], true ),
			]
		);
		$message = apply_filters( Plugin::PREFIX . '_access_request_mail_content', $message, $object, $delete_link );
		static::sendMail( $email, $subject, $message, static::getMailHeaders( $object ) );
	}

	/**
	 * @param array $data
	 */
	public static function handleDeleteRequest( array $data = [] ) {
		$token = ! empty( $data['token'] ) ? esc_html( urldecode( $data['token'] ) ) : false;
		if ( empty( $token ) ) {
			static::returnError( __( 'Missing token.', 'wp-gdpr-compliance' ) );
		}

		$value = ! empty( $data['value'] ) && is_numeric( $data['value'] ) ? (int) $data['value'] : 0;
		if ( empty( $value ) ) {
			static::returnError( __( 'No value selected.', 'wp-gdpr-compliance' ) );
		}

		$settings = ! empty( $data['settings'] ) ? ( is_array( $data['settings'] ) ? $data['settings'] : (array) $data['settings'] ) : [];
		$type     = ! empty( $settings['type'] ) && in_array( $settings['type'], Data::getPossibleDataTypes(), true ) ? $settings['type'] : '';
		if ( empty( $type ) ) {
			static::returnError( __( 'Missing or invalid type.', 'wp-gdpr-compliance' ) );
		}

		$request = RequestAccess::getByToken( $token );
		if ( empty( $request ) ) {
			static::returnError( __( 'No session found.', 'wp-gdpr-compliance' ) );
		}

		if ( ! Session::check( $request->getSessionId() ) || ! IpAddress::checkIp( $request->getIpAddress() ) ) {
			static::returnError( __( "Session doesn't match.", 'wp-gdpr-compliance' ) );
		}

		$object = new RequestDelete();
		$object->setSiteId( get_current_blog_id() );
		$object->setAccessRequestId( $request->getId() );
		$object->setSessionId( $request->getSessionId() );
		$object->setIpAddress( $request->getIpAddress() );
		$object->setDataId( $value );
		$object->setType( $type );
		$deleteRequestId = $object->save();

		if ( empty( $deleteRequestId ) ) {
			static::returnError( __( 'Something went wrong while saving the request. Please try again.', 'wp-gdpr-compliance' ) );
		}

		$site_info = AdminHelper::getSiteInfo( $object->getSiteId() );

		/* translators: %1s: Email subject site name. */
		$subject = esc_attr( sprintf( __( '%1s - New anonymize request', 'wp-gdpr-compliance' ), $site_info['name'] ) );
		$subject = apply_filters( Plugin::PREFIX . '_delete_request_admin_mail_subject', $subject, $object, $site_info['name'] );

		$admin_link = Elements::getLink( PageRequests::getPageUrl(), __( 'Requests', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ], true );

		$message = Template::get(
			'Email/delete_request',
			[
				'site_link'  => Elements::getLink( $site_info['url'], $site_info['name'], [ 'target' => '_blank' ], true ),
				'admin_link' => $admin_link,
			]
		);
		$message = apply_filters( Plugin::PREFIX . '_delete_request_admin_mail_content', $message, $object, $admin_link );
		static::sendMail( $site_info['email'], $subject, $message, static::getMailHeaders( $object ) );
	}

	/**
	 * @param AbstractRequest $object
	 * @return array
	 */
	public static function getMailHeaders( AbstractRequest $object ): array {
		$site_info = AdminHelper::getSiteInfo( $object->getSiteId() );
		$from_mail = apply_filters( Plugin::PREFIX . '_request_mail_from', $site_info['name'] . ' <' . $site_info['email'] . '>' );

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $from_mail,
		];
		return apply_filters( Plugin::PREFIX . '_request_mail_headers', $headers, $object );
	}

	/**
	 * @param string $email
	 * @param string $subject
	 * @param string $message
	 * @param array  $headers
	 */
	public static function sendMail( string $email = '', string $subject = '', string $message = '', array $headers = [] ) {
		if ( empty( $email ) ) {
			static::returnError( 'No valid email provided.' ); // @TODO Better error message?
		}

		$response = wp_mail( $email, $subject, $message, $headers );
		if ( empty( $response ) ) {
			static::returnError( '' ); // @TODO Better error message?
		}

		static::returnSuccess( __( 'Success. You will receive an email with your data shortly.', 'wp-gdpr-compliance' ) );
	}

}
