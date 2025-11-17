<?php

namespace WPGDPRC\Objects\Data;

use WPGDPRC\Integrations\Plugins\WooCommerce;
use WPGDPRC\Utils\Anonymous;

/**
 * Class WooCommerceOrder
 * @package WPGDPRC\Objects\Data
 */
class WooCommerceOrder {

	/** @var int */
	protected $orderId = 0;
	/** @var string */
	protected $billingEmailAddress = '';
	/** @var string */
	protected $billingFirstName = '';
	/** @var string */
	protected $billingLastName = '';
	/** @var string */
	protected $billingCompany = '';
	/** @var string */
	protected $billingAddressOne = '';
	/** @var string */
	protected $billingAddressTwo = '';
	/** @var string */
	protected $billingCity = '';
	/** @var string */
	protected $billingState = '';
	/** @var string */
	protected $billingPostCode = '';
	/** @var string */
	protected $billingCountry = '';
	/** @var string */
	protected $billingPhone = '';
	/** @var string */
	protected $shippingFirstName = '';
	/** @var string */
	protected $shippingLastName = '';
	/** @var string */
	protected $shippingCompany = '';
	/** @var string */
	protected $shippingAddressOne = '';
	/** @var string */
	protected $shippingAddressTwo = '';
	/** @var string */
	protected $shippingCity = '';
	/** @var string */
	protected $shippingState = '';
	/** @var string */
	protected $shippingPostCode = '';
	/** @var string */
	protected $shippingCountry = '';

	/**
	 * WooCommerceOrder constructor
	 *
	 * @param int $order_id
	 */
	public function __construct( int $order_id = 0 ) {
		if ( empty( $order_id ) ) {
			return;
		}

		$this->setOrderId( (int) $order_id );
		$this->load();
	}

	/**
	 * @return string
	 */
	public static function getDataSlug(): string {
		return WooCommerce::getInstance()->getID();
	}

	/**
	 * @return string
	 */
	public static function getDataName(): string {
		return __( 'Order', 'wp-gdpr-compliance' );
	}

	/**
	 * Sets WooCommerce Order attributes based on DB row
	 */
	public function load() {
		$this->setBillingEmailAddress( get_post_meta( $this->getOrderId(), '_billing_email', true ) );
		$this->setBillingFirstName( get_post_meta( $this->getOrderId(), '_billing_first_name', true ) );
		$this->setBillingLastName( get_post_meta( $this->getOrderId(), '_billing_last_name', true ) );
		$this->setBillingCompany( get_post_meta( $this->getOrderId(), '_billing_company', true ) );
		$this->setBillingAddressOne( get_post_meta( $this->getOrderId(), '_billing_address_1', true ) );
		$this->setBillingAddressTwo( get_post_meta( $this->getOrderId(), '_billing_address_2', true ) );
		$this->setBillingCity( get_post_meta( $this->getOrderId(), '_billing_city', true ) );
		$this->setBillingState( get_post_meta( $this->getOrderId(), '_billing_state', true ) );
		$this->setBillingPostCode( get_post_meta( $this->getOrderId(), '_billing_postcode', true ) );
		$this->setBillingCountry( get_post_meta( $this->getOrderId(), '_billing_country', true ) );
		$this->setBillingPhone( get_post_meta( $this->getOrderId(), '_billing_phone', true ) );
		$this->setShippingFirstName( get_post_meta( $this->getOrderId(), '_shipping_first_name', true ) );
		$this->setShippingLastName( get_post_meta( $this->getOrderId(), '_shipping_last_name', true ) );
		$this->setShippingCompany( get_post_meta( $this->getOrderId(), '_shipping_company', true ) );
		$this->setShippingAddressOne( get_post_meta( $this->getOrderId(), '_shipping_address_1', true ) );
		$this->setShippingAddressTwo( get_post_meta( $this->getOrderId(), '_shipping_address_2', true ) );
		$this->setShippingCity( get_post_meta( $this->getOrderId(), '_shipping_city', true ) );
		$this->setShippingState( get_post_meta( $this->getOrderId(), '_shipping_state', true ) );
		$this->setShippingPostCode( get_post_meta( $this->getOrderId(), '_shipping_postcode', true ) );
		$this->setShippingCountry( get_post_meta( $this->getOrderId(), '_shipping_country', true ) );
	}

	/**
	 * Lists WooCommerce Orders with specific email address
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
		$query   = 'SELECT * FROM ' . $wpdb->postmeta . " WHERE `meta_key` = '_billing_email' AND `meta_value` = %s";
		$results = $wpdb->get_results( $wpdb->prepare( $query, $email ) );
		if ( empty( $results ) ) {
			return $output;
		}

		foreach ( $results as $row ) {
			$output[] = new self( $row->post_id );
		}

		return $output;
	}

	/**
	 * @param int $order_id
	 */
	public static function anonymize( int $order_id = 0 ) {
		$user_id = get_post_meta( $order_id, '_customer_user', true );
		if ( empty( $user_id ) || get_user_by( 'id', $user_id ) === false ) {
			$user_id = false;
		}

		$data = [
			'billing_first_name'  => 'FIRST_NAME',
			'billing_last_name'   => 'LAST_NAME',
			'billing_company'     => 'COMPANY_NAME',
			'billing_address_1'   => 'ADDRESS_1',
			'billing_address_2'   => 'ADDRESS_2',
			'billing_postcode'    => 'ZIP_CODE',
			'billing_city'        => 'CITY',
			'billing_phone'       => 'PHONE_NUMBER',
			'billing_email'       => Anonymous::getEmailAddress( $order_id ),
			'shipping_first_name' => 'FIRST_NAME',
			'shipping_last_name'  => 'LAST_NAME',
			'shipping_company'    => 'COMPANY_NAME',
			'shipping_address_1'  => 'ADDRESS_1',
			'shipping_address_2'  => 'ADDRESS_2',
			'shipping_postcode'   => 'ZIP_CODE',
			'shipping_city'       => 'CITY',
		];
		foreach ( $data as $meta_key => $meta_value ) {
			update_post_meta( $order_id, '_' . $meta_key, $meta_value );
			if ( empty( $user_id ) ) {
				continue;
			}

			update_user_meta( $user_id, $meta_key, $meta_value );
		}
	}

	/**
	 * Gets billing full name (combines first & last name)
	 * @return string
	 */
	public function getBillingFullName(): string {
		return implode( ' ', [ $this->getBillingFirstName(), $this->getBillingLastName() ] );
	}

	/**
	 * Gets billing full address (combines both address lines)
	 * @return string
	 */
	public function getBillingFullAddress(): string {
		$address = [ $this->getBillingAddressOne() ];
		$append  = $this->getBillingAddressTwo();
		if ( ! empty( $append ) ) {
			$address[] = $append;
		}

		return implode( ',<br />', $address );
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return (int) $this->getOrderId();
	}

	/**
	 * @param int $id
	 */
	public function setId( int $id = 0 ) {
		$this->setOrderId( $id );
	}

	/**
	 * @return int
	 */
	public function getOrderId(): int {
		return (int) $this->orderId;
	}

	/**
	 * @param int $order_id
	 */
	public function setOrderId( int $order_id = 0 ) {
		$this->orderId = $order_id;
	}

	/**
	 * @return string
	 */
	public function getBillingEmailAddress(): string {
		return $this->billingEmailAddress;
	}

	/**
	 * @param string $billing_email_address
	 */
	public function setBillingEmailAddress( string $billing_email_address = '' ) {
		$this->billingEmailAddress = $billing_email_address;
	}

	/**
	 * @return string
	 */
	public function getBillingFirstName(): string {
		return $this->billingFirstName;
	}

	/**
	 * @param string $billing_first_name
	 */
	public function setBillingFirstName( string $billing_first_name = '' ) {
		$this->billingFirstName = $billing_first_name;
	}

	/**
	 * @return string
	 */
	public function getBillingLastName(): string {
		return $this->billingLastName;
	}

	/**
	 * @param string $billing_last_name
	 */
	public function setBillingLastName( string $billing_last_name = '' ) {
		$this->billingLastName = $billing_last_name;
	}

	/**
	 * @return string
	 */
	public function getBillingCompany(): string {
		return $this->billingCompany;
	}

	/**
	 * @param string $billing_company
	 */
	public function setBillingCompany( string $billing_company = '' ) {
		$this->billingCompany = $billing_company;
	}

	/**
	 * @return string
	 */
	public function getBillingAddressOne(): string {
		return $this->billingAddressOne;
	}

	/**
	 * @param string $billing_address_one
	 */
	public function setBillingAddressOne( string $billing_address_one = '' ) {
		$this->billingAddressOne = $billing_address_one;
	}

	/**
	 * @return string
	 */
	public function getBillingAddressTwo(): string {
		return $this->billingAddressTwo;
	}

	/**
	 * @param string $billing_address_two
	 */
	public function setBillingAddressTwo( string $billing_address_two = '' ) {
		$this->billingAddressTwo = $billing_address_two;
	}

	/**
	 * @return string
	 */
	public function getBillingCity(): string {
		return $this->billingCity;
	}

	/**
	 * @param string $billing_city
	 */
	public function setBillingCity( string $billing_city = '' ) {
		$this->billingCity = $billing_city;
	}

	/**
	 * @return string
	 */
	public function getBillingState(): string {
		return $this->billingState;
	}

	/**
	 * @param string $billing_state
	 */
	public function setBillingState( string $billing_state = '' ) {
		$this->billingState = $billing_state;
	}

	/**
	 * @return string
	 */
	public function getBillingPostCode(): string {
		return $this->billingPostCode;
	}

	/**
	 * @param string $billing_post_code
	 */
	public function setBillingPostCode( string $billing_post_code = '' ) {
		$this->billingPostCode = $billing_post_code;
	}

	/**
	 * @return string
	 */
	public function getBillingCountry(): string {
		return $this->billingCountry;
	}

	/**
	 * @param string $billing_country
	 */
	public function setBillingCountry( string $billing_country = '' ) {
		$this->billingCountry = $billing_country;
	}

	/**
	 * @return string
	 */
	public function getBillingPhone(): string {
		return $this->billingPhone;
	}

	/**
	 * @param string $billing_phone
	 */
	public function setBillingPhone( string $billing_phone = '' ) {
		$this->billingPhone = $billing_phone;
	}

	/**
	 * @return string
	 */
	public function getShippingFirstName(): string {
		return $this->shippingFirstName;
	}

	/**
	 * @param string $shipping_first_name
	 */
	public function setShippingFirstName( string $shipping_first_name = '' ) {
		$this->shippingFirstName = $shipping_first_name;
	}

	/**
	 * @return string
	 */
	public function getShippingLastName(): string {
		return $this->shippingLastName;
	}

	/**
	 * @param string $shipping_last_name
	 */
	public function setShippingLastName( string $shipping_last_name = '' ) {
		$this->shippingLastName = $shipping_last_name;
	}

	/**
	 * @return string
	 */
	public function getShippingCompany(): string {
		return $this->shippingCompany;
	}

	/**
	 * @param string $shipping_company
	 */
	public function setShippingCompany( string $shipping_company = '' ) {
		$this->shippingCompany = $shipping_company;
	}

	/**
	 * @return string
	 */
	public function getShippingAddressOne(): string {
		return $this->shippingAddressOne;
	}

	/**
	 * @param string $shipping_address_one
	 */
	public function setShippingAddressOne( string $shipping_address_one = '' ) {
		$this->shippingAddressOne = $shipping_address_one;
	}

	/**
	 * @return string
	 */
	public function getShippingAddressTwo(): string {
		return $this->shippingAddressTwo;
	}

	/**
	 * @param string $shipping_address_two
	 */
	public function setShippingAddressTwo( string $shipping_address_two = '' ) {
		$this->shippingAddressTwo = $shipping_address_two;
	}

	/**
	 * @return string
	 */
	public function getShippingCity(): string {
		return $this->shippingCity;
	}

	/**
	 * @param string $shipping_city
	 */
	public function setShippingCity( string $shipping_city = '' ) {
		$this->shippingCity = $shipping_city;
	}

	/**
	 * @return string
	 */
	public function getShippingState(): string {
		return $this->shippingState;
	}

	/**
	 * @param string $shipping_state
	 */
	public function setShippingState( string $shipping_state = '' ) {
		$this->shippingState = $shipping_state;
	}

	/**
	 * @return string
	 */
	public function getShippingPostCode(): string {
		return $this->shippingPostCode;
	}

	/**
	 * @param string $shipping_post_code
	 */
	public function setShippingPostCode( string $shipping_post_code = '' ) {
		$this->shippingPostCode = $shipping_post_code;
	}

	/**
	 * @return string
	 */
	public function getShippingCountry(): string {
		return $this->shippingCountry;
	}

	/**
	 * @param string $shipping_country
	 */
	public function setShippingCountry( string $shipping_country = '' ) {
		$this->shippingCountry = $shipping_country;
	}

}
