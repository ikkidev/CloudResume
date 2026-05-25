<?php
namespace WPGDPRC\WordPress\Front\Consent;

use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\ConsentBar;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class AbstractConsent
 * @package WPGDPRC\WordPress\Front\Consent
 */
abstract class AbstractConsent {

	/**
	 * @return string
	 */
	public static function render() {
		return '';
	}

	/**
	 * @param string       $text
	 * @param false|string $filter
	 * @return string
	 */
	public static function filterText( $text = '', $filter = false ) {
		$text = wp_kses( $text, AdminHelper::getAllowedHTMLTags() );
		return is_string( $filter ) ? apply_filters( $filter, $text ) : $text;
	}

	/**
	 * Gets the consent bar accept button text
	 * return string
	 */
	public static function getAcceptButton() {
		$output = Settings::get( Settings::KEY_CONSENT_BTN_TEXT );
		return self::filterText( $output, Plugin::PREFIX . '_consents_bar_more_information_text' );
	}

}
