<?php

namespace WPGDPRC\Integrations\Plugins;

use WPGDPRC\Objects\Data\Data;
use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Plugin;

/**
 * Class WooCommerce
 * @package WPGDPRC\Integrations\Plugins
 */
class WooCommerce extends AbstractPlugin {

	/**
	 * @return string
	 */
	public function getID(): string {
		return 'woocommerce';
	}

	/**
	 * @return string
	 */
	public function getVersion(): string {
		return '2.5.0';
	}

	/**
	 * @return string
	 */
	public function getFile(): string {
		return 'woocommerce/woocommerce.php';
	}

	/**
	 * Inits all integration actions & filters
	 */
	public function initHooks() {
		if ( ! $this->isEnabled() ) {
			return;
		}

		add_action( 'woocommerce_checkout_process', [ $this, 'checkPostCheckoutForm' ] );
		add_action( 'woocommerce_register_post', [ $this, 'checkPostRegisterForm' ], 10, 3 );
		add_action( 'woocommerce_review_order_before_submit', [ $this, 'addField' ], 999 );
		add_action( 'woocommerce_register_form', [ $this, 'addField' ], 999 );
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'addAcceptedDateToOrderMeta' ] );
		add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'acceptedDateInOrderData' ] );
		add_action( 'manage_shop_order_posts_custom_column', [ $this, 'acceptedDateInOrderOverview' ], 10, 2 );
		add_filter( 'manage_edit-shop_order_columns', [ $this, 'acceptedDateColumnInOrderOverview' ] );
	}

	/**
	 * @return bool
	 */
	public function hasData(): bool {
		return true;
	}

	/**
	 * @return bool
	 */
	public function hasForms(): bool {
		return false;
	}

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	public function getData( string $email ): array {
		return Data::getWooCommerceOrders( $email );
	}

	/**
	 * @param $front
	 * @param $search
	 *
	 * @return array
	 */
	public function getResults( bool $front, string $search ): array {
		return [
			'icon'   => self::getIcon(),
			'title'  => self::getName( $front ),
			/* translators: %1s: search query */
			'notice' => sprintf( __( 'No orders found with email address%1s.', 'wp-gdpr-compliance' ), $search ),
		];
	}

	/**
	 * @return string
	 */
	public function getIcon(): string {
		return 'icon-woocommerce.svg';
	}

	/**
	 * @param bool $front
	 *
	 * @return string
	 */
	public function getName( bool $front = false ): string {
		if ( $front ) {
			return __( 'Orders', 'wp-gdpr-compliance' );
		}

		return _x( 'WooCommerce', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		if ( ! $this->isInstalled() ) {
			return $this->notInstalledText();
		}
		if ( ! $this->isActivated() ) {
			return $this->notActivatedText();
		}
		if ( ! $this->isSupported() ) {
			return $this->notSupportedText();
		}

		return _x( 'When activated the GDPR checkbox will be added at the end of the WooCommerce checkout form.', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * Returns text for Anonymize button on the front
	 *
	 * @param int $plural
	 *
	 * @return string
	 */
	public function getButtonText( int $plural = 1 ): string {
		return _nx( 'Anonymize selected shop order', 'Anonymize selected shop order(s)', $plural, 'amount of shop orders', 'wp-gdpr-compliance' );
	}


	/**
	 * Check if WP GDPR checkbox is checked
	 */
	public function checkPostCheckoutForm() {
		if ( isset( $_POST[ $this->getFieldTag() ] ) ) {
			return;
		}
		wc_add_notice( $this->getErrorText( $this->getID() ), 'error' );
	}

	/**
	 * Check if WP GDPR checkbox is checked on register
	 *
	 * @param string $user
	 * @param string $email
	 * @param \WP_Error|null $errors
	 */
	public function checkPostRegisterForm( string $user = '', string $email = '', \WP_Error $errors = null ) {
		if ( isset( $_POST[ $this->getFieldTag() ] ) ) {
			return;
		}
		$errors->add( Plugin::PREFIX . '_error', $this->getErrorText( $this->getID() ) );
	}

	/**
	 * Add WP GDPR field before submit button
	 */
	public function addField() {
		$required = Template::get(
			'Front/Elements/required',
			[
				'message' => $this->getRequiredText( (int) $this->getID() ),
			]
		);
		$args     = [
			'type'     => 'checkbox',
			'class'    => [ Plugin::PREFIX . '-checkbox' ],
			'label'    => implode( '', [ $this->getCheckboxText( $this->getID() ), $required ] ),
			'required' => true,
		];
		woocommerce_form_field( $this->getFieldTag(), apply_filters( Plugin::PREFIX . '_woocommerce_field_args', $args ) );
	}

	/**
	 * @param int $order_id
	 */
	public function addAcceptedDateToOrderMeta( int $order_id = 0 ) {
		if ( ! isset( $_POST[ $this->getFieldTag() ] ) ) {
			return;
		}
		if ( empty( $order_id ) ) {
			return;
		}

		update_post_meta( $order_id, '_' . $this->getFieldTag(), time() );
	}

	/**
	 * @param \WC_Order $order
	 */
	public function acceptedDateInOrderData( \WC_Order $order ) {
		$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : ( property_exists( $order, 'ID' ) ? $order->ID : 0 );
		$label    = __( 'GDPR accepted on:', 'wp-gdpr-compliance' );

		$date  = get_post_meta( $order_id, '_' . $this->getFieldTag(), true );
        $value = Time::localDateFormat( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $date );
		$text  = sprintf( '<p class="form-field form-field-wide wpgdprc-accepted-date"><strong>%1s</strong><br />%2s</p>', $label, $value );

        echo wp_kses( apply_filters( Plugin::PREFIX . '_woocommerce_accepted_date_in_order_data', $text, $label, $value, $order ), \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags() );
	}

	/**
	 * @param string $column
	 * @param int $order_id
	 *
	 * @return string
	 */
	public function acceptedDateInOrderOverview( string $column = '', int $order_id = 0 ): string {
		if ( $column !== $this->getFieldTag() . '-privacy' ) {
			return $column;
		}

		$date  = get_post_meta( $order_id, '_' . $this->getFieldTag(), true );
        $value = Time::localDateFormat( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $date );

		echo esc_html( apply_filters( Plugin::PREFIX . '_accepted_date_in_woocommerce_order_overview', $value, $order_id ) );

		return $column;
	}

	/**
	 * @param array $columns
	 *
	 * @return array
	 */
	public function acceptedDateColumnInOrderOverview( array $columns = [] ): array {
		$text = apply_filters( Plugin::PREFIX . '_accepted_date_column_in_woocommerce_order_overview', $this->getPrivacyLabel() );

		$columns[ $this->getFieldTag() . '-privacy' ] = $text;

		return $columns;
	}
}
