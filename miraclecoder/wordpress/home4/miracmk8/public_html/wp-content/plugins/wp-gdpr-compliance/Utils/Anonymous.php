<?php

namespace WPGDPRC\Utils;

/**
 * Class Anonymous
 * @package WPGDPRC\Utils
 */
class Anonymous {

	/**
	 * @return string
	 */
	public static function getIpAddress() {
		return '127.0.0.1';
	}

	/**
	 * @param string $prefix
	 *
	 * @return string
	 */
	public static function getEmailAddress( $prefix = 'xxx' ) {
		$date = Time::formatLocalDateTime( 'Ymd.His', time() );
		return self::concatEmail( $prefix . '.' . $date, self::getSiteUrl() );
	}

	/**
	 * @return mixed
	 */
	public static function getName() {
		return _x( 'Anonymous', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	public static function getSiteUrl(): string {
		return '';
	}


	/**
	 * @param string $email
	 *
	 * @return null|string
	 */
	public static function anonymizeEmail( $email = '' ) {
		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return null;
		}

		$parts  = explode( '@', $email );
		$first  = reset( $parts );
		$length = strlen( $first );
		$domain = $parts[1];

		if ( $length < 2 ) {
			$domain_parts = explode( '.', $domain );
			$domain_first = reset( $domain_parts );
			$domain       = str_replace( $domain_first, '***', $domain_first ) . '.' . $domain_parts[1];

			return static::concatEmail( $first, $domain );
		}

		$replace = '*';
		if ( in_array( $length, range( 3, 5 ), true ) ) {
			$replace = '**';
		} elseif ( $length > 5 ) {
			$replace = '***';
		}

		return static::concatEmail( substr_replace( $first, $replace, $length - strlen( $replace ) ), $domain );
	}

	/**
	 * @param string $name
	 * @param string $domain
	 *
	 * @return string
	 */
	public static function concatEmail( $name = '', $domain = '' ) {
		if ( empty( $name ) ) {
			return '';
		}
		if ( empty( $domain ) ) {
			return '';
		}

		return implode( '@', [ $name, $domain ] );
	}

	/**
	 * @param string $ip
	 *
	 * @return null|string
	 */
	public static function anonymizeIP( $ip = '' ) {
		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return null;
		}

		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$last_dot = strrpos( $ip, '.' ) + 1;

			return substr( $ip, 0, $last_dot ) . str_repeat( '*', strlen( $ip ) - $last_dot );
		}
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
			$last_colon = strrpos( $ip, ':' ) + 1;

			return substr( $ip, 0, $last_colon ) . str_repeat( '*', strlen( $ip ) - $last_colon );
		}

		return null;
	}

}
