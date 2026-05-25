<?php

namespace WPGDPRC\WordPress;

use WPGDPRC\Utils\Elements;
use WPGDPRC\Utils\Helper;
use WPGDPRC\Utils\Template;

/**
 * Class Config
 * @package WPGDPRC\WordPress
 */
class Config {

	/**
	 * Gets the plugin name
	 * @return string
	 */
	public static function pluginName(): string {
		$data = Helper::getPluginData();

		return $data['Name'];
	}

	public static function addUTMParams( $url ) {
		$args = [
			'utm_campaign' => 'van-ons-go-premium',
			'utm_source'   => 'van-ons-wp',
			'utm_medium'   => 'referral',
		];

		return add_query_arg( $args, $url );
	}

	/**
	 * @return string
	 */
	public static function cookieInformationUrl(): string {
		return self::addUTMParams( 'https://cookieinformation.com/' );
	}

	/**
	 * @return string
	 */
	public static function aboutUrl(): string {
		return Helper::getPremiumAlternative( 'https://wordpress.org/plugins/wp-gdpr-compliance/', self::addUTMParams( 'https://cookieinformation.com/about-us/' ) );
	}

	/**
	 * @return string
	 */
	public static function faqUrl(): string {
		return self::addUTMParams( 'https://support.cookieinformation.com/en/collections/2554611-6-faq-legal-and-more' );
	}

	/**
	 * @return string
	 */
	public static function supportUrl(): string {
		return Helper::getPremiumAlternative( 'https://wordpress.org/support/plugin/wp-gdpr-compliance#new-post', self::addUTMParams( 'https://support.cookieinformation.com/en/articles/5485573-wp-gdpr-compliance-plugin' ) );
	}

	/**
	 * @return string
	 */
	public static function premiumUrl(): string {
		return self::addUTMParams( 'https://cookieinformation.com/wp-gdpr-business-cookie-banner/' );
	}

	/**
	 * @return string
	 */
	public static function premiumDashboardUrl(): string {
		return self::addUTMParams( 'https://app.cookieinformation.com/' );
	}

	/**
	 * @return string
	 */
	public static function premiumScriptUrl(): string {
		return 'https://policy.app.cookieinformation.com/uc.js';
	}

	/**
	 * @return string
	 */
	public static function rateUrl(): string {
		return 'https://wordpress.org/support/plugin/wp-gdpr-compliance/reviews/#new-post';
	}

	/**
	 * @return string
	 */
	public static function consentText(): string {
		return __( 'By using this form you agree with the storage and handling of your data by this website.', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	public static function explainText(): string {
		return sprintf(
			__( "Below we show you all of the data stored by %1\$1s on %2\$1s. Select the data you wish the site owner to anonymize so it cannot be linked to your email address any longer. It is the site's owner responsibility to act upon your request. When your data is anonymized you will receive an email confirmation.", 'wp-gdpr-compliance' ),
			get_option( 'blogname' ),
			get_option( 'siteurl' )
		);
	}

	/**
	 * @return string
	 */
	public static function welcomeTitle(): string {
		return _x( 'Welcome to Cookie Information | Free WP GDPR Consent Plugin', 'admin', 'wp-gdpr-compliance' );
	}

	/**
	 * @return string
	 */
	public static function welcomeText(): string {
		$link = Elements::getLink(
			self::premiumUrl(),
			_x( 'try it out here', 'admin', 'wp-gdpr-compliance' ),
			[
				'target' => '_blank',
				'class'  => 'wpgdprc-sign-up-button',
			]
		);

		/* translators: %s: Link */
		return sprintf( _x( 'Thank you very much for choosing this plugin to help you with your GDPR Compliance. This is a free tool that offers Compliance. We also provide a version that automates many of these tasks and makes sure that you are always up to date: %1s', 'admin', 'wp-gdpr-compliance' ), $link );
	}

	/**
	 * @return string
	 */
	public static function wizardIntro(): string {
		return Template::get( 'Admin/Wizard/Intro' );
	}

}
