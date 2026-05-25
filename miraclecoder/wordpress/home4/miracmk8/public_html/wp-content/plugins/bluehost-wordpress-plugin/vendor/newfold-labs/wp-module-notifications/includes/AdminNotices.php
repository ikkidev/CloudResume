<?php

namespace NewFoldLabs\WP\Module\Notifications;

use wpscholar\Url;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * Class AdminNotices
 */
class AdminNotices {

	/**
	 * Render admin notices where appropriate.
	 */
	public static function maybeRenderAdminNotices() {

		$screen = get_current_screen();

		// Bail if we're in the plugin app, since we already handle notifications in our React app.
		if ( false !== strpos( $screen->id, container()->plugin()->id ) ) {
			return;
		}

        // Bail if we're on a post or page list view in the admin
		if ( $screen->base === 'edit' ) {
            return;
		}

		// Handle realtime notifications
		if ( 'plugin-install' === $screen->id ) {
			?>
            <style>
                .newfold-realtime-notice {
                    margin: 5px 0 15px 0;
                }
            </style>
			<?php
		}

		$page          = str_replace( admin_url(), '', Url::getCurrentUrl() );
		$notifications = new NotificationsRepository( false );
		$collection    = $notifications->collection();

		// Constant container for admin notices
		self::openContainer();

		if ( $collection->count() ) {
			// Only show the latest notification that should be shown on this page
			foreach ( $collection as $notification ) {
				if ( $notification->shouldShow( 'wp-admin-notice', array( 'page' => $page ) ) ) {
					?>
					<div class="newfold-notice" data-id="<?php echo esc_attr( $notification->id ); ?>">
						<?php echo $notification->content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<?php
					break;
				}
			}
		}

		self::closeContainer();

		self::adminScripts( $collection->count() );

	}


	/**
	 * Open the notifactions container
	 */
	public static function openContainer() {
		echo wp_kses_post( '<div id="newfold-notifications" class="newfold-notifications-wrapper">' );
	}

	/**
	 * Close the notifications container
	 */
	public static function closeContainer() {
		echo wp_kses_post( '</div>' );
	}

	/**
	 * Handle scripts
	 *
	 * 	@param int $notifications_count The number of notifications
	 */
	public static function adminScripts( $notifications_count ) {

		// Handle realtime notifications
		$screen = get_current_screen();
		if ( 'plugin-install' === $screen->id || 'theme-install' === $screen->id ) {
			// Enqueue and set local values for realtime script on plugin install page only
			wp_enqueue_script(
				'newfold-plugin-realtime-notices',
				plugins_url( 'vendor/newfold-labs/wp-module-notifications/assets/js/realtime-notices.js', container()->plugin()->file ),
				array( 'lodash', 'nfd-runtime' ),
				container()->plugin()->version,
				true
			);

			// Localize the script with screen ID
			wp_localize_script( 'newfold-plugin-realtime-notices', 'newfoldRealtimeData', array(
				'screenID' => $screen->id,
			) );
		}

		if ( $notifications_count ) {
			// Enqueue and set local values for dismiss script
			wp_enqueue_script(
				'newfold-dismiss-notices',
				plugins_url( 'vendor/newfold-labs/wp-module-notifications/assets/js/dismiss-notices.js', container()->plugin()->file ),
				array( 'nfd-runtime' ),
				container()->plugin()->version,
				true
			);
		}

	}

}
