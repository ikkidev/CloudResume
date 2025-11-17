<?php

namespace WPGDPRC\Integrations;

use WPGDPRC\Utils\HelperSettings;
use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\PrivacyPolicy;
use WPGDPRC\Utils\Time;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class AbstractIntegration
 * @package WPGDPRC\Integrations
 */
abstract class AbstractIntegration {

	private static $instances = [];

	private $values = [];

	public static function getInstance(): AbstractIntegration {
		$class = get_called_class();
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new $class();
		}

		return self::$instances[ $class ];
	}

	protected function __construct() {
		foreach ( Integration::defaultTexts() as $name => $default ) {
			$value = Settings::get( $this->getID() . '_' . $name, Settings::INTEGRATIONS_GROUP );
			if ( $value === false ) {
				$value = $default;
			}
			$this->setValues( $value, $name );
		}

		$this->initHooks();
	}

	/**
	 * @param $values
	 * @param null $value
	 *
	 */
	public function setValues( $value, $name = null ) {
		if ( is_null( $name ) ) {
			$this->values = $value;
		}

		$this->values[ $name ] = $value;
	}

	/**
	 * @param null $name
	 *
	 * @return array
	 */
	public function getValues( $name = null ): array {
		if ( is_null( $name ) || empty( $this->values[ $name ] ) ) {
			return $this->values;
		}

		return $this->values[ $name ];
	}

	/**
	 * @return bool
	 */
	public function getSelectForm(): bool {
		return false;
	}

	/**
	 * @return string
	 */
	abstract public function getID(): string;

	/**
	 * @return bool
	 */
	abstract public function hasData(): bool;

	/**
	 * @return bool
	 */
	abstract public function hasForms(): bool;

	/**
	 * @param string $email
	 *
	 * @return array
	 */
	abstract public function getData( string $email ): array;

	/**
	 * @param bool $front
	 * @param string $search
	 *
	 * @return array
	 */
	abstract public function getResults( bool $front, string $search ): array;


	abstract public function isValid():bool;


	/**
	 * @return bool
	 */
	public function isInstalled(): bool {
		return true;
	}

	/**
	 * @return bool
	 */
	public function isActivated(): bool {
		return true;
	}

	/**
	 * @return bool
	 */
	public function isSupported(): bool {
		return true;
	}

	/**
	 * Returns integration icon
	 * @return string
	 */
	public function getIcon(): string {
		return 'icon-wordpress.svg';
	}

	/**
	 * @return null
	 */
	abstract public function initHooks();

	/**
	 * Returns integration name
	 *
	 * @param bool $front
	 *
	 * @return string
	 */
	abstract public function getName( bool $front = false ): string;

	/**
	 * @return string
	 */
	abstract public function getDescription(): string;

	/**
	 * Returns text for Anonymize button on the front
	 *
	 * @param int $plural
	 *
	 * @return string
	 */
	abstract public function getButtonText( int $plural = 1 ): string;

	/**
	 * @return bool
	 */
	public function isEnabled(): bool {
		return Settings::isEnabled( $this->getID(), Settings::INTEGRATIONS_GROUP ) && $this->isValid();
	}

	/**
	 * Returns notice text for possible problems
	 * @return false|string
	 */
	public function getNotice() {
		return false;
	}

	/**
	 * Gets specific text
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public function getFormText( string $type = '' ): string {
		if ( empty( $type ) ) {
			return '';
		}

		$setting = Settings::get( $this->getID() . '_' . $type, Settings::INTEGRATIONS_GROUP );

		return $setting ?: Integration::getDefaultText( $type );
	}

	/**
	 * Gets checkbox text
	 * @return string
	 */
	public function getCheckboxText(): string {
		return PrivacyPolicy::replaceLink( $this->getFormText( Integration::KEY_TEXT ) );
	}

	/**
	 * Gets error text
	 * @return string
	 */
	public function getErrorText(): string {
		return PrivacyPolicy::replaceLink( $this->getFormText( Integration::KEY_ERROR ) );
	}

	/**
	 * Gets required text
	 * @return string
	 */
	public function getRequiredText(): string {
		return $this->getFormText( Integration::KEY_REQUIRED );
	}

	/**
	 * Gets text for (not) accepted consents
	 *
	 * @param string|bool $accepted
	 *
	 * @return string
	 */
	public function getAcceptedDate( bool $accepted = true ): string {
		if ( empty( $accepted ) ) {
			return __( 'Not accepted.', 'wp-gdpr-compliance' );
		}

		$date = Time::localDateFormat( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), time() );

		/* translators: %s: date */
		return sprintf( __( 'Accepted on %s.', 'wp-gdpr-compliance' ), $date );
	}

	/**
	 * Gets WPGDPRC field tag (for easier recognition & hooks)
	 * @return string
	 */
	public function getFieldTag(): string {
		return Plugin::PREFIX;
	}

	/**
	 * @return string
	 */
	public function getPrivacyLabel(): string {
		return __( 'Privacy Policy', 'wp-gdpr-compliance' );
	}

}
