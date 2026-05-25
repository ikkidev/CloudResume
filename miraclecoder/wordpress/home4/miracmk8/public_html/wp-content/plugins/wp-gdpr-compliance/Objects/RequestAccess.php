<?php
namespace WPGDPRC\Objects;

use WPGDPRC\Utils\Anonymous;
use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Plugin;

/**
 * Class RequestAccess
 * @package WPGDPRC\Objects
 */
class RequestAccess extends AbstractRequest {

	const TABLE = 'access_requests';

	const KEY_EMAIL_ADDRESS = 'email_address';
	const KEY_SESSION_ID    = 'session_id';
	const KEY_IP_ADDRESS    = 'ip_address';
	const KEY_EXPIRED       = 'expired';
	const KEY_TOKEN         = 'token';

	const STATUS_ACTIVE  = 'active';
	const STATUS_EXPIRED = 'expired';

	/** @var string */
	private $emailAddress = '';
	/** @var string */
	private $sessionId = '';
	/** @var string */
	private $ipAddress = '';
	/** @var string */
	private $token = '';
	/** @var int */
	private $expired = 0;
	/** @var array */
	private $deleteRequests;

	/**
	 * @return string
	 */
	public function getEmailAddress(): string {
		return stripslashes( $this->emailAddress );
	}

	/**
	 * @param string $emailAddress
	 */
	public function setEmailAddress( string $emailAddress = '' ) {
		$this->emailAddress = $emailAddress;
	}

	/**
	 * @return string
	 */
	public function getSessionId(): string {
		return stripslashes( $this->sessionId );
	}

	/**
	 * @param string $sessionId
	 */
	public function setSessionId( string $sessionId = '' ) {
		$this->sessionId = $sessionId;
	}

	/**
	 * @return string
	 */
	public function getIpAddress(): string {
		return stripslashes( $this->ipAddress );
	}

	/**
	 * @param string $ipAddress
	 */
	public function setIpAddress( string $ipAddress = '' ) {
		$this->ipAddress = $ipAddress;
	}

	/**
	 * @return string
	 */
	public function getToken(): string {
		return stripslashes( $this->token );
	}

	/**
	 * @param string $token
	 */
	public function setToken( string $token = '' ) {
		$this->token = $token;
	}

	/**
	 * @return int
	 */
	public function getExpired(): int {
		return (int) $this->expired;
	}

	/**
	 * @param int $expired
	 */
	public function setExpired( int $expired = 0 ) {
		$this->expired = $expired;
	}

	/**
	 * Gets update action (to be fired after saving to the database)
	 * @return string
	 */
	public function getUpdateAction(): string {
		return Plugin::PREFIX . '_request_access_updated';
	}

	/**
	 * Installs/Updates the database table
	 */
	public static function installDbTable() {
		$key     = self::tableVersionKey();
		$version = self::tableVersion();

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// create table
		if ( version_compare( $version, '1.0', '<' ) ) {
			$query = 'CREATE TABLE IF NOT EXISTS `' . self::getTable() . '` (
                `' . static::KEY_ID . '` bigint(20) NOT NULL AUTO_INCREMENT,
                `' . static::KEY_SITE_ID . '` bigint(20) NOT NULL,
                `' . static::KEY_EMAIL_ADDRESS . '` varchar(100) NOT NULL,
                `' . static::KEY_SESSION_ID . '` varchar(255) NOT NULL,
                `' . static::KEY_IP_ADDRESS . '` varchar(100) NOT NULL,
                `' . static::KEY_EXPIRED . "` tinyint(1) DEFAULT '0' NOT NULL,
                `" . static::KEY_CREATED . "` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY (`" . static::KEY_ID . '`)
            ) ' . $wpdb->get_charset_collate() . ';';
			dbDelta( $query );
			update_option( $key, '1.0' );
		}

		// add column to table
		if ( version_compare( $version, '1.7', '<' ) ) {
			if ( self::insertDbColumn( static::KEY_TOKEN, 'text NOT NULL', static::KEY_IP_ADDRESS ) ) {
				update_option( $key, '1.7' );
			}
		}
	}

	/**
	 * Lists the data for saving to the database
	 * @param bool $new
	 * @return array
	 */
	public function getData( bool $new = false ): array {
		return array_merge(
			parent::getData( $new ),
			[
				static::KEY_EMAIL_ADDRESS => $this->getEmailAddress(),
				static::KEY_SESSION_ID    => $this->getSessionId(),
				static::KEY_IP_ADDRESS    => $this->getIpAddress(),
				static::KEY_TOKEN         => $this->getToken(),
				static::KEY_EXPIRED       => $this->getExpired(),
			]
		);
	}

	/**
	 * Lists the special data types for saving to the database
	 * @return array
	 */
	public function listDataTypes(): array {
		return [
			static::KEY_ID      => '%d',
			static::KEY_SITE_ID => '%d',
			static::KEY_EXPIRED => '%d',
		];
	}

	/**
	 * Loads the database data to the object
	 * @param array $row
	 */
	protected function loadByRow( array $row = [] ) {
		parent::loadByRow( $row );

		$this->setEmailAddress( $row[ static::KEY_EMAIL_ADDRESS ] ?? '' );
		$this->setSessionId( $row[ static::KEY_SESSION_ID ] ?? '' );
		$this->setIpAddress( $row[ static::KEY_IP_ADDRESS ] ?? '' );
		$this->setToken( $row[ static::KEY_TOKEN ] ?? '' );
		$this->setExpired( $row[ static::KEY_EXPIRED ] ?? 0 );
	}

	/**
	 * @return bool
	 */
	public function isAnonymized(): bool {
		return $this->getIpAddress() === Anonymous::getIpAddress();
	}

	/**
	 * @return RequestDelete[]
	 */
	public function getDeleteRequests(): array {
		if ( ! isset( $this->deleteRequests ) ) {
			$this->deleteRequests = RequestDelete::getByAccessId( $this->id );
		}
		return $this->deleteRequests;
	}

	/**
	 * @return bool
	 */
	public function hasDeleteRequests(): bool {
		return ! empty( $this->getDeleteRequests() );
	}

	/**
	 * @return bool
	 */
	public function isProcessed(): bool {
		$unprocessed = array_filter(
			$this->getDeleteRequests(),
			function ( $item ) {
				return $item->getProcessed() === 0;
			}
		);

		return empty( $unprocessed );
	}

	/**
	 * @return RequestAccess[]
	 */
	public static function getActiveList(): array {
		return self::getList( [ self::KEY_EXPIRED => [ 'value' => '0' ] ] );
	}

	/**
	 * @return RequestAccess[]
	 */
	public static function getExpiredList(): array {
		return self::getList( [ self::KEY_EXPIRED => [ 'value' => '1' ] ] );
	}

	/**
	 * @param string    $limit
	 * @param null|bool $expired
	 * @param null|bool $anonymous
	 * @return RequestAccess[]
	 */
	public static function getOldRequests( string $limit = '-24 hours', $expired = null, $anonymous = null ): array {
		$date = Time::localDateTime( time() );
		if ( empty( $date ) ) {
			return [];
		}

		$date->modify( $limit );

		$filters = [
			static::KEY_CREATED => [
				'value'   => $date->format( 'Y-m-d H:i:s' ),
				'compare' => '<=',
			],
		];
		if ( ! is_null( $expired ) ) {
			$filters[ static::KEY_EXPIRED ] = [ 'value' => $expired ? '1' : '0' ];
		}
		if ( ! is_null( $anonymous ) ) {
			$filters[ static::KEY_IP_ADDRESS ] = [
				'value'   => Anonymous::getIpAddress(),
				'compare' => $anonymous ? '=' : '!=',
			];
		}

		return static::getList( $filters );
	}

	/**
	 * Gets by token
	 * @param string $token
	 * @return false|RequestAccess
	 */
	public static function getByToken( string $token = '' ) {
		if ( empty( $token ) ) {
			return false;
		}

		global $wpdb;
		$query  = 'SELECT * FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_TOKEN . '` = %s';
		$query .= ' AND `' . static::KEY_EXPIRED . "` = '0'";
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );

		$result = $wpdb->get_row( $wpdb->prepare( $query, $token ), ARRAY_A );
		if ( $result !== null ) {
			return new static( $result[ static::KEY_ID ] );
		}

		return false;
	}

	/**
	 * Gets by email_address
	 * @param string $email
	 * @param bool   $active_only
	 * @return false|RequestAccess
	 */
	public static function getByEmail( string $email = '', bool $active_only = false ) {
		if ( empty( $email ) ) {
			return false;
		}

		global $wpdb;
		$query  = 'SELECT * FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_EMAIL_ADDRESS . '` = %s';
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );
		if ( $active_only === true ) {
			$query .= ' AND `' . static::KEY_EXPIRED . "` = '0'";
		}

		$result = $wpdb->get_row( $wpdb->prepare( $query, $email ), ARRAY_A );
		if ( $result !== null ) {
			return new static( $result[ static::KEY_ID ] );
		}

		return false;
	}

	/**
	 * Gets by email_address and session ID
	 * @param string $email
	 * @param string $session_id
	 * @return false|static
	 */
	public static function getByEmailAndSessionID( string $email = '', string $session_id = '' ) {
		if ( empty( $email ) ) {
			return false;
		}
		if ( empty( $session_id ) ) {
			return false;
		}

		global $wpdb;
		$query  = 'SELECT * FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_EMAIL_ADDRESS . '` = %s';
		$query .= ' AND `' . static::KEY_SESSION_ID . '` = %s';
		$query .= ' AND `' . static::KEY_EXPIRED . "` = '0'";
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );

		$result = $wpdb->get_row( $wpdb->prepare( $query, $email, $session_id ), ARRAY_A );
		if ( $result !== null ) {
			return new static( $result[ static::KEY_ID ] );
		}

		return false;
	}
}
