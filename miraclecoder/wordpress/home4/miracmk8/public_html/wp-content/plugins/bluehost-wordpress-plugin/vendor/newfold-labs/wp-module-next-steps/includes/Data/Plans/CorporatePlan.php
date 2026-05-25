<?php

namespace NewfoldLabs\WP\Module\NextSteps\Data\Plans;

use NewfoldLabs\WP\Module\NextSteps\DTOs\Plan;
use NewfoldLabs\WP\Module\NextSteps\RedirectHelper;

/**
 * CorporatePlan - Defines the structured plan for corporate/business website setup
 *
 * This class provides a comprehensive step-by-step plan specifically designed for
 * corporate and business website owners. The plan focuses on establishing a professional
 * online presence, building brand credibility, and implementing business-focused features.
 *
 * Plan Structure:
 * The plan is organized into three main tracks:
 * - Build Track: Basic setup, website customization, navigation configuration, and legal compliance
 * - Brand Track: Brand establishment, marketing tools, and contact/engagement setup
 * - Grow Track: Online presence strengthening, content strategy, marketing automation, and performance monitoring
 *
 * Build Track Sections:
 * - Basic site setup and configuration
 * - Website customization (logo, branding, layout)
 * - Navigation structure and menu creation
 * - Legal and trust content (privacy policy, terms, accessibility)
 *
 * Brand Track Sections:
 * - Brand establishment (domain, favicon, Google Business, branded email)
 * - Marketing tools (Search Console, SEO plugin, social sharing)
 * - Contact and engagement (forms, maps, social profiles)
 *
 * Grow Track Sections:
 * - Online presence (testimonials, certifications, awards)
 * - Content and SEO (blog posts, FAQ, keyword optimization, sitemaps)
 * - Marketing and lead generation (email capture, CRM integration, CTAs)
 * - Performance and security (security plugins, staging sites)
 * - Monitoring and improvement (speed tests, content planning)
 *
 * Each task includes:
 * - Unique identifier for tracking completion
 * - Localized title and description
 * - Direct links to WordPress admin areas or external resources
 * - Priority ordering for logical progression
 * - Status tracking (new, done, dismissed)
 * - Source attribution for analytics
 *
 * The plan is designed to guide business owners through establishing a professional
 * corporate website that builds trust, generates leads, and supports business growth.
 *
 * @package NewfoldLabs\WP\Module\NextSteps\Data\Plans
 * @since 1.0.0
 * @author Newfold Labs
 */
class CorporatePlan {

	/**
	 * Get default corporate or business plan
	 *
	 * @return Plan
	 */
	public static function get_plan() {
		return new Plan(
			array(
				'id'          => 'corporate_setup',
				'type'        => 'corporate',
				'label'       => __( 'Corporate setup', 'wp-module-next-steps' ),
				'description' => __( 'Set up your corporate website with these essential steps:', 'wp-module-next-steps' ),
				'tracks'      => array(
					array(
						'id'       => 'corporate_build_track',
						'label'    => __( 'Build', 'wp-module-next-steps' ),
						'open'     => true,
						'sections' => array(
							array(
								'id'    => 'basic_site_setup',
								'label' => __( 'Basic site setup', 'wp-module-next-steps' ),
								'open'  => true,
								'tasks' => array(
									array(
										'id'              => 'corporate_quick_setup',
										'title'           => __( 'Quick Setup', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/options-general.php',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-test-id' => 'corporate_quick_setup',
											'data-nfd-id'  => 'corporate_quick_start',
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'customize_website',
								'label' => __( 'Customize your website', 'wp-module-next-steps' ),
								'open'  => true,
								'tasks' => array(
									array(
										'id'              => 'corporate_upload_logo',
										'title'           => __( 'Upload Company Logo', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=header&template=index',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_choose_brand_colors',
										'title'           => __( 'Choose Brand Colors and Fonts', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/site-editor.php?p=%2Fstyles',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_customize_header',
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
										'id'              => 'corporate_customize_footer',
										'title'           => __( 'Customize Footer', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&area=footer&template=index',
										'status'          => 'new',
										'priority'        => 4,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_customize_homepage',
										'title'           => __( 'Customize Homepage Layout', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=template&template=home',
										'status'          => 'new',
										'priority'        => 5,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'configure_navigation',
								'label' => __( 'Configure navigation', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_add_navigation_pages',
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
										'id'              => 'corporate_create_primary_menu',
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
										'id'              => 'corporate_add_footer_menu',
										'title'           => __( 'Add a Footer Menu', 'wp-module-next-steps' ),
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
								'id'    => 'add_legal_trust_content',
								'label' => __( 'Add legal & trust content', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_privacy_policy',
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
										'id'              => 'corporate_terms_conditions',
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
										'id'              => 'corporate_accessibility_statement',
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
						'id'       => 'corporate_brand_track',
						'label'    => __( 'Brand', 'wp-module-next-steps' ),
						'sections' => array(
							array(
								'id'    => 'establish_brand_online',
								'label' => __( 'Establish your brand online', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_setup_custom_domain',
										'title'           => __( 'Set Up a Custom Domain', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/my-account/domain-center-update/list',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_create_favicon',
										'title'           => __( 'Create a favicon', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/customize.php?autofocus[section]=title_tagline',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_connect_google_business',
										'title'           => __( 'Connect Your Google Business Profile', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/transfer-google-business-profile-free-website-bluehost/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_create_branded_email',
										'title'           => __( 'Create a Branded Email Address', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-create-a-business-email-for-free/',
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
								'id'    => 'launch_marketing_tools',
								'label' => __( 'Launch essential marketing tools', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_setup_jetpack_stats',
										'title'           => __( 'Set Up Jetpack Stats', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=stats' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_connect_search_console',
										'title'           => __( 'Connect Google Search Console', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-submit-your-website-to-search-engines/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_install_seo_plugin',
										'title'           => __( 'Explore SEO Plugin', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=wpseo_dashboard',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_add_social_sharing',
										'title'           => __( 'Add Social Sharing Settings', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=jetpack-social' ),
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
								'id'    => 'setup_contact_engagement',
								'label' => __( 'Set up contact & engagement', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_add_contact_form',
										'title'           => __( 'Add a Contact Form with email routing', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/create-contact-form-wordpress-guide/',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_embed_map',
										'title'           => __( 'Embed a Map or Location', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/top-wordpress-store-locator-plugins/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_link_social_profiles',
										'title'           => __( 'Link to Social Media Profiles', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/site-editor.php',
										'status'          => 'new',
										'priority'        => 4,
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
						'id'       => 'corporate_grow_track',
						'label'    => __( 'Grow', 'wp-module-next-steps' ),
						'sections' => array(
							array(
								'id'    => 'strengthen_online_presence',
								'label' => __( 'Strengthen online presence', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_add_client_testimonials',
										'title'           => __( 'Add Client Logos or Testimonials or Reviews', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?post_type=page',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_add_certifications',
										'title'           => __( 'Add Certifications, Memberships, or Awards', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?post_type=page',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
								),
							),
							array(
								'id'    => 'build_content_seo_trust',
								'label' => __( 'Build content for SEO & trust', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_publish_first_blog_post',
										'title'           => __( 'Publish Your First Company Blog Post', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?wb-library=patterns&wb-category=text',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_create_faq_page',
										'title'           => __( 'Create a FAQ Page', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/post-new.php?post_type=page&wb-library=patterns&wb-category=features',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_optimize_key_pages',
										'title'           => __( 'Optimize Your Key Pages for Keywords', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/content-optimization-guide/',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_generate_submit_sitemap',
										'title'           => __( 'Generate and Submit XML Sitemap', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/what-is-a-sitemap-how-it-helps-seo-and-navigation/',
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
								'id'    => 'marketing_lead_generation',
								'label' => __( 'Marketing & lead generation', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_setup_email_capture',
										'title'           => __( 'Set Up an Email Capture Form', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/how-to-add-an-email-opt-in-form-to-your-website/',
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_connect_crm',
										'title'           => __( 'Connect to CRM or Email Tool', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/marketing-automation-tools/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_add_cta_section',
										'title'           => __( 'Add a Call-to-Action Section to Homepage', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/call-to-action-tips/',
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
								'id'    => 'site_performance_security',
								'label' => __( 'Site performance & security', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_install_jetpack_boost',
										'title'           => __( 'Install Jetpack Boost or Caching Plugin', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'my-jetpack#/add-boost' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_enable_auto_backups',
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
										'id'              => 'corporate_install_security_plugin',
										'title'           => __( 'Install a Security Plugin', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=bluehost#/marketplace/security',
										'status'          => 'new',
										'priority'        => 3,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_setup_staging_site',
										'title'           => __( 'Set Up a Staging Site', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=nfd-staging',
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
								'id'    => 'monitor_improve',
								'label' => __( 'Monitor & improve', 'wp-module-next-steps' ),
								'tasks' => array(
									array(
										'id'              => 'corporate_review_traffic_engagement',
										'title'           => __( 'Review Traffic & Engagement', 'wp-module-next-steps' ),
										'href'            => '{siteUrl}/wp-admin/admin.php?page=redirect-check&type=plugin&p=jetpack&r=' . rawurlencode( 'admin.php?page=stats' ),
										'status'          => 'new',
										'priority'        => 1,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'false',
										),
									),
									array(
										'id'              => 'corporate_run_speed_test',
										'title'           => __( 'Run a Speed Test', 'wp-module-next-steps' ),
										'href'            => 'https://www.bluehost.com/blog/what-is-my-page-speed/',
										'status'          => 'new',
										'priority'        => 2,
										'source'          => 'wp-module-next-steps',
										'data_attributes' => array(
											'data-nfd-complete-on-click' => 'true',
										),
									),
									array(
										'id'              => 'corporate_plan_next_content',
										'title'           => __( 'Plan Your Next Content or Campaign Update', 'wp-module-next-steps' ),
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
						),
					),
				),
			)
		);
	}
}
