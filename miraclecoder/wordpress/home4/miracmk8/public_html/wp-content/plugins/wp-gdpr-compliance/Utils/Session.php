<?php
namespace WPGDPRC\Utils;

/**
 * Class Session
 * @package WPGDPRC\Utils
 */
class Session {

	/**
	 * Gets the session ID
	 * @return string
	 */
	public static function getId() {
		self::start();
		return session_id();
	}

	/**
	 * Starts the session if not started yet
	 */
	public static function start() {
		if ( ! session_id() ) {
			session_start();
		}
	}

	/**
	 * Validates session ID
	 * @param string $sessionId
	 * @return bool
	 */
	public static function check( $sessionId = '' ) {
		return self::getId() === $sessionId;
	}

	/**
	 * Stores session variable
	 * @param string $key
	 * @param string $value
	 */
	public static function setVar( $key = '', $value = '' ) {
		self::start();
		$_SESSION[ $key ] = $value;
	}
}
