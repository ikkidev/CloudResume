<?php

namespace NewfoldLabs\WP\Module\ComingSoon;

use NewfoldLabs\WP\ModuleLoader\Container;
use function NewfoldLabs\WP\ModuleLoader\container;

/**
 * This class adds a coming soon page functionality.
 **/
#[\AllowDynamicProperties]
class ComingSoon {

	/**
	 * Register functionality using WordPress Actions.
	 *
	 * @param Container $container the container from the module loader.
	 */
	public function __construct( Container $container ) {
		$this->container = $container;

		$coming_soon_svg = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 215 55"><path fill="#121212" d="M214.38 22.81c.26-.1.45-.01.57.27.12.26.05.44-.21.54a88.88 88.88 0 0 0-10.23 5.37 23.08 23.08 0 0 1-2.2 1.26c-.4.16-.72.17-.98.03-.3-.18-.44-.51-.42-.99 0-.7.34-2 1.02-3.9.6-1.74.85-2.73.75-2.97-.12-.02-.31.03-.57.15-.94.44-2.07 1.09-3.4 1.95a78.8 78.8 0 0 0-3.23 2.19c-.86.62-2.02 1.48-3.48 2.58l-.66.51c-.88.66-1.51.99-1.9.99a.7.7 0 0 1-.5-.21.77.77 0 0 1-.18-.39c-.02-.14.1-.49.39-1.05a24.91 24.91 0 0 1 4.8-6.33c.22-.2.43-.19.63.03.2.2.19.4-.03.6a24.3 24.3 0 0 0-2.82 3.27 16.1 16.1 0 0 0-1.95 3.09c.26-.14.6-.37 1.02-.69l.69-.51c1.48-1.12 2.65-1.99 3.5-2.61.87-.64 1.97-1.39 3.3-2.25a27.15 27.15 0 0 1 3.46-1.98c.7-.3 1.2-.3 1.53 0 .16.14.26.32.3.54.04.2.02.49-.06.87a37.12 37.12 0 0 1-.78 2.52c-.64 1.8-.96 3-.96 3.6 0 .06 0 .12.03.18.22-.04.97-.45 2.25-1.23a88.98 88.98 0 0 1 10.32-5.43Z"/><path fill="#121212" d="M193.86 22.84c.28-.14.48-.07.6.21.14.26.07.45-.21.57a20.54 20.54 0 0 1-11.4 1.92c-.28.74-.8 1.48-1.56 2.22-.74.72-1.58 1.37-2.52 1.95-1.48.9-2.7 1.35-3.66 1.35-.46 0-.83-.1-1.11-.33-.54-.44-.63-1.18-.27-2.22.54-1.6 1.84-3.43 3.9-5.49 0-.2.06-.39.18-.57.22-.32.62-.53 1.2-.63a3.68 3.68 0 0 1 2.76.45c.34.2.61.45.81.75.22.3.37.63.45 1 .04.21.05.44.03.68l1.11.06c3.5.16 6.73-.48 9.69-1.92Zm-15.54 6.12c.82-.5 1.56-1.06 2.22-1.68a6.52 6.52 0 0 0 1.41-1.86c-1.86-.3-3.15-.83-3.87-1.59-1.86 1.9-3.03 3.56-3.51 4.98-.22.64-.22 1.05 0 1.23.14.2.58.21 1.32.03.72-.2 1.53-.57 2.43-1.11Zm3.87-4.38a.84.84 0 0 0-.03-.39 1.6 1.6 0 0 0-.27-.66 1.6 1.6 0 0 0-.45-.45c-.16-.1-.34-.18-.54-.24a2.3 2.3 0 0 0-.51-.15l-.36-.06c-.6-.04-1.07.04-1.41.24.04.1.05.2.03.3.56.64 1.74 1.11 3.54 1.41Z"/><path fill="#121212" d="M178.13 22.84c.28-.14.48-.07.6.21.14.26.07.45-.21.57a20.54 20.54 0 0 1-11.4 1.92c-.28.74-.8 1.48-1.56 2.22-.74.72-1.58 1.37-2.52 1.95-1.48.9-2.7 1.35-3.66 1.35-.46 0-.83-.1-1.11-.33-.54-.44-.63-1.18-.27-2.22.54-1.6 1.84-3.43 3.9-5.49 0-.2.06-.39.18-.57.22-.32.62-.53 1.2-.63a3.68 3.68 0 0 1 2.76.45c.34.2.6.45.8.75.23.3.38.63.46 1 .04.21.05.44.03.68l1.1.06c3.5.16 6.74-.48 9.7-1.92Zm-15.54 6.12c.82-.5 1.56-1.06 2.22-1.68a6.52 6.52 0 0 0 1.4-1.86c-1.85-.3-3.14-.83-3.86-1.59-1.86 1.9-3.03 3.56-3.51 4.98-.22.64-.22 1.05 0 1.23.14.2.58.21 1.32.03.72-.2 1.53-.57 2.43-1.11Zm3.87-4.38a.84.84 0 0 0-.03-.39 1.6 1.6 0 0 0-.27-.66 1.6 1.6 0 0 0-.45-.45c-.16-.1-.34-.18-.54-.24a2.3 2.3 0 0 0-.51-.15l-.36-.06c-.6-.04-1.07.04-1.41.24.04.1.05.2.03.3.56.64 1.74 1.11 3.54 1.41Z"/><path fill="#121212" d="M162.07 23.26a.4.4 0 0 1 .3.12c.1.08.15.18.15.3s-.04.23-.12.33a.54.54 0 0 1-.3.15c-3 .14-6.12.65-9.36 1.53-1.22 2.54-3.23 5.22-6.03 8.04a35.7 35.7 0 0 1-8.07 6.33c-2.66 1.46-4.7 2.19-6.15 2.19-.88 0-1.49-.28-1.83-.84-.6-.96-.17-2.43 1.3-4.4a26.53 26.53 0 0 1 6.26-5.77 42.62 42.62 0 0 1 6.24-3.45 52.35 52.35 0 0 1 7.62-2.82c.72-1.62.93-2.9.63-3.87-.58-1.74-2.88-2.33-6.9-1.77-1.46.18-3.77.77-6.93 1.77l-2.4.75c-.52.14-1.14.29-1.86.45a7.6 7.6 0 0 1-1.7.18 1.94 1.94 0 0 1-1.03-.36c-1.36-1.04-1.29-2.86.21-5.46a26.8 26.8 0 0 1 4.17-5.25 53.35 53.35 0 0 1 5.91-5.22 28.7 28.7 0 0 1 6.15-3.6c2.04-.86 3.61-1.06 4.71-.6 1.8.74 2.2 2.98 1.17 6.72a.36.36 0 0 1-.2.27.47.47 0 0 1-.34.06.48.48 0 0 1-.27-.21.46.46 0 0 1-.03-.36c.88-3.22.65-5.11-.69-5.67-.86-.36-2.19-.17-3.99.57a27.67 27.67 0 0 0-5.94 3.51 46.59 46.59 0 0 0-5.88 5.13 26.66 26.66 0 0 0-4.02 5.1c-1.24 2.14-1.38 3.57-.42 4.3.08.07.2.13.33.17.16.02.35.02.57 0l.63-.06a17.4 17.4 0 0 0 1.68-.39 32.71 32.71 0 0 0 1.95-.54l1.02-.33a47.3 47.3 0 0 1 7.08-1.83c4.5-.6 7.12.2 7.86 2.4.32 1.02.2 2.3-.39 3.84 3.08-.8 6.04-1.27 8.88-1.41h.03Zm-13.5 7.11a27.42 27.42 0 0 0 3-4.32c-5 1.5-9.28 3.48-12.84 5.94a31.62 31.62 0 0 0-3.69 2.91c-1.06 1-1.87 1.9-2.43 2.67a9.36 9.36 0 0 0-1.17 2.04c-.2.6-.2 1.05-.03 1.35.04.06.1.12.18.18.1.08.31.14.63.18.32.04.71.02 1.17-.06a8.7 8.7 0 0 0 1.95-.6c.86-.34 1.82-.8 2.88-1.38a30.54 30.54 0 0 0 5.28-3.78 40.92 40.92 0 0 0 5.07-5.13Zm-31.24-7.29c.04.12.03.24-.03.36a.36.36 0 0 1-.24.21 95.97 95.97 0 0 0-13.68 6.18 206.52 206.52 0 0 1-6.12 11.13 65.51 65.51 0 0 1-3.33 5.13 41.62 41.62 0 0 1-3.06 3.75c-.92 1-1.8 1.83-2.64 2.49-.86.66-1.62 1.13-2.28 1.41a4.6 4.6 0 0 1-1.77.42 2 2 0 0 1-1.77-.87 3 3 0 0 1-.51-1.59c-.08-1.44.5-3.3 1.74-5.58 1.22-2.28 3.17-4.72 5.85-7.32a62.87 62.87 0 0 1 13.23-9.6c1.44-2.86 2.28-4.76 2.52-5.7l-.66.48c-2.64 1.96-4.61 3.17-5.91 3.63-2.1.7-3.36.62-3.78-.24-.26-.5-.13-1.13.39-1.89a8.6 8.6 0 0 1 2.37-2.25c.62-.4 1.25-.74 1.89-1.02a7.83 7.83 0 0 1 1.89-.57c.62-.1 1.12-.03 1.5.21.48.3.67.8.57 1.5 0 .06-.02.12-.06.18a.47.47 0 0 1-.1.12.38.38 0 0 1-.14.09h-.18a.54.54 0 0 1-.3-.15.4.4 0 0 1-.1-.33c.05-.34-.01-.56-.17-.66-.32-.2-.91-.16-1.77.12-.84.26-1.7.68-2.55 1.26-.6.4-1.11.82-1.53 1.26-.42.44-.7.81-.81 1.11-.14.28-.18.49-.12.63.02.04.07.08.15.12.06.04.16.07.3.09.14.02.3.02.5 0s.46-.06.76-.12c.3-.08.63-.18.99-.3 1.22-.42 3.1-1.58 5.67-3.48.64-.48 1.03-.76 1.17-.84.32-.16.58-.13.78.09.08.08.13.16.15.24.04.06.05.22.03.48-.02.24-.1.55-.24.93a42.34 42.34 0 0 1-1.86 4.26 98.98 98.98 0 0 1 12.69-5.64c.28-.1.47-.01.57.27ZM96.48 40.51c2-3.36 3.84-6.65 5.52-9.87a60.7 60.7 0 0 0-11.91 8.79 30.9 30.9 0 0 0-5.61 6.99c-1.2 2.18-1.76 3.92-1.68 5.22.04.92.37 1.45.99 1.59.56.14 1.27-.01 2.13-.45a13.8 13.8 0 0 0 2.9-2.13 30.9 30.9 0 0 0 3.64-4.11 58.38 58.38 0 0 0 4.02-6.03Z"/><path fill="#121212" d="M98.27 22.81c.26-.1.45-.01.57.27.12.26.05.44-.2.54a88.88 88.88 0 0 0-10.24 5.37 23.08 23.08 0 0 1-2.19 1.26c-.4.16-.73.17-.99.03-.3-.18-.44-.51-.42-.99 0-.7.34-2 1.02-3.9.6-1.74.85-2.73.75-2.97-.12-.02-.3.03-.57.15-.94.44-2.07 1.09-3.39 1.95a78.8 78.8 0 0 0-3.24 2.19c-.86.62-2.02 1.48-3.48 2.58l-.66.51c-.88.66-1.5.99-1.89.99a.7.7 0 0 1-.5-.21.77.77 0 0 1-.19-.39c-.02-.14.11-.49.4-1.05a24.91 24.91 0 0 1 4.8-6.33c.21-.2.42-.19.62.03.2.2.2.4-.03.6a24.3 24.3 0 0 0-2.82 3.27 16.1 16.1 0 0 0-1.95 3.09c.26-.14.6-.37 1.02-.69l.7-.51c1.47-1.12 2.64-1.99 3.5-2.61.86-.64 1.96-1.39 3.3-2.25a27.15 27.15 0 0 1 3.45-1.98c.7-.3 1.21-.3 1.53 0 .16.14.26.32.3.54.04.2.02.49-.06.87a37.12 37.12 0 0 1-.78 2.52c-.64 1.8-.96 3-.96 3.6l.03.18c.22-.04.97-.45 2.25-1.23a88.98 88.98 0 0 1 10.32-5.43Z"/><path fill="#121212" d="M77.66 23.2c.12.04.2.12.24.24.04.12.03.24-.03.36a.36.36 0 0 1-.21.21c-.9.32-2.89 1.49-5.97 3.51a90.93 90.93 0 0 1-4.92 3.09c-1.1.6-1.95.9-2.55.9-.32 0-.58-.09-.78-.27a1 1 0 0 1-.24-.69c-.02-.28.06-.7.24-1.26.18-.58.57-1.37 1.17-2.37a61.13 61.13 0 0 1 2.34-3.72c.16-.24.36-.27.6-.09.26.16.3.36.12.6a57.84 57.84 0 0 0-1.92 3c-.52.88-.89 1.55-1.11 2.01-.22.46-.38.85-.48 1.17-.1.3-.14.49-.12.57 0 .08.01.13.03.15.24.18 1.04-.11 2.4-.87.92-.5 2.5-1.48 4.74-2.94 3.12-2.06 5.16-3.27 6.12-3.63a.38.38 0 0 1 .33.03Zm-8.1-4.59h-.06c-.02-.02-.06-.03-.12-.03a2.1 2.1 0 0 1-.54-.24 1.36 1.36 0 0 1-.24-.24.63.63 0 0 1-.09-.33.48.48 0 0 1 .15-.42c.24-.26.69-.39 1.35-.39.12 0 .22.05.3.15.1.08.15.18.15.3s-.05.23-.15.33a.4.4 0 0 1-.3.12h-.12c.1.1.14.22.12.36-.04.26-.19.39-.45.39Z"/><path fill="#121212" d="M67.87 22.75c.12.04.2.12.24.24.04.1.03.21-.03.33a.36.36 0 0 1-.24.21c-1.64.6-4.29 1.93-7.95 3.99a66.43 66.43 0 0 1-5.43 2.88c-.96.4-1.59.41-1.89.03-.26-.3-.26-.74 0-1.32.26-.58.81-1.54 1.65-2.88.5-.76.85-1.33 1.05-1.71.66-1.12.95-1.87.87-2.25-.16 0-.42.12-.78.36-1.4.88-3.55 2.59-6.45 5.13a38.52 38.52 0 0 1-3.15 2.58c-.5.34-.89.41-1.17.21a.97.97 0 0 1-.18-.81c.08-.32.3-.81.63-1.47 1.42-2.72 2.06-4.34 1.92-4.86-.18-.02-.49.1-.93.36a53.92 53.92 0 0 0-3.6 2.61c-.5.4-1.2.96-2.13 1.68l-.69.57c-2.08 1.66-3.44 2.49-4.08 2.49a.68.68 0 0 1-.54-.24c-.4-.5.16-1.72 1.68-3.66a25.36 25.36 0 0 1 4.14-4.32c.22-.18.42-.15.6.09.2.22.18.42-.06.6-1.22 1-2.48 2.3-3.78 3.9a12.52 12.52 0 0 0-1.77 2.67c.62-.26 1.7-1 3.24-2.22l.72-.57a75.02 75.02 0 0 1 5.58-4.2c1.04-.64 1.77-.79 2.2-.45.2.18.3.45.32.81.02.36-.14.97-.48 1.83-.32.84-.84 1.95-1.56 3.33a25 25 0 0 0-.39.81c.44-.3 1.41-1.1 2.91-2.4a57.62 57.62 0 0 1 6.54-5.22c.86-.56 1.5-.65 1.9-.27.51.52.27 1.64-.73 3.36-.22.38-.58.96-1.08 1.74-1.1 1.74-1.67 2.78-1.7 3.12.2-.02.47-.1.83-.24a72.2 72.2 0 0 0 5.37-2.85c3.68-2.08 6.37-3.42 8.07-4.02a.38.38 0 0 1 .33.03Z"/><path fill="#121212" d="M40.6 22.84c.29-.14.49-.07.6.21.15.26.08.45-.2.57a20.54 20.54 0 0 1-11.4 1.92c-.28.74-.8 1.48-1.56 2.22-.74.72-1.58 1.37-2.52 1.95-1.48.9-2.7 1.35-3.66 1.35-.46 0-.83-.1-1.11-.33-.54-.44-.63-1.18-.27-2.22.54-1.6 1.84-3.43 3.9-5.49 0-.2.06-.39.18-.57.22-.32.62-.53 1.2-.63a3.68 3.68 0 0 1 2.76.45c.34.2.6.45.8.75.23.3.38.63.46 1 .04.21.05.44.03.68l1.1.06c3.5.16 6.74-.48 9.7-1.92Zm-15.53 6.12c.82-.5 1.56-1.06 2.22-1.68a6.52 6.52 0 0 0 1.4-1.86c-1.85-.3-3.14-.83-3.86-1.59-1.86 1.9-3.03 3.56-3.51 4.98-.22.64-.22 1.05 0 1.23.14.2.58.21 1.32.03.72-.2 1.53-.57 2.43-1.11Zm3.87-4.38a.84.84 0 0 0-.03-.39 1.6 1.6 0 0 0-.27-.66 1.6 1.6 0 0 0-.45-.45c-.16-.1-.34-.18-.54-.24a2.3 2.3 0 0 0-.51-.15l-.36-.06c-.6-.04-1.07.04-1.41.24.04.1.05.2.03.3.56.64 1.74 1.11 3.54 1.41Z"/><path fill="#121212" d="M25.98 1.3c1.78.96 2.19 3.56 1.23 7.8a.48.48 0 0 1-.84.15.46.46 0 0 1-.03-.36c.86-3.78.6-6.05-.78-6.81-1.16-.62-2.94-.24-5.34 1.14a31.05 31.05 0 0 0-6.9 5.43 45.57 45.57 0 0 0-5.82 7.53 48.24 48.24 0 0 0-4.29 8.64 27.4 27.4 0 0 0-1.74 7.17c-.16 2.06.14 3.51.9 4.35.76.82 1.96.95 3.6.39 1.64-.54 3.63-1.73 5.97-3.57 1.26-.98 3.18-2.61 5.76-4.89 2.3-2 4.01-3.43 5.13-4.29 1.12-.86 1.98-1.34 2.58-1.44a.4.4 0 0 1 .33.09c.1.08.16.18.18.3a.4.4 0 0 1-.09.33.3.3 0 0 1-.27.15c-.28.04-.66.21-1.14.51-.48.3-1.11.77-1.89 1.41a121.53 121.53 0 0 0-4.23 3.63c-2.6 2.26-4.54 3.9-5.82 4.92-3.46 2.72-6.22 4.08-8.28 4.08a3.22 3.22 0 0 1-2.49-1.05c-.74-.8-1.14-2.03-1.2-3.69a19 19 0 0 1 .84-5.61c.62-2.1 1.45-4.29 2.49-6.57a49.61 49.61 0 0 1 8.85-12.99 33.43 33.43 0 0 1 4.71-4.14 18.11 18.11 0 0 1 4.8-2.55c1.58-.54 2.84-.56 3.78-.06Z"/></svg>';
		$sitename        = get_bloginfo( 'name' );

		$h1 = __( 'A new website is on the way!', 'wp-module-coming-soon' );

		if ( $sitename ) {
			$tagline = get_bloginfo( 'description' );
			$h1      = $sitename;
			if ( $tagline ) {
				$h1 = $h1 . ' — ' . $tagline;
			}
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );

		$logo_image = '';
		if ( $custom_logo_id ) {
			$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( $logo_url ) {
				$logo_image = '<img src="' . esc_url( $logo_url ) . '" alt="' . get_bloginfo( 'name' ) . ' Logo">';
			}
		}

		// setup args.
		$defaults   = array(
			'admin_screen_id'      => container()->plugin()->id,
			'admin_app_url'        => \admin_url( 'admin.php?page=newfold' ),
			'admin_notice_text'    => __( 'Your site has Coming Soon mode active.', 'wp-module-coming-soon' ),
			'template_page_title'  => __( 'Coming Soon!', 'wp-module-coming-soon' ),
			'template_styles'      => false,
			'template_content'     => false,
			'template_site_logo'   => $logo_image ? $logo_image : $coming_soon_svg,
			'template_h1'          => $h1,
			'template_login_btn'   => false,
			'template_p'           => __( 'Signup to be the first to know when we launch.', 'wp-module-coming-soon' ),
			'template_msg_success' => __( 'Thank you, please check your email to confirm your subscription.', 'wp-module-coming-soon' ),
			'template_msg_active'  => __( 'Your email address is already subscribed to this website.<br>Stay tuned to your inbox for our updates or try a different email address.', 'wp-module-coming-soon' ),
			'template_msg_invalid' => __( 'There was an error with your submission and you were not subscribed.<br>Please try again with a valid email address.', 'wp-module-coming-soon' ),
			'template_email_ph'    => __( 'Enter your email address', 'wp-module-coming-soon' ),
			'template_subscribe'   => __( 'Subscribe', 'wp-module-coming-soon' ),
		);
		$this->args = apply_filters( 'newfold/coming-soon/filter/args', wp_parse_args( $container->has( 'comingsoon' ) ? $container['comingsoon'] : array(), $defaults ), $defaults, $container );

		if ( false !== $this->args['template_styles'] && isset( $container['plugin'] ) ) {
			// add plugin version to plugin styles file for cache busting.
			$this->args['template_styles'] = $this->args['template_styles'] . '?v=' . container()->plugin()->version;
		}

		new WooCommerceOptionsSync();

		// set up all actions.
		\add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		\add_action( 'admin_enqueue_scripts', array( $this, 'add_portal_app_scripts' ) );
		\add_action( 'init', array( __CLASS__, 'load_text_domain' ), 0 );
		\add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
		\add_action( 'newfold/onboarding/completed', array( $this, 'handle_onboarding_completed' ) );
		\add_action( 'admin_notices', array( $this, 'notice_display' ) );
		\add_action( 'template_redirect', array( $this, 'maybe_load_template' ) );
		\add_action( 'wp_ajax_newfold_coming_soon_subscribe', array( $this, 'coming_soon_subscribe' ) );
		\add_action( 'wp_ajax_nopriv_newfold_coming_soon_subscribe', array( $this, 'coming_soon_subscribe' ) );
		\add_action( 'plugins_loaded', array( $this, 'coming_soon_prevent_emails' ) );
		\add_filter( 'default_option_nfd_coming_soon', array( $this, 'filter_coming_soon_fallback' ) );
		\add_action( 'update_option_nfd_coming_soon', array( $this, 'on_update_nfd_coming_soon' ), 10, 2 );
		\add_action( 'update_option_mm_coming_soon', array( $this, 'on_update_mm_coming_soon' ), 10, 2 );
		\add_filter( 'jetpack_is_under_construction_plugin', array( $this, 'filter_jetpack_is_under_construction' ) );

		new AdminBarSiteStatusBadge( $container );
		new SitePreviewWarning();
		new PrePublishModal();
	}

	/**
	 * When the coming soon state is updated, make sure we trigger actions and update the legacy option value.
	 *
	 * @param mixed $old_value Old option value.
	 * @param mixed $value     New option value.
	 *
	 * @return mixed
	 */
	public function on_update_nfd_coming_soon( $old_value, $value ) {

		// Ensure the value is a boolean.
		$value = wp_validate_boolean( $value );

		// Trigger any actions associated with the coming soon state.
		$this->conditionally_trigger_coming_soon_action_hooks( $value );

		// When the database value changes for the new value, make sure we update the legacy value.
		remove_filter( 'update_option_mm_coming_soon', array( $this, 'on_update_mm_coming_soon' ) );
		update_option( 'mm_coming_soon', $value );
		add_filter( 'update_option_mm_coming_soon', array( $this, 'on_update_mm_coming_soon' ), 10, 2 );

		return $value;
	}

	/**
	 * When the coming soon state is updated, make sure we trigger actions and update the new option value.
	 *
	 * @param mixed $old_value Old option value.
	 * @param mixed $value     New option value.
	 *
	 * @return mixed
	 */
	public function on_update_mm_coming_soon( $old_value, $value ) {

		// Ensure the value is a boolean.
		$value = wp_validate_boolean( $value );

		// Trigger any actions associated with the coming soon state.
		$this->conditionally_trigger_coming_soon_action_hooks( $value );

		// When the database value changes for the legacy value, make sure we update the new value.
		remove_filter( 'update_option_nfd_coming_soon', array( $this, 'on_update_nfd_coming_soon' ) );
		update_option( 'nfd_coming_soon', $value );
		add_filter( 'update_option_nfd_coming_soon', array( $this, 'on_update_nfd_coming_soon' ), 10, 2 );

		return $value;
	}

	/**
	 * Conditionally trigger coming soon actions.
	 *
	 * The data module only starts listening for events after the init hook.
	 *  - If the init hook has run, we trigger the action immediately.
	 *  - If the init hook has not run, we add a callback to the init hook to trigger the action.
	 *
	 * @param bool $is_enabled True if coming soon is enabled, false otherwise.
	 *
	 * @return void
	 */
	public function conditionally_trigger_coming_soon_action_hooks( bool $is_enabled ) {

		if ( ! did_action( 'init' ) ) {
			add_action(
				'init',
				function () use ( $is_enabled ) {
					$this->conditionally_trigger_coming_soon_action_hooks( $is_enabled );
				},
				99
			);

			return;
		}

		if ( $is_enabled ) {
			$this->trigger_enabled_action_hook();
		} else {
			$this->trigger_disabled_action_hook();
		}
	}

	/**
	 * Trigger the enabled action hook.
	 *
	 * @return void
	 */
	public function trigger_enabled_action_hook() {
		if ( ! did_action( 'newfold/coming-soon/enabled' ) ) {
			do_action( 'newfold/coming-soon/enabled' ); // phpcs:ignore
		}
	}

	/**
	 * Trigger the disabled action hook.
	 *
	 * @return void
	 */
	public function trigger_disabled_action_hook() {
		if ( ! did_action( 'newfold/coming-soon/disabled' ) ) {
			do_action( 'newfold/coming-soon/disabled' ); // phpcs:ignore
		}
	}

	/**
	 * If nfd_coming_soon is not defined, set it to the value of mm_coming_soon.
	 *
	 * @return bool
	 */
	public function filter_coming_soon_fallback() {
		return wp_validate_boolean( get_option( 'mm_coming_soon', false ) );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts() {
		$assets_dir = container()->plugin()->url . 'vendor/newfold-labs/wp-module-coming-soon/static/js/';

		wp_enqueue_script(
			'newfold-coming-soon-api',
			$assets_dir . 'coming-soon.js',
			array( 'wp-api-fetch', 'nfd-runtime', 'wp-i18n' ),
			container()->plugin()->version,
			true
		);

		self::load_js_translations(
			'newfold-coming-soon-api',
			'wp-module-coming-soon',
			NFD_COMING_SOON_DIR . '/languages'
		);
	}

	/**
	 * Enqueue the portal app scripts.
	 */
	public function add_portal_app_scripts() {
		$asset_file = NFD_COMING_SOON_BUILD_DIR . '/sitePreviewPortal/bundle.asset.php';
		$build_dir  = NFD_COMING_SOON_BUILD_URL . 'sitePreviewPortal/';

		if ( is_readable( $asset_file ) ) {

			$asset = include_once $asset_file;

			\wp_register_script(
				'nfd-coming-soon-portal',
				$build_dir . '/bundle.js',
				array_merge( $asset['dependencies'], array() ),
				$asset['version'],
				true
			);
			\wp_register_style(
				'nfd-coming-soon-portal-style',
				$build_dir . 'style-sitePreviewPortal.css',
				null, // still dependant on plugin styles but they are loaded on the plugin page
				$asset['version']
			);

			self::load_js_translations(
				'nfd-coming-soon-portal',
				'wp-module-coming-soon',
				NFD_COMING_SOON_DIR . '/languages'
			);

			$screen = \get_current_screen();
			if ( isset( $screen->id ) && false !== strpos( $screen->id, $this->container->plugin()->id ) ) {
				\wp_enqueue_script( 'nfd-coming-soon-portal' );
				\wp_enqueue_style( 'nfd-coming-soon-portal-style' );

				// Get coming soon site data
				$comingsoon_portal_data = array(
					'isComingSoon' => isComingSoonActive(),
					'viewUrl'      => home_url(),
					'editUrl'      => get_admin_url(
						null,
						wp_is_block_theme() ? 'site-editor.php?canvas=edit' : 'customize.php'
					),
					'previewUrl'   => home_url() . '/?preview=coming_soon',
				);

				\wp_localize_script(
					'nfd-coming-soon-portal',
					'NewfoldComingSoonPortal',
					$comingsoon_portal_data
				);
			}
		}
	}

	/**
	 * Register the coming soon route.
	 */
	public function rest_api_init() {
		new API\ComingSoon();
	}

	/**
	 * Handle the onboarding complete action.
	 * When the onboarding is complete, disable the coming soon page if the user has not opted in.
	 *
	 * @return void
	 */
	public function handle_onboarding_completed() {
		$coming_soon_service = container()->get( 'comingSoon' );

		$coming_soon_last_changed = $coming_soon_service->get_last_changed_timestamp();
		if ( ! $coming_soon_last_changed ) {
			$coming_soon_service->disable();
		}
	}

	/**
	 * Display coming soon notice.
	 */
	public function notice_display() {

		$screen = get_current_screen();

		$allowed_notice_html = array(
			// formatting.
			'strong' => array(),
			'em'     => array(),
			// and links.
			'a'      => array(
				'href'  => array(),
				'title' => array(),
			),
		);

		if (
			isComingSoonActive() && // coming soon is active.
			false === strpos( $screen->id, $this->args['admin_screen_id'] ) && // not on our app screen.
			current_user_can( 'manage_options' ) // current user can manage options.
		) {
			?>
			<div class='notice notice-warning'>
				<p><?php echo wp_kses( $this->args['admin_notice_text'], $allowed_notice_html ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Load the coming soon page, if necessary.
	 */
	public function maybe_load_template() {
		if ( ! is_user_logged_in() || ( isset( $_SERVER['QUERY_STRING'] ) && 'preview=coming_soon' === $_SERVER['QUERY_STRING'] ) ) {
			if ( isComingSoonActive() ) {
				self::coming_soon_content( $this->args );
				die();
			}
		}
	}

	/**
	 * Render the coming soon page.
	 *
	 * @param array $args The args from container and defaults to pass to the template.
	 */
	public static function coming_soon_content( $args ) {
		$coming_soon_template = __DIR__ . '/template/index.php';
		load_template( $coming_soon_template, true, $args );
	}

	/**
	 * Handle the AJAX subscribe action.
	 */
	public function coming_soon_subscribe() {

		$response   = array();
		$a_response = array();
		$email      = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'newfold_coming_soon_subscribe_nonce' ) ) {

			$a_response['message'] = __( 'Gotcha!', 'wp-module-coming-soon' );
			$a_response['status']  = 'nonce_failure';

		} elseif ( ! is_email( $email ) ) {

			$a_response['message'] = __( 'Please provide a valid email address', 'wp-module-coming-soon' );
			$a_response['status']  = 'invalid_email';

		} else {

			// Initialize JetPack_Subscriptions.
			$jetpack = \Jetpack_Subscriptions::init();

			// ensure jetpack subscribe is callable, bail if not.
			if ( ! is_callable( array( $jetpack, 'subscribe' ) ) ) {
				$a_response['message'] = __( 'Jetpack encountered an error with the subscription', 'wp-module-coming-soon' );
				$a_response['status']  = 'jetpack-error';
				wp_send_json( $a_response );
				exit;
			}

			// Get JetPack response and subscribe email if response is true.
			$response = $jetpack->subscribe(
				$email,
				0,
				false,
				// See Jetpack subscribe `extra_data` attribute.
				array(
					'server_data' => jetpack_subscriptions_cherry_pick_server_data(),
				)
			);

			if ( isset( $response[0]->errors ) ) {

				$error_text = array_keys( $response[0]->errors );
				$error_text = $error_text[0];

				$a_response['message'] = __( 'There was an error with the subscription', 'wp-module-coming-soon' );
				$a_response['status']  = $error_text;

			} else {

				$a_response['message'] = __( 'Subscription successful', 'wp-module-coming-soon' );
				$a_response['status']  = 'success';

			}
		}
		wp_send_json( $a_response );
		exit;
	}

	/**
	 * When the coming soon module is enabled, add a filter to override Jetpack to prevent emails from being sent.
	 */
	public function coming_soon_prevent_emails() {

		if ( isComingSoonActive() ) {
			add_filter(
				'jetpack_subscriptions_exclude_all_categories_except',
				__CLASS__ . '\\coming_soon_prevent_emails_return_array'
			);
		}
	}

	/**
	 * Prevent emails from being sent.
	 *
	 * @return string[]
	 * @see coming_soon_prevent_emails
	 */
	public function coming_soon_prevent_emails_return_array() {

		return array(
			'please-for-the-love-of-all-things-do-not-exist',
		);
	}

	/**
	 * Filter Jetpack's is_under_construction_plugin to return true if the coming soon module is active.
	 *
	 * @see https://github.com/Automattic/jetpack/blob/trunk/projects/plugins/jetpack/_inc/lib/class.core-rest-api-endpoints.php#L1149-L1184
	 *
	 * @param bool $value Current value.
	 *
	 * @return bool
	 */
	public function filter_jetpack_is_under_construction( $value ) {
		if ( isComingSoonActive() ) {
			return true;
		}

		return $value;
	}

	/**
	 * Load text domain for Module
	 *
	 * @return void
	 */
	public static function load_text_domain() {

		\load_plugin_textdomain(
			'wp-module-coming-soon',
			false,
			NFD_COMING_SOON_DIR . '/languages'
		);
	}

	/**
	 * Sets translated strings for a script.
	 *
	 * @global WP_Scripts $wp_scripts    The WP_Scripts object for printing scripts.
	 *
	 * @param string $script_handle Script handle the textdomain will be attached to.
	 * @param string $domain        Text domain. Default 'default'.
	 * @param string $languages_dir The full file path to the directory containing translation files.
	 * @return bool True if the text domain was successfully localized, false otherwise.
	 */
	public static function load_js_translations( $script_handle, $domain, $languages_dir ) {
		\add_filter(
			'load_script_translation_file',
			function ( $file, $handle, $domain ) use ( $script_handle, $languages_dir ) {
				global $wp_scripts;

				if ( $script_handle !== $handle ) {
					return $file;
				}

				$src = $wp_scripts->registered[ $handle ]->src ?? false;

				if ( ! $src ) {
					return $file;
				}

				$locale  = determine_locale();
				$baseurl = plugins_url( '/', $languages_dir );
				$hash    = md5( str_replace( $baseurl, '', $src ) );
				$file    = "{$languages_dir}/{$domain}-{$locale}-{$hash}.json";

				return $file;
			},
			10,
			3
		);

		return \wp_set_script_translations(
			$script_handle,
			$domain,
			$languages_dir
		);
	}
}
