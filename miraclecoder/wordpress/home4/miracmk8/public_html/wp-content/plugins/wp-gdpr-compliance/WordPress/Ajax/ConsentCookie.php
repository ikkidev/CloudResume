<?php

namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Cookie;
use WPGDPRC\WordPress\Plugin;

/**
 * Class ConsentCookie
 * @package WPGDPRC\WordPress\Ajax
 */
class ConsentCookie extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_consent_cookie';
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return true;
	}

	/**
	 * @return array
	 */
	public static function requiredData() {
		return [ 'checked' ];
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		$result = [];
		foreach ( DataProcessor::getListByType( 'active' ) as $object ) {
			if ( $object->getRequired() ) {
				$result[ $object->getId() ] = $object->getId() . '_' . Cookie::REQUIRED;
				continue;
			}
			if ( $data['checked'] === 'all' || ( is_array( $data['checked'] ) && in_array( $object->getId(), $data['checked'], true ) ) ) {
				$result[ $object->getId() ] = $object->getId() . '_' . Cookie::ACCEPTED;
				continue;
			}
			$result[ $object->getId() ] = $object->getId() . '_' . Cookie::DECLINED;
		}

		ksort( $result );
		static::returnSuccess( apply_filters( Plugin::PREFIX . '_consent_cookie', implode( ',', $result ), $data['checked'] ) );
	}

}
