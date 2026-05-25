<?php

namespace NewfoldLabs\WP\Module\ComingSoon;

use NewfoldLabs\WP\ModuleLoader\Container;
use WP_Admin_Bar;

/**
 * Add site status badge (Coming Soon or Live) to WP admin bar.
 * If WooCommerce is active, this badge will not be added.
 * Instead, WooCommerce's site visibility badge will be displayed.
 */
class AdminBarSiteStatusBadge {
	/**
	 * Container.
	 *
	 * @var Container
	 */
	private $container;

	/**
	 * Default values.
	 *
	 * @var array
	 */
	private $defaults = array();

	/**
	 * Constructor.
	 *
	 * @param Container $container Container.
	 */
	public function __construct( Container $container ) {
		// Bail if WooCommerce is active.
		if ( isWoocommerceActive() ) {
			return;
		}

		$this->container = $container;

		$this->defaults = array(
			'admin_bar_cs_active'   => __( 'Coming soon', 'wp-module-coming-soon' ),
			'admin_bar_cs_inactive' => __( 'Live', 'wp-module-coming-soon' ),
		);

		add_action( 'admin_bar_menu', array( $this, 'site_status_badge' ), 31 );
		add_action( 'wp_head', array( $this, 'site_status_badge_styles' ) );
		add_action( 'admin_head', array( $this, 'site_status_badge_styles' ) );
		add_action( 'update_option_nfd_coming_soon', array( __CLASS__, 'site_status_badge_timer' ), 10, 2 );
	}

	/**
	 * Add site status badge to WP admin bar.
	 *
	 * @param WP_Admin_Bar $admin_bar An instance of the WP_Admin_Bar class.
	 */
	public function site_status_badge( WP_Admin_Bar $admin_bar ): void {
		if ( current_user_can( 'manage_options' ) ) {

			$is_coming_soon = isComingSoonActive();
			$title          = $is_coming_soon ? $this->defaults['admin_bar_cs_active'] : $this->defaults['admin_bar_cs_inactive'];
			$class          = $this->site_status_badge_class( $is_coming_soon );

			$site_status_menu = array(
				'id'     => 'nfd-site-visibility-badge',
				'parent' => 'root-default',
				'href'   => apply_filters( 'nfd_build_url', admin_url( 'admin.php?page=' . $this->container->plugin()->id . '&nfd-target=coming-soon-section#/settings' ) ),
				'title'  => $title,
				'meta'   => array(
					'class' => 'nfd-site-status-badge-' . $class,
				),
			);
			$admin_bar->add_menu( $site_status_menu );
		}
	}

	/**
	 * Determine the class for the site status badge.
	 *
	 * @param bool $is_coming_soon Whether the site is in Coming Soon mode.
	 */
	private function site_status_badge_class( $is_coming_soon ): string {
		$class = $is_coming_soon ? 'coming-soon' : 'live';

		// Hide badge if the site has been live for more than 10 minutes.
		if ( ! $is_coming_soon && ! get_transient( 'nfd_coming_soon_site_status_badge_timer' ) ) {
			$class = 'hidden';
		}

		return $class;
	}

	/**
	 * Output CSS for site status badge.
	 */
	public function site_status_badge_styles(): void {
		if ( is_admin_bar_showing() ) {
			?>
			<style>
				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge a.ab-item {
					background-color: #F6F7F7;
					color: black;
					margin-top:7px;
					padding: 0 6px;
					height: 18px;
					line-height: 17px;
					border-radius: 2px;
				}

				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge a.ab-item:hover,
				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge a.ab-item:focus {
					background-color: #DCDCDE;
				}

				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge a.ab-item:focus {
					outline: var(--wp-admin-border-width-focus) solid var(--wp-admin-theme-color-darker-20);
				}

				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge.nfd-site-status-badge-live a.ab-item {
					background-color: #E6F2E8;
					color: #00450C;
				}

				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge.nfd-site-status-badge-live a.ab-item:hover,
				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge.nfd-site-status-badge-live a.ab-item:focus {
					background-color: #B8E6BF;
				}

				#wpadminbar .quicklinks #wp-admin-bar-nfd-site-visibility-badge.nfd-site-status-badge-hidden {
					display: none;
				}
			</style>
			<?php
		}
	}

	/**
	 * Set 10 minutes transient timer for site status badge when coming soon is turned off.
	 *
	 * @param bool $old_value The old option value.
	 * @param bool $new_value The new option value.
	 */
	public static function site_status_badge_timer( $old_value, $new_value ): void {
		$value = wp_validate_boolean( $new_value );

		if ( false === $value ) {
			set_transient( 'nfd_coming_soon_site_status_badge_timer', true, 10 * MINUTE_IN_SECONDS );
		}
	}
}
