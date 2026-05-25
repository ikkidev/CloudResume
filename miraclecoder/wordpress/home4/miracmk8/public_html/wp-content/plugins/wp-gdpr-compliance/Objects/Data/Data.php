<?php

namespace WPGDPRC\Objects\Data;

use WPGDPRC\Integrations\Plugins\ContactForm;
use WPGDPRC\Integrations\Plugins\GravityForms;
use WPGDPRC\Integrations\Plugins\WooCommerce;
use WPGDPRC\Integrations\WPComments;
use WPGDPRC\Integrations\WPRegistration;
use WPGDPRC\Objects\RequestDelete;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;

/**
 * Class Data
 * @package WPGDPRC\Objects\Data
 */
class Data extends AbstractData {

	const COLUMN_ACTION       = 'action';
	const COLUMN_USER_NAME    = 'user_name';
	const COLUMN_DISPLAY_NAME = 'display_name';
	const COLUMN_EMAIL        = 'email_address';
	const COLUMN_WEBSITE      = 'website';
	const COLUMN_CREATED      = 'created';
	const COLUMN_CONTENT      = 'content';
	const COLUMN_USER_IP      = 'ip_address';
	const COLUMN_ORDER_ID     = 'order_id';
	const COLUMN_ADDRESS      = 'address';
	const COLUMN_ZIPCODE      = 'zipcode';
	const COLUMN_CITY         = 'city';
	const COLUMN_FORM         = 'form';

	const NO_ACTION = '&nbsp;';

	/** @var string */
	protected $emailAddress = '';

	/**
	 * Data constructor
	 *
	 * @param string $email
	 */
	public function __construct( string $email = '' ) {
		if ( empty( $email ) ) {
			wp_die( wp_kses_post( Template::get( 'Front/Elements/error', [ 'message' => __( 'Email Address is required.', 'wp-gdpr-compliance' ) ] ) ) );
		}
		$this->setEmailAddress( $email );
	}

	/**
	 * @return array
	 */
	public static function getPossibleDataTypes(): array {
		return [ User::getDataSlug(), Comment::getDataSlug(), WooCommerceOrder::getDataSlug(), GravityFormsEntry::getDataSlug() ];
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	private static function getOutputColumns( string $type = '' ): array {
		$output = [];
		switch ( $type ) {
			case User::getDataSlug():
				$output = [
					static::COLUMN_USER_NAME    => __( 'Username', 'wp-gdpr-compliance' ),
					static::COLUMN_DISPLAY_NAME => __( 'Display Name', 'wp-gdpr-compliance' ),
					static::COLUMN_EMAIL        => __( 'Email Address', 'wp-gdpr-compliance' ),
					static::COLUMN_WEBSITE      => __( 'Website', 'wp-gdpr-compliance' ),
					static::COLUMN_CREATED      => __( 'Registered on', 'wp-gdpr-compliance' ),
				];

				// No need for naming website if it's not a multi-site
				if ( ! is_multisite() ) {
					unset( $output[ static::COLUMN_WEBSITE ] );
				}
				break;

			case Comment::getDataSlug():
				$output = [
					static::COLUMN_DISPLAY_NAME => __( 'Author', 'wp-gdpr-compliance' ),
					static::COLUMN_CONTENT      => __( 'Content', 'wp-gdpr-compliance' ),
					static::COLUMN_EMAIL        => __( 'Email Address', 'wp-gdpr-compliance' ),
					static::COLUMN_USER_IP      => __( 'IP Address', 'wp-gdpr-compliance' ),
					static::COLUMN_CREATED      => __( 'Date', 'wp-gdpr-compliance' ),
				];
				break;

			case WooCommerceOrder::getDataSlug():
				$output = [
					static::COLUMN_ORDER_ID     => __( 'Order', 'wp-gdpr-compliance' ),
					static::COLUMN_EMAIL        => __( 'Email Address', 'wp-gdpr-compliance' ),
					static::COLUMN_DISPLAY_NAME => __( 'Name', 'wp-gdpr-compliance' ),
					static::COLUMN_ADDRESS      => __( 'Address', 'wp-gdpr-compliance' ),
					static::COLUMN_ZIPCODE      => __( 'Postcode / ZIP', 'wp-gdpr-compliance' ),
					static::COLUMN_CITY         => __( 'City', 'wp-gdpr-compliance' ),
				];
				break;

			case GravityFormsEntry::getDataSlug():
				$output = [
					static::COLUMN_CREATED => __( 'Date', 'wp-gdpr-compliance' ),
					static::COLUMN_FORM    => __( 'Form', 'wp-gdpr-compliance' ),
					static::COLUMN_EMAIL   => __( 'Email Address', 'wp-gdpr-compliance' ),
					static::COLUMN_USER_IP => __( 'IP Address', 'wp-gdpr-compliance' ),
				];
				break;
			case ContactForm::getInstance()->getId():
				return $output;
		}

		$output[ static::COLUMN_ACTION ] = function() { ?><input type="checkbox" class="wpgdprc-select-all" /><?php };

		return $output;
	}

	/**
	 * @param array $data
	 * @param string $type
	 * @param int $request_id
	 *
	 * @return array
	 */
	public static function getOutputData( array $data = [], string $type = '', int $request_id = 0 ): array {
		$output  = [];
		$columns = array_keys( self::getOutputColumns( $type ) );
		if ( empty( $columns ) ) {
			return $output;
		}

		switch ( $type ) {
			case User::getDataSlug():
				/** @var User $object */
				foreach ( $data as $object ) {
					$request = RequestDelete::getByTypeAndDataIdAndAccessId( $type, $object->getId(), $request_id );

					$output[ $object->getId() ] = Helper::fillArray(
						$columns,
						[
                            static::COLUMN_USER_NAME    => $object->getUsername(),
                            static::COLUMN_DISPLAY_NAME => $object->getDisplayName(),
                            static::COLUMN_EMAIL        => $object->getEmailAddress(),
                            static::COLUMN_WEBSITE      => $object->getWebsite(),
                            static::COLUMN_CREATED      => $object->getRegisteredDate(),
                            static::COLUMN_ACTION       => !empty( $request ) ?  static::NO_ACTION  : function () use ($object) {static::renderColumnAction($object->getId() );},
						]
					);
				}
				break;

			case Comment::getDataSlug():
				/** @var Comment $object */
				foreach ( $data as $object ) {
					$request = RequestDelete::getByTypeAndDataIdAndAccessId( $type, $object->getId(), $request_id );

					$output[ $object->getId() ] = Helper::fillArray(
						$columns,
						[
                            static::COLUMN_DISPLAY_NAME => $object->getName(),
                            static::COLUMN_CONTENT      => Helper::shortenStringByWords( wp_strip_all_tags( $object->getContent(), true ), 5 ),
                            static::COLUMN_EMAIL        => $object->getEmailAddress(),
                            static::COLUMN_USER_IP      => $object->getIpAddress(),
                            static::COLUMN_CREATED      => $object->getDate(),
                            static::COLUMN_ACTION       => !empty( $request ) ?  static::NO_ACTION  : function () use ($object) {static::renderColumnAction($object->getId() );},
						]
					);
				}
				break;

			case WooCommerceOrder::getDataSlug():
				/** @var WooCommerceOrder $object */
				foreach ( $data as $object ) {
					$request = RequestDelete::getByTypeAndDataIdAndAccessId( $type, $object->getOrderId(), $request_id );

					$output[ $object->getOrderId() ] = Helper::fillArray(
						$columns,
						[
                            static::COLUMN_ORDER_ID     => sprintf( '#%d', $object->getOrderId() ),
                            static::COLUMN_EMAIL        => $object->getBillingEmailAddress(),
                            static::COLUMN_DISPLAY_NAME => $object->getBillingFullName(),
                            static::COLUMN_ADDRESS      => $object->getBillingFullAddress(),
                            static::COLUMN_ZIPCODE      => $object->getBillingPostCode(),
                            static::COLUMN_CITY         => $object->getBillingCity(),
                            static::COLUMN_ACTION       => !empty( $request ) ?  static::NO_ACTION  : function () use ($object) {static::renderColumnAction($object->getId() );},
						]
					);
				}
				break;

			case GravityFormsEntry::getDataSlug():
				/** @var GravityFormsEntry $object */
				foreach ( $data as $object ) {
					$request                    = RequestDelete::getByTypeAndDataIdAndAccessId( $type, $object->getId(), $request_id );
					$output[ $object->getId() ] = [
                        static::COLUMN_CREATED => $object->getDate(),
                        static::COLUMN_FORM    => $object->getFormName(),
                        static::COLUMN_EMAIL   => $object->getEmail(),
                        static::COLUMN_USER_IP => $object->getIp(),
                        static::COLUMN_ACTION  => !empty( $request ) ?  static::NO_ACTION  : function () use ($object) {static::renderColumnAction($object->getId() );},
					];
				}
				break;
			case ContactForm::getInstance()->getId():
				return $output;
		}

		return $output;
	}

	/**
	 * @param string $replace
	 */
	public static function renderColumnAction( string $replace = '' ) {
        ?>
		    <input type="checkbox" name="<?php echo esc_attr(Plugin::PREFIX) ?>_remove[]" class="wpgdprc-checkbox" value="<?php echo esc_attr($replace) ?>" tabindex="1" />
        <?php
	}

	/**
	 * @param array $data
	 * @param string $type
	 * @param int $request_id
	 */
	public static function renderOutput( array $data = [], string $type = '', int $request_id = 0 ) {
		if ( empty( $data ) ) {
			return;
		}

		$plural = count( $data );

        Template::render(
			'Front/Form/AccessRequest/Result/main',
			[
				'type'    => wp_json_encode( [ 'type' => $type ] ),
				'columns' => static::getOutputColumns( $type ),
				'data'    => static::getOutputData( $data, $type, $request_id ),
				'submit'  => static::getButtonText( $type, $plural ),
			]
		);
	}

	/**
	 * @param string $type
	 * @param int $plural
	 *
	 * @return string
	 */
	public static function getButtonText( string $type = '', int $plural = 1 ): string {
		$integrations = Integration::getList();
		if ( isset( $integrations[ $type ] ) ) {
			return $integrations[ $type ]->getButtonText( $plural );
		}

		return __( 'Anonymize selected data', 'wp-gdpr-compliance' );
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public static function getUsers( string $email = '' ): array {
		if ( empty( $email ) ) {
			return [];
		}

		return User::getByEmail( $email );
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public static function getComments( string $email = '' ): array {
		if ( empty( $email ) ) {
			return [];
		}

		return Comment::getByEmail( $email );
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public static function getWooCommerceOrders( string $email = '' ): array {
		if ( empty( $email ) ) {
			return [];
		}

		return WooCommerceOrder::getByEmail( $email );
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

}
