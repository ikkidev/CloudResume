<?php

namespace WPGDPRC\WordPress\Shortcodes;

use WPGDPRC\Objects\Data\Data;
use WPGDPRC\Objects\RequestAccess;
use WPGDPRC\Utils\AdminHelper;
use WPGDPRC\Utils\IpAddress;
use WPGDPRC\Utils\Request;
use WPGDPRC\Utils\Session;
use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Admin\Pages\PageDashboard;
use WPGDPRC\WordPress\Admin\Pages\PageSettings;
use WPGDPRC\WordPress\Plugin;
use WPGDPRC\WordPress\Settings;

/**
 * Class RequestAccessForm
 * @package WPGDPRC\WordPress\Shortcodes
 */
class RequestAccessForm extends AbstractShortcode {

	const SHORTCODE = Plugin::PREFIX . '_access_request_form';

	/**
	 * @param array $args
	 * @param string $content
	 *
	 * @return string
	 */
	public function generateOutput( array $args = [], string $content = '' ): string {
		if ( isset( $_REQUEST[ Plugin::PREFIX ] ) ) {
			$output = self::getData();

			return $this->wrapOutput( $output );
		}

		if ( ! Settings::canRequest() ) {
			if ( AdminHelper::userIsAdmin() ) {
				return $this->wrapOutput(
					sprintf(
						/* translators: %s: URL to the settings page */
						wp_kses( __( 'The request form has been disabled, you can enable it <a href="%s">here</a>.', 'wp-gdpr-compliance' ), [ 'a' => [ 'href' => [] ] ] ),
						add_query_arg(
							[
								'page'    => Plugin::PLUGIN_SLUG,
								'tab'     => PageDashboard::TAB_SETTINGS,
								'section' => PageSettings::SECTION_REQUEST,
							],
							admin_url()
						)
					)
				);
			}

			return '';
		}

		$output = apply_filters(
			Plugin::PREFIX . '_request_form',
			Template::get(
				'Front/Form/AccessRequest/main',
				[
					'email'   => esc_attr__( 'Your Email Address', 'wp-gdpr-compliance' ),
					'consent' => Settings::getAccessRequestFormCheckboxText(),
					'submit'  => esc_attr__( 'Send', 'wp-gdpr-compliance' ),
				]
			)
		);

		return $this->wrapOutput( $output );
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function wrapOutput( string $content = '' ): string {
		return '<div class="' . Plugin::PREFIX . '">' . $content . '</div>';
	}

	/**
	 * @return string
	 */
	private static function getData(): string {
		$token   = isset( $_REQUEST[ Plugin::PREFIX ] ) ? sanitize_title_with_dashes( urldecode( wp_unslash( $_REQUEST[ Plugin::PREFIX ] ) ) ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$request = $token !== false ? RequestAccess::getByToken( $token ) : false;
		if ( empty( $request ) ) {
			return self::getNoneText();
		}

		if ( ! Session::check( $request->getSessionId() ) || ! IpAddress::checkIp( $request->getIpAddress() ) ) {
			return self::getInvalidText();
		}

		$email        = $request->getEmailAddress();
		$integrations = Request::getData( $request, true );
		$chapters     = [
			'intro' => [
				'title'   => '',
				'content' => '',
				'notice'  => apply_filters( Plugin::PREFIX . '_the_content', Settings::getDeleteRequestFormExplanationText() ),
			],
		] + $integrations;

		if ( empty( $integrations ) ) {
			$chapters = array_merge(
				$chapters,
				[
					'no-integrations' => [
						'title'   => '',
						'content' => '',
						'notice'  => __( 'No data is stored in relation to this email address', 'wp-gdpr-compliance' ),
					],
				]
			);
		}

		$output = Template::get(
			'Front/Form/AccessRequest/Submit/success',
			[
				'chapters' => $chapters,
			]
		);

		return apply_filters( Plugin::PREFIX . '_request_data', $output, new Data( $email ), $request );
	}

	/**
	 * Gets the text to display when no request is found
	 * @return string
	 */
	public static function getNoneText(): string {
		$template = 'Front/Elements/error';
		$message  = __( "This request is expired or doesn't exist.", 'wp-gdpr-compliance' );
		$link_el  = Request::getAccessPageLinkEl();
		if ( empty( $link_el ) ) {
			return Template::get( $template, [ 'message' => $message ] );
		}

		/* translators: %1s: The link element */
		$append  = sprintf( __( 'If needed, you can put in a new request here: %1s', 'wp-gdpr-compliance' ), $link_el );
		$message = implode( '<br /><br />', [ $message, $append ] );

		return Template::get( $template, [ 'message' => $message ] );
	}

	/**
	 * Gets the text to display for an invalid request
	 * @return string
	 */
	public static function getInvalidText(): string {
		$template = 'Front/Form/AccessRequest/Submit/error';
		$message  = __( 'You are only able to view your data when visiting this page on the same device with the same IP and in the same browser session as when you performed your request. This is an extra security measure to keep your data safe.', 'wp-gdpr-compliance' );
		$link_el  = Request::getAccessPageLinkEl();
		if ( empty( $link_el ) ) {
			return Template::get( $template, [ 'message' => $message ] );
		}

		/* translators: %1s: The link element */
		$append  = sprintf( __( 'If needed, you can put in a new request after 24 hours here: %1s', 'wp-gdpr-compliance' ), $link_el );
		$message = implode( '<br /><br />', [ $message, $append ] );

		return Template::get( $template, [ 'message' => $message ] );
	}

}
