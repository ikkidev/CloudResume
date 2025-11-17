<?php

namespace WPGDPRC\Utils;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\WordPress\Settings;

class Banner {

	/**
	 * @param bool|null $active
	 * @return string
	 */
	public static function statusText( $active = null ): string {
		if ( is_null( $active ) ) {
			$active = self::isActive();
		}
		return self::getStatusText( $active );
	}

	/**
	 * @param bool|null $premium
	 * @return string
	 */
	public static function enabledText( $premium = null ): string {
		if ( is_null( $premium ) ) {
			$premium = Settings::isPremium();
		}

		return self::getPremiumText( $premium );
	}

	/**
	 * @param bool|null $active`
	 * @return string
	 */
	public static function getStatusBanner( bool $active = null ): string {
		if ( is_null( $active ) ) {
			$active = DataProcessor::isActive();
		}
		return self::getBanner( $active, static::statusText( $active ) );
	}

	/**
	 * @param bool|null $premium
	 *
	 * @return string
	 */
	public static function getPremiumBanner( bool $premium = null ) {
		if ( is_null( $premium ) ) {
			$premium = Settings::isPremium();
		}
		return self::getBanner( $premium, static::enabledText( $premium ) );
	}

	/**
	 * @param bool $active
	 * @param string $text
	 *
	 * @return string
	 */
	public static function getBanner( bool $active, string $text ): string {
		$args = [
			'status' => $active ? 'success' : 'warning',
			'class'  => 'wpgdprc-label--large',
			'text'   => $text,
		];
		return Template::get( 'Admin/banner', $args );
	}

	/**
	 * @param null $active
	 * @param null $premium
	 *
	 * @return string
	 */
	public static function getStatusAndPremiumBanner( $active = null, $premium = null ): string {
		if ( is_null( $active ) && is_null( $premium ) ) {
			$premium = Settings::isPremium();
			$active  = DataProcessor::isActive();

			if ( $premium ) {
				$active = $premium;
			}
		}

		return implode(
			'',
			[
				self::getStatusBanner( $active ),
				self::getPremiumBanner( $premium ),
			]
		);
	}

	/**
	 * @param $status
	 * @return mixed|string
	 */
	public static function getStatusText( $status ) {

		return [
			0 => _x( 'Cookie pop-up is not live', 'admin', 'wp-gdpr-compliance' ),
			1 => _x( 'Cookie pop-up is live', 'admin', 'wp-gdpr-compliance' ),
		][ $status ] ?? '';
	}

	/**
	 * @param $status
	 * @return mixed|string
	 */
	public static function getPremiumText( $status ) {
		return [
			0 => _x( 'Currently in non-business mode', 'admin', 'wp-gdpr-compliance' ),
			1 => _x( 'Currently in full compliant mode', 'admin', 'wp-gdpr-compliance' ),
		][ $status ] ?? '';
	}
}
