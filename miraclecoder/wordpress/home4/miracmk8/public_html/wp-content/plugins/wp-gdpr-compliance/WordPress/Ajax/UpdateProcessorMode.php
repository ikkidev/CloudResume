<?php

namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\Banner;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Plugin;

/**
 * Class UpdateProcessorMode
 * @package WPGDPRC\WordPress\Ajax
 */
class UpdateProcessorMode extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_update_plugin_mode';
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
		return [ 'value' ];
	}

    public static function hasData()
    {
        return true;
    }

	/**
	 * Builds the AJAX response
	 * (security handling + data validation -if any- is done in the abstract class)
	 * @param array $data
	 */
	public static function buildResponse( $data = [] ) {
		$updated = [];
		if ( ! empty( $data['value'] ) ) {
			// enabling data processors should be done one by one
			$response = [
				'success' => true,
				'data'    => $data,
			];
			static::returnResponse( $response );
		}

		// disable all active data processors
		$list = DataProcessor::getListByType( DataProcessor::KEY_ACTIVE );
		if ( empty( $list ) ) {
			static::returnSuccess( _x( 'No active data processors.', 'admin', 'wp-gdpr-compliance' ) );
		}

		foreach ( $list as $object ) {
			$object->setActive( 0 );
			$updated[] = $object->save();
		}

		$tile = [
			'text'   => Banner::getStatusText(),
			'footer' => '<p class="wpgdprc-tile__message">' . _x( 'To enable the consent bar, activate one (or more) data processor(s).', 'admin', 'wp-gdpr-compliance' ) . '</p>',
		];

		$response = [
			'success' => true,
			'data'    => $data,
			'tile'    => $tile,
			'updated' => $updated,
			'header'  => Template::get( 'Admin/header' ),
		];
		static::returnResponse( $response );
	}

}
