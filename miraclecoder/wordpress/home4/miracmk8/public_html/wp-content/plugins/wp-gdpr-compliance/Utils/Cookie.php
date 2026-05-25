<?php
namespace WPGDPRC\Utils;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\WordPress\Plugin;

/**
 * Class Cookie
 * @package WPGDPRC\Utils
 */
class Cookie {

	const KEY_VERSION     = Plugin::PREFIX . '_consent_version';
	const VERSION_PREFIX  = 'v';
	const DEFAULT_VERSION = self::VERSION_PREFIX . '2';

	const REQUIRED = 'required';
	const ACCEPTED = 'accepted';
	const DECLINED = 'declined';

	/**
	 * @param string $version
	 * @return bool
	 */
	public static function setVersion( $version = self::DEFAULT_VERSION ) {
		return update_option( self::KEY_VERSION, $version );
	}

	/**
	 * @param string $default
	 * @return string
	 */
	public static function getVersion( $default = self::DEFAULT_VERSION ) {
		$version = get_option( self::KEY_VERSION, '' );
		if ( ! empty( $version ) ) {
			return $version;
		}

		self::setVersion( $default );
		return $default;
	}

	/**
	 * @return string
	 */
	public static function getNextVersion() {
		$version = self::getVersion();
		$number  = (int) str_replace( self::VERSION_PREFIX, '', $version );
		return self::VERSION_PREFIX . ( $number + 1 );
	}

	/**
	 * @return string
	 */
	public static function getConsentKey() {
		$key = Plugin::PREFIX . '-consent-' . self::getVersion();
		return is_multisite() ? get_current_blog_id() . '-' . $key : $key;
	}

	/**
	 * Lists consent ID's set in the cookie
	 * @return array
	 */
	public static function getConsentIDs() {
		$output   = [];
		$required = DataProcessor::getListByType( 'required' );
		if ( ! empty( $required ) ) {
			foreach ( $required as $item ) {
				$output[] = (int) $item->getId();
			}
		}

		$cookie   = self::getConsentKey();
		$consents = ! empty( $_COOKIE[ $cookie ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $cookie ] ) ) : '';

		if ( empty( $consents ) ) {
			return $output;
		}

		switch ( $consents ) {
			case 'decline':
				// nothing. Just return output
				break;
			case 'accept':
				/** @var DataProcessor $item */
				foreach ( DataProcessor::getListByType( DataProcessor::STATUS_OPTIONAL ) as $item ) {
					$output[] = $item->getId();
				}
				break;

			default:
				if ( ! is_string( $consents ) ) {
					break;
				}

				$consents = explode( ',', $consents );
				foreach ( $consents as $consent ) {

					extract( array_combine( [ 'id', 'status' ], explode( '_', $consent ) ) );

					/**
					 * @var int $id
					 * @var string $status
					 */

					if ( ! is_numeric( $id ) ) {
						continue;
					}
					if ( ! DataProcessor::exists( $id ) ) {
						continue;
					}
					if ( $status !== self::ACCEPTED ) {
						continue;
					}
					$output[] = (int) $id;
				}
				break;
		}
		return $output;
	}

}
