<?php
namespace WPGDPRC\WordPress\Front\Consent;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\ConsentBar;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\UserTypeHelper;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Bar
 * @package WPGDPRC\WordPress\Front\Consent
 */
class Bar extends AbstractConsent {

	/**
	 * Renders the consent bar
	 */
	public static function render() {
        Template::render(
			'Front/Consent/bar',
			[
				'explanation'     => self::getExplainText(),
				'settings_button' => self::getSettingButton(),
				'accept_button'   => self::getAcceptButton(),
			]
		);
	}

	/**
	 * Gets the consent bar explanation text
	 * @return string
	 */
	public static function getExplainText() {
		$output = Settings::get( Settings::KEY_CONSENT_EXPLAIN_TEXT );
		$output = apply_filters( Plugin::PREFIX . '_replace_privacy_link', $output );
		$output = self::filterText( $output, Plugin::PREFIX . '_consents_bar_explanation_text' );
		return apply_filters( Plugin::PREFIX . '_the_content', $output );
	}

	/**
	 * Gets the consent bar settings button text
	 * @return string
	 */
	public static function getSettingButton() {
		$output = Settings::get( Settings::KEY_CONSENT_INFO_TEXT );
		return self::filterText( $output, Plugin::PREFIX . '_consents_bar_more_information_text' );
	}

	/**
	 * Gets the consent bar background color
	 * @return string
	 */
	public static function getBgColor() {
		return Settings::get( Settings::KEY_CONSENT_BG_COLOR );
	}

	/**
	 * Gets the consent bar text color
	 * @return string
	 */
	public static function getTextColor() {
		return Settings::get( Settings::KEY_CONSENT_TEXT_COLOR );
	}

	/**
	 * Gets the consent bar (Accept) button background color
	 * @return string
	 */
	public static function getBtnBgColor() {
		return Settings::get( Settings::KEY_CONSENT_BTN_PRIMARY );
	}

	/**
	 * Gets the consent bar (Accept) button slightly darker background color
	 * @return string
	 */
	public static function getBtnBgColorDarken() {
		return Helper::darkenHex( self::getBtnBgColor(), 15 );
	}

	/**
	 * Gets the consent bar (Accept) button text color
	 * @return string
	 */
	public static function getBtnTextColor() {
		return Settings::get( Settings::KEY_CONSENT_BTN_SECONDARY );
	}

	/**
	 * Gets the consent bar font family (with fallback)
	 * @return string
	 */
	public static function getFont() {
		return Settings::get( Settings::KEY_CONSENT_FONT );
	}

	/**
	 * Gets the correct font name for the Google Font API URL
	 * @return string
	 */
	public static function getGoogleFontName() {
		$font = static::getFont();
		if ( empty( $font ) ) {
			return '';
		}

		return str_replace( ' ', '+', $font );
	}

	/**
	 * Gets the Google Font API URL
	 * @return string
	 */
	public static function getGoogleFontUrl() {
		$name = static::getGoogleFontName();
		if ( empty( $name ) ) {
			return '';
		}

		return add_query_arg(
			[
				'family'  => $name,
				'display' => 'swap',
			],
			'https://fonts.googleapis.com/css'
		);
	}

	/**
	 * Gets the consent bar position
	 * @return string
	 */
	public static function getPosition() {
		return Settings::get( Settings::KEY_CONSENT_POSITION );
	}

	/**
	 * Gets the consent bar animation style
	 * @return string
	 */
	public static function getAnimation() {
		return Settings::get( Settings::KEY_CONSENT_ANIMATION );
	}

	/**
	 * Lists custom color variables for the consent bar styling
	 * @return string
	 */
	public static function listCustomStyleVars() {
		$font_family = self::getFont();
		$vars        = [
			'--wp-gdpr--bar--background-color: ' . self::getBgColor(),
			'--wp-gdpr--bar--color: ' . self::getTextColor(),
			'--wp-gdpr--button--background-color: ' . self::getBtnBgColor(),
			'--wp-gdpr--button--background-color--darken: ' . self::getBtnBgColorDarken(),
			'--wp-gdpr--button--color: ' . self::getBtnTextColor(),
		];

		if ( $font_family ) {
			$vars[] = "--wp-gdpr--bar--font-family: \"$font_family\"";
		}

		return ':root{' . implode( ';', $vars ) . ';}';
	}

}
