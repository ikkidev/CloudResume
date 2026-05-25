<?php
namespace WPGDPRC\Objects;

use WPGDPRC\WordPress\Plugin;

/**
 * Class AbstractObject
 * @package WPGDPRC\Objects
 */
abstract class AbstractObject {

	const TABLE       = '';
	const KEY_ID      = 'ID';
	const KEY_SITE_ID = 'site_id';
	const KEY_CREATED = 'date_created';

	/** @var int */
	protected $id = 0;
	/** @var int */
	private $siteId = 0;
	/** @var string */
	private $dateCreated = '0000-00-00 00:00:00';

	/**
	 * AbstractObject constructor
	 * @param int $id
	 */
	public function __construct( int $id = 0 ) {
		static::installDbTable();
		if ( (int) $id < 0 ) {
			return;
		}

		$this->setId( $id );
		$this->load();
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return (int) $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( int $id = 0 ) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getSiteId(): int {
		return (int) $this->siteId;
	}

	/**
	 * @param int $siteId
	 */
	public function setSiteId( int $siteId = 0 ) {
		$this->siteId = $siteId;
	}

	/**
	 * @return string
	 */
	public function getDateCreated(): string {
		return $this->dateCreated;
	}

	/**
	 * @param string $dateCreated
	 */
	public function setDateCreated( string $dateCreated = '0000-00-00 00:00:00' ) {
		$this->dateCreated = $dateCreated;
	}

	/**
	 * Gets update action (to be fired after saving to the database)
	 * @return string
	 */
	abstract public function getUpdateAction(): string;

	/**
	 * Gets the database table name
	 * @return string
	 */
	public static function getTable(): string {
		global $wpdb;
		return $wpdb->base_prefix . Plugin::PREFIX . '_' . static::TABLE;
	}

	/**
	 * Gets teh database version key
	 * @return string
	 */
	public static function tableVersionKey(): string {
		return Plugin::PREFIX . '_db_version_' . static::TABLE;
	}

	/**
	 * Gets the database version
	 * @return string
	 */
	public static function tableVersion(): string {
		return get_option( static::tableVersionKey(), '0' );
	}

	/**
	 * Checks if table exists
	 * @return bool
	 */
	public static function tableExists(): bool {

		if ( Cache::getInstance()->isset( [ 'tableExists', static::TABLE ] ) ) {
			return Cache::getInstance()->get( [ 'tableExists', static::TABLE ] );
		}

		global $wpdb;
		$result = $wpdb->query( "SHOW TABLES LIKE '" . static::getTable() . "'" );
		$result === 1;

		Cache::getInstance()->set( [ 'tableExists', static::TABLE ], $result );

		return $result;
	}

	/**
	 * @param string $column
	 * @param string $type
	 * @param string $after
	 * @return bool
	 */
	public static function insertDbColumn( string $column = '', string $type = '', string $after = '' ): bool {
		if ( ! static::tableExists() ) {
			return false;
		}

		global $wpdb;
		if ( $wpdb->get_var( 'SHOW COLUMNS FROM ' . static::getTable() . " LIKE '" . $column . "'" ) ) {
			return true;
		}

		$query = 'ALTER TABLE `' . static::getTable() . '` ADD column `' . $column . '` ' . $type;
		if ( ! empty( $after ) ) {
			$query .= ' AFTER `' . $after . '`;';
		}
		$wpdb->query( $query );
		return true;
	}

	/**
	 * Installs/Updates the database table
	 */
	public static function installDbTable() {
		// Fill in extended class
	}

	/**
	 * Lists the data for saving to the database
	 * @param bool $new
	 * @return array
	 */
	abstract public function getData( bool $new = false ): array;

	/**
	 * Lists the special data types for saving to the database
	 * @return array
	 */
	abstract public function listDataTypes(): array;

	/**
	 * Gets the data types for saving to the database
	 * @param bool $new
	 * @return array
	 */
	public function getTypes( bool $new = true ): array {
		$list   = $this->listDataTypes();
		$result = [];
		foreach ( $this->getData( $new ) as $key => $value ) {
			$result[] = $list[ $key ] ?? '%s';
		}

		return $result;
	}

	/**
	 * Checks if object exists
	 * @param int $id
	 * @return bool
	 */
	public static function exists( int $id = 0 ): bool {
		global $wpdb;
		$query  = 'SELECT * FROM `' . static::getTable() . '` WHERE `' . static::KEY_ID . '` = %d';
		$result = $wpdb->get_row( $wpdb->prepare( $query, (int) $id ) );
		return $result !== null;
	}

	/**
	 * Loads the database data
	 */
	public function load() {

		$cacheKey = [ 'load', static::getTable(), $this->getId() ];
		if ( Cache::getInstance()->isset( $cacheKey ) ) {
			$row = Cache::getInstance()->get( $cacheKey );
		} else {
			global $wpdb;
			$query = 'SELECT * FROM `' . static::getTable() . '` WHERE `' . static::KEY_ID . '` = %d';
			$row   = $wpdb->get_row( $wpdb->prepare( $query, (int) $this->getId() ), ARRAY_A );
			Cache::getInstance()->set( $cacheKey, $row );
		}

		if ( is_array( $row ) ) {
			$this->loadByRow( $row );
		}
	}

	/**
	 * Loads the database data to the object
	 * @param array $row
	 */
	protected function loadByRow( array $row = [] ) {
		$this->setId( $row[ static::KEY_ID ] ?? 0 );
		$this->setSiteId( $row[ static::KEY_SITE_ID ] ?? 0 );
		$this->setDateCreated( $row[ static::KEY_CREATED ] ?? '0000-00-00 00:00:00' );
	}

	/**
	 * @param array $filters
	 * @param int   $limit
	 * @param int   $offset
	 * @return static[]
	 */
	public static function getList( array $filters = [], int $limit = 0, int $offset = 0 ): array {

		$cacheKey = [ 'getList', static::getTable(), $filters, $limit, $offset ];
		$output   = [];

		if ( Cache::getInstance()->isset( $cacheKey ) ) {
			$results = Cache::getInstance()->get( $cacheKey );
		} else {
			global $wpdb;
			$query  = 'SELECT * FROM `' . static::getTable() . '` WHERE 1';
			$query .= self::getQueryByFilters( $filters );
			$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );
			$query .= ' ORDER BY `' . static::KEY_CREATED . '` DESC';

			if ( ! empty( $limit ) ) {
				$query .= " LIMIT $offset, $limit";
			}

			$results = $wpdb->get_results( $query, ARRAY_A );
			Cache::getInstance()->set( $cacheKey, $results );
		}

		if ( empty( $results ) ) {
			return $output;
		}

		foreach ( $results as $row ) {
			$object = new static();
			$object->loadByRow( $row );
			$output[ $object->getId() ] = $object;
		}
		return $output;
	}

	/**
	 * @param array $filters
	 * @param bool  $grouped
	 * @return string
	 */
	public static function getQueryByFilters( array $filters = [], bool $grouped = false ): string {
		$output = '';
		if ( empty( $filters ) ) {
			return $output;
		}

		$count = 0;
		foreach ( $filters as $column => $filter ) {
			if ( isset( $filter['columns'] ) ) {
				$output .= ' AND ( ';
				$output .= trim( self::getQueryByFilters( $filter['columns'], true ) );
				$output .= ' )';
				$count++;
				continue;
			}

			$value = $filter['value'] ?? false;
			if ( $value === false ) {
				$count++;
				continue;
			}

			$or = isset( $filter['or'] ) && filter_var( $filter['or'], FILTER_VALIDATE_BOOLEAN ) ? 'OR' : 'AND';
			$or = $grouped === true && $count === 0 ? '' : $or;

			$compare  = $filter['compare'] ?? '=';
			$wildcard = isset( $filter['wildcard'] ) && filter_var( $filter['wildcard'], FILTER_VALIDATE_BOOLEAN ) ? '%' : '';

			if ( ( $compare === 'IN' || $compare === 'NOT IN' ) && is_array( $value ) ) {
				$in = '';
				foreach ( $value as $key => $data ) {
					$in .= $key !== 0 ? ', ' : '';
					$in .= is_numeric( $data ) ? $data : "'" . $data . "'";
				}
				$value   = '(' . $in . ')';
				$output .= " $or `$column` $compare $wildcard$value$wildcard";
			} else {
				$output .= " $or `$column` $compare '$wildcard$value$wildcard'";
			}
			$count++;
		}
		return $output;
	}

	/**
	 * @param array $filters
	 * @return int
	 */
	public static function getTotal( array $filters = [] ): int {

		if ( Cache::getInstance()->isset( [ 'getTotal', $filters ] ) ) {
			return Cache::getInstance()->get( [ 'getTotal', $filters ] );
		}

		global $wpdb;
		$query  = 'SELECT COUNT(`' . static::KEY_ID . '`) FROM `' . self::getTable() . '` WHERE 1';
		$query .= self::getQueryByFilters( $filters );
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . "` = '%d'", get_current_blog_id() );

		$result = $wpdb->get_var( $query );
		$result = $result !== null ? absint( $result ) : 0;

		Cache::getInstance()->set( [ 'getTotal', $filters ], $result );
		return $result;
	}

	/**
	 * @return string
	 */
	public static function getLastCreated(): string {
		global $wpdb;
		$query  = 'SELECT `' . static::KEY_CREATED . '` FROM `' . self::getTable() . '` WHERE 1';
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . "` = '%d'", get_current_blog_id() );
		$query .= ' ORDER BY `' . static::KEY_CREATED . '` DESC';

		$result = $wpdb->get_var( $query );
		return $result !== null ? $result : '';
	}

	/**
	 * Saves the data to the database
	 * @return bool|int
	 */
	public function save() {
		global $wpdb;

		if ( static::exists( $this->getId() ) ) {
			$data  = $this->getData();
			$types = $this->getTypes();

			$wpdb->update(
				static::getTable(),
				$data,
				[ static::KEY_ID => $this->getId() ],
				$types,
				[ '%d' ]
			);
			do_action( $this->getUpdateAction(), $this->getId() );
			return $this->getId();
		}

		$result = $wpdb->insert(
			static::getTable(),
			$this->getData( true ),
			$this->getTypes( true )
		);

		if ( $result !== false ) {
			$this->setId( $wpdb->insert_id );
			do_action( $this->getUpdateAction(), $this->getId() );
			return $this->getId();
		}
		return false;
	}

	/**
	 * Deletes the data from the database
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById( int $id = 0 ): bool {
		if ( ! static::exists( $id ) ) {
			return false;
		}

		$object = new static( $id );
		return $object->delete();
	}

	/**
	 * @return bool
	 */
	public function delete(): bool {
		global $wpdb;
		$result = $wpdb->delete( static::getTable(), [ static::KEY_ID => $this->getId() ], [ '%d' ] );
		if ( $result !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if object has been updated
	 * To be overwritten in the extended classes
	 * @param static $before
	 * @return bool
	 */
	public function updated( $before ): bool {
		if ( $this->getId() != $before->getId() ) {
			return true;
		}
		return false;
	}

}
