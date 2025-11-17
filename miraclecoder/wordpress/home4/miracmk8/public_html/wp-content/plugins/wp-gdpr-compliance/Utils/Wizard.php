<?php

namespace WPGDPRC\Utils;

use WPGDPRC\WordPress\Admin\Pages\PageAdmin;
use WPGDPRC\WordPress\Admin\Pages\PageWizard;
use WPGDPRC\WordPress\Ajax\DismissWizard;
use WPGDPRC\WordPress\Ajax\UpdateWizardConsent;
use WPGDPRC\WordPress\Ajax\UpdateWizardSettings;
use WPGDPRC\WordPress\Config;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class Wizard
 * @package WPGDPRC\Utils
 */
class Wizard {


	const OPTION_KEY = Plugin::PREFIX . '_fts_finished';
	const URL_KEY    = Plugin::PREFIX . '_fts';

	const STATUS_FINISHED = 'finished';
	const STATUS_RESTART  = 'restart';

	const AJAX_SAVE_CONSENT  = Plugin::PREFIX . '_wizard_save_consent';
	const AJAX_SAVE_SETTINGS = Plugin::PREFIX . '_wizard_save_settings';

	public static function initHooks() {
		if ( self::isCompleted() ) {
			return;
		}

		UpdateWizardConsent::init();
		UpdateWizardSettings::init();

		add_action( 'admin_notices', [ self::class, 'simpleNotice' ] );
	}

	/**
	 * @return bool
	 */
	public static function isCompleted(): bool {
		return (bool) get_option( self::OPTION_KEY, false );
	}

	/**
	 * @param bool $status
	 *
	 * @return bool
	 */
	public static function setCompletionStatus( bool $status = true ): bool {
		return update_option( self::OPTION_KEY, $status );
	}

	/**
	 * Gets the main dashboard url and skips the wizard
	 *
	 * @param string $reason
	 * @param string $url
	 *
	 * @return string
	 */
	private static function getStatusLink( string $reason = '', string $url = '' ): string {
		if ( empty( $url ) ) {
			$url = PageAdmin::getPageUrl();
		}

		return add_query_arg( [ self::URL_KEY => sanitize_key( $reason ) ], $url );
	}

	/**
	 * @param string $url
	 *
	 * @return string
	 */
	public static function getRestartLink( string $url = '' ): string {
		return self::getStatusLink( self::STATUS_RESTART, $url );
	}

	/**
	 * @param string $url
	 *
	 * @return string
	 */
	public static function getFinishLink( string $url = '' ): string {
		return self::getStatusLink( self::STATUS_FINISHED, $url );
	}

	/**
	 * Checks if the setup has been completed or been skipped wit a query param
	 */
	public static function checkStatusChange(): bool {
		if ( ! isset( $_GET[ self::URL_KEY ] ) ) {
			return false;
		}

		$status = sanitize_key( $_GET[ self::URL_KEY ] );
		if ( ! in_array( $status, [ self::STATUS_FINISHED, self::STATUS_RESTART ], true ) ) {
			return false;
		}

		// if it is not finished because of the check above it has to be restarted.
		$isDone = $status === self::STATUS_FINISHED;

		self::setCompletionStatus( $isDone );

		return $isDone;
	}

	/**
	 * Show the simple wizard notice on the main dashboard page.
	 */
	public static function simpleNotice() {
		if ( ! is_admin() ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id !== 'dashboard' ) {
			return;
		}

		if ( get_option( Settings::ACTIVATION_KEY, false ) ) {
			return;
		}

		$link = Elements::getLink(
			esc_url( PageWizard::getPageUrl() ),
			_x( 'First time setup Wizard', 'admin', 'wp-gdpr-compliance' )
		);
        AdminHelper::wrapNotice(
			sprintf(
				/* translators: %s: Plugin name */
				_x( '<strong>Hey!</strong> Thank you for installing %s!', 'admin', 'wp-gdpr-compliance' ),
				Config::pluginName()
			) . '<br/>' . sprintf(
				/* translators: %1s: Site link element */
				_x( 'To setup the plugin and see what this plugin can do, go to our %1s.', 'admin', 'wp-gdpr-compliance' ),
				$link
			)
		);
	}

	/**
	 * Renders the notice to start the wizard
	 */
	public static function renderNotice() {
		$title = _x( 'WP GDPR Compliance will become even better!', 'admin', 'wp-gdpr-compliance' );
		Elements::notice( $title, Config::wizardIntro(), '', 'wizard' );
	}

}
