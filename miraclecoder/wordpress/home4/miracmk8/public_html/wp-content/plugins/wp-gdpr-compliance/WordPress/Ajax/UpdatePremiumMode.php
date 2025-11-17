<?php
namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Utils\Template;
use WPGDPRC\Utils\UserTypeHelper;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class UpdatePremiumMode
 * @package WPGDPRC\WordPress\Ajax
 */
class UpdatePremiumMode extends AbstractAjax {

	/**
	 * Returns AJAX action name
	 * @return string
	 */
	protected static function getAction() {
		return Plugin::PREFIX . '_update_premium';
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
		return [ 'checked' ];
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
		$userType = $data['userType'] ?? null;
		UserTypeHelper::setUserType( $userType );

		$checked = filter_var( $data['checked'], FILTER_VALIDATE_BOOLEAN );
		if ( $userType === UserTypeHelper::business ) { // if usertype was set to business with this call update to premium.
			$checked = true;
		}

		if ( ! is_null( $userType ) ) {
			UserTypeHelper::setLastShown( time() );
		}

		$success = Settings::saveSetting( Settings::KEY_PREMIUM, $checked );

		$response = [
			'success'   => $success,
			'premium'   => Settings::isPremium(),
			'user_type' => UserTypeHelper::getUserType(),
			'header'    => Template::get( 'Admin/header' ),
		];
		static::returnResponse( $response );
	}

}
