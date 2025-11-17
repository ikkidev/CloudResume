<?php
namespace WPGDPRC\WordPress\Front;

use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\PrivacyPolicy;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Filters
 * @package WPGDPRC\WordPress\Front
 */
class Filters {

	/**
	 * Filters constructor
	 */
	public static function init() {

		add_filter( 'core_version_check_query_args', [ self::class, 'onlyEssentialDataForUpdateCheck' ] );

		add_filter( Plugin::PREFIX . '_replace_privacy_link', [ self::class, 'replacePrivacy' ] );

		add_filter( Plugin::PREFIX . '_the_content', 'wptexturize' );
		add_filter( Plugin::PREFIX . '_the_content', 'convert_smilies', 20 );
		add_filter( Plugin::PREFIX . '_the_content', 'wpautop' );
		add_filter( Plugin::PREFIX . '_the_content', 'shortcode_unautop' );

		if ( Helper::filterExists( 'wp_filter_content_tags' ) ) {
			add_filter( Plugin::PREFIX . '_the_content', 'wp_filter_content_tags' );
		}

		// Use Cookie Information
		if ( Settings::isPremium() ) {
			add_filter( Plugin::PREFIX . '_consent_bar', '__return_empty_string' );
			add_filter( Plugin::PREFIX . '_consent_modal', '__return_empty_string' );
		}
	}

	/**
	 * Stops WordPress from sending anything but essential data during the update check
	 * @TODO Do we want this, 'cause it's messing with the WP stuff..
	 * @param array $query
	 * @return array
	 */
	public static function onlyEssentialDataForUpdateCheck( $query = [] ) {
		unset( $query['php'] );
		unset( $query['mysql'] );
		unset( $query['local_package'] );
		unset( $query['blogs'] );
		unset( $query['users'] );
		unset( $query['multisite_enabled'] );
		unset( $query['initial_db_version'] );
		return $query;
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public static function replacePrivacy( $content = '' ) {
		return PrivacyPolicy::replaceLink( $content );
	}

	/**
	 * List all filters available in this plugin
	 * @TODO scan code for more filter & add them
	 * @return array
	 */
	public static function listAll() {
		return [
			Plugin::PREFIX . '_allowed_html_tags',
			Plugin::PREFIX . '_consent_bar',
			Plugin::PREFIX . '_consent_modal',
			Plugin::PREFIX . '_consents_bar_explanation_text',
			Plugin::PREFIX . '_consents_bar_more_information_text',
			Plugin::PREFIX . '_consents_modal_explanation_text',
			Plugin::PREFIX . '_consents_modal_title',
			Plugin::PREFIX . '_privacy_policy_link', // optional
			Plugin::PREFIX . '_privacy_policy_text', // optional
			Plugin::PREFIX . '_privacy_policy_link_html',  // optional
			Plugin::PREFIX . '_replace_privacy_link', // filter to replace the PLACEHOLDER
			Plugin::PREFIX . '_request_form',
			Plugin::PREFIX . '_request_form_consent_field',
			Plugin::PREFIX . '_request_form_email_field',
			Plugin::PREFIX . '_request_form_email_label',
			Plugin::PREFIX . '_request_form_email_placeholder',
			Plugin::PREFIX . '_request_form_submit_field',
			Plugin::PREFIX . '_request_form_submit_label',
			Plugin::PREFIX . '_the_content',
			Plugin::PREFIX . '_access_request_form_checkbox_text',
			Plugin::PREFIX . '_delete_request_form_explanation_text',
			Plugin::PREFIX . '_request_data',
			Plugin::PREFIX . '_access_request_mail_subject',
			Plugin::PREFIX . '_access_request_mail_content',
			Plugin::PREFIX . '_delete_request_admin_mail_subject',
			Plugin::PREFIX . '_delete_request_admin_mail_content',
			Plugin::PREFIX . '_request_mail_from',
			Plugin::PREFIX . '_request_mail_headers',
			Plugin::PREFIX . '_consent_cookie',
		];
	}

}
