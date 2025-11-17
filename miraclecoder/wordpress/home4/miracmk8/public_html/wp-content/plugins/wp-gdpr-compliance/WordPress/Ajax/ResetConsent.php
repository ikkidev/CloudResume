<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Utils\Cookie;
use WPGDPRC\WordPress\Plugin;

/**
 * Class ResetConsent
 * @package WPGDPRC\WordPress\Ajax
 */
class ResetConsent extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_reset_consent';
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

	/**
	 * Determines if AJAX call is sending a data attribute
	 * @return bool
	 */
	public static function hasData() {
		return false;
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		// Update consent cookie version
		$success = Cookie::setVersion( Cookie::getNextVersion() );
		$message = _x( 'Unable to reset consent', 'admin', 'wp-gdpr-compliance' );
		if ( empty( $success ) ) {
			static::returnError( $message );
		}

		$message = _x( 'Consent has been reset', 'admin', 'wp-gdpr-compliance' );
		static::returnSuccess( $message );
	}

}
