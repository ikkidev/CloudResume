<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Objects\Data\Comment;
use WPGDPRC\Objects\Data\User;
use WPGDPRC\Objects\Data\WooCommerceOrder;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class ProcessRequest
 * @package WPGDPRC\WordPress\Ajax
 */
class ProcessRequest extends AbstractAjax {

	// @TODO Originally this would work per delete request, now it handles all in one go

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_process_request';
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

	/**
	 * @return array
	 */
	public static function requiredData() {
		return [ 'item' ];
	}

    public static function hasData()
    {
        return true;
    }

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		if ( ! Settings::isEnabled( Settings::KEY_ACCESS_ENABLE ) ) {
			static::returnError( _x( 'The access request functionality is not enabled.', 'admin', 'wp-gdpr-compliance' ) );
		}

		$object = new RequestAccess( $data['item'] );
		if ( empty( $object ) ) {
			static::returnError( _x( 'Unable to locate request.', 'admin', 'wp-gdpr-compliance' ) );
		}

		$result  = static::processRequest( $object );
		$message = empty( $result['error'] ) ? _x( 'Data anonymized.', 'admin', 'wp-gdpr-compliance' ) : _x( 'Unable to (fully) anonymize all data.', 'admin', 'wp-gdpr-compliance' );
		if ( ! empty( $result['message'] ) ) {
			$message = implode( ' ', [ $message, $result['message'] ] );
		}

		$response = [
			'success' => true,
			'message' => $message,
			'result'  => $result,
			'debug'   => $data,
		];
		static::returnResponse( $response );
	}

	/**
	 * @param RequestAccess $object
	 * @return array
	 */
	public static function processRequest( RequestAccess $object ) {
		$list = RequestDelete::getByAccessId( $object->getId() );
		if ( empty( $list ) ) {
			$response = [
				'success' => true,
				'message' => __( 'No requests to process.', 'wp-gdpr-compliance' ),
			];
			static::returnResponse( $response );
		}

		$output = RequestDelete::anonymizeByAccessId( $object->getId() );
		if ( ! empty( $output['error'] ) ) {
			return $output;
		}

		$success = RequestDelete::sendNotification( $object, $output );
		if ( $success ) {
			$output['message'] = $success;
		}
		return $output;
	}

}
