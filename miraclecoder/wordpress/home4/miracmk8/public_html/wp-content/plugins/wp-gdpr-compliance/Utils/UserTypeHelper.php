<?php

namespace WPGDPRC\Utils;

use DateTime;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

abstract class UserTypeHelper {

	const private  = 'private';
	const business = 'business';

	const user_type_option       = Plugin::PREFIX . '_user_type';
	const last_shown_option      = Plugin::PREFIX . '_last_shown';
    const form_last_shown_option = Plugin::PREFIX . '_form_last_shown';
    const form_submitted_option  = Plugin::PREFIX . '_form_submitted_option';

	/**
	 * Validate user type value.
	 *
	 * @param $value
	 * @return bool
	 */
	public static function isValid( $value ): bool {
		return in_array( $value, [ self::private, self::business ], true );
	}

	/**
	 * Get the current user type.
	 * @return string
	 */
	public static function getUserType(): string {
		return get_option( self::user_type_option );
	}

	/**
	 * Validate new user type.
	 *
	 * @param $value
	 * @return bool
	 */
	public static function setUserType( $value ): bool {
		if ( ! self::isValid( $value ) ) {
			return false;
		}

		update_option( self::user_type_option, $value );
		return true;
	}

	/**
	 * Get the modal day of month or false as default.
	 * @return mixed
	 */
	public static function getLastShown() {
		return get_option( self::last_shown_option, false );
	}

	/**
	 * Set the modal day of month
	 * @return mixed
	 */
	public static function setLastShown( $value ) {
		return update_option( self::last_shown_option, $value );
	}

	/**
	 * get the last shown of the modal
	 *
	 * @return bool
	 */
	public static function getFormLastShow() {
		return get_option( self::form_last_shown_option, false );
	}

	/**
	 * Set the modal day of month
	 * @return mixed
	 */
	public static function setFormLastShown( $value ) {
		return update_option( self::form_last_shown_option, $value );
	}

    /**
     * get the submitted of the form
     *
     * @return bool
     */
    public static function getFormSubmitted(): bool {
        return get_option( self::form_submitted_option, false );
    }

    /**
     * Set the submitted of the form
     */
    public static function setFormSubmitted( $value ) {
        update_option( self::form_submitted_option, $value );
    }

	/**
	 * Determine whether to show the sign-up modal (again)
	 *
	 * @return bool
	 */
	public static function showSignUpModal(): bool {
		if ( self::getUserType() === self::private ) {
			return false;
		}

		if ( Settings::isPremium() ) {
			return false;
		}

		$date = self::getLastShown();
		if ( $date === false ) {
			return false;
		}

		try {
			$date = ( new DateTime() )->setTimestamp( $date );
			$now  = new DateTime();

			if ( $date->diff( $now )->days > 1 ) {
				return true;
			}
		} catch ( \Exception $e ) {
			return false;
		}

		return false;
	}

	/**
	 * Determine whether to show form the modal)
	 *
	 * @return bool
	 */
	public static function showFormModal():bool {
		if ( strtotime("+1 day", self::getFormLastShow()) < time() && ! self::getFormSubmitted() && !Settings::isPremium() ) {
			self::setFormLastShown( time() );
			return true;
		}

		return false;
	}
}
