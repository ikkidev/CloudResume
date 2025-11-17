<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\WordPress\Plugin;

/**
 * Class HideWelcome
 * @package WPGDPRC\WordPress\Ajax
 */
class HideWelcome extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_hide_welcome';
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
		// Store setting with plugin version when clicked on "hide this message"
		$success = update_option( static::getAction(), Plugin::VERSION );
		static::returnResponse( [ 'success' => $success ] );
	}

}
