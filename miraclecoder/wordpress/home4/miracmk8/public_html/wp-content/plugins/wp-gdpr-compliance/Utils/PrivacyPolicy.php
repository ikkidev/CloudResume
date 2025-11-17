<?php
namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class PrivacyPolicy
 * @package WPGDPRC\Utils
 */
class PrivacyPolicy {

	const REPLACER = '%privacy_policy%';

	/**
	 * @return bool
	 */
	public static function useExternal() {
		return Settings::isEnabled( Settings::KEY_POLICY_EXTERN );
	}

	/**
	 * @return string
	 */
	public static function getText() {
		$output = Settings::get( Settings::KEY_POLICY_TEXT );
		return apply_filters( Plugin::PREFIX . '_privacy_policy_text', $output );
	}

	/**
	 * @return string
	 */
	public static function getLink() {
		if ( self::useExternal() ) {
			$output = Settings::get( Settings::KEY_POLICY_LINK );
		} else {
			$id         = Settings::get( Settings::KEY_POLICY_PAGE );
			$postStatus = get_post_status( $id );
			$output     = ( ( $postStatus !== false ) ? get_page_link( $id ) : Settings::get( Settings::KEY_POLICY_LINK ) );
		}

		// allow for optional changes by developer
		return apply_filters( Plugin::PREFIX . '_privacy_policy_link', $output );
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public static function replaceLink( $content = '' ) {
		if ( empty( $content ) ) {
			return '';
		}

		$text = self::getText();
		if ( empty( $text ) ) {
			return $content;
		}

		$url = self::getLink();
		if ( empty( $url ) ) {
			return $content;
		}

		$link = Elements::getLink(
			$url,
			esc_html( $text ),
			[
				'target' => '_blank',
				'rel'    => 'noopener noreferrer',
			],
			true
		);

		// allow for optional changes by developer
		$link = apply_filters( Plugin::PREFIX . '_privacy_policy_link_html', $link, $url, $text );
		return str_replace( self::REPLACER, $link, $content );
	}

}
