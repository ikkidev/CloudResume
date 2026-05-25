<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Integration;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class UpdateIntegration
 * @package WPGDPRC\WordPress\Ajax
 */
class UpdateIntegration extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_update_integration';
	}

	/**
	 * Determines if AJAX is public
	 * @return bool
	 */
	protected static function isPublic() {
		return false;
	}

	/**
	 * @return array
	 */
	public static function requiredData() {
		return [ 'value', 'name', 'type' ];
	}

    public static function hasData()
    {
        return true;
    }

    /**
	 * @inheritDoc
	 */
	public static function sanitizeData($data)
	{
		return [
			'value' => wp_kses(wp_unslash($data['value']), AdminHelper::getAllowedHTMLTags()),
			'name' => sanitize_text_field($data['name']),
			'type' => sanitize_text_field($data['type']),
			'integration' => sanitize_text_field($data['integration'])
		];
	}

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		$meta_key   = explode( '[', $data['name'] );
		$success    = false;
		$data_value = $data['value'];

		switch ( count( $meta_key ) ) {
			case 1:
				$meta_key = reset( $meta_key );
				$settings = $data_value;
				$success  = update_option( $meta_key, $settings );
				break;

			case 2:
				$item_id  = (int) str_replace( ']', '', $meta_key[1] );
				$meta_key = reset( $meta_key );
				$settings = get_option( $meta_key );

				if ( empty( $settings ) ) {
					$settings = [];
				}

				if ( $data['type'] === 'text' ) {
					$settings[ $item_id ] = $data_value;
					$success              = update_option( $meta_key, $settings );
					break;
				}

				if ( $data_value ) {
					$settings[] = $item_id;
					$settings   = array_unique( $settings );
					$success    = update_option( $meta_key, $settings );
					break;
				}

				$settings = array_diff( $settings, [ $item_id ] );
				$success  = update_option( $meta_key, $settings );
				break;

			case 3:
				if ( $data['type'] !== 'button' ) {
					break;
				}

				$key     = str_replace( ']', '', $meta_key[2] );
				$success = Settings::saveSetting( $key, $data_value ? '1' : '0', Settings::INTEGRATIONS_GROUP );
				break;
		}

		$integrations  = Integration::getList();
		$integrationId = $data['integration'] ?? '';
		if ( ! empty( $integration = $integrations[ $integrationId ] ) && is_subclass_of( $integration, '\WPGDPRC\Integrations\Plugins\AbstractPlugin' ) ) {
			$integration->updateFormField();
		}

		$response = [
			'success' => $success,
		];
		static::returnResponse( $response );
	}

}
