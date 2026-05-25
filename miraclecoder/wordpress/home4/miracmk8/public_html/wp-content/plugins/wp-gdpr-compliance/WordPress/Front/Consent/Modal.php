<?php
namespace WPGDPRC\WordPress\Front\Consent;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Cookie;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Modal
 * @package WPGDPRC\WordPress\Front\Consent
 */
class Modal extends AbstractConsent {

	/**
	 * Renders the consent modal
	 * @return void
	 */
	public static function render() {
		Template::render( 'Front/Consent/modal' );
	}

	/**
	 * Gets the consent modal title
	 * @return string
	 */
	public static function getTitle() {
		$output = Settings::get( Settings::KEY_CONSENT_MODAL_TITLE );
		$output = apply_filters( Plugin::PREFIX . '_replace_privacy_link', $output );
		return self::filterText( $output, Plugin::PREFIX . '_consents_modal_title' );
	}

	/**
	 * Gets the consent modal text
	 * @return string
	 */
	public static function getText() {
		$output = Settings::get( Settings::KEY_CONSENT_MODAL_TEXT );
		$output = apply_filters( Plugin::PREFIX . '_replace_privacy_link', $output );
		$output = self::filterText( $output, Plugin::PREFIX . '_consents_modal_explanation_text' );
		return apply_filters( Plugin::PREFIX . '_the_content', $output );
	}

	/**
	 * Gets the consent modal note text
	 * @return string
	 */
	public static function getNote() {
		$output = Elements::getWarning( __( 'These settings will only apply to the browser and device you are currently using.', 'wp-gdpr-compliance' ), false );
		return apply_filters( Plugin::PREFIX . '_the_content', $output );
	}

	/**
	 * Gets the consent modal button text
	 * @return string
	 */
	public static function getButton() {
		return DataProcessor::allRequired() ? self::getAcceptButton() : __( 'Save my settings', 'wp-gdpr-compliance' );
	}

}
