<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\Wizard;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class UpdateWizardSettings
 * @package WPGDPRC\WordPress\Ajax
 */
class UpdateWizardSettings extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Wizard::AJAX_SAVE_SETTINGS;
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

	/**
	 * Determines if AJAX call is sending a data attribute
	 * @return bool
	 */
	public static function hasData() {
		return true;
	}

	/**
	 * Validates the data attribute
	 * @return array|void
	 */
	public static function validateData() {
		return $_POST;
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {

		$section = PageSettings::SECTION_PRIVACY;
		$fields  = PageSettings::getSectionFields( $section );
		$names   = array_keys( $fields );

		foreach ( $names as $name ) {
			$key = Settings::getKey( $name );
			if ( isset( $_REQUEST[ $key ] ) ) {
				$value = sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
				if ( ! is_array( $value ) ) {
					$value = trim( $value );
				}
				$value = wp_unslash( $value );
			}
			if ( ! isset( $value ) ) {
				$value = null;
			}
			Settings::saveSetting( $name, $value );
		}

		ob_start();
		Template::render( 'Admin/Pages/Wizard/Steps/Parts/policy-form' );
		$response = [
			'success' => true,
			'form'    => ob_get_clean(),
		];

		static::returnResponse( $response );
	}
}
