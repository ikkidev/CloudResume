<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\WordPress\Plugin;

/**
 * Class DeleteRequest
 * @package WPGDPRC\WordPress\Ajax
 */
class DeleteRequest extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_delete_request';
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
		$object = new RequestAccess( $data['item'] );
		if ( empty( $object ) ) {
			static::returnError( _x( 'Unable to locate request.', 'admin', 'wp-gdpr-compliance' ) );
		}

		$success  = $object->delete();
		$message  = $success ? _x( 'Request deleted.', 'admin', 'wp-gdpr-compliance' ) : _x( 'Unable to delete request.', 'admin', 'wp-gdpr-compliance' );
		$response = [
			'success' => $success,
			'message' => $message,
			'debug'   => $data,
		];
		static::returnResponse( $response );
	}

}
