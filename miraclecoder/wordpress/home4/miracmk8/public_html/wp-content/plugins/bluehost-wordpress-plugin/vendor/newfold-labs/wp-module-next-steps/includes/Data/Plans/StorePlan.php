<?php

namespace NewfoldLabs\WP\Module\NextSteps\Data\Plans;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;
use NewfoldLabs\WP\Module\NextSteps\RedirectHelper;

/**
 * StorePlan - Defines the structured plan for ecommerce store setup
 *
 * This class provides a comprehensive step-by-step plan specifically designed for
 * ecommerce store owners using WooCommerce. The plan focuses on building a complete
 * online store from initial setup to advanced marketing and performance optimization.
 *
 * @package NewfoldLabs\WP\Module\NextSteps\Data\Plans
 * @since 1.0.0
 * @author Newfold Labs
 */
class StorePlan {

	/**
	 * Get default store or ecommerce plan
	 *
	 * @return Plan
	 */
	public static function get_plan() {
		return new Plan(
			array(
				'id'          => 'store_setup',
				'type'        => 'ecommerce',
				'label'       => __( 'Store Setup', 'wp-module-next-steps' ),
				'description' => __( 'Complete your ecommerce store setup with these essential steps:', 'wp-module-next-steps' ),
				'tracks'      => array(
					array( // track
						'id'       => 'store_build_track',
						'label'    => __( 'Next Steps for your store', 'wp-module-next-steps' ),
						'open'     => true,
						'sections' => array(
							array( // section
								'id'          => 'customize_your_store',
								'label'       => __( 'Step 1: Build your store', 'wp-module-next-steps' ),
								'description' => __( 'Congrats — you’ve built your store! Now it’s time to make it yours. Customize the header, footer, and homepage to give your site a personal touch.', 'wp-module-next-steps' ),
								'cta'         => __( 'Customize Store', 'wp-module-next-steps' ),
								'status'      => 'new',
								'icon'        => 'paint-brush',
								'modal_title' => __( 'Customize your Store', 'wp-module-next-steps' ),
								'modal_desc'  => __( 'Customize the header, footer, and homepage — just few steps to give your site a personal touch.', 'wp-module-next-steps' ),
								'mandatory'   => true,
								'open'        => true,
								'tasks'       => array(
									array( // task
										'id'              => 'store_upload_logo',
										'title'           => __( 'Upload Logo', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=header&template=index',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array( // task
										'id'              => 'store_choose_colors_fonts',
										'title'           => __( 'Choose Colors and Fonts', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/site-editor.php?p=%2Fstyles',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array( // task
										'id'              => 'store_customize_header',
										'title'           => __( 'Customize Header', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=header&template=index',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array( // task
										'id'              => 'store_customize_footer',
										'title'           => __( 'Customize Footer', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=footer&template=index',
										'status'          => 'new',
										'priority'        => 4,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array( // task
										'id'              => 'store_customize_homepage',
										'title'           => __( 'Customize Homepage', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&template=home',
										'status'          => 'new',
										'priority'        => 5,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
								),
							),
							array( // section
								'id'                => 'setup_products',
								'label'             => __( 'Step 2: Add your first product', 'wp-module-next-steps' ),
								'description'       => __( 'Start bringing your store to life by adding a product in just a few simple steps.', 'wp-module-next-steps' ),
								'cta'               => __( 'Add product', 'wp-module-next-steps' ),
								'icon'              => 'archive-box',
								'status'            => 'new',
								'complete_on_event' => 'nfd-submit-quick-add-product-success',
								'mandatory'         => true,
								'tasks'             => array(
									array( // task
										'id'              => 'store_add_product',
										'title'           => __( 'Add product', 'wp-module-next-steps' ),
										'description'     => __( 'Start bringing your store to life by adding a product in just a few simple steps.', 'wp-module-next-steps' ),
										'status'          => 'new',
										'priority'        => 1,
										'href'            => '#quick-add-product',
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-quick-add-product-trigger' => 'true',
											'data-nfd-prevent-default'       => 'true',
											'data-nfd-complete-on-click'     => 'false',
										),
									),
								),
							),
							array( // section
								'id'          => 'setup_payments_shipping',
								'label'       => __( 'Step 3: Set up payments', 'wp-module-next-steps' ),
								'description' => __( 'Set up payments to start selling — choose your preferred payment methods and connect them in just a few clicks.', 'wp-module-next-steps' ),
								'cta'         => __( 'Set up Payments', 'wp-module-next-steps' ),
								'icon'        => 'credit-card',
								'status'      => 'new',
								'mandatory'   => true,
								'tasks'       => array(
									array( // task
										'id'              => 'store_setup_payments',
										'title'           => __( 'Set up payments', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=woocommerce&r=' . rawurlencode( 'admin.php?page=wc-settings&tab=checkout' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array( // section
								'id'          => 'store_customize',
								'label'       => __( 'Setup the shopping experience', 'wp-module-next-steps' ),
								'description' => __( 'Personalize your cart and checkout to match your brand and give customers a seamless shopping experience', 'wp-module-next-steps' ),
								'cta'         => __( 'Start Now', 'wp-module-next-steps' ),
								'icon'        => 'shopping-cart',
								'modal_title' => __( 'Setup the shopping experience', 'wp-module-next-steps' ),
								'modal_desc'  => __( 'Personalize your cart and checkout experience, and configure taxes and shipping options', 'wp-module-next-steps' ),
								'tasks'       => array(
									array( // task
										'id'              => 'store_customize_shop_page',
										'title'           => __( 'Customize the shop page', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&template=archive-product',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array( // task
										'id'              => 'store_customize_cart_page',
										'title'           => __( 'Customize the cart page', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&template=page-cart',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array( // task
										'id'              => 'store_customize_checkout_page',
										'title'           => __( 'Customize the checkout flow', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&template=page-checkout',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
								),
							),
							array( // section
								'id'          => 'first_marketing_steps',
								'label'       => __( 'Build your marketing strategy', 'wp-module-next-steps' ),
								'description' => __( 'Kickstart your store’s success with smart marketing strategies. Just few steps to build visibility, attract shoppers, and turn visits into loyal sales.', 'wp-module-next-steps' ),
								'cta'         => __( 'Start now', 'wp-module-next-steps' ),
								'icon'        => 'rocket-launch',
								'modal_title' => __( 'Build your marketing strategy', 'wp-module-next-steps' ),
								'modal_desc'  => __( 'Kickstart your store’s success with smart marketing strategies', 'wp-module-next-steps' ),
								'tasks'       => array(
									array( // task
										'id'              => 'store_marketing_welcome_popup',
										'title'           => __( 'Configure a welcome discount popup', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=wondercart',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array( // task
										'id'              => 'store_create_gift_card',
										'title'           => __( 'Create a gift card to sell in your shop', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=gift-cards',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array( // task
										'id'              => 'store_customize_emails',
										'title'           => __( 'Customize your store emails', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=email-templates',
										'status'          => 'new',
										'priority'        => 4,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array( // section
								'id'          => 'store_improve_performance',
								'label'       => __( 'Improve the performance and speed of your shop', 'wp-module-next-steps' ),
								'description' => __( 'Speed up your store by optimizing page performance with Jetpack Boost. Easily activate one-click optimizations to boost your Core Web Vitals.', 'wp-module-next-steps' ),
								'cta'         => __( 'Start now', 'wp-module-next-steps' ),
								'icon'        => 'jetpack',
								'status'      => 'new',
								'tasks'       => array(
									array( // task
										'id'              => 'store_improve_performance',
										'title'           => __( 'Improve the performance and speed of your shop', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array( // section
								'id'          => 'store_collect_reviews',
								'label'       => __( 'Collect and show reviews for your products', 'wp-module-next-steps' ),
								'description' => __( 'Collect authentic reviews from your customers to build trust, highlight product quality, and help increase conversions.', 'wp-module-next-steps' ),
								'href'        => '',
								'cta'         => __( 'Start now', 'wp-module-next-steps' ),
								'icon'        => 'start',
								'tasks'       => array(
									array( // task
										'id'              => 'store_collect_reviews_task',
										'title'           => __( 'Collect and Show Reviews for Your Products', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=advanced-reviews',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array( // section
								'id'          => 'advanced_social_marketing',
								'label'       => __( 'Launch an affiliate program', 'wp-module-next-steps' ),
								'description' => __( 'Launch your own affiliate program to promote your products, reach new audiences and grow sales.', 'wp-module-next-steps' ),
								'status'      => 'new',
								'cta'         => __( 'Start now', 'wp-module-next-steps' ),
								'icon'        => 'users',
								'tasks'       => array(
									array( // task
										'id'              => 'store_launch_affiliate',
										'title'           => __( 'Launch an affiliate program', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=affiliates',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array( // section
								'id'          => 'next_marketing_steps',
								'label'       => __( 'Setup Yoast Premium to drive traffic to your store', 'wp-module-next-steps' ),
								'description' => __( 'Optimize your content for search engines to improve rankings, attract more visitors, and boost your store’s visibility online.', 'wp-module-next-steps' ),
								'status'      => 'new',
								'cta'         => __( 'Start now', 'wp-module-next-steps' ),
								'icon'        => 'yoast',
								'tasks'       => array(
									array( // task
										'id'              => 'store_setup_yoast_premium',
										'title'           => __( 'Setup Yoast Premium to drive traffic to your store', 'wp-module-next-steps' ),
										'description'     => '',
										'href'            => 'https://yoast.com/?yst-add-to-cart=2811749&utm_source=plugin-home&utm_medium=brand-plugin&channelid=P99C100S1N0B3003A151D115E0000V112',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-action' => 'load-nfd-ctb',
											'data-ctb-id' => '57d6a568-783c-45e2-a388-847cff155897',
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
						),
					),
				),
			)
		);
	}
}
