<?php

namespace WPGDPRC\WordPress;

use WPGDPRC\Objects\DataProcessor;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\Google;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Integration;
use WPGDPRC\Utils\Request;

/**
 * Class Settings
 * @package WPGDPRC\WordPress
 */
class Settings {

	const ACTIVATION_KEY     = Plugin::PREFIX . '_activated';
	const SETTINGS_KEY       = Plugin::PREFIX;
	const SETTINGS_GROUP     = 'settings';
	const INTEGRATIONS_GROUP = 'integrations';

	const KEY_PREMIUM = 'enable_premium_mode';

	const KEY_POLICY_TEXT   = 'privacy_policy_text';
	const KEY_POLICY_EXTERN = 'enable_privacy_policy_extern';
	const KEY_POLICY_PAGE   = 'privacy_policy_page';
	const KEY_POLICY_LINK   = 'privacy_policy_link';

	const KEY_ACCESS_ENABLE      = 'enable_access_request';
	const KEY_ACCESS_PAGE        = 'access_request_page';
	const KEY_ACCESS_TEXT        = 'access_request_form_checkbox_text';
	const KEY_ACCESS_DELETE_TEXT = 'delete_request_form_explanation_text';

	const KEY_CONSENT_EXPLAIN_TEXT  = 'consents_bar_explanation_text';
	const KEY_CONSENT_INFO_TEXT     = 'consents_bar_more_information_text';
	const KEY_CONSENT_BTN_TEXT      = 'consents_bar_button_text';
	const KEY_CONSENT_FONT          = 'consents_bar_font';
	const KEY_CONSENT_API_KEY       = 'consents_api_key';
	const KEY_CONSENT_POSITION      = 'consents_bar_position';
	const KEY_CONSENT_ANIMATION     = 'consents_bar_animation';
	const KEY_CONSENT_BG_COLOR      = 'consents_bar_color';
	const KEY_CONSENT_TEXT_COLOR    = 'consents_bar_text_color';
	const KEY_CONSENT_BTN_PRIMARY   = 'consents_bar_button_color_primary';
	const KEY_CONSENT_BTN_SECONDARY = 'consents_bar_button_color_secondary';

	const KEY_CONSENT_MODAL_TITLE = 'consents_modal_title';
	const KEY_CONSENT_MODAL_TEXT  = 'consents_modal_explanation_text';

	// Transients
	const KEY_PUBLISHED_POSTS = 'published_posts';

	/**
	 * Registers all the settings
	 */
	public static function registerSettings() {
		// Premium mode
		self::register( self::KEY_PREMIUM, self::getSettingArgs( 'integer', 0 ) );

		// Privacy Policy
		self::register( self::KEY_POLICY_TEXT, self::getSettingArgs( 'text', __( 'Privacy Policy', 'wp-gdpr-compliance' ) ) );
		self::register( self::KEY_POLICY_EXTERN, self::getSettingArgs( 'integer', 0 ) );
		self::register( self::KEY_POLICY_PAGE, self::getSettingArgs( 'integer', 0 ) );
		self::register( self::KEY_POLICY_LINK, self::getSettingArgs( 'url', __( 'https://www.example.com/privacypolicy', 'wp-gdpr-compliance' ) ) );

		// Access Request
		self::register( self::KEY_ACCESS_PAGE, self::getSettingArgs( 'integer', 0 ) );
		self::register( self::KEY_ACCESS_ENABLE, self::getSettingArgs( 'integer', 0 ) ); // VERY IMPORTANT that KEY_ACCESS_ENABLE gets registered later than KEY_ACCESS_PAGE. On value change of KEY_ACCESS_ENABLE sometimes edits the value of KEY_ACCESS_PAGE!
		self::register( self::KEY_ACCESS_TEXT, self::getSettingArgs( 'text', Config::consentText() ) );
		self::register( self::KEY_ACCESS_DELETE_TEXT, self::getSettingArgs( 'textarea', Config::explainText() ) );

		// Consent bar and modal
		$default = DataProcessor::allRequired() ? esc_attr__( 'More information', 'wp-gdpr-compliance' ) : esc_attr__( 'My settings', 'wp-gdpr-compliance' );
		self::register( self::KEY_CONSENT_INFO_TEXT, self::getSettingArgs( 'text', $default ) );
		self::register(
			self::KEY_CONSENT_EXPLAIN_TEXT,
			[
				'type'    => 'text',
				'default' => __(
					'This site uses functional cookies and external scripts to improve your experience.',
					'wp-gdpr-compliance'
				),
			]
		);
		self::register( self::KEY_CONSENT_BTN_TEXT, self::getSettingArgs( 'text', esc_attr__( 'Accept', 'wp-gdpr-compliance' ) ) );
		self::register( self::KEY_CONSENT_FONT, self::getSettingArgs( 'text', Helper::arrayKeyFirst( self::listFontChoices() ) ) );
		self::register( self::KEY_CONSENT_API_KEY, self::getSettingArgs( 'text', '' ) );
		self::register( self::KEY_CONSENT_POSITION, self::getSettingArgs( 'text', Helper::arrayKeyFirst( self::listPositionChoices() ) ) );
		self::register( self::KEY_CONSENT_ANIMATION, self::getSettingArgs( 'text' ) ); // not used.
		self::register( self::KEY_CONSENT_BG_COLOR, self::getSettingArgs( 'hex', '#000000' ) );
		self::register( self::KEY_CONSENT_TEXT_COLOR, self::getSettingArgs( 'hex', '#ffffff' ) );
		self::register( self::KEY_CONSENT_BTN_PRIMARY, self::getSettingArgs( 'hex', '#000000' ) );
		self::register( self::KEY_CONSENT_BTN_SECONDARY, self::getSettingArgs( 'hex', '#ffffff' ) );

		self::register( self::KEY_CONSENT_MODAL_TITLE, self::getSettingArgs( 'text', __( 'Privacy Settings', 'wp-gdpr-compliance' ) ) );
		self::register(
			self::KEY_CONSENT_MODAL_TEXT,
			[
				'type'    => 'textarea',
				'default' =>
											__( 'This site uses functional cookies and external scripts to improve your experience. Which cookies and scripts are used and how they impact your visit is specified on the left. You may change your settings at any time. Your choices will not impact your visit.', 'wp-gdpr-compliance' ),
			]
		);

		// Add action on option save
		add_action(
			'pre_update_option_' . self::getKey( self::KEY_POLICY_TEXT ),
			[
				self::class,
				'onOptionUpdate',
			]
		);

		add_action(
			'save_post',
			[
				self::class,
				'onPostSave',
			]
		);
	}

	/**
	 * Creates the settings args based on type
	 *
	 * @param string $type
	 * @param mixed $default
	 *
	 * @return array
	 */
	public static function getSettingArgs( $type = 'string', $default = null ) {
		$args = [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		];

		if ( ! is_null( $default ) ) {
			$args['default'] = $default;
		}

		switch ( $type ) {
			case 'hex':
				$args['sanitize_callback'] = 'sanitize_hex_color';

				return $args;

			case 'integer':
				$args['type']              = 'integer';
				$args['sanitize_callback'] = 'intval';

				return $args;

			case 'textarea':
				$args['sanitize_callback'] = 'sanitize_textarea_field';

				return $args;

			case 'text':
			case 'string':
				return $args;

			case 'url':
				$args['sanitize_callback'] = 'esc_url_raw';

				return $args;

			default:
				return [];
		}
	}

	/**
	 * Registers a setting
	 *
	 * @param string $key
	 * @param array $args
	 * @param string $group
	 */
	public static function register( $key, $args = [], $group = self::SETTINGS_GROUP ) {
		register_setting( self::getGroupKey( $group ), self::getKey( $key, $group ), $args );
	}

	/**
	 * Returns the full setting key including the prefix
	 *
	 * @param string $key
	 * @param string $group
	 *
	 * @return string
	 */
	public static function getKey( $key, $group = self::SETTINGS_GROUP ) {
		return self::SETTINGS_KEY . '_' . $group . '_' . $key;
	}

	/**
	 * Return the transient settings key.
	 *
	 * @param $key
	 * @return string
	 */
	public static function getTransientKey( $key ) {
		return self::SETTINGS_KEY . '_transient_' . $key;
	}

	/**
	 * Returns the full setting group key including the prefix
	 *
	 * @param string $group
	 *
	 * @return string
	 */
	public static function getGroupKey( $group = self::SETTINGS_GROUP ) {
		return self::SETTINGS_KEY . '_group_' . $group;
	}

	/**
	 * Returns the settings value
	 *
	 * @param string $key
	 * @param string $group
	 *
	 * @return mixed
	 */
	public static function get( $key = '', $group = self::SETTINGS_GROUP ) {
		return get_option( self::getKey( $key, $group ) );
	}

	/**
	 * Return the transient value
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function getTransient( $key = '' ) {
		return get_transient( self::getTransientKey( $key ) );
	}

	/**
	 * Set the transient value
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $expiration
	 * @return mixed
	 */
	public static function setTransient( $key = '', $value = '', $expiration = 0 ) {
		return set_transient( self::getTransientKey( $key ), $value, $expiration );
	}

	/**
	 * Lists all the plugin settings
	 * @return array
	 */
	public static function getAll() {
		global $wpdb;

		$query  = $wpdb->prepare(
			'SELECT * FROM `' . $wpdb->options . '` WHERE `option_name` LIKE %s',
			[
				self::SETTINGS_KEY . '_%',
			]
		);
		$result = $wpdb->get_results( $query, ARRAY_A );
		if ( empty( $result ) ) {
			return [];
		}

		$list = [];
		foreach ( $result as $row ) {
			$key          = $row['option_name'];
			$list[ $key ] = maybe_unserialize( $row['option_value'] );
		}

		return $list;
	}

	/**
	 * Saves the setting
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param string $group
	 *
	 * @return bool
	 */
	public static function saveSetting( $key, $value, $group = self::SETTINGS_GROUP ) {
		return update_option( self::getKey( $key, $group ), $value );
	}

	/**
	 * Deletes all plugin settings
	 * Deletes all options from the options table starting with the plugins' settings key, followed by an underscore
	 * @return bool
	 */
	public static function deleteAll() {
		global $wpdb;

		$query = $wpdb->prepare(
			'DELETE FROM `' . $wpdb->options . '` WHERE `option_name` LIKE %s',
			[
				self::SETTINGS_KEY . '_%',
			]
		);

		return $wpdb->query( $query );
	}

	/**
	 * Sets the default settings (upon plugin activation)
	 */
	public static function setDefaults() {
		$list = [
			self::KEY_PREMIUM       => 0,
			self::KEY_ACCESS_ENABLE => 0,
		];

		foreach ( $list as $key => $default ) {
			if ( self::get( $key ) !== false ) {
				continue;
			}
			self::saveSetting( $key, $default );
		}
	}

	/**
	 * Checks for premium mode
	 * @return bool
	 */
	public static function isPremium() {
		return self::isEnabled( self::KEY_PREMIUM );
	}

	/**
	 * Checks if access requests are enabled
	 * @return bool
	 */
	public static function canRequest() {
		return self::isEnabled( self::KEY_ACCESS_ENABLE ) && ! empty( Request::getAccessPage() );
	}

	/**
	 * Lists available font choices (for the consent bar)
	 * @return array
	 */
	public static function listFontChoices() {
		$list = [
			'' => _x( 'Use the font of my website', 'admin', 'wp-gdpr-compliance' ),
		];

		return array_merge( $list, Google::getPopularFontsList() );
	}

	/**
	 * Lists available position choices (for the consent bar)
	 * @return array
	 */
	public static function listPositionChoices() {
		return [
			'bottom' => _x( 'Bottom', 'admin', 'wp-gdpr-compliance' ),
			'top'    => _x( 'Top', 'admin', 'wp-gdpr-compliance' ),
		];
	}

	/**
	 * Lists available animation choices (for the consent bar)
	 * @return array
	 */
	public static function listAnimationChoices() {
		return [
			'fade-out'   => _x( 'Fade out', 'admin', 'wp-gdpr-compliance' ),
			'bounce-out' => _x( 'Bounce out', 'admin', 'wp-gdpr-compliance' ),
			'zoom-out'   => _x( 'Zoom out', 'admin', 'wp-gdpr-compliance' ),
			''           => _x( 'Instant (no animation)', 'admin', 'wp-gdpr-compliance' ),
		];
	}

	/**
	 * Gets page ID of page with Access Request form
	 * @return int
	 */
	public static function getAccessRequestPage() {
		return (int) self::get( self::KEY_ACCESS_PAGE );
	}

	/**
	 * Gets the checkbox text for the Access Request form
	 *
	 * @param bool $insert
	 *
	 * @return string
	 */
	public static function getAccessRequestFormCheckboxText( $insert = true ) {
		$default = Config::consentText();
		$output  = self::get( self::KEY_ACCESS_TEXT );
		if ( $insert ) {
			$output = apply_filters( Plugin::PREFIX . '_replace_privacy_link', $output );
		}

		return apply_filters( Plugin::PREFIX . '_access_request_form_checkbox_text', wp_kses( $output, AdminHelper::getAllowedHTMLTags() ) );
	}

	/**
	 * Gets the explanation text for the Delete Request form
	 *
	 * @param bool $insert
	 *
	 * @return string
	 */
	public static function getDeleteRequestFormExplanationText( $insert = true ) {
		$default = Config::explainText();
		$output  = self::get( self::KEY_ACCESS_DELETE_TEXT );
		if ( $insert ) {
			$output = apply_filters( Plugin::PREFIX . '_replace_privacy_link', $output );
		}

		return apply_filters( Plugin::PREFIX . '_delete_request_form_explanation_text', wp_kses( $output, AdminHelper::getAllowedHTMLTags() ) );
	}

	/**
	 * Checks whether the given settings has been enabled
	 * Used for toggle settings being saved as 0 or 1
	 *
	 * @param string $key
	 * @param string $group
	 *
	 * @return bool
	 */
	public static function isEnabled( $key, $group = self::SETTINGS_GROUP ) {
		return (int) self::get( $key, $group ) === 1;
	}

	/**
	 * Lists enabled plugin/integration forms
	 *
	 * @param string $plugin_id
	 * @param array $default
	 *
	 * @return array
	 */
	public static function getForms( $plugin_id = '', $default = [] ) {
		if ( empty( $plugin_id ) ) {
			return $default;
		}
		$result = self::get( $plugin_id . '_' . Integration::KEY_FORMS, self::INTEGRATIONS_GROUP );

		return ! empty( $result ) ? $result : $default;
	}

	/**
	 * Stores current section on updating settings
	 * for better settings tab/sections control
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public static function onOptionUpdate( $value = '' ) {
		if ( empty( $_POST[ self::SETTINGS_GROUP ] ) ) {
			return $value;
		}
		if ( empty( $_POST[ self::SETTINGS_GROUP ]['submit'] ) ) {
			return $value;
		}

        $submit =  esc_sql( sanitize_text_field( wp_unslash( $_POST[ self::SETTINGS_GROUP ]['submit'] ) ) );
		self::setSectionTransient( $submit );

		return $value;
	}

	/**
	 * All actions to trigger on post save,
	 *
	 * - Reset the published posts list cache.
	 */
	public static function onPostSave() {
		self::setTransient( self::KEY_PUBLISHED_POSTS, false );
	}

	/**
	 * @param string $value
	 */
	public static function setSectionTransient( $value = '' ) {
		if ( empty( $value ) ) {
			return;
		}
		set_transient( self::getSectionsTransientKey(), $value, MINUTE_IN_SECONDS );
	}

	/**
	 * @return string
	 */
	public static function getSectionTransient() {
		$cached = get_transient( self::getSectionsTransientKey() );

		return ! empty( $cached ) ? $cached : '';
	}

	/**
	 * @return string
	 */
	public static function getSectionsTransientKey() {
		$prefix = implode( '_', [ Plugin::PREFIX, self::SETTINGS_GROUP, 'section' ] );

		return implode( '__', [ $prefix, get_current_user_id() ] );
	}
}
