<?php

namespace WPGDPRC\Integrations;

use WP_Error;
use WPGDPRC\Objects\Data\Data;
use WPGDPRC\Objects\Log;
use WPGDPRC\Utils\Anonymous;
use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\IpAddress;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class WPRegistration
 * @package WPGDPRC\Integrations
 */
class WPRegistration extends AbstractIntegration {


	/**
	 * @return string
	 */
	public function getID(): string {
		return 'wp_registration';
	}

	public function getVersion(): string {
		return 'core WordPress';
	}

	/**
	 * Inits all integration actions & filters
	 */
	public function initHooks() {
		if ( ! get_option( 'users_can_register', false ) ) {
			return;
		}

		if ( ! is_multisite() ) {
			add_action( 'register_form', [ $this, 'addField' ], 10 );
			add_action( 'user_register', [ $this, 'logConsent' ], 10 );

			add_filter( 'registration_errors', [ $this, 'validateField' ], 10 );

			return;
		}

		add_action( 'signup_extra_fields', [ $this, 'addMultiSiteField' ], 10 );
		add_action( 'wpmu_new_user', [ $this, 'logConsent' ], 10 );

		add_filter( 'wpmu_validate_user_signup', [ $this, 'validateMultisiteField' ], 10 );
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
		return Data::getUsers( $email );
	}

	/**
	 * @return bool
	 */
	public function isValid(): bool {
		return true;
	}

	/**
	 * @param bool $front
	 * @param string $search
	 *
	 * @return array
	 */
	public function getResults( bool $front, string $search ): array {
		return [
			'icon'   => self::getIcon(),
			'title'  => self::getName( $front ),
			/* translators: %1s: search query */
			'notice' => sprintf( __( 'No users found with email address%1s.', 'wp-gdpr-compliance' ), $search ),
		];
	}

	/**
	 * Returns integration name
	 *
	 * @param bool $front
	 *
	 * @return string
	 */
	public function getName( bool $front = false ): string {
		if ( $front ) {
			return __( 'Users', 'wp-gdpr-compliance' );
		}

		return _x( 'WordPress Registration', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	public function getDescription(): string {
		return _x( 'When activated the GDPR checkbox will be added automatically, just above the register button.', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * Returns text for Anonymize button on the front
	 *
	 * @param int $plural
	 *
	 * @return string
	 */
	public function getButtonText( int $plural = 1 ): string {
		return _nx( 'Anonymize selected user', 'Anonymize selected user(s)', $plural, 'amount of users', 'wp-gdpr-compliance' );
	}

	/**
	 * Gets the GDPRC field tag for the registration form
	 * @return string
	 */
	public function getFieldTag(): string {
		return Plugin::PREFIX . '_consent';
	}

	/**
	 * Adds a GDPRC field to the site registration form
	 */
	public function addField() {
		$required = Template::get(
			'Front/Elements/required',
			[
				'message' => $this->getRequiredText(),
			]
		);

		Template::render(
			'Front/Registration/checkbox',
			[
				'name'  => $this->getFieldTag(),
				'label' => implode( ' ', [ $this->getCheckboxText(), $required ] ),
			]
		);
	}

	/**
	 * Validates the GDPRC field from the site registration form
	 *
	 * @param WP_Error $error
	 *
	 * @return WP_Error
	 */
	public function validateField( WP_Error $error ): WP_Error {
		if ( isset( $_POST[ $this->getFieldTag() ] ) ) {
			return $error;
		}

		/* translators: %s: error message */
		$error->add( 'gdpr_consent_error', sprintf( __( '<strong>ERROR</strong>: %1s', 'wp-gdpr-compliance' ), $this->getErrorText() ) );

		return $error;
	}

	/**
	 * Adds a GDPRC field to the Multi site registration form
	 *
	 * @param WP_Error $error
	 */
	public function addMultiSiteField( WP_Error $error ) {
		$this->addField();

		$message = $error->get_error_message( $this->getFieldTag() );
		if ( empty( $message ) ) {
			return;
		}

		Template::render(
			'Front/Registration/checkbox',
			[
				'text' => $message,
			]
		);
	}

	/**
	 * Validates the GDPRC field from the Multi site registration form
	 *
	 * @param array $result
	 *
	 * @return array
	 */
	public function validateMultisiteField( array $result = [] ): array {
		if ( ! empty( $_POST[ $this->getFieldTag() ] ) ) {
			$result[ $this->getFieldTag() ] = sanitize_text_field( wp_unslash( $_POST[ $this->getFieldTag() ] ) );

			return $result;
		}

		$result['errors']->add( $this->getFieldTag(), $this->getErrorText(), 'wp-gdpr-compliance' );
		$result[ $this->getFieldTag() ] = '';

		return $result;
	}

	/**
	 * Logs the users consent to the log DB table
	 *
	 * @param $user
	 */
	public function logConsent( $user ) {
		$data = [
			Log::KEY_PLUGIN_ID    => $this->getID(),
			Log::KEY_USER         => Anonymous::anonymizeEmail( sanitize_email( wp_unslash($_POST['user_email'] ?? '' ) ) ),
			Log::KEY_IP_ADDRESS   => Anonymous::anonymizeIP( IpAddress::getClientIp() ),
			Log::KEY_LOG          => __( 'User has given consent when registering', 'wp-gdpr-compliance' ),
			Log::KEY_CONSENT_TEXT => $this->getCheckboxText(),
		];
		if ( ! is_multisite() ) {
			Log::insertLog( $data );

			return;
		}

		$user                  = get_userdata( $user );
		$data[ Log::KEY_USER ] = Anonymous::anonymizeEmail( $user->user_email );
		Log::insertLog( $data );
	}
}
