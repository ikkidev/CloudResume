<?php
namespace WPGDPRC\Utils;

use DateTime;
use DateTimeZone;
use WPGDPRC\WordPress\Plugin;

/**
 * Class Time
 * @package WPGDPRC\Utils
 */
class Time {

	/**
	 * @param string $format
	 * @param int    $timestamp
	 * @return string
	 */
	public static function localDateFormat( $format = '', $timestamp = 0 ) {
		$date = self::localDateTime( $timestamp );
		if ( empty( $date ) ) {
			return '';
		}

		return date_i18n( $format, $date->getTimestamp(), true );
	}

	/**
	 * @param int $timestamp
	 * @return DateTime|false
	 */
	public static function localDateTime( $timestamp = 0 ) {
		$gmt_offset = get_option( 'gmt_offset', '' );
		$time_zone  = static::getDateTimeZone();
		$format     = 'Y-m-d H:i:s';

		try {
			if ( $gmt_offset === '' ) {
				$date = new DateTime( null, static::getDateTimeZone( get_option( 'timezone_string', 'UTC' ) ) );
				$date->setTimestamp( $timestamp );
				return new DateTime( $date->format( $format ), $time_zone );
			}

			$negative   = (int) $gmt_offset < 0;
			$gmt_offset = absint( $gmt_offset );
			$hour       = (int) floor( $gmt_offset );
			$minutes    = ( $gmt_offset - $hour ) * 60;
			if ( $negative ) {
				$hour    = -1 * $hour;
				$minutes = -1 * $minutes;
			}
			$date = new DateTime( null, $time_zone );
			$date->setTimestamp( $timestamp );
			$date->modify( $hour . ' hour' );
			$date->modify( $minutes . ' minutes' );
			return new DateTime( $date->format( $format ), $time_zone );

		} catch ( \Exception $exception ) {
			Debug::log( $exception, Plugin::PLUGIN_SLUG );
			return false;
		}
	}

	/**
	 * @param string $format
	 * @param int    $timestamp
	 * @return string
	 */
	public static function formatLocalDateTime( $format = 'Y-m-d H:i:s', $timestamp = 0 ) {
		$date = static::localDateTime( $timestamp );
		if ( empty( $date ) ) {
			return '';
		}

		return $date->format( $format );
	}

	/**
	 * @param string $timezone
	 * @return DateTimeZone
	 */
	public static function getDateTimeZone( $timezone = 'UTC' ) {
		return new DateTimeZone( $timezone );
	}

}
