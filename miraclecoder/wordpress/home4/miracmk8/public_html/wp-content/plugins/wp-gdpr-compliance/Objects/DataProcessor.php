<?php
namespace WPGDPRC\Objects;

use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Front\Consent\Bar as ConsentBar;
use WPGDPRC\WordPress\Front\Consent\Modal as ConsentModal;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Consent
 * @package WPGDPRC\Objects
 */
class DataProcessor extends AbstractObject {

	const TABLE = 'consents';

	const KEY_TITLE       = 'title';
	const KEY_DESCRIPTION = 'description';
	const KEY_SNIPPET     = 'snippet';
	const KEY_WRAP        = 'wrap';
	const KEY_PLACEMENT   = 'placement';
	const KEY_PLUGINS     = 'plugins';
	const KEY_REQUIRED    = 'required';
	const KEY_ACTIVE      = 'active';
	const KEY_MODIFIED    = 'date_modified';

	const STATUS_ACTIVE   = 'active';
	const STATUS_REQUIRED = 'required';
	const STATUS_OPTIONAL = 'optional';

	const PLACE_HEAD = 'head';
	const PLACE_BODY = 'body';
	const PLACE_FOOT = 'footer';

	/** @var string */
	private $title = '';
	/** @var string */
	private $description = '';
	/** @var string */
	private $snippet = '';
	/** @var int */
	private $wrap = 1;
	/** @var string */
	private $placement = '';
	/** @var string */
	private $plugins = '';
	/** @var int */
	private $required = 0;
	/** @var int */
	private $active = 0;
	/** @var string */
	private $dateModified = '';

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return stripslashes( $this->title );
	}

	/**
	 * @param string $title
	 */
	public function setTitle( string $title = '' ) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return stripslashes( $this->description );
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description = '' ) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getSnippet(): string {
		return stripslashes( $this->snippet );
	}

	/**
	 * @param string $snippet
	 */
	public function setSnippet( string $snippet = '' ) {
		$this->snippet = $snippet;
	}

	/**
	 * @return string
	 */
	public function getWrap(): string {
		return (string) $this->wrap;
	}

	/**
	 * @param string $wrap
	 */
	public function setWrap( string $wrap = '1' ) {
		$this->wrap = $wrap;
	}

	/**
	 * @return string
	 */
	public function getPlacement(): string {
		return $this->placement;
	}

	/**
	 * @param string $placement
	 */
	public function setPlacement( string $placement = '' ) {
		$this->placement = $placement;
	}

	/**
	 * @return string
	 */
	public function getPlugins(): string {
		return $this->plugins;
	}

	/**
	 * @param string $plugins
	 */
	public function setPlugins( string $plugins = '' ) {
		$this->plugins = $plugins;
	}

	/**
	 * @return int
	 */
	public function getRequired(): int {
		return (int) $this->required;
	}

	/**
	 * @param int $required
	 */
	public function setRequired( int $required = 0 ) {
		$this->required = $required;
	}

	/**
	 * @return int
	 */
	public function getActive(): int {
		return (int) $this->active;
	}

	/**
	 * @param int $active
	 */
	public function setActive( int $active = 0 ) {
		$this->active = $active;
	}

	/**
	 * @return string
	 */
	public function getDateModified(): string {
		return $this->dateModified;
	}

	/**
	 * @param string $dateModified
	 */
	public function setDateModified( string $dateModified = '0000-00-00 00:00:00' ) {
		$this->dateModified = $dateModified;
	}

	/**
	 * Gets update action (to be fired after saving to the database)
	 * @return string
	 */
	public function getUpdateAction(): string {
		return Plugin::PREFIX . '_consent_updated';
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
                `' . static::KEY_TITLE . '` text NOT NULL,
                `' . static::KEY_DESCRIPTION . '` longtext NOT NULL,
                `' . static::KEY_SNIPPET . '` longtext NOT NULL,
                `' . static::KEY_PLACEMENT . '` varchar(20) NOT NULL,
                `' . static::KEY_PLUGINS . '` longtext NOT NULL,
                `' . static::KEY_ACTIVE . "` tinyint(1) DEFAULT '1' NOT NULL,
                `" . static::KEY_MODIFIED . "` timestamp DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                `" . static::KEY_CREATED . "` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY (`" . static::KEY_ID . '`)
            ) ' . $wpdb->get_charset_collate() . ';';
			dbDelta( $query );
			update_option( $key, '1.0' );
		}

		// add column to table
		if ( version_compare( $version, '1.1', '<' ) ) {
			if ( self::insertDbColumn( static::KEY_WRAP, "tinyint(1) DEFAULT '1' NOT NULL", static::KEY_SNIPPET ) ) {
				update_option( $key, '1.1' );
			}
		}

		// add column to table
		if ( version_compare( $version, '1.2', '<' ) ) {
			if ( self::insertDbColumn( static::KEY_REQUIRED, "tinyint(1) DEFAULT '0' NOT NULL", static::KEY_PLUGINS ) ) {
				update_option( $key, '1.2' );
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
			static::KEY_TITLE       => $this->getTitle(),
			static::KEY_DESCRIPTION => $this->getDescription(),
			static::KEY_SNIPPET     => $this->getSnippet(),
			static::KEY_WRAP        => $this->getWrap(),
			static::KEY_PLACEMENT   => $this->getPlacement(),
			static::KEY_PLUGINS     => $this->getPlugins(),
			static::KEY_REQUIRED    => $this->getRequired(),
			static::KEY_ACTIVE      => $this->getActive(),
			static::KEY_MODIFIED    => date_i18n( 'Y-m-d H:i:s' ),
		];
		if ( ! $new ) {
			return $list;
		}

		$list[ static::KEY_SITE_ID ] = $this->getSiteId();
		$list[ static::KEY_CREATED ] = date_i18n( 'Y-m-d H:i:s' );
		return $list;
	}

	/**
	 * Lists the special data types for saving to the database
	 * @return array
	 */
	public function listDataTypes(): array {
		return [
			static::KEY_ID       => '%d',
			static::KEY_SITE_ID  => '%d',
			static::KEY_WRAP     => '%d',
			static::KEY_REQUIRED => '%d',
			static::KEY_ACTIVE   => '%d',
		];
	}

	/**
	 * @param array $row
	 */
	protected function loadByRow( array $row = [] ) {
		parent::loadByRow( $row );

		$this->setTitle( $row[ static::KEY_TITLE ] ?? '' );
		$this->setDescription( $row[ static::KEY_DESCRIPTION ] ?? '' );
		$this->setSnippet( $row[ static::KEY_SNIPPET ] ?? '' );
		$this->setWrap( $row[ static::KEY_WRAP ] ?? 1 );
		$this->setPlacement( $row[ static::KEY_PLACEMENT ] ?? '' );
		$this->setPlugins( $row[ static::KEY_PLUGINS ] ?? '' );
		$this->setRequired( $row[ static::KEY_REQUIRED ] ?? 0 );
		$this->setActive( $row[ static::KEY_ACTIVE ] ?? 1 );
		$this->setDateModified( $row[ static::KEY_MODIFIED ] ?? '' );
	}

	/**
	 * Checks if object has been updated
	 * @param static $before
	 * @return bool
	 */
	public function updated( $before ): bool {
		if ( $this->getId() !== $before->getId() ) {
			return true;
		}
		if ( $this->getTitle() !== $before->getTitle() ) {
			return true;
		}
		if ( $this->getDescription() !== $before->getDescription() ) {
			return true;
		}
		if ( $this->getSnippet() !== $before->getSnippet() ) {
			return true;
		}
		if ( $this->getPlacement() !== $before->getPlacement() ) {
			return true;
		}
		if ( $this->getPlugins() !== $before->getPlugins() ) {
			return true;
		}
		if ( $this->getActive() !== $before->getActive() ) {
			return true;
		}
		return false;
	}

	/**
	 * Lists possible values for wrapping
	 * @return array
	 */
	public static function listWrapChoices(): array {
		return [
			'1' => esc_html_x( 'Wrap my code in <script> tags', 'admin', 'wp-gdpr-compliance' ),
			'0' => esc_html_x( 'Do not wrap my code', 'admin', 'wp-gdpr-compliance' ),
		];
	}

	/**
	 * Lists possible values for placement
	 * @return array
	 */
	public static function listPlaceChoices(): array {
		return [
			static::PLACE_HEAD => esc_html_x( 'Head (in the <head> tag)', 'admin', 'wp-gdpr-compliance' ),
			static::PLACE_BODY => esc_html_x( 'Body (direct after the <body> tag)', 'admin', 'wp-gdpr-compliance' ),
			static::PLACE_FOOT => esc_html_x( 'Footer (at the end of the <body> tag)', 'admin', 'wp-gdpr-compliance' ),
		];
	}

	/**
	 * @param null|string $string
	 * @return string
	 */
	public static function validatePlace( $string = null ): string {
		$default = static::PLACE_HEAD;
		if ( is_null( $string ) ) {
			return $default;
		}

		$result  = ! empty( $string ) ? sanitize_key( $string ) : $default;
		$options = self::listPlaceChoices();
		return isset( $options[ $result ] ) ? $result : $default;
	}

	/**
	 * @return array
	 */
	public static function listObjects(): array {
		$list = [
			'active'   => [],
			'disabled' => [],
		];

		global $wpdb;
		$query  = 'SELECT * FROM `' . static::getTable() . '`';
		$query .= sprintf( 'WHERE `' . static::KEY_SITE_ID . "` = '%d'", get_current_blog_id() );
		$result = $wpdb->get_results( $query, ARRAY_A );
		if ( empty( $result ) ) {
			return $list;
		}

		foreach ( $result as $row ) {
			$object = new DataProcessor( $row[ static::KEY_ID ] );
			$key    = $object->getActive() ? 'active' : 'disabled';

			$list[ $key ][ $object->getId() ] = $object;
		}
		return $list;
	}

	/**
	 * @return bool
	 */
	public static function isActive(): bool {
		return self::tableExists() && self::getTotal( [ static::KEY_ACTIVE => [ 'value' => 1 ] ] ) > 0;
	}

	/**
	 * @param string $type
	 * @param array  $filters
	 * @param int    $limit
	 * @param int    $offset
	 * @return DataProcessor[]
	 */
	public static function getListByType( string $type = self::STATUS_ACTIVE, array $filters = [], int $limit = 0, int $offset = 0 ): array {
		$filters[ static::KEY_ACTIVE ] = [ 'value' => 1 ];
		if ( $type === static::STATUS_REQUIRED ) {
			$filters[ static::KEY_REQUIRED ] = [ 'value' => 1 ];
		}
		if ( $type === static::STATUS_OPTIONAL ) {
			$filters[ static::KEY_REQUIRED ] = [ 'value' => 0 ];
		}

		return parent::getList( $filters, $limit, $offset );
	}

	/**
	 * @return array
	 */
	public static function getListByPlacements(): array {
		$output   = [];
		$consents = self::getListByType( 'active' );
		if ( empty( $consents ) ) {
			return $output;
		}

		foreach ( $consents as $consent ) {
			$content = $consent->getSnippet();
			if ( $consent->getWrap() ) {
				$content = sprintf( '<script type="text/javascript">%1s</script>', $consent->getSnippet() );
			}

			$output[] = [
				static::KEY_ID        => $consent->getId(),
				static::KEY_REQUIRED  => $consent->getRequired() === 1,
				static::KEY_PLACEMENT => $consent->getPlacement(),
				'content'             => $content,
			];
		}
		return $output;
	}

	/**
	 * Renders consent bar
	 */
	public static function renderBar() {
		ConsentBar::render();
	}

	/**
	 * Renders consent popup/modal
	 */
	public static function renderModal() {
		ConsentModal::render();
	}

	/**
	 * Checks if all the (active) consents are required
	 * @return bool
	 */
	public static function allRequired(): bool {
		return count( static::getListByType( static::STATUS_REQUIRED ) ) === count( static::getListByType( static::STATUS_ACTIVE ) );
	}

}
