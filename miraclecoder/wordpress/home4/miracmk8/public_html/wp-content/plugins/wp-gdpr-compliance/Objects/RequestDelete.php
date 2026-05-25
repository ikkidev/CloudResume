<?php
namespace WPGDPRC\Objects;

use WPGDPRC\Objects\Data\Comment;
use WPGDPRC\Objects\Data\GravityFormsEntry;
use WPGDPRC\Objects\Data\User;
use WPGDPRC\Objects\Data\WooCommerceOrder;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Anonymous;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Admin;
use WPGDPRC\WordPress\Plugin;

/**
 * Class RequestDelete
 * @package WPGDPRC\Objects
 */
class RequestDelete extends AbstractRequest {

	const TABLE = 'delete_requests';

	const KEY_ACCESS_ID  = 'access_request_id';
	const KEY_SESSION_ID = 'session_id';
	const KEY_IP_ADDRESS = 'ip_address';
	const KEY_DATA_ID    = 'data_id';
	const KEY_TYPE       = 'type';
	const KEY_PROCESSED  = 'processed';

	/** @var int */
	private $accessRequestId = 0;
	/** @var string */
	private $sessionId = '';
	/** @var string */
	private $ipAddress = '';
	/** @var int */
	private $dataId = 0;
	/** @var string */
	private $type = '';
	/** @var int */
	private $processed = 0;

	/**
	 * @return int
	 */
	public function getAccessRequestId(): int {
		return (int) $this->accessRequestId;
	}

	/**
	 * @param int $accessRequestId
	 */
	public function setAccessRequestId( int $accessRequestId = 0 ) {
		$this->accessRequestId = $accessRequestId;
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
	 * @return int
	 */
	public function getDataId(): int {
		return (int) $this->dataId;
	}

	/**
	 * @param int $dataId
	 */
	public function setDataId( int $dataId = 0 ) {
		$this->dataId = $dataId;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return stripslashes( $this->type );
	}

	/**
	 * @param string $type
	 */
	public function setType( string $type = '' ) {
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getProcessed(): int {
		return (int) $this->processed;
	}

	/**
	 * @param int $processed
	 */
	public function setProcessed( int $processed = 0 ) {
		$this->processed = $processed;
	}

	/**
	 * @return string
	 */
	public function getNiceTypeLabel(): string {
		switch ( $this->getType() ) {
			case Comment::getDataSlug():
			case 'comment': // legacy name
				return Comment::getDataName();

			case User::getDataSlug():
			case 'user': // legacy name
				return User::getDataName();

			case WooCommerceOrder::getDataSlug():
			case 'woocommerce_order': // legacy name
				return WooCommerceOrder::getDataName();

			default:
				return ucfirst( str_replace( [ '-' . '_' ], ' ', $this->getType() ) );
		}
	}

	/**
	 * Gets update action (to be fired after saving to the database)
	 * @return string
	 */
	public function getUpdateAction(): string {
		return Plugin::PREFIX . '_request_delete_updated';
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
                `' . static::KEY_ACCESS_ID . '` bigint(20) NOT NULL,
                `' . static::KEY_SESSION_ID . '` varchar(255) NOT NULL,
                `' . static::KEY_IP_ADDRESS . '` varchar(100) NOT NULL,
                `' . static::KEY_DATA_ID . '` bigint(20) NOT NULL,
                `' . static::KEY_TYPE . '` varchar(255) NOT NULL,
                `' . static::KEY_PROCESSED . "` tinyint(1) DEFAULT '0' NOT NULL,
                `" . static::KEY_CREATED . "` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY (`" . static::KEY_ID . '`)
            ) ' . $wpdb->get_charset_collate() . ';';
			dbDelta( $query );
			update_option( $key, '1.0' );
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
				static::KEY_ACCESS_ID  => $this->getAccessRequestId(),
				static::KEY_SESSION_ID => $this->getSessionId(),
				static::KEY_IP_ADDRESS => $this->getIpAddress(),
				static::KEY_DATA_ID    => $this->getDataId(),
				static::KEY_TYPE       => $this->getType(),
				static::KEY_PROCESSED  => $this->getProcessed(),
			]
		);
	}

	/**
	 * Lists the special data types for saving to the database
	 * @return array
	 */
	public function listDataTypes(): array {
		return [
			static::KEY_ID        => '%d',
			static::KEY_SITE_ID   => '%d',
			static::KEY_ACCESS_ID => '%d',
			static::KEY_DATA_ID   => '%d',
			static::KEY_PROCESSED => '%d',
		];
	}

	/**
	 * Loads the database data to the object
	 * @param array $row
	 */
	protected function loadByRow( array $row = [] ) {
		parent::loadByRow( $row );

		$this->setAccessRequestId( $row[ static::KEY_ACCESS_ID ] ?? 0 );
		$this->setSessionId( $row[ static::KEY_SESSION_ID ] ?? '' );
		$this->setIpAddress( $row[ static::KEY_IP_ADDRESS ] ?? '' );
		$this->setDataId( $row[ static::KEY_DATA_ID ] ?? 0 );
		$this->setType( $row[ static::KEY_TYPE ] ?? '' );
		$this->setProcessed( $row[ static::KEY_PROCESSED ] ?? 0 );
	}

	/**
	 * @param string    $limit
	 * @param null|bool $anonymous
	 * @return RequestDelete[]
	 */
	public static function getOldRequests( string $limit = '-24 hours', $anonymous = null ): array {
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
		if ( ! is_null( $anonymous ) ) {
			$filters[ static::KEY_IP_ADDRESS ] = [
				'value'   => Anonymous::getIpAddress(),
				'compare' => $anonymous ? '=' : '!=',
			];
		}

		return static::getList( $filters );
	}

	/**
	 * Gets by type, data_id and access_request_id
	 * @param string $type
	 * @param int    $data_id
	 * @param int    $access_id
	 * @return bool|static
	 */
	public static function getByTypeAndDataIdAndAccessId( string $type = '', int $data_id = 0, int $access_id = 0 ) {
		if ( empty( $type ) ) {
			return false;
		}

		global $wpdb;
		$query  = 'SELECT `' . static::KEY_ID . '` FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_TYPE . '` = %s';
		$query .= ' AND `' . static::KEY_DATA_ID . '` = %d';
		$query .= ' AND `' . static::KEY_ACCESS_ID . '` = %d';
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );

		$result = $wpdb->get_row( $wpdb->prepare( $query, $type, (int) $data_id, (int) $access_id ), ARRAY_A );
		if ( $result !== null ) {
			return new static( $result[ static::KEY_ID ] );
		}

		return false;
	}

	/**
	 * Gets by type and access_request_id
	 * @param string $type
	 * @param int    $access_id
	 * @return static[]
	 */
	public static function getByTypeAndAccessId( string $type = '', int $access_id = 0 ): array {
		$list = [];
		if ( empty( $type ) ) {
			return $list;
		}

		global $wpdb;
		$query  = 'SELECT `' . static::KEY_ID . '` FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_TYPE . '` = %s';
		$query .= ' AND `' . static::KEY_ACCESS_ID . '` = %d';
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );

		$result = $wpdb->get_results( $wpdb->prepare( $query, $type, (int) $access_id ), ARRAY_A );
		if ( empty( $result ) ) {
			return $list;
		}

		foreach ( $result as $row ) {
			$list[ $row[ static::KEY_ID ] ] = new static( $row[ static::KEY_ID ] );
		}
		return $list;
	}

	/**
	 * @param int $access_id
	 * @return static[]
	 */
	public static function getByAccessId( int $access_id = 0 ): array {
		$list = [];
		if ( empty( $access_id ) ) {
			return $list;
		}

		global $wpdb;
		$query  = 'SELECT `' . static::KEY_ID . '` FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_ACCESS_ID . '` = %d';
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );

		$result = $wpdb->get_results( $wpdb->prepare( $query, (int) $access_id ), ARRAY_A );
		if ( empty( $result ) ) {
			return $list;
		}

		foreach ( $result as $row ) {
			$list[ $row[ static::KEY_ID ] ] = new static( $row[ static::KEY_ID ] );
		}
		return $list;
	}

	/**
	 * Gets total by access_request_id (including/excluding anonymized entries)
	 * @param int  $access_id
	 * @param bool $anonymous
	 * @return int
	 */
	public static function getCountByAccessId( int $access_id = 0, bool $anonymous = true ): int {
		if ( empty( $access_id ) ) {
			return false;
		}

		global $wpdb;
		$query  = 'SELECT COUNT(`' . static::KEY_ID . '`) FROM `' . self::getTable() . '`';
		$query .= ' WHERE `' . static::KEY_PROCESSED . "` = '0'";
		$query .= ' AND `' . static::KEY_ACCESS_ID . '` = %d';
		$query .= sprintf( ' AND `' . static::KEY_SITE_ID . '` = %d', get_current_blog_id() );
		if ( $anonymous === false ) {
			$query .= ' AND `' . static::KEY_IP_ADDRESS . "` != '" . Anonymous::getIpAddress() . "'";
		}

		$result = $wpdb->get_var( $wpdb->prepare( $query, (int) $access_id ) );
		if ( $result !== null ) {
			return (int) $result;
		}

		return 0;
	}

	/**
	 * @return int
	 */
	public static function getTotalToProcess(): int {
		$total = 0;
		$list  = RequestAccess::getActiveList();
		if ( empty( $list ) ) {
			return $total;
		}

		foreach ( $list as $object ) {
			$total += static::getCountByAccessId( $object->getId(), false );
		}
		return $total;
	}

	/**
	 * @param int $access_id
	 * @return array
	 */
	public static function anonymizeByAccessId( int $access_id = 0 ): array {
		$output = [
			'success'   => false,
			'error'     => [],
			'processed' => [],
			'message'   => '',
		];
		if ( empty( $access_id ) ) {
			$output['message'] = __( 'Unable to locate request.', 'wp-gdpr-compliance' );
			return $output;
		}

		$access = new RequestAccess( $access_id );
		if ( empty( $access ) ) {
			$output['message'] = __( 'Unable to locate request.', 'wp-gdpr-compliance' );
			return $output;
		}

		$email = $access->getEmailAddress();
		$list  = static::getByAccessId( $access->getId() );
		if ( empty( $list ) ) {
			$output['message'] = __( 'No requests to process.', 'wp-gdpr-compliance' );
			return $output;
		}

		foreach ( $list as $object ) {
			$request_id = $object->getId();
			$processed  = $object->getProcessed();

			if ( $processed ) {
				$output['error'][ $request_id ] = _x( 'This request has already been processed.', 'admin', 'wp-gdpr-compliance' );
				continue;
			}

			switch ( $object->getType() ) {
				case User::getDataSlug():
					if ( ! current_user_can( 'edit_users' ) ) {
						$output['error'][ $request_id ] = _x( "You're not allowed to edit users.", 'admin', 'wp-gdpr-compliance' );
						break;
					}

					$result = User::anonymize( $object->getDataId() );
					if ( is_wp_error( $result ) ) {
						$output['error'][ $request_id ] = _x( "This user doesn't exist.", 'admin', 'wp-gdpr-compliance' );
						break;
					}

					global $wpdb;
					$date = Time::formatLocalDateTime( 'Ymd.His', time() );
					$wpdb->update( $wpdb->users, [ 'user_login' => 'USERNAME_' . $date ], [ 'ID' => $object->getDataId() ] );

					$processed = 1;
					break;

				case Comment::getDataSlug():
					if ( ! current_user_can( 'edit_posts' ) ) {
						$output['error'][ $request_id ] = _x( "You're not allowed to edit comments.", 'admin', 'wp-gdpr-compliance' );
						break;
					}

					$result = Comment::anonymize( $object->getDataId() );
					if ( empty( $result ) ) {
						$output['error'][ $request_id ] = _x( "This comment doesn't exist.", 'admin', 'wp-gdpr-compliance' );
						break;
					}

					$processed = 1;
					break;

				case WooCommerceOrder::getDataSlug():
					if ( ! current_user_can( 'edit_shop_orders' ) ) {
						$output['error'][ $request_id ] = _x( "You're not allowed to edit WooCommerce orders.", 'admin', 'wp-gdpr-compliance' );
						break;
					}

					WooCommerceOrder::anonymize( $object->getDataId() );
					$processed = 1;
					break;
				case GravityFormsEntry::getDataSlug():
					if ( ! AdminHelper::userIsAdmin() ) {
						$output['error'][ $request_id ] = _x( "You're not allowed to edit form entries.", 'admin', 'wp-gdpr-compliance' );
						break;
					}
					GravityFormsEntry::getByDataId( $object->getDataId() )->anonymize();
					$processed = 1;
					break;
				default:
					break;
			}
			if ( empty( $processed ) ) {
				continue;
			}

			$object->setProcessed( 1 );
			$object->save();

			/* translators: %1$1s: Nice label %2$2s: id %3$3s: email*/
			$output['processed'][ $request_id ] = sprintf( __( '%1$1s #%2$2s with email address %3$3s.', 'wp-gdpr-compliance' ), $object->getNiceTypeLabel(), $object->getDataId(), $email );
		}

		return $output;
	}

	/**
	 * @param RequestAccess $object
	 * @param array         $data
	 * @return false|string
	 */
	public static function sendNotification( RequestAccess $object, array $data = [] ) {
		if ( empty( $data ) ) {
			return false;
		}
		if ( ! empty( $data['error'] ) && empty( $data['processed'] ) ) {
			return false;
		}
		if ( empty( $data['processed'] ) ) {
			return false;
		}

		$site_name  = AdminHelper::getSiteData( 'blogname', $object->getSiteId() );
		$site_email = AdminHelper::getSiteData( 'admin_email', $object->getSiteId() );
		$site_url   = AdminHelper::getSiteData( 'siteurl', $object->getSiteId() );

		/* translators: %s Email subject - site name */
		$subject = sprintf( __( '%s - Your request', 'wp-gdpr-compliance' ), $site_name );
		$subject = apply_filters( Plugin::PREFIX . '_delete_request_mail_subject', $subject, $object );

		$site_link = Elements::getLink( $site_url, $site_name, [ 'target' => '_blank' ], true );
		/* translators: %s Site home link element */
		$message  = sprintf( __( 'We have successfully processed your request and your data has been anonymized on %s.', 'wp-gdpr-compliance' ), $site_link ) . '<br /><br />';
		$message .= __( 'The following has been processed:', 'wp-gdpr-compliance' ) . '<br />';
		$message .= implode( '<br />', $data['processed'] );

		$message = apply_filters( Plugin::PREFIX . '_delete_request_mail_content', $message, $object );
		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $site_name . ' <' . $site_email . '>',
		];

		$response = wp_mail( $object->getEmailAddress(), $subject, $message, $headers );
		if ( empty( $response ) ) {
			return false;
		}

		return _x( 'Successfully sent a confirmation mail to the user.', 'admin', 'wp-gdpr-compliance' );
	}

}
