<?php

namespace NewfoldLabs\WP\Module\NextSteps;

/**
 * Class for handling task completion triggers.
 *
 * Tasks that have smart or automatic completion are managed here.
 * Each task type has:
 * - Task path constants (TASK_PATHS) for single source of truth
 * - Registration method that sets up both hooks and validators
 * - Handler methods that respond to WordPress/plugin events
 * - Validation methods that check existing site state on plan initialization
 *
 * Architecture:
 * - Task paths are defined once in TASK_PATHS constant
 * - Registration methods combine hook and validator setup
 * - Handlers use mark_task_as_complete_by_path() with constants
 * - Validators reuse the same logic as handlers for consistency
 *
 * File Organization:
 * # Constructor & Setup
 * # Product Tasks (WooCommerce)
 * # Payment Tasks (WooCommerce)
 * # Blog Tasks (Content Creation)
 * # Gift Card Tasks
 * # Welcome Popup Tasks
 * # Logo Upload Tasks
 * # Jetpack Tasks (Performance & Stats)
 * # Yoast Tasks (SEO)
 * # Advanced Reviews Tasks
 * # Affiliates Tasks
 * # Email Templates Tasks
 * # Utility Methods
 */
class TaskCompletionTriggers {

	/**
	 * Task path constants for each task type for easy reference and reuse
	 */
	const TASK_PATHS = array(
		// Product tasks - new post (post type product) created
		'store_add_product'               => 'store_setup.store_build_track.setup_products.store_add_product',

		// Payment tasks - any payment method configured
		'store_setup_payments'            => 'store_setup.store_build_track.setup_payments_shipping.store_setup_payments',

		// Blog tasks - new blog post created
		'blog_first_post'                 => 'blog_setup.blog_build_track.create_content.blog_first_post',

		// Jetpack tasks
		// Boost - Jetpack connected and boost module activated
		'store_improve_performance'       => 'store_setup.store_build_track.store_improve_performance.store_improve_performance',
		'blog_speed_up_site'              => 'blog_setup.blog_grow_track.blog_performance_security.blog_speed_up_site',
		'corporate_install_jetpack_boost' => 'corporate_setup.corporate_grow_track.site_performance_security.corporate_install_jetpack_boost',
		// Stats - jetpack connected and stats module activated
		'blog_connect_jetpack_stats'      => 'blog_setup.blog_brand_track.first_audience_building.blog_connect_jetpack_stats',
		'corporate_setup_jetpack_stats'   => 'corporate_setup.corporate_brand_track.launch_marketing_tools.corporate_setup_jetpack_stats',

		// Yoast tasks - plugin installed
		'store_setup_yoast_premium'       => 'store_setup.store_build_track.next_marketing_steps.store_setup_yoast_premium',
		'blog_install_yoast_premium'      => 'blog_setup.blog_grow_track.content_traffic_strategy.blog_install_yoast_premium',

		// Advanced Reviews tasks - plugin installed
		'store_collect_reviews'           => 'store_setup.store_build_track.store_collect_reviews.store_collect_reviews_task',

		// Affiliate program tasks - plugin installed
		'store_setup_affiliate_program'   => 'store_setup.store_build_track.advanced_social_marketing.store_launch_affiliate',

		// Welcome discount popup - discount campaign created
		'store_marketing_welcome_popup'   => 'store_setup.store_build_track.first_marketing_steps.store_marketing_welcome_popup',
		// Gift card tasks - discount product type post created
		'store_create_gift_card'          => 'store_setup.store_build_track.first_marketing_steps.store_create_gift_card',
		// Email templates - plugin installed
		'store_customize_emails'          => 'store_setup.store_build_track.first_marketing_steps.store_customize_emails',

		// Logo upload - site logo set
		'store_upload_logo'               => 'store_setup.store_build_track.customize_your_store.store_upload_logo',
		'blog_upload_logo'                => 'blog_setup.blog_build_track.customize_blog.blog_upload_logo',
		'corporate_upload_logo'           => 'corporate_setup.corporate_build_track.customize_website.corporate_upload_logo',
	);

	/**
	 * Map of plan_id prefixes to plan types
	 */
	const PLAN_ID_TO_TYPE_MAP = array(
		'store_setup'     => 'ecommerce',
		'blog_setup'      => 'blog',
		'corporate_setup' => 'corporate',
	);

	// ========================================
	// # Constructor & Setup
	// ========================================

	/**
	 * Init the Task Completion Triggers
	 *
	 * @param Container $container the container
	 */
	public function __construct( $container ) {
		// Register all hooks and validators
		$this->register_product_hooks_and_validators();
		$this->register_payment_hooks_and_validators();
		$this->register_blog_hooks_and_validators();
		$this->register_gift_card_hooks_and_validators();
		$this->register_welcome_popup_hooks_and_validators();
		$this->register_logo_hooks_and_validators();
		$this->register_jetpack_hooks_and_validators();
		$this->register_yoast_hooks_and_validators();
		$this->register_advanced_reviews_hooks_and_validators();
		$this->register_affiliates_hooks_and_validators();
		$this->register_email_templates_hooks_and_validators();
	}

	/**
	 * Register hooks and validators for product-related tasks
	 *
	 * @return void
	 */
	private function register_product_hooks_and_validators(): void {
		// Product creation via REST API
		\add_action( 'woocommerce_rest_insert_product_object', array( __CLASS__, 'on_product_creation' ), 10, 3 );
		// Product creation via post publish (covers admin interface and other methods)
		\add_action( 'publish_product', array( __CLASS__, 'on_product_published' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_add_product'],
			array( __CLASS__, 'validate_product_creation_state' )
		);
	}

	/**
	 * Register hooks and validators for payment-related tasks
	 *
	 * @return void
	 */
	private function register_payment_hooks_and_validators(): void {

		// Payment method configuration - hook into payment gateway settings updates
		if ( class_exists( '\WC_Payment_Gateways' ) ) {
			$gateways = \WC_Payment_Gateways::instance()->get_payment_gateway_ids();

			foreach ( $gateways as $gateway_id ) {
				add_action( "update_option_woocommerce_{$gateway_id}_settings", array( __CLASS__, 'on_payment_gateway_updated' ) );
			}
		}

		// Also hook into individual payment gateway updates for better coverage
		\add_action( 'init', array( __CLASS__, 'register_payment_gateway_hooks' ), 20 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_setup_payments'],
			array( __CLASS__, 'validate_payment_setup_state' )
		);
	}

	/**
	 * Check if any payment gateways are available
	 *
	 * @return bool True if at least one payment gateway is available, false otherwise
	 */
	private static function has_enabled_payment_gateways(): bool {
		// Check if WooCommerce is active and loaded
		if ( ! function_exists( 'WC' ) || ! WC() ) {
			return false;
		}

		// Check if payment gateways are available
		// This filters for enabled AND properly configured payment methods
		// So a user must enable and configure the payment method to complete the task
		$payment_gateways = WC()->payment_gateways();
		if ( ! $payment_gateways ) {
			return false;
		}

		// Get available payment gateways
		$available_gateways = $payment_gateways->get_available_payment_gateways();

		// If any gateways are available, payment setup is complete
		return ! empty( $available_gateways );
	}


	/**
	 * Register hooks and validators for blog-related tasks
	 *
	 * @return void
	 */
	private function register_blog_hooks_and_validators(): void {
		// Blog post creation
		\add_action( 'publish_post', array( __CLASS__, 'on_blog_post_published' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['blog_first_post'],
			array( __CLASS__, 'validate_blog_post_creation_state' )
		);
	}


	/**
	 * Register hooks and validators for gift card-related tasks
	 *
	 * @return void
	 */
	private function register_gift_card_hooks_and_validators(): void {
		// Gift card creation (custom post type)
		\add_action( 'publish_post', array( __CLASS__, 'on_gift_card_published' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_create_gift_card'],
			array( __CLASS__, 'validate_gift_card_creation_state' )
		);
	}

	/**
	 * Register hooks and validators for welcome popup-related tasks
	 *
	 * @return void
	 */
	private function register_welcome_popup_hooks_and_validators(): void {
		// Welcome popup creation (YITH campaign custom post type)
		\add_action( 'publish_post', array( __CLASS__, 'on_welcome_popup_published' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_marketing_welcome_popup'],
			array( __CLASS__, 'validate_welcome_popup_creation_state' )
		);
	}

	/**
	 * Register hooks and validators for logo upload-related tasks
	 *
	 * @return void
	 */
	private function register_logo_hooks_and_validators(): void {
		// Logo upload via Customizer (theme_mod changes)
		\add_action( 'customize_save_after', array( __CLASS__, 'on_logo_updated' ), 10 );

		// Logo upload via Site Editor (site_logo option changes)
		\add_action( 'update_option_site_logo', array( __CLASS__, 'on_logo_updated' ), 10 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_upload_logo'],
			array( __CLASS__, 'validate_logo_upload_state' )
		);
	}

	/**
	 * Register hooks and validators for Jetpack-related tasks
	 *
	 * @return void
	 */
	private function register_jetpack_hooks_and_validators(): void {
		// Jetpack connection and Jetpack Boost activation
		\add_action( 'jetpack_site_registered', array( __CLASS__, 'on_jetpack_connected' ), 10 );
		\add_action( 'activated_plugin', array( __CLASS__, 'on_jetpack_boost_activation' ), 10, 2 );
		\add_action( 'jetpack_activate_module', array( __CLASS__, 'on_jetpack_module_activated' ), 10, 1 );
		\add_action( 'jetpack_activate_module_boost', array( __CLASS__, 'on_jetpack_boost_activated' ), 10, 1 );

		// Store plan validators
		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_improve_performance'],
			array( __CLASS__, 'validate_jetpack_performance_state' )
		);

		// Blog plan validators
		TaskStateValidator::register_validator(
			self::TASK_PATHS['blog_speed_up_site'],
			array( __CLASS__, 'validate_jetpack_performance_state' )
		);
		TaskStateValidator::register_validator(
			self::TASK_PATHS['blog_connect_jetpack_stats'],
			array( __CLASS__, 'validate_jetpack_stats_state' )
		);

		// Corporate plan validators
		TaskStateValidator::register_validator(
			self::TASK_PATHS['corporate_install_jetpack_boost'],
			array( __CLASS__, 'validate_jetpack_performance_state' )
		);
		TaskStateValidator::register_validator(
			self::TASK_PATHS['corporate_setup_jetpack_stats'],
			array( __CLASS__, 'validate_jetpack_stats_state' )
		);
	}

	/**
	 * Register hooks and validators for Yoast-related tasks
	 *
	 * @return void
	 */
	private function register_yoast_hooks_and_validators(): void {
		// Yoast SEO Premium activation
		\add_action( 'activated_plugin', array( __CLASS__, 'on_yoast_premium_activation' ), 10, 2 );

		// Store plan validators
		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_setup_yoast_premium'],
			array( __CLASS__, 'validate_yoast_premium_state' )
		);

		// Blog plan validators
		TaskStateValidator::register_validator(
			self::TASK_PATHS['blog_install_yoast_premium'],
			array( __CLASS__, 'validate_yoast_premium_state' )
		);
	}

	/**
	 * Register hooks and validators for Advanced Reviews-related tasks
	 *
	 * @return void
	 */
	private function register_advanced_reviews_hooks_and_validators(): void {
		// Advanced Reviews plugin activation
		\add_action( 'activated_plugin', array( __CLASS__, 'on_advanced_reviews_activation' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_collect_reviews'],
			array( __CLASS__, 'validate_advanced_reviews_state' )
		);
	}

	/**
	 * Register hooks and validators for Affiliates-related tasks
	 *
	 * @return void
	 */
	private function register_affiliates_hooks_and_validators(): void {
		// YITH WooCommerce Affiliates plugin activation
		\add_action( 'activated_plugin', array( __CLASS__, 'on_affiliates_activation' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_setup_affiliate_program'],
			array( __CLASS__, 'validate_affiliates_state' )
		);
	}

	/**
	 * Register hooks and validators for Email Templates-related tasks
	 *
	 * @return void
	 */
	private function register_email_templates_hooks_and_validators(): void {
		// Email Templates plugin activation
		\add_action( 'activated_plugin', array( __CLASS__, 'on_email_templates_activation' ), 10, 2 );

		TaskStateValidator::register_validator(
			self::TASK_PATHS['store_customize_emails'],
			array( __CLASS__, 'validate_email_templates_state' )
		);
	}


	// ========================================
	// # Product Tasks (WooCommerce)
	// ========================================

	/**
	 * Handle product creation via REST API
	 *
	 * @param object $product  The product object
	 * @param object $request  The request object
	 * @param bool   $creating Whether the product is being created
	 * @return void
	 */
	public static function on_product_creation( $product, $request, $creating ): void {
		// Check if WooCommerce is active and loaded
		if ( ! function_exists( 'WC' ) || ! WC() ) {
			return;
		}

		if ( $creating ) {
			// Mark the "Add Products" section and task as complete
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_add_product'] );
		}
	}

	/**
	 * Validate if products already exist
	 *
	 * @return bool True if products are already created
	 */
	public static function validate_product_creation_state(): bool {
		// Check if WooCommerce is active first
		if ( ! function_exists( 'WC' ) || ! WC() ) {
			return false;
		}

		// Check if any published products exist
		$products = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		return ! empty( $products );
	}

	/**
	 * Register hooks for individual payment gateway updates
	 *
	 * This dynamically registers hooks for all available payment gateways
	 *
	 * @return void
	 */
	public static function register_payment_gateway_hooks() {
		// Check if WooCommerce is active and loaded
		if ( ! function_exists( 'WC' ) || ! WC() ) {
			return;
		}

		// Get all available payment gateways
		$payment_gateways = WC()->payment_gateways();
		if ( ! $payment_gateways ) {
			return;
		}

		$available_gateways = $payment_gateways->payment_gateways();

		// Register hooks for each individual payment gateway
		foreach ( $available_gateways as $gateway_id => $gateway ) {
			$hook_name = "woocommerce_update_options_payment_gateways_{$gateway_id}";
			\add_action( $hook_name, array( __CLASS__, 'on_payment_gateway_updated' ), 10 );
		}
	}

	// ========================================
	// # Payment Tasks (WooCommerce)
	// ========================================

	/**
	 * Validate if payment setup is already complete
	 *
	 * @return bool True if payment gateways are already configured
	 */
	public static function validate_payment_setup_state(): bool {
		return self::has_enabled_payment_gateways();
	}

	/**
	 * Handle product published via post publish hook
	 *
	 * This covers product creation through the WordPress admin interface and other methods
	 * that don't go through the REST API
	 *
	 * @param int     $post_id The post ID
	 * @param WP_Post $post    The post object
	 * @return void
	 */
	public static function on_product_published( $post_id, $post ): void {
		// Only proceed if this is a new product (not an update)
		if ( 'product' === $post->post_type && 'publish' === $post->post_status ) {
			// Mark the "Add Products" section and task as complete
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_add_product'] );
		}
	}

	/**
	 * Handle payment gateway settings updated
	 *
	 * This triggers when any payment gateway settings are updated
	 *
	 * @return void
	 */
	public static function on_payment_gateway_updated(): void {
		// Check if WooCommerce is active and loaded
		if ( ! function_exists( 'WC' ) || ! WC() ) {
			return;
		}

		// Check if any payment gateways are enabled or manual payments are configured
		if ( self::validate_payment_setup_state() ) {
			// Mark the "Setup Payments" task as complete
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_setup_payments'] );
		}
	}

	// ========================================
	// # Blog Tasks (Content Creation)
	// ========================================

	/**
	 * Handle blog post published
	 *
	 * This triggers when a blog post is published
	 *
	 * @param int     $post_id The post ID
	 * @param WP_Post $post    The post object
	 * @return void
	 */
	public static function on_blog_post_published( $post_id, $post ): void {
		// Only proceed if this is a published post (not an update from draft)
		if ( 'post' === $post->post_type && 'publish' === $post->post_status ) {
			// Skip the default "Hello World" post
			if ( self::is_hello_world_post( $post ) ) {
				return;
			}

			// Mark the "Add Your First Blog Post" task as complete
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_first_post'] );
		}
	}

	/**
	 * Validate if a blog post has already been created
	 *
	 * @return bool True if a non-Hello World blog post exists
	 */
	public static function validate_blog_post_creation_state(): bool {
		// Get minimal data and check count first
		$posts = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 2, // Get max 2 posts
				'fields'         => 'ids', // Only get IDs for performance
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		// No posts at all
		if ( empty( $posts ) ) {
			return false;
		}

		// If we have 2+ posts, at least one must be real (not Hello World)
		if ( count( $posts ) > 1 ) {
			return true;
		}

		// If we have exactly 1 post, check if it's Hello World
		$post = get_post( $posts[0] );
		return $post && ! self::is_hello_world_post( $post );
	}



	/**
	 * Check if a post is the default "Hello World" post
	 *
	 * For now this is limited to the default post in English.
	 * We may need to expand this to other languages in the future.
	 *
	 * @param WP_Post $post The post object
	 * @return bool True if this is the default Hello World post
	 */
	private static function is_hello_world_post( $post ): bool {
		// Check post title
		if ( false !== stripos( $post->post_title, 'Hello world!' ) ) {
			return true;
		}
		// Check post slug
		if ( 'hello-world' === $post->post_name ) {
			return true;
		}
		return false;
	}

	// ========================================
	// # Gift Card Tasks
	// ========================================

	/**
	 * Handle gift card published
	 *
	 * This triggers when a gift card (custom post type) is published
	 *
	 * @param int     $post_id The post ID
	 * @param WP_Post $post    The post object
	 * @return void
	 */
	public static function on_gift_card_published( $post_id, $post ): void {
		// Only proceed if this is a published gift card post
		if ( 'bh_gift_card' === $post->post_type && 'publish' === $post->post_status ) {
			// Mark the "Create Gift Card" task as complete
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_create_gift_card'] );
		}
	}

	/**
	 * Validate if a gift card has already been created
	 *
	 * @return bool True if a gift card post exists
	 */
	public static function validate_gift_card_creation_state(): bool {
		// Check if any published gift card posts exist
		$gift_cards = get_posts(
			array(
				'post_type'      => 'bh_gift_card',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		return ! empty( $gift_cards );
	}

	// ========================================
	// # Welcome Popup Tasks
	// ========================================

	/**
	 * Handle welcome popup published
	 *
	 * This triggers when a YITH campaign (custom post type) is published
	 *
	 * @param int     $post_id The post ID
	 * @param WP_Post $post    The post object
	 * @return void
	 */
	public static function on_welcome_popup_published( $post_id, $post ): void {
		// Only proceed if this is a published YITH campaign post
		if ( 'yith_campaign' === $post->post_type && 'publish' === $post->post_status ) {
			// Mark the "Create Welcome Popup" task as complete
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_marketing_welcome_popup'] );
		}
	}

	/**
	 * Validate if a welcome popup has already been created
	 *
	 * @return bool True if a YITH campaign post exists
	 */
	public static function validate_welcome_popup_creation_state(): bool {
		// Check if any published YITH campaign posts exist
		$campaigns = get_posts(
			array(
				'post_type'      => 'yith_campaign',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);

		return ! empty( $campaigns );
	}

	// ========================================
	// # Logo Upload Tasks
	// ========================================

	/**
	 * Handle logo upload/update
	 *
	 * This triggers when a site logo is set via Customizer or Site Editor
	 *
	 * @return void
	 */
	public static function on_logo_updated(): void {
		// Check custom_logo theme mod (classic themes, customizer)
		$custom_logo = get_theme_mod( 'custom_logo' );

		// Check site_logo option (block themes, site editor)
		$site_logo = get_option( 'site_logo' );

		// Check if logo exists in either location
		$has_logo = ! empty( $custom_logo ) || ! empty( $site_logo );

		// For block themes, also check if site-logo block exists in header template
		if ( ! $has_logo ) {
			$has_logo = self::check_template_for_logo();
		}

		if ( ! $has_logo ) {
			return;
		}

		// Mark the "Upload Logo" task as complete for the current plan
		// Each plan has its own logo task path
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_upload_logo'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_upload_logo'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_upload_logo'] );
	}

	/**
	 * Check if the template for the logo exists
	 *
	 * @return bool True if the template for the logo exists
	 */
	public static function check_template_for_logo(): bool {
		// For block themes, also check if site-logo block exists in header template
		if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
			$header_template = get_block_template( get_stylesheet() . '//header', 'wp_template_part' );
			if ( $header_template && ! empty( $header_template->content ) ) {
				return strpos( $header_template->content, 'wp:site-logo' ) !== false;
			}
		}

		return false;
	}

	/**
	 * Validate if a logo has already been uploaded
	 *
	 * @return bool True if a site logo is set
	 */
	public static function validate_logo_upload_state(): bool {
		// Check custom_logo theme mod (classic themes, customizer)
		$custom_logo = get_theme_mod( 'custom_logo' );
		if ( ! empty( $custom_logo ) ) {
			return true;
		}

		// Check site_logo option (block themes, site editor)
		$site_logo = get_option( 'site_logo' );
		if ( ! empty( $site_logo ) ) {
			return true;
		}

		// For block themes, check if site-logo block exists in header template
		return self::check_template_for_logo();
	}

	// ========================================
	// # Jetpack Tasks (Performance & Stats)
	// ========================================

	/**
	 * Handle Jetpack connection
	 *
	 * This triggers when Jetpack is successfully connected to WordPress.com
	 *
	 * @return void
	 */
	public static function on_jetpack_connected(): void {
		// Check if Jetpack Boost is active - if so, mark performance tasks
		if ( self::is_jetpack_performance_ready() ) {
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_improve_performance'] );
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_speed_up_site'] );
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_install_jetpack_boost'] );
		}

		// Check if Stats module is active - if so, mark stats tasks
		if ( self::is_jetpack_stats_ready() ) {
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_connect_jetpack_stats'] );
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_setup_jetpack_stats'] );
		}
	}

	/**
	 * Handle Jetpack Boost activation via plugin activation hook
	 *
	 * @param string $plugin       The plugin name
	 * @param bool   $network_wide Whether the plugin is being activated on the network
	 * @return void
	 */
	public static function on_jetpack_boost_activation( $plugin, $network_wide ): void {
		// Check if this is Jetpack Boost being activated
		if ( 'jetpack-boost/jetpack-boost.php' !== $plugin ) {
			return;
		}

		// Check if both Jetpack is connected AND Jetpack Boost is now active
		if ( ! self::is_jetpack_performance_ready() ) {
			return;
		}

		// Mark Jetpack Boost performance tasks as complete
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_improve_performance'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_speed_up_site'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_install_jetpack_boost'] );
	}

	/**
	 * Handle Jetpack module activation
	 *
	 * This triggers when any Jetpack module is activated, including Boost and Stats modules
	 *
	 * @param string $module The module name that was activated
	 * @return void
	 */
	public static function on_jetpack_module_activated( $module ): void {
		// Check if this is a Boost-related module or performance module
		$boost_modules = array(
			'boost',
			'photon',
			'photon-cdn',
			'lazy-images',
			'minify',
		);

		// Handle Boost module activation
		if ( in_array( $module, $boost_modules, true ) && self::is_jetpack_performance_ready() ) {
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_improve_performance'] );
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_speed_up_site'] );
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_install_jetpack_boost'] );
		}

		// Handle Stats module activation
		if ( 'stats' === $module && self::is_jetpack_stats_ready() ) {
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_connect_jetpack_stats'] );
			self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_setup_jetpack_stats'] );
		}
	}

	/**
	 * Handle Jetpack boost activation
	 *
	 * This triggers when Jetpack Boost is activated
	 *
	 * @return void
	 */
	public static function on_jetpack_boost_activated(): void {
		// Check if both Jetpack is connected AND Jetpack Boost is active
		if ( ! self::is_jetpack_performance_ready() ) {
			return;
		}

		// Mark Jetpack Boost performance tasks as complete
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_improve_performance'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_speed_up_site'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['corporate_install_jetpack_boost'] );
	}

	/**
	 * Validate if Jetpack performance setup is already complete
	 *
	 * @return bool True if Jetpack is connected and Boost is active
	 */
	public static function validate_jetpack_performance_state(): bool {
		return self::is_jetpack_performance_ready();
	}

	/**
	 * Validate if Jetpack Stats is already connected
	 *
	 * @return bool True if Jetpack is connected and Stats module is active
	 */
	public static function validate_jetpack_stats_state(): bool {
		return self::is_jetpack_stats_ready();
	}

	/**
	 * Check if Jetpack is connected
	 *
	 * Checks multiple methods to determine if Jetpack has an active connection
	 *
	 * @return bool True if Jetpack is connected, false otherwise
	 */
	private static function is_jetpack_connected(): bool {
		// Check if Jetpack class exists
		if ( ! class_exists( 'Jetpack' ) ) {
			return false;
		}

		// Primary method: is_connection_ready()
		if ( method_exists( 'Jetpack', 'is_connection_ready' ) ) {
			return \Jetpack::is_connection_ready();
		}

		// Fallback: jetpack_is_connected() function
		if ( function_exists( 'jetpack_is_connected' ) ) {
			return jetpack_is_connected();
		}

		// Final fallback: check if Jetpack has connection data
		if ( class_exists( 'Jetpack_Options' ) && method_exists( 'Jetpack_Options', 'get_option' ) ) {
			return ! empty( \Jetpack_Options::get_option( 'id' ) );
		}

		return false;
	}

	/**
	 * Check if Jetpack performance setup is ready
	 *
	 * Validates that both Jetpack is connected and Jetpack Boost is active
	 *
	 * @return bool True if both conditions are met, false otherwise
	 */
	private static function is_jetpack_performance_ready(): bool {
		// Check if Jetpack is connected
		if ( ! self::is_jetpack_connected() ) {
			return false;
		}

		// Check if Jetpack Boost is active
		return is_plugin_active( 'jetpack-boost/jetpack-boost.php' ) || class_exists( 'Automattic\Jetpack_Boost\Jetpack_Boost' );
	}

	/**
	 * Check if Jetpack Stats setup is ready
	 *
	 * Validates that both Jetpack is connected and Stats module is active
	 *
	 * @return bool True if both conditions are met, false otherwise
	 */
	private static function is_jetpack_stats_ready(): bool {
		// Check if Jetpack is connected
		if ( ! self::is_jetpack_connected() ) {
			return false;
		}

		// Check if Stats module is active (it's usually active by default when connected)
		if ( method_exists( 'Jetpack', 'is_module_active' ) ) {
			return \Jetpack::is_module_active( 'stats' );
		}

		// If we can't check module status but Jetpack is connected, assume stats is available
		return true;
	}

	// ========================================
	// # Yoast Tasks (SEO)
	// ========================================

	/**
	 * Handle Yoast SEO Premium activation
	 *
	 * @param string $plugin       The plugin name
	 * @param bool   $network_wide Whether the plugin is being activated on the network
	 * @return void
	 */
	public static function on_yoast_premium_activation( $plugin, $network_wide ): void {
		// Check if this is Yoast SEO Premium being activated
		$yoast_premium_plugins = array(
			'wordpress-seo-premium/wp-seo-premium.php',
			'yoast-seo-premium/wp-seo-premium.php',
			// 'wordpress-seo/wp-seo.php', // to test with free version
		);

		if ( ! in_array( $plugin, $yoast_premium_plugins, true ) ) {
			return;
		}

		// Handle different plan types using the helper method
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_setup_yoast_premium'] );
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['blog_install_yoast_premium'] );

		// Note: Corporate plan doesn't have a specific Yoast Premium task
		// but has general SEO tasks that could be marked complete
	}

	/**
	 * Validate if Yoast SEO Premium is already active
	 *
	 * @return bool True if Yoast SEO Premium is already active
	 */
	public static function validate_yoast_premium_state(): bool {
		$yoast_premium_plugins = array(
			'wordpress-seo-premium/wp-seo-premium.php',
			'yoast-seo-premium/wp-seo-premium.php',
			// 'wordpress-seo/wp-seo.php', // to test with free version
		);

		foreach ( $yoast_premium_plugins as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				return true;
			}
		}

		return false;
	}

	// ========================================
	// # Advanced Reviews Tasks
	// ========================================

	/**
	 * Handle Advanced Reviews plugin activation
	 *
	 * @param string $plugin       The plugin name
	 * @param bool   $network_wide Whether the plugin is being activated on the network
	 * @return void
	 */
	public static function on_advanced_reviews_activation( $plugin, $network_wide ): void {
		// Check if this is Advanced Reviews being activated
		if ( 'wp-plugin-advanced-reviews/wp-plugin-advanced-reviews.php' !== $plugin ) {
			return;
		}

		// Mark complete for store/ecommerce plan
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_collect_reviews'] );
	}

	/**
	 * Validate if Advanced Reviews plugin is already active
	 *
	 * @return bool True if Advanced Reviews is already active
	 */
	public static function validate_advanced_reviews_state(): bool {
		return is_plugin_active( 'wp-plugin-advanced-reviews/wp-plugin-advanced-reviews.php' );
	}

	// ========================================
	// # Affiliates Tasks
	// ========================================

	/**
	 * Handle YITH WooCommerce Affiliates plugin activation
	 *
	 * @param string $plugin       The plugin name
	 * @param bool   $network_wide Whether the plugin is being activated on the network
	 * @return void
	 */
	public static function on_affiliates_activation( $plugin, $network_wide ): void {
		// Check if this is YITH WooCommerce Affiliates being activated
		if ( 'yith-woocommerce-affiliates/init.php' !== $plugin ) {
			return;
		}

		// Mark complete for store/ecommerce plan
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_setup_affiliate_program'] );
	}

	/**
	 * Validate if YITH WooCommerce Affiliates plugin is already active
	 *
	 * @return bool True if Affiliates plugin is already active
	 */
	public static function validate_affiliates_state(): bool {
		return is_plugin_active( 'yith-woocommerce-affiliates/init.php' );
	}

	// ========================================
	// # Email Templates Tasks
	// ========================================

	/**
	 * Handle Email Templates plugin activation
	 *
	 * @param string $plugin       The plugin name
	 * @param bool   $network_wide Whether the plugin is being activated on the network
	 * @return void
	 */
	public static function on_email_templates_activation( $plugin, $network_wide ): void {
		// Check if this is Email Templates being activated
		if ( 'wp-plugin-email-templates/wp-plugin-email-templates.php' !== $plugin ) {
			return;
		}

		// Mark complete for store/ecommerce plan
		self::mark_task_complete_if_plan_matches( self::TASK_PATHS['store_customize_emails'] );
	}

	/**
	 * Validate if Email Templates plugin is already active
	 *
	 * @return bool True if Email Templates is already active
	 */
	public static function validate_email_templates_state(): bool {
		return is_plugin_active( 'wp-plugin-email-templates/wp-plugin-email-templates.php' );
	}

	// ========================================
	// # Utility Methods
	// ========================================

	/**
	 * Helper method to mark a task as complete using task path
	 *
	 * @param string $task_path The full task path (plan_id.track_id.section_id.task_id)
	 * @return bool True if the task was marked as complete, false otherwise
	 */
	public static function mark_task_as_complete_by_path( string $task_path ): bool {
		$parts = explode( '.', $task_path );
		if ( count( $parts ) !== 4 ) {
			return false;
		}

		list( $plan_id, $track_id, $section_id, $task_id ) = $parts;
		return self::mark_task_as_complete( $track_id, $section_id, $task_id );
	}

	/**
	 * Helper method to mark a task as complete for hooks to use
	 *
	 * This method will mark a task as complete and save the plan
	 * If the section has multiple tasks, it will mark the task as complete
	 * If the section has one tasks, it will mark the section as complete
	 *
	 * @param string $track_id   The track id
	 * @param string $section_id The section id
	 * @param string $task_id    The task id
	 * @return bool True if the task was marked as complete, false otherwise
	 */
	public static function mark_task_as_complete( $track_id, $section_id, $task_id ): bool {
		$current_plan = PlanRepository::get_current_plan(); // Plan object
		if ( ! $current_plan ) {
			return false;
		}

		// validate the track section and task exist - optimized single call
		$validtask = $current_plan->has_exact_task( $track_id, $section_id, $task_id );
		if ( ! $validtask ) {
			return false;
		}

		// see if section has more tasks, if not, just mark section as complete
		$section = $current_plan->get_section( $track_id, $section_id );
		if ( $section && count( $section->tasks ) === 1 ) {
			$current_plan->update_section_status( $track_id, $section_id, 'done' );
		} else {
			// otherwise mark task as complete
			$current_plan->update_task_status( $track_id, $section_id, $task_id, 'done' );
		}

		// save the plan
		PlanRepository::save_plan( $current_plan );
		return true;
	}

	/**
	 * Check if the current plan matches the expected plan type for a task path
	 *
	 * This extracts the plan_id from the task path and compares it to the current plan type.
	 *
	 * @param string $task_path The full task path (plan_id.track_id.section_id.task_id)
	 * @return bool True if current plan matches the task path's plan type
	 */
	private static function is_current_plan_for_task( string $task_path ): bool {
		$current_plan = PlanRepository::get_current_plan();
		if ( ! $current_plan ) {
			return false;
		}

		// Extract plan_id from task path (first segment)
		$path_parts = explode( '.', $task_path );
		if ( empty( $path_parts[0] ) ) {
			return false;
		}

		$plan_id = $path_parts[0];

		// Map plan_id to plan type
		if ( ! isset( self::PLAN_ID_TO_TYPE_MAP[ $plan_id ] ) ) {
			return false;
		}

		$expected_plan_type = self::PLAN_ID_TO_TYPE_MAP[ $plan_id ];

		return $current_plan->type === $expected_plan_type;
	}

	/**
	 * Get the plan type for a given task path
	 *
	 * @param string $task_path The full task path (plan_id.track_id.section_id.task_id)
	 * @return string|null The plan type ('ecommerce', 'blog', 'corporate') or null if invalid
	 */
	private static function get_plan_type_from_task_path( string $task_path ): ?string {
		// Extract plan_id from task path (first segment)
		$path_parts = explode( '.', $task_path );
		if ( empty( $path_parts[0] ) ) {
			return null;
		}

		$plan_id = $path_parts[0];

		// Map plan_id to plan type
		return self::PLAN_ID_TO_TYPE_MAP[ $plan_id ] ?? null;
	}

	/**
	 * Mark task complete if current plan matches the task path's plan type
	 *
	 * This is a convenience method that combines plan type checking with task completion.
	 *
	 * @param string $task_path The full task path (plan_id.track_id.section_id.task_id)
	 * @return bool True if task was marked complete, false otherwise
	 */
	private static function mark_task_complete_if_plan_matches( string $task_path ): bool {
		if ( ! self::is_current_plan_for_task( $task_path ) ) {
			return false;
		}

		return self::mark_task_as_complete_by_path( $task_path );
	}
}
