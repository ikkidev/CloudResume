<?php

namespace WPGDPRC\WordPress\Cron;

use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class AbstractCron
 * @package WPGDPRC\WordPress\Cron
 */
abstract class AbstractCron {


	public static function init() {
		if ( ! Settings::canRequest() ) {
			static::clear();
			return;
		}

		static::schedule( time() );
	}

	/**
	 * Gets the cron hook name
	 * @return string
	 */
	public static function getHook(): string {
		return '';
	}

	/**
	 * Gets the cron recurrence
	 * @return string
	 */
	public static function getRecurrence(): string {
		return 'hourly';
	}

	/**
	 * Schedules cron event
	 * @param int $time
	 */
	public static function schedule( int $time = 0 ) {
		$hook = static::getHook();
		if ( empty( $hook ) ) {
			return;
		}

		add_action( $hook, [ static::class, 'execute' ] );
		if ( wp_next_scheduled( $hook ) ) {
			return;
		}

		wp_schedule_event( $time, static::getRecurrence(), $hook );
	}

	/**
	 * Clears the scheduled cron event
	 */
	public static function clear() {
		$hook = static::getHook();
		if ( empty( $hook ) ) {
			return;
		}
		if ( ! wp_next_scheduled( $hook ) ) {
			return;
		}

		wp_clear_scheduled_hook( $hook );
	}

	/**
	 * Gets the frequency of teh cron (in seconds)
	 * @return false|int
	 */
	public static function getFrequency() {
		$hook = static::getHook();
		if ( empty( $hook ) ) {
			return false;
		}

		$recurrence = wp_get_schedule( $hook );
		$schedules  = wp_get_schedules();
		if ( empty( $schedules[ $recurrence ] ) ) {
			return false;
		}
		return empty( $schedules[ $recurrence ]['interval'] ) ? $schedules[ $recurrence ]['interval'] : false;
	}

	/**
	 * Executes the cron code
	 */
	public static function execute() {
		$hook = static::getHook();
		if ( empty( $hook ) ) {
			return;
		}

		do_action( Plugin::PREFIX . '_before_' . $hook );
	}

}
