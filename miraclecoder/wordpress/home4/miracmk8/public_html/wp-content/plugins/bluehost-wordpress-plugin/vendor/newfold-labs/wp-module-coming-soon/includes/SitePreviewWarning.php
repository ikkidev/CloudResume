<?php

namespace NewfoldLabs\WP\Module\ComingSoon;

/**
 * Display a site preview warning when the site is not live.
 * If WooCommerce is active, this warning will not be displayed.
 * Instead, WooCommerce's warning will be displayed.
 */
class SitePreviewWarning {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Bail if WooCommerce is active or if the site is live.
		if ( isWoocommerceActive() || ! isComingSoonActive() ) {
			return;
		}

		add_action( 'wp_body_open', array( $this, 'site_preview_warning' ) );
	}

	/**
	 * Display site preview warning.
	 */
	public function site_preview_warning() {
		echo "<div class='nfd-site-preview-warning' style='background-color: #e71616; padding: 0 16px;color:#ffffff;font-size:16px;text-align:center;font-weight: 590;'>" . esc_html__( 'Site Preview - This site is NOT LIVE, only admins can see this view.', 'wp-module-coming-soon' ) . '</div>';
	}
}
