<?php

namespace WPGDPRC\WordPress\Admin\Pages;

use WPGDPRC\Utils\PrivacyPolicy;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;
use WPGDPRC\WordPress\Shortcodes\ProcessorSettingsLink;
use WPGDPRC\WordPress\Shortcodes\RequestAccessForm;

/**
 * Class PageSettings
 * @package WPGDPRC\WordPress\PageAdmin\Pages
 */
class PageSettings extends AbstractPage {

	const SECTION_PRIVACY   = 'policy';
	const SECTION_CONSENT   = 'consent';
	const SECTION_REQUEST   = 'request';
	const SECTION_INTEGRATE = 'integrations';

	public static function init() {
		parent::init();
		add_action( 'admin_init', [ static::class, 'redirect' ] );
	}

	/**
	 * Returns the page slug for this page
	 * @return string
	 */
	public static function getPageSlug() {
		return Plugin::PLUGIN_SLUG . '-settings';
	}

	/**
	 * @return string
	 */
	protected static function getParentSlug() {
		return Plugin::PLUGIN_SLUG;
	}

	/**
	 * @return string
	 */
	protected static function getPageTitle() {
		return _x( 'Settings', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return bool
	 */
	protected static function isSubMenu() {
		return true;
	}

	/**
	 * Generates the page content
	 */
	public static function generate() {
		// TODO: Nothing for now
	}

	/**
	 * Redirects to dashboards settings tab (with section)
	 */
	public static function redirect() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}
		if ( sanitize_text_field( strtolower( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) ) !== self::getPageSlug() ) {
			return;
		}

		$redirect = PageDashboard::getTabUrl( PageDashboard::TAB_SETTINGS );

		// Check for GET params in URL
		if ( empty( $_GET['section'] ) ) {
			// Just redirect to main Settings section
			wp_safe_redirect( $redirect );
			exit;
		}

		$sections = self::listSections();
		$section  = sanitize_key( $_GET['section'] );
		if ( isset( $sections[ $section ] ) ) {
			$redirect = self::getSectionUrl( $section );
		}

		wp_safe_redirect( $redirect );
	}

	/**
	 * Gets the url for the settings tab
	 *
	 * @param null|string $section
	 *
	 * @return string
	 */
	public static function getSectionUrl( $section = null ) {
		if ( is_null( $section ) ) {
			$section = self::SECTION_PRIVACY;
		}

		return add_query_arg( [ 'section' => $section ], PageDashboard::getTabUrl( PageDashboard::TAB_SETTINGS ) );
	}

	/**
	 * @return array[]
	 */
	public static function listSections() {
		$list = [
			self::SECTION_PRIVACY   => [
				'title' => _x( 'Privacy Policy', 'admin', 'wp-gdpr-compliance' ),
			],
			self::SECTION_CONSENT   => [
				'title' => _x( 'Consent bar & pop-up', 'admin', 'wp-gdpr-compliance' ),
			],
			self::SECTION_REQUEST   => [
				'title' => _x( 'Request user data form', 'admin', 'wp-gdpr-compliance' ),
			],
			self::SECTION_INTEGRATE => [
				'title' => _x( 'Integrations', 'admin', 'wp-gdpr-compliance' ),
			],
		];

		foreach ( $list as $slug => $data ) {
			$list[ $slug ]['url']      = self::getSectionUrl( $slug );
			$list[ $slug ]['template'] = 'Admin/Pages/Settings/' . $slug;
		}

		return $list;
	}

	/**
	 * @param string $section
	 *
	 * @return array|array[]
	 */
	public static function getSectionFields( $section = '' ) {
		switch ( $section ) {
			case self::SECTION_PRIVACY:
				$external = Settings::isEnabled( Settings::KEY_POLICY_EXTERN );

				return [
					Settings::KEY_POLICY_TEXT   => [
						'key'     => Settings::KEY_POLICY_TEXT,
						'type'    => 'text',
						'label'   => _x( 'Privacy policy link text', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => true,
						'args'    => [
							'class'       => 'regular-text',
							'placeholder' => _x( 'Our Privacy Policy', 'admin', 'wp-gdpr-compliance' ),
							/* translators: %s: The privacy policy page placeholder */
							'description' => sprintf( _x( 'Your text will be used in the following pop-up sentence: “Learn more on %1s page.”', 'admin', 'wp-gdpr-compliance' ), PrivacyPolicy::REPLACER ),
						],
					],
					Settings::KEY_POLICY_EXTERN => [
						'key'     => Settings::KEY_POLICY_EXTERN,
						'type'    => 'truefalse',
						'label'   => _x( 'Use external privacy policy', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => true,
						'args'    => [
							'value'       => $external,
							'class'       => 'regular-text',
							'description' => _x( 'Enabling this will allow you to use an external Privacy Policy instance', 'admin', 'wp-gdpr-compliance' ),

						],
					],
					Settings::KEY_POLICY_PAGE   => [
						'key'     => Settings::KEY_POLICY_PAGE,
						'type'    => 'pageselect',
						'label'   => _x( 'Privacy policy page', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => true,
						'args'    => [
							'description' => _x( 'Select the page where the Privacy Policy is written.', 'admin', 'wp-gdpr-compliance' ),
						],
					],
					Settings::KEY_POLICY_LINK   => [
						'key'     => Settings::KEY_POLICY_LINK,
						'type'    => 'url',
						'label'   => _x( 'Privacy policy url', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => true,
						'args'    => [
							'class'       => 'regular-text',
							'description' => _x( 'Enter the webpage link where people can read your Privacy Policy. ', 'admin', 'wp-gdpr-compliance' ),
						],
					],
				];

			case self::SECTION_CONSENT:
				return [
					Settings::KEY_CONSENT_EXPLAIN_TEXT  => [
						'key'     => Settings::KEY_CONSENT_EXPLAIN_TEXT,
						'type'    => 'text',
						'label'   => _x( 'Explanation', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_FONT          => [
						'key'     => Settings::KEY_CONSENT_FONT,
						'type'    => 'select',
						'label'   => _x( 'Font family for bar and pop-up', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class'   => 'regular-text',
							'choices' => Settings::listFontChoices(),
						],
					],
					Settings::KEY_CONSENT_API_KEY       => [
						'key'     => Settings::KEY_CONSENT_API_KEY,
						'type'    => 'text',
						'label'   => _x( 'Google Fonts API key', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class'       => 'regular-text',
							'description' => _x( 'Google fonts API key', 'admin', 'wp-gdpr-compliance' ),
						],
					],
					Settings::KEY_CONSENT_POSITION      => [
						'key'     => Settings::KEY_CONSENT_POSITION,
						'type'    => 'select',
						'label'   => _x( 'Position', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class'   => 'regular-text',
							'choices' => Settings::listPositionChoices(),
						],
					],
					Settings::KEY_CONSENT_ANIMATION     => [
						'key'     => Settings::KEY_CONSENT_ANIMATION,
						'type'    => 'select',
						'label'   => _x( 'Animation', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class'   => 'regular-text',
							'choices' => Settings::listAnimationChoices(),
						],
					],
					Settings::KEY_CONSENT_BTN_TEXT      => [
						'key'     => Settings::KEY_CONSENT_BTN_TEXT,
						'type'    => 'text',
						'label'   => _x( 'Accept button text', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_INFO_TEXT     => [
						'key'     => Settings::KEY_CONSENT_INFO_TEXT,
						'type'    => 'text',
						'label'   => _x( 'Settings button text', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_BG_COLOR      => [
						'key'     => Settings::KEY_CONSENT_BG_COLOR,
						'type'    => 'colorpicker',
						'label'   => _x( 'Bar color', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_TEXT_COLOR    => [
						'key'     => Settings::KEY_CONSENT_TEXT_COLOR,
						'type'    => 'colorpicker',
						'label'   => _x( 'Text color', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_BTN_PRIMARY   => [
						'key'     => Settings::KEY_CONSENT_BTN_PRIMARY,
						'type'    => 'colorpicker',
						'label'   => _x( 'Button background', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_BTN_SECONDARY => [
						'key'     => Settings::KEY_CONSENT_BTN_SECONDARY,
						'type'    => 'colorpicker',
						'label'   => _x( 'Button text color', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_MODAL_TITLE   => [
						'key'     => Settings::KEY_CONSENT_MODAL_TITLE,
						'type'    => 'text',
						'label'   => _x( 'Title', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_CONSENT_MODAL_TEXT    => [
						'key'     => Settings::KEY_CONSENT_MODAL_TEXT,
						'type'    => 'textarea',
						'label'   => _x( 'Explanation', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
							'value' => Settings::get( Settings::KEY_CONSENT_MODAL_TEXT ),
						],
					],
				];

			case self::SECTION_REQUEST:
				return [
					Settings::KEY_ACCESS_ENABLE      => [
						'key'     => Settings::KEY_ACCESS_ENABLE,
						'type'    => 'truefalse',
						'label'   => _x( 'Activate request form', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class'       => 'regular-text',
							'value'       => Settings::get( Settings::KEY_ACCESS_ENABLE ),
							/* translators: %s: The shortcode */
							'description' => sprintf( _x( 'Enabling this will create one private page containing the necessary shortcode: %1s. You can determine when and how to publish this page yourself.', 'admin', 'wp-gdpr-compliance' ), '<strong>[' . RequestAccessForm::getShortcode() . ']</strong>' ),
						],
					],
					Settings::KEY_ACCESS_TEXT        => [
						'key'     => Settings::KEY_ACCESS_TEXT,
						'type'    => 'text',
						'label'   => _x( 'Checkbox text', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
					Settings::KEY_ACCESS_PAGE        => [
						'key'     => Settings::KEY_ACCESS_PAGE,
						'type'    => 'pageselect',
						'label'   => _x( 'Request access page', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'post_status' => [ 'publish', 'draft', 'private' ],
							'description' => implode(
								' ',
								[
									_x( 'Visitors will receive a link to this page on which the visitor can view all the data stored which is associated with their email address.', 'admin', 'wp-gdpr-compliance' ),
									'<br>',
									/* translators: %s: The shortocde */
									sprintf( _x( 'Please make sure that the access request form shortcode %s is active on the selected page', 'admin', 'wp-gdpr-compliance' ), '<strong>[wpgdprc_access_request_form]</strong>' ),
								]
							),
						],
					],
					Settings::KEY_ACCESS_DELETE_TEXT => [
						'key'     => Settings::KEY_ACCESS_DELETE_TEXT,
						'type'    => 'textarea',
						'label'   => _x( 'Anonymize request explanation', 'admin', 'wp-gdpr-compliance' ),
						'sr_only' => false,
						'args'    => [
							'class' => 'regular-text',
						],
					],
				];

			default:
				return [];
		}
	}

	/**
	 * @param bool $wrap
	 * @param bool $warning
	 *
	 * @return string
	 */
	public static function getShortcodeNotice( $wrap = true, $warning = true ) {
		return ProcessorSettingsLink::getUsageNotice( $wrap, $warning );
	}

}
