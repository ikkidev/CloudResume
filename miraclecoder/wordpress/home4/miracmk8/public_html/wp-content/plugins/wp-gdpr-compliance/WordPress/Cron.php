<?php
namespace WPGDPRC\WordPress;

use WPGDPRC\WordPress\Cron\DeactivateRequest;
use WPGDPRC\WordPress\Cron\ProcessRequest;

/**
 * Class Cron
 * @package WPGDPRC\WordPress
 */
class Cron {

	const KEY_MONTHLY = Plugin::PREFIX . '-monthly';

	/**
	 * Cron constructor
	 */
	public static function init() {
		add_filter( 'cron_schedules', [ self::class, 'addSchedules' ] );

		DeactivateRequest::init();
		ProcessRequest::init();
	}

	/**
	 * @param array $schedules
	 * @return array
	 */
	public static function addSchedules( $schedules = [] ) {
		$schedules[ self::KEY_MONTHLY ] = [
			'interval' => 30.5 * DAY_IN_SECONDS,
			'display'  => _x( 'Once a month', 'admin', 'wp-gdpr-compliance' ),
		];
		return $schedules;
	}

}
