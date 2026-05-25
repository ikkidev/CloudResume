<?php

namespace WPGDPRC\Objects\Data;

use WPGDPRC\Integrations\WPRegistration;
use WPGDPRC\Utils\Anonymous;
use WPGDPRC\WordPress\Plugin;

/**
 * Class User
 * @package WPGDPRC\Objects\Data
 */
class User {

	/** @var int */
	protected $id = 0;
	/** @var string */
	protected $username = '';
	/** @var string */
	protected $displayName = '';
	/** @var string */
	protected $emailAddress = '';
	/** @var string */
	protected $website = '';
	/** @var array */
	protected $metaData = [];
	/** @var string */
	protected $registeredDate = '';

	/**
	 * User constructor
	 *
	 * @param int $id
	 */
	public function __construct( int $id = 0 ) {
		if ( empty( $id ) ) {
			return;
		}

		$this->setId( (int) $id );
		$this->load();
		$this->loadMetaData();
	}

	/**
	 * @return string
	 */
	public static function getDataSlug(): string {
		return WPRegistration::getInstance()->getID();
	}

	/**
	 * @return string
	 */
	public static function getDataName(): string {
		return __( 'Registration', 'wp-gdpr-compliance' );
	}

	/**
	 * Loads User attributes
	 */
	public function load() {
		global $wpdb;
		$query = 'SELECT * FROM `' . $wpdb->users . '` WHERE `ID` = %d';
		$row   = $wpdb->get_row( $wpdb->prepare( $query, $this->getId() ) );
		if ( empty( $row ) ) {
			return;
		}

		$this->loadByRow( $row );
	}

	/**
	 * Loads User meta data attribute
	 */
	public function loadMetaData() {
		$this->setMetaData( $this->getMetaDataByUserId( $this->getId() ) );
	}

	/**
	 * Sets User attributes based on DB row
	 *
	 * @param \stdClass $row
	 */
	public function loadByRow( \stdClass $row ) {
		$this->setId( $row->ID );
		$this->setUsername( $row->user_login );
		$this->setDisplayName( $row->display_name );
		$this->setEmailAddress( $row->user_email );
		$this->setWebsite( $row->user_url );
		$this->setRegisteredDate( $row->user_registered );
	}

	/**
	 * Gets meta data by User ID
	 *
	 * @param int $user_id
	 *
	 * @return array
	 */
	public function getMetaDataByUserId( int $user_id = 0 ): array {
		$output = [];
		if ( empty( $user_id ) ) {
			return $output;
		}

		global $wpdb;
		$output  = [];
		$query   = 'SELECT * FROM `' . $wpdb->usermeta . '` WHERE `user_id` = %d';
		$results = $wpdb->get_results( $wpdb->prepare( $query, $user_id ) );
		if ( empty( $results ) ) {
			return $output;
		}

		foreach ( $results as $row ) {
			$output[] = $row;
		}

		return $output;
	}

	/**
	 * Lists Users with specific email address
	 *
	 * @param string $email
	 *
	 * @return array
	 */
	public static function getByEmail( string $email = '' ): array {
		$output = [];
		if ( empty( $email ) ) {
			return $output;
		}

		global $wpdb;
		$query   = 'SELECT * FROM `' . $wpdb->users . '` WHERE `user_email` = %s';
		$results = $wpdb->get_results( $wpdb->prepare( $query, $email ) );
		if ( empty( $results ) ) {
			return $output;
		}

		foreach ( $results as $row ) {
			$object = new self();
			$object->loadByRow( $row );
			$output[] = $object;
		}

		return $output;
	}

	/**
	 * @param int $user_id
	 *
	 * @return int|\WP_Error
	 */
	public static function anonymize( int $user_id = 0 ) {
		$data = [
			'ID'            => (int) $user_id,
			'user_pass'     => wp_generate_password( 30 ),
			'display_name'  => 'DISPLAY_NAME',
			'user_nicename' => 'NICE_NAME' . $user_id,
			'first_name'    => 'FIRST_NAME',
			'last_name'     => 'LAST_NAME',
			'user_email'    => Anonymous::getEmailAddress( $user_id ),
			'nickname'      => 'NICK_NAME' . $user_id,
		];

		$meta = [
			'billing_first_name',
			'billing_last_name',
			'billing_company',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_postcode',
			'billing_state',
			'billing_country',
			'billing_email',
			'billing_phone',
			'shipping_first_name',
			'shipping_last_name',
			'shipping_company',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_city',
			'shipping_postcode',
			'shipping_state',
			'shipping_country',
			'shipping_phone',
		];

		static::process_meta( $user_id, $meta );

		return wp_update_user( $data );
	}

	/**
	 * Anonymize usermeta
	 *
	 * @param int $user_id
	 * @param array $meta
	 */
	protected static function process_meta( int $user_id, array $meta ) {
		foreach ( $meta as $key ) {
			$value = strtoupper( $key ) . '_' . $user_id;
			update_user_meta( $user_id, $key, $value );
		}
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
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( string $username = '' ) {
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getDisplayName(): string {
		return $this->displayName;
	}

	/**
	 * @param string $display_name
	 */
	public function setDisplayName( string $display_name = '' ) {
		$this->displayName = $display_name;
	}

	/**
	 * @return string
	 */
	public function getEmailAddress(): string {
		return $this->emailAddress;
	}

	/**
	 * @param string $email_address
	 */
	public function setEmailAddress( string $email_address = '' ) {
		$this->emailAddress = $email_address;
	}

	/**
	 * @return string
	 */
	public function getWebsite(): string {
		return $this->website;
	}

	/**
	 * @param string $website
	 */
	public function setWebsite( string $website = '' ) {
		$this->website = $website;
	}

	/**
	 * @return array
	 */
	public function getMetaData(): array {
		return $this->metaData;
	}

	/**
	 * @param array $meta_data
	 */
	public function setMetaData( array $meta_data = [] ) {
		$this->metaData = $meta_data;
	}

	/**
	 * @return string
	 */
	public function getRegisteredDate(): string {
		return $this->registeredDate;
	}

	/**
	 * @param string $registered_date
	 */
	public function setRegisteredDate( string $registered_date = '' ) {
		$this->registeredDate = $registered_date;
	}
}
