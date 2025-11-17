<?php

namespace WPGDPRC\WordPress\Cron;

use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\WordPress\Plugin;

/**
 * Class DeactivateRequest
 * @package WPGDPRC\WordPress\Cron
 */
class DeactivateRequest extends AbstractCron {

	const EXPIRE = '24 hours';

	/**
	 * Gets the cron hook name
	 * @return string
	 */
	public static function getHook(): string {
		return Plugin::PREFIX . '_deactivate_requests';
	}

	/**
	 * Gets the cron recurrence
	 * @return string
	 */
	public static function getRecurrence(): string {
		return 'hourly';
	}

	/**
	 * Executes the cron code
	 */
	public static function execute() {
		parent::execute();

		$list = RequestAccess::getOldRequests( '-' . static::EXPIRE, false );
		if ( empty( $list ) ) {
			return;
		}

		foreach ( $list as $object ) {
			$object->setExpired( 1 );
			$object->save();
		}
	}

}
