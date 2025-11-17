<?php

namespace WPGDPRC\WordPress\Cron;

use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\Anonymous;
use WPGDPRC\Utils\Helper;
use WPGDPRC\WordPress\Cron;
use WPGDPRC\WordPress\Plugin;

/**
 * Class ProcessRequest
 * @package WPGDPRC\WordPress\Cron
 */
class ProcessRequest extends AbstractCron {

	const EXPIRE = '1 month';

	/**
	 * Gets the cron hook name
	 * @return string
	 */
	public static function getHook(): string {
		return Plugin::PREFIX . '_process_requests';
	}

	/**
	 * Gets the cron recurrence
	 * @return string
	 */
	public static function getRecurrence(): string {
		return Cron::KEY_MONTHLY;
	}

	/**
	 * Executes the cron code
	 */
	public static function execute() {
		parent::execute();

		static::anonymizeAccessRequests();
		static::anonymizeDeleteRequests();
	}

	/**
	 * Anonymizes all the old Access Requests
	 */
	public static function anonymizeAccessRequests() {
		$list = RequestAccess::getOldRequests( '-' . static::EXPIRE, null, false );
		if ( empty( $list ) ) {
			return;
		}

		$ip_address = Anonymous::getIpAddress();
		foreach ( $list as $object ) {
			$email = Anonymous::getEmailAddress( $object->getId() );
			$object->setEmailAddress( $email );
			$object->setIpAddress( $ip_address );
			$object->setExpired( 1 );
			$object->save();
		}
	}

	/**
	 * Anonymizes all the old Delete Requests
	 */
	public static function anonymizeDeleteRequests() {
		$list = RequestDelete::getOldRequests();
		if ( empty( $list ) ) {
			return;
		}

		$ip_address = Anonymous::getIpAddress();
		foreach ( $list as $object ) {
			$object->setIpAddress( $ip_address );
			$object->setDataId( 0 );
			$object->setType( 'unknown' );
			$object->save();
		}
	}

}
