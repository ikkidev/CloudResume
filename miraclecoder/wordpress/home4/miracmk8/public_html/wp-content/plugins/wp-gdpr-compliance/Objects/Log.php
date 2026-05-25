<?php
namespace WPGDPRC\Objects;

use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Plugin;

/**
 * Class Log
 * @package WPGDPRC\Objects
 */
class Log extends AbstractObject {

	const TABLE = 'log';

	const KEY_PLUGIN_ID    = 'plugin_id';
	const KEY_FORM_ID      = 'form_id';
	const KEY_USER         = 'user';
	const KEY_IP_ADDRESS   = 'ip_address';
	const KEY_LOG          = 'log';
	const KEY_CONSENT_TEXT = 'consent_text';

	/** @var int */
	private $pluginId = 0;
	/** @var int */
	private $formId = 0;
	/** @var string */
	private $user = '';
	/** @var string */
	private $ipAddress = '';
	/** @var string */
	private $log = '';
	/** @var string */
	private $consentText = '';

	/**
	 * @return int
	 */
	public function getPluginId(): int {
		return (int) $this->pluginId;
	}

	/**
	 * @param int $pluginId
	 */
	public function setPluginId( int $pluginId = 0 ) {
		$this->pluginId = $pluginId;
	}

	/**
	 * @return int
	 */
	public function getFormId(): int {
		return (int) $this->formId;
	}

	/**
	 * @param int $formId
	 */
	public function setFormId( int $formId = 0 ) {
		$this->formId = $formId;
	}

	/**
	 * @return string
	 */
	public function getUser(): string {
		return stripslashes( $this->user );
	}

	/**
	 * @param string $user
	 */
	public function setUser( string $user = '' ) {
		$this->user = $user;
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
	public function getLog(): string {
		return stripslashes( $this->log );
	}

	/**
	 * @param string $log
	 */
	public function setLog( string $log = '' ) {
		$this->log = $log;
	}

	/**
	 * @return string
	 */
	public function getConsentText(): string {
		return stripslashes( $this->consentText );
	}

	/**
	 * @param string $consentText
	 */
	public function setConsentText( string $consentText = '' ) {
		$this->consentText = $consentText;
	}

	/**
	 * Gets update action (to be fired after saving to the database)
	 * @return string
	 */
	public function getUpdateAction(): string {
		return Plugin::PREFIX . '_log_updated';
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
		if ( version_compare( $version, '1.6', '<' ) ) {
			$query = 'CREATE TABLE IF NOT EXISTS `' . self::getTable() . '` (
                `' . static::KEY_ID . '` bigint(20) NOT NULL AUTO_INCREMENT,
                `' . static::KEY_PLUGIN_ID . '` varchar(255) NULL,
                `' . static::KEY_FORM_ID . '` varchar(255) NULL,
                `' . static::KEY_USER . '` varchar(255) NULL,
                `' . static::KEY_IP_ADDRESS . '` varchar(255) NOT NULL,
                `' . static::KEY_LOG . '` varchar(255) NULL,
                `' . static::KEY_CONSENT_TEXT . '` varchar(255) NULL,
                `' . static::KEY_CREATED . "` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY (`" . static::KEY_ID . '`)
            ) ' . $wpdb->get_charset_collate() . ';';
			dbDelta( $query );
			update_option( $key, '1.6' );
		}

		// add column to table
		if ( version_compare( $version, '1.8', '<' ) ) {
			if ( self::insertDbColumn( static::KEY_SITE_ID, 'bigint(20) NULL', static::KEY_ID ) ) {
				update_option( $key, '1.8' );
			}
		}
	}

	/**
	 * Lists the data for saving to the database
	 * @param bool $new
	 * @return array
	 */
	public function getData( bool $new = false ): array {
		$list = [
			static::KEY_PLUGIN_ID    => $this->getPluginId(),
			static::KEY_FORM_ID      => $this->getFormId(),
			static::KEY_USER         => $this->getUser(),
			static::KEY_IP_ADDRESS   => $this->getIpAddress(),
			static::KEY_LOG          => $this->getLog(),
			static::KEY_CONSENT_TEXT => $this->getConsentText(),
		];
		if ( ! $new ) {
			return $list;
		}

		$list[ static::KEY_SITE_ID ] = $this->getSiteId();
		$list[ static::KEY_CREATED ] = date_i18n( 'Y-m-d H:i:s' );
		return $list;
	}

	public function listDataTypes(): array {
		return [
			static::KEY_ID      => '%d',
			static::KEY_SITE_ID => '%d',
		];
	}

	/**
	 * @param array $row
	 */
	protected function loadByRow( array $row = [] ) {
		parent::loadByRow( $row );

		$this->setPluginId( $row[ static::KEY_PLUGIN_ID ] ?? 0 );
		$this->setFormId( $row[ static::KEY_FORM_ID ] ?? 0 );
		$this->setUser( $row[ static::KEY_USER ] ?? '' );
		$this->setIpAddress( $row[ static::KEY_IP_ADDRESS ] ?? '' );
		$this->setLog( $row[ static::KEY_LOG ] ?? '' );
		$this->setConsentText( $row[ static::KEY_CONSENT_TEXT ] ?? '' );
	}

	/**
	 * @param array $data
	 * @return bool|int
	 */
	public static function insertLog( array $data = [] ) {
		if ( empty( $data ) ) {
			return false;
		}
		if ( empty( $data[ static::KEY_LOG ] ) ) {
			return false;
		}

		$default = [
			static::KEY_SITE_ID      => get_current_blog_id(),
			static::KEY_PLUGIN_ID    => '',
			static::KEY_FORM_ID      => '',
			static::KEY_USER         => '',
			static::KEY_IP_ADDRESS   => '',
			static::KEY_LOG          => '',
			static::KEY_CONSENT_TEXT => '',
			static::KEY_CREATED      => Time::formatLocalDateTime( 'Y-m-d H:i:s', time() ),
		];

		global $wpdb;
		return $wpdb->insert( self::getTable(), array_merge( $default, $data ) );
	}

}
