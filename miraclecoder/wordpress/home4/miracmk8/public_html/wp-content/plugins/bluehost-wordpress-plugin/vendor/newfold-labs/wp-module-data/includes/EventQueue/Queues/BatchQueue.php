<?php

namespace NewfoldLabs\WP\Module\Data\EventQueue\Queues;

use NewfoldLabs\WP\Module\Data\Event;
use NewfoldLabs\WP\Module\Data\EventQueue\Queryable;
use NewfoldLabs\WP\ModuleLoader\Container;

/**
 * A table for storing events to later process.
 *
 * id | event | attempts | reserved_at | available_at | created_at
 */
class BatchQueue implements BatchQueueInterface {

	use Queryable;

	/**
	 * Dependency injection container
	 *
	 * @used-by Queryable::query()
	 * @used-by Queryable::table()
	 *
	 * @var Container $container
	 */
	protected $container;

	/**
	 * Create the `nfd_data_event_queue` table.
	 *
	 * Uses the `dbDelta` function to create the table if it doesn't exist.
	 *
	 * Used by activation hook and upgrade handler.
	 */
	public static function create_table(): void {
		global $wpdb;

		if ( ! function_exists( 'dbDelta' ) ) {
			require ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$wpdb->hide_errors();

		$charset_collate = $wpdb->get_charset_collate();

		$sql = <<<SQL
				CREATE TABLE {$wpdb->prefix}nfd_data_event_queue (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					event longtext NOT NULL,
					attempts tinyint(3) NOT NULL DEFAULT 0,
					reserved_at datetime DEFAULT NULL,
					available_at datetime NOT NULL,
					created_at datetime NOT NULL,
					PRIMARY KEY (id)
					) $charset_collate;
				SQL;

		dbDelta( $sql );
	}

	/**
	 * Constructor
	 *
	 * @param  Container $container Dependency injection container for query object and table name.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Push events onto the queue
	 *
	 * @param  non-empty-array<Event> $events The events to store in the queue.
	 *
	 * @return bool
	 */
	public function push( array $events ) {

		$time = current_time( 'mysql' );

		$inserts = array();
		foreach ( $events as $event ) {
			$inserts[] = array(
				// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				'event'        => serialize( $event ),
				'available_at' => $time,
				'created_at'   => $event->created_at ?? $time,
				// Events are stored for later if they have failed once, except pageviews which are never sent synchronously.
				'attempts'     => 'pageview' === $event->key ? 0 : 1,
			);
		}

		return (bool) $this->bulkInsert( $this->table(), $inserts );
	}

	/**
	 * Pull events from the queue
	 *
	 * @param int $count The number of events to pull (limit).
	 *
	 * @return Event[]
	 */
	public function pull( int $count ) {

		$events = array();

		$raw_events = $this
			->query()
			->select( '*' )
			->from( $this->table(), false )
			->whereNull( 'reserved_at' )
			->where( 'available_at', '<=', current_time( 'mysql' ) )
			->order_by( 'available_at' )
			->limit( $count )
			->get();

		if ( ! is_array( $raw_events ) ) {
			return $events;
		}

		foreach ( $raw_events as $raw_event ) {
			if ( property_exists( $raw_event, 'id' ) && property_exists( $raw_event, 'event' ) ) {
				$event_data = maybe_unserialize( $raw_event->event );
				if ( is_array( $event_data ) && property_exists( $raw_event, 'created_at' ) ) {
					$event_data['created_at'] = $raw_event->created_at;
				}
				$events[ $raw_event->id ] = $event_data;
			}
		}

		return $events;
	}
	/**
	 * Remove events from the queue that have exceeded the attempts limit
	 *
	 * @param  int $limit number of attempts
	 * @return bool
	 */
	public function remove_events_exceeding_attempts_limit( $limit ) {
		return (bool) $this
			->query()
			->select( '*' )
			->from( $this->table(), false )
			->where( 'attempts', '>=', $limit )
			->delete();
	}

	/**
	 * Increment the attempts for a given event
	 *
	 * @param  int[] $ids list of ids to increment
	 *
	 * @return bool
	 */
	public function increment_attempt( array $ids ) {
		global $wpdb;

		$table = $this->table();
		$ids   = array_map( 'intval', $ids );

		$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

		return (bool) $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$table} SET attempts = attempts + 1 WHERE id IN ($placeholders)", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				...$ids
			)
		);
	}

	/**
	 * Remove events from the queue
	 *
	 * @param  int[] $ids list of ids to remove
	 *
	 * @return bool
	 */
	public function remove( array $ids ) {
		return (bool) $this
			->query()
			->table( $this->table(), false )
			->whereIn( 'id', $ids )
			->delete();
	}

	/**
	 * Reserve events in the queue
	 *
	 * @param  int[] $ids list of ids to reserve
	 *
	 * @return bool
	 */
	public function reserve( array $ids ) {
		return (bool) $this
			->query()
			->table( $this->table(), false )
			->whereIn( 'id', $ids )
			->update( array( 'reserved_at' => current_time( 'mysql' ) ) );
	}

	/**
	 * Release events back onto the queue
	 *
	 * @param  int[] $ids list of ids to release
	 *
	 * @return bool
	 */
	public function release( array $ids ) {
		return (bool) $this
			->query()
			->table( $this->table(), false )
			->whereIn( 'id', $ids )
			->update( array( 'reserved_at' => null ) );
	}

	/**
	 * Count the number of events in the queue
	 *
	 * @return int
	 */
	public function count() {
		return $this
			->query()
			->select( '*' )
			->from( $this->table(), false )
			->whereNull( 'reserved_at' )
			->where( 'available_at', '<=', current_time( 'mysql' ) )
			->count();
	}
}
