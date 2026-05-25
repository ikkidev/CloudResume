<?php

namespace NewfoldLabs\WP\Module\NextSteps\Data\Plans;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;
use NewfoldLabs\WP\Module\NextSteps\RedirectHelper;

/**
 * BlogPlan - Defines the structured plan for blog/personal website setup
 *
 * This class provides a comprehensive step-by-step plan specifically designed for
 * bloggers and personal website owners. The plan is organized into three main tracks:
 * Build, Brand, and Grow, each containing multiple sections with actionable tasks.
 *
 * Plan Structure:
 * - Build Track: Basic setup, customization, content creation, navigation, and legal pages
 * - Brand Track: Audience building, social presence, and SEO optimization
 * - Grow Track: Enhanced user experience, advanced marketing, content strategy, and performance
 *
 * Each task includes:
 * - Unique identifier for tracking completion
 * - Localized title and description
 * - Direct links to WordPress admin areas or external resources
 * - Priority ordering for logical progression
 * - Status tracking (new, done, dismissed)
 * - Source attribution for analytics
 *
 * The plan is designed to guide users through the complete process of setting up
 * and growing a successful blog, from initial setup to advanced marketing strategies.
 *
 * @package NewfoldLabs\WP\Module\NextSteps\Data\Plans
 * @since 1.0.0
 * @author Newfold Labs
 */
class BlogPlan {

	/**
	 * Get default blog or personal plan
	 *
	 * @return Plan
	 */
	public static function get_plan() {
		return new Plan(
			array(
				'id'          => 'blog_setup',
				'type'        => 'blog',
				'label'       => __( 'Blog Setup', 'wp-module-next-steps' ),
				'description' => __( 'Get your blog up and running with these essential steps:', 'wp-module-next-steps' ),
				'tracks'      => array(
					array(
						'id'       => 'blog_build_track',
						'label'    => __( 'Build', 'wp-module-next-steps' ),
						'open'     => true,
						'sections' => array(
							array(
								'id'    => 'basic_blog_setup',
								'label' => __( 'Basic blog setup', 'wp-module-next-steps' ),
								'open'  => true,
								'tasks' => array(
									array(
										'id'              => 'blog_quick_setup',
										'title'           => __( 'Quick Setup', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/options-general.php',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-test-id' => 'blog_quick_setup',
											'data-nfd-id'  => 'blog_quick_start',
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'customize_blog',
								'label' => __( 'Customize your blog', 'wp-module-next-steps' ),
								'open'  => true,
								'tasks' => array(
									array(
										'id'              => 'blog_upload_logo',
										'title'           => __( 'Upload Logo', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=header&template=index',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_choose_colors_fonts',
										'title'           => __( 'Choose Colors and Fonts', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/site-editor.php?p=%2Fstyles',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_customize_header',
										'title'           => __( 'Customize Header', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=header&template=index',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_customize_footer',
										'title'           => __( 'Customize Footer', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=footer&template=index',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'create_content',
								'label' => __( 'Create content', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_first_post',
										'title'           => __( 'Add Your First Blog Post', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_about_page',
										'title'           => __( 'Create an "About" Page', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?post_type=page&wb-library=patterns&wb-category=features',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_set_featured_image',
										'title'           => __( 'Set a Featured Image for One Post', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post.php?post=1&action=edit',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'setup_navigation',
								'label' => __( 'Set up navigation', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_add_pages',
										'title'           => __( 'Add Pages for Home, Blog, About, Contact', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/edit.php?post_type=page',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_create_primary_menu',
										'title'           => __( 'Create a Primary Menu', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/site-editor.php?p=/navigation',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_create_footer_menu',
										'title'           => __( 'Create a Footer Menu', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/site-editor.php?p=%2Fpattern&postType=wp_template_part&categoryId=footer',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'setup_essential_pages',
								'label' => __( 'Set up essential pages', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_privacy_policy',
										'title'           => __( 'Add a Privacy Policy', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/options-privacy.php',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_terms_conditions',
										'title'           => __( 'Add Terms & Conditions', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?wb-library=patterns&wb-category=text',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_accessibility_statement',
										'title'           => __( 'Add an Accessibility Statement', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?wb-library=patterns&wb-category=text',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
						),
					),
					array(
						'id'       => 'blog_brand_track',
						'label'    => __( 'Brand', 'wp-module-next-steps' ),
						'sections' => array(
							array(
								'id'    => 'first_audience_building',
								'label' => __( 'First Audience-Building Steps', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_welcome_subscribe_popup',
										'title'           => __( 'Add a Welcome-Subscribe Popup', 'wp-module-next-steps' ),
										'description'     => __( 'Convert visitors to email subscribers.', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/improve-conversion-rate-website-pop-ups/',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_customize_notification_emails',
										'title'           => __( 'Customize Notification Emails', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=email-templates',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_connect_jetpack_stats',
										'title'           => __( 'Connect Jetpack Stats (or Google Analytics 4)', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=stats' ),
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'blog_promote_social',
								'label' => __( 'Social presence', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_connect_facebook',
										'title'           => __( 'Connect Facebook Page Auto-Sharing', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=jetpack-social' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_add_social_sharing',
										'title'           => __( 'Add Social-Sharing Buttons', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'site-editor.php?p=%2Fstyles&section=%2Fblocks%2Fjetpack%252Fsharing-buttons' ),
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_embed_social_feed',
										'title'           => __( 'Embed a Social Media Feed on Homepage', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-incorporate-a-social-media-marketing-strategy-with-your-wordpress-website/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
								),
							),
							array(
								'id'    => 'blog_promote_seo',
								'label' => __( 'SEO & visibility', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_optimize_seo',
										'title'           => __( 'Optimize On-Page SEO', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=yoast-seo',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_submit_search_console',
										'title'           => __( 'Submit Site to Google Search Console', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-submit-your-website-to-search-engines/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_generate_sitemap',
										'title'           => __( 'Generate & Submit XML Sitemap', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/what-is-a-sitemap-how-it-helps-seo-and-navigation/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
								),
							),
						),
					),
					array(
						'id'       => 'blog_grow_track',
						'label'    => __( 'Grow', 'wp-module-next-steps' ),
						'sections' => array(
							array(
								'id'    => 'enhance_reader_experience',
								'label' => __( 'Enhance reader experience', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_enable_comments',
										'title'           => __( 'Enable & Style Comments Section', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=akismet',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_customize_author_boxes',
										'title'           => __( 'Customize Author/Profile Boxes', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/site-editor.php?p=%2F&canvas=edit',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_display_testimonials',
										'title'           => __( 'Display Testimonials or Highlighted Comments', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/customer-testimonials/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_create_favicon',
										'title'           => __( 'Create a Favicon', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/customize.php?autofocus[section]=title_tagline',
										'status'          => 'new',
										'priority'        => 4,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'advanced_promotion_partnerships',
								'label' => __( 'Advanced social & influencer marketing', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_build_newsletter',
										'title'           => __( 'Build an Email Newsletter', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-create-an-email-newsletter/',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_draft_outreach_list',
										'title'           => __( 'Draft an Influencer/Guest-Post Outreach List', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/guest-blogging/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_run_first_ad',
										'title'           => __( 'Run pillar article promotion on social ad', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/social-media-advertising-tips/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_track_utm_campaigns',
										'title'           => __( 'Track Campaigns with UTM Links', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-create-a-content-calendar/',
										'status'          => 'new',
										'priority'        => 4,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
								),
							),
							array(
								'id'    => 'content_traffic_strategy',
								'label' => __( 'Content & traffic strategy', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_plan_content_series',
										'title'           => __( 'Plan a Content Series or Editorial Calendar', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-create-a-content-calendar/',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_implement_internal_linking',
										'title'           => __( 'Implement Internal-Linking Strategy', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/internal-linking-guide/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'blog_install_yoast_premium',
										'title'           => __( 'Install Yoast Premium for Advanced Schemas', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=yoast-seo',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'blog_performance_security',
								'label' => __( 'Performance & security', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_speed_up_site',
										'title'           => __( 'Speed-up Site with Jetpack Boost', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'my-jetpack#/add-boost' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_enable_auto_backups',
										'title'           => __( 'Enable Automatic Backups & Update Alerts', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=jetpack-backup' ),
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'blog_create_staging_site',
										'title'           => __( 'Create a Staging Site', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/what-is-a-staging-site-and-how-to-create-a-bluehost-staging-site-for-your-wordpress-website/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
								),
							),
							array(
								'id'    => 'blog_analytics',
								'label' => __( 'Blog analytics', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'blog_monitor_traffic',
										'title'           => __( 'Monitor Traffic & Engagement', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=stats' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
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
