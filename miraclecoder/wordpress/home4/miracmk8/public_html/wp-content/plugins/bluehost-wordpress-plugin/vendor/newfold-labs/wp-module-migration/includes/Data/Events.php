<?php
namespace NewfoldLabs\WP\Module\Migration\Data;

/**
 * Contains data related to performance Hiive events.
 */
final class Events {
	/**
	 * The Array of categories in an event.
	 *
	 * @var array
	 */
	protected static $category = array(
		'wonder_start',
		'wp_migration',
	);

	/**
	 * List of valid actions that an event can perform.
	 *
	 * A value of true indicates that the action is valid, set it to null if you want to invalidate an action.
	 *
	 * @var array
	 */
	protected static $valid_actions = array(
		'migration_completed'              => true,
		'migration_failed'                 => true,
		'migration_aborted'                => true,
		'migration_vendor_plugin_activate' => true,
		'migration_vendor_plugin_connect'  => true,
		'migration_get_vendor_api_key'     => true,
		'migration_successful'             => true,
		'migration_initiated_tools'        => true,
		'mfe_migration_initiated'          => true,
		'migration_initiated'              => true,
	);

	/**
	 * Returns the list of valid actions that an event can perform
	 *
	 * @return array
	 */
	public static function get_valid_actions() {
		return self::$valid_actions;
	}

	/**
	 * Valid categories of on event.
	 *
	 * @return array
	 */
	public static function get_category() {
		return self::$category;
	}
}
