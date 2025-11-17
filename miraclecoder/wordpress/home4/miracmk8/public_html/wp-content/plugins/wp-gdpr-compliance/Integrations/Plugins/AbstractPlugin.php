<?php

namespace WPGDPRC\Integrations\Plugins;

use WPGDPRC\Integrations\AbstractIntegration;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\PrivacyPolicy;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class AbstractPlugin
 * @package WPGDPRC\Integrations\Plugins
 */
abstract class AbstractPlugin extends AbstractIntegration {

	public function __construct() {
		if ( ! $this->isValid() ) {
			return;
		}

		if ( empty( $this->getSelectForm() ) ) {
			AbstractIntegration::__construct();

			return;
		}

		add_action( 'init', [ $this, 'setupValues' ], 1 );

		$this->initHooks();
	}

	/**
	 * Set all values which are needed from the database.
	 */
	public function setupValues() {
		$this->setValues( Settings::getForms( $this->getID(), [] ), Integration::KEY_FORMS );
		foreach ( Integration::defaultTexts() as $name => $default ) {
			if ( empty( $this->getValues( $name ) ) ) {
				$this->setValues( [], $name );
			}

			$stored = Settings::get( $this->getID() . '_' . $name, Settings::INTEGRATIONS_GROUP );
			foreach ( $this->getList() as $id => $form ) {
				$values        = $this->getValues( $name );
				$values[ $id ] = ! isset( $stored[ $id ] ) || $stored[ $id ] === false ? $default : $stored[ $id ];
				$this->setValues( $values, $name );
			}
		}
	}

	/**
	 * @return string
	 */
	abstract public function getFile(): string;

	/**
	 * @return string
	 */
	abstract public function getVersion(): string;

	/**
	 * Adds actions to fire when an option for a form has been updated
	 */
	public function initUpdateOption() {
		$prefix = implode( '_', [ 'update_option', Plugin::PREFIX, Settings::INTEGRATIONS_GROUP, $this->getID() ] );

		add_action( $prefix, [ $this, 'updateFormField' ] );
		add_action( $prefix . '_' . Integration::KEY_FORMS, [ $this, 'updateFormField' ] );
		add_action( $prefix . '_' . Integration::KEY_TEXT, [ $this, 'updateFormField' ] );
		add_action( $prefix . '_' . Integration::KEY_ERROR, [ $this, 'updateFormField' ] );
	}

	/**
	 * Inserts consent checkbox at the end of the (enabled) forms
	 */
	public function updateFormField() {
		// Do nothing for now, allow for override in form related plugins
	}

	/**
	 * Checks if the plugin is installed
	 * @return bool
	 */
	public function isInstalled(): bool {
		return AdminHelper::pluginInstalled( $this->getFile() );
	}

	/**
	 * Checks if the plugin is activated
	 * @return bool
	 */
	public function isActivated(): bool {
		return AdminHelper::pluginActivated( $this->getFile() );
	}

	/**
	 * Checks if the plugin version is supported
	 * @return bool
	 */
	public function isSupported(): bool {
		return AdminHelper::pluginSupported( $this->getFile(), $this->getVersion() );
	}

	/**
	 * Checks if integration is valid (installed, activated & correct version)
	 * @return bool
	 */
	public function isValid(): bool {
		if ( ! $this->isInstalled() ) {
			return false;
		}
		if ( ! $this->isActivated() ) {
			return false;
		}
		if ( ! $this->isSupported() ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the text to display when the plugin is not installed
	 * @return string
	 */
	public function notInstalledText(): string {
		/* translators: %1s: plugin name */
		return sprintf( _x( 'Currently the %1s plugin is not installed.', 'admin', 'wp-gdpr-compliance' ), $this->getName() );
	}

	/**
	 * Returns the text to display when the plugin is not activated
	 * @return string
	 */
	public function notActivatedText(): string {
		$url  = add_query_arg( [ 'plugin_status' => 'inactive' ], admin_url( 'plugins.php' ) );
		$link = Elements::getLink( $url, _x( 'not activated', 'admin', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ] );

		/* translators: %1$1s: plugin name %2$2s: activation link element with text "Not activated" */
		return sprintf( _x( 'Currently the %1$1s plugin is installed, but %2$2s.', 'admin', 'wp-gdpr-compliance' ), $this->getName(), $link );
	}

	/**
	 * Returns the text to display when the plugin version is not supported
	 * @return string
	 */
	public function notSupportedText(): string {
		$url  = add_query_arg( [ 'plugin_status' => 'upgrade' ], admin_url( 'plugins.php' ) );
		$link = Elements::getLink( $url, _x( 'this version is not supported', 'admin', 'wp-gdpr-compliance' ), [ 'target' => '_blank' ] );

		/* translators: %1$1s: plugin name %2$2s: link %3$3s: Version */
		return sprintf( _x( 'Currently the %1$1s plugin is installed, but %2$2s. The plugin will be supported from version %3$3s and up.', 'admin', 'wp-gdpr-compliance' ), $this->getName(), $link, $this->getVersion() );
	}

	/**
	 * Returns the text to display when no forms are available (yet)
	 * @return string
	 */
	public function noFormsText(): string {
		/* translators: %1$1s: plugin name */
		return sprintf( _x( 'No active %1s forms found. Please create a form and return to set the checkbox message(s).', 'admin', 'wp-gdpr-compliance' ), $this->getName() );
	}

	/**
	 * Lists texts for specific form
	 *
	 * @param int|string $form_id
	 *
	 * @return array
	 */
	public function getFormTextsByForm( $form_id = 0 ): array {
		$list = Integration::defaultTexts();
		if ( empty( $form_id ) ) {
			return $list;
		}

		foreach ( $list as $name => $value ) {
			$list[ $name ] = $this->getFormTextByForm( $name, (int) $form_id );
		}

		return $list;
	}

	/**
	 * Gets specific text for specific form
	 *
	 * @param string $type
	 * @param int|string $form_id
	 *
	 * @return string
	 */
	public function getFormTextByForm( string $type = '', $form_id = 0 ): string {
		if ( empty( $form_id ) ) {
			return '';
		}
		if ( empty( $type ) ) {
			return '';
		}

		$setting = Settings::get( $this->getID() . '_' . $type, Settings::INTEGRATIONS_GROUP );

		return $setting[ $form_id ] ?? Integration::getDefaultText( $type );
	}

	/**
	 * Gets checkbox text for specific form
	 *
	 * @param int|string $form_id
	 *
	 * @return string
	 */
	public function getCheckboxTextByForm( $form_id = 0 ): string {
		if ( empty( $form_id ) ) {
			return '';
		}

		return PrivacyPolicy::replaceLink( $this->getFormTextByForm( Integration::KEY_TEXT, (int) $form_id ) );
	}

	/**
	 * Gets error text for specific form
	 *
	 * @param int|string $form_id
	 *
	 * @return string
	 */
	public function getErrorTextByForm( $form_id = 0 ): string {
		if ( empty( $form_id ) ) {
			return '';
		}

		return PrivacyPolicy::replaceLink( $this->getFormTextByForm( Integration::KEY_ERROR, (int) $form_id ) );
	}

	/**
	 * Gets required text for specific form
	 *
	 * @param int|string $form_id
	 *
	 * @return string
	 */
	public function getRequiredTextByForm( $form_id = 0 ): string {
		if ( empty( $form_id ) ) {
			return '';
		}

		return $this->getFormTextByForm( Integration::KEY_REQUIRED, (int) $form_id );
	}

	/**
	 * Lists enabled forms
	 * @return array
	 */
	public function getEnabledForms(): array {
		return $this->isEnabled() ? Settings::getForms( $this->getID(), [] ) : [];
	}

	/**
	 * Checks if specific form is enabled
	 *
	 * @param int $form_id
	 *
	 * @return bool
	 */
	public function isEnabledForm( int $form_id = 0 ): bool {
		if ( empty( $form_id ) ) {
			return false;
		}
		if ( ! $this->isEnabled() ) {
			return false;
		}

		$list = $this->getEnabledForms();
		if ( empty( $list ) ) {
			return false;
		}

		return in_array( (int) $form_id, $list, true );
	}

}
