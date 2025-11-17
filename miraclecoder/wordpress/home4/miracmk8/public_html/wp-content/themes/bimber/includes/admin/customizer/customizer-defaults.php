<?php
/**
 * Default options for WP Customizer
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

$bimber_customizer_defaults = array(

	// ---
	// Site Identity.
	// ---

	'branding_show_tagline'                  => true,
	'branding_logo'                          => '',
	'branding_logo_width'                    => '',
	'branding_logo_height'                   => '',
	'branding_logo_hdpi'                     => '',
	'branding_logo_inverted'                 => '',
	'branding_logo_inverted_hdpi'            => '',
	'branding_logo_small'                    => '',
	'branding_logo_small_width'              => '',
	'branding_logo_small_height'             => '',
	'branding_logo_small_hdpi'               => '',
	'branding_logo_small_inverted'           => '',
	'branding_logo_small_inverted_hdpi'      => '',
	'footer_text'                            => '',
	'footer_stamp'                           => '',
	'footer_stamp_width'                     => '',
	'footer_stamp_height'                    => '',
	'footer_stamp_hdpi'                      => '',
	'footer_stamp_label'                     => '',
	'footer_stamp_label_hide'                => false,
	'footer_stamp_url'                       => '',

	// ---
	// Home > Featured Entries.
	// ---

	'home_featured_entries'                  => 'none',
	'home_featured_entries_template'         => '2of3-3v-3v-boxed',
	'home_featured_entries_gutter'           => 'none',
	'home_featured_entries_title'            => '',
	'home_featured_entries_title_hide'       => false,
	'home_featured_entries_category'         => '',
	'home_featured_entries_tag'              => array( '' ),
	'home_featured_entries_time_range'       => 'all',
	'home_featured_entries_hide_elements'    => 'call_to_action',
	'home_featured_entries_call_to_action_hide_buttons'       => '',

	// ---
	// Home > Before Main Collection.
	// ---

	//'podcast_above_collection'                 => false,
	'newsletter_before_collection'              => false,
	'patreon_above_collection'                 => false,
	'instagram_above_collection'               => false,
	'links_above_collection'                   => false,
	'promoted_products_above_collection'       => false,
	'promoted_product_above_collection'        => false,

	//'above_collection_podcast_order'           => 10,
	'above_collection_newsletter_order'        => 15,
	'above_collection_patreon_order'           => 20,
	'above_collection_instagram_order'         => 25,
	'above_collection_links_order'             => 30,
	'above_collection_promoted_products_order' => 35,
	'above_collection_promoted_product_order'  => 40,

	// ---
	// Home > Main Collection.
	// ---

	'home_template'                          => 'grid-sidebar',
	'home_sidebar_location'                  => 'standard',
	'home_title'                             => '',
	'home_title_hide'                        => false,
	'home_inject_embeds'              		 => 'standard',
	'home_highlight_items'                   => 'standard',
	'home_highlight_items_offset'            => 3,
	'home_highlight_items_repeat'            => 5,
	'home_pagination'                        => 'infinite-scroll-on-demand',
	'home_hide_elements'                     => 'subtitle,call_to_action',
	'home_call_to_action_hide_buttons'       => '',
	'home_main_collection_excluded_categories'         => '',
	'home_newsletter'                        => 'standard',
	'home_newsletter_after_post'             => 2,
	'home_newsletter_repeat'                 => 12,
	'home_product'                           => 'none',
	'home_product_after_post'                => 4,
	'home_product_repeat'                    => 12,
	'home_product_category'                  => '',
	'home_ad'                                => 'standard',
	'home_ad_after_post'                     => 6,
	'home_ad_repeat'                         => 12,

	// ---
	// Posts > Global.
	// ---

	'posts_top_in_menu' 					 => 'separate',
	'posts_popular_enable'                   => true,
	'posts_hot_enable'                   	 => true,
	'posts_trending_enable'                  => true,
	'posts_lists_ordered_by'                 => 'views',
	'posts_top_page'                         => '',
	'posts_latest_page'                      => true,
	'posts_hot_page'                         => '',
	'posts_popular_page'                     => '',
	'posts_trending_page'                    => '',
	'posts_excerpt_length'                   => 55,
	'posts_views_threshold'                  => 10,
	'posts_fake_view_count_base'			 => '',
	'posts_fake_view_disable_for_new'	     => 'standard',
	'posts_comments_threshold'               => 1,
	'posts_dates'                            => 'publication',
	'posts_timeago'                          => 'standard',
	'posts_auto_play_videos'                  => false,
	'posts_use_gif_player'                  => true,
	'posts_page_waypoints'                  => true,
	'posts_set_target_blank'                  => true,
	'posts_auto_play_videos'                  => false,

	// Posts > Single.

	'post_template'                          => 'classic',
	'post_sidebar_location'                  => 'standard',
	'post_dates'                             => 'publication',
	'post_hide_elements'                     => '',
	'post_sharebar'                          => 'standard',
	'post_flyin_nav'                         => true,
	'post_native_comments_label'             => '',
	'post_pagination_overview'               => 'page_links',
	'post_pagination_adjacent_label'         => 'adjacent',
	'post_pagination_adjacent_style'         => 'link',
	'post_pagination_next_post'              => 'standard',

	'post_dont_miss_hide_elements'           => 'categories,summary,views,comments_link,subtitle,call_to_action',
	'post_dont_miss_call_to_action_hide_buttons' => '',
	'post_related_hide_elements'             => 'summary,author,date,views,comments_link,subtitle,call_to_action',
	'post_related_call_to_action_hide_buttons' => '',
	'post_more_from_hide_elements'           => 'categories,summary,views,comments_link,subtitle,call_to_action',
	'post_more_from_call_to_action_hide_buttons' => '',

	'post_pagination_single_order'			 => 10,
	'post_bottom_share_buttons_order'        => 15,
	'post_tags_order'                        => 20,
	'post_newsletter_order'                  => 25,
	'post_nav_single_order'                  => 30,
	'post_voting_box_order'					 => 35,
	'post_reactions_order'					 => 40,
	'post_author_info_order'                 => 45,
	'post_related_entries_order'			 => 50,
	'post_more_from_order'					 => 55,
	'post_comments_order'					 => 60,
	'post_dont_miss_order'					 => 65,
	'post_related_max_posts'		         => 6,
	'post_more_from_max_posts'		         => 6,
	'post_dont_miss_max_posts'		         => 6,
	'post_dont_miss_order'					 => 65,
	'post_related_template'			         => 'grid',
	'post_more_from_template'			     => 'list',
	'post_dont_miss_template'			     => 'grid',

	// Posts > Single: Link format.

	'post_link_frame_icon'                   => true,
	'post_link_show_domain'                  => false,
	'post_link_single_page'                  => 'none',
	'post_link_visit_direct_link_label'      => __( 'Visit Direct Link', 'bimber' ),
	'post_link_open_method'                  => 'new_window',
	'post_link_landing_page'                 => 0,
	'post_link_redirection_delay'            => 5,

	// Posts > Single: Video format.
	'post_video_template'                           => '',
	'post_video_frame_icon'                         => true,
	'post_video_single_featured_media_allow_video'  => true,
	'post_video_hide_in_content'                    => true,

	// Posts > Single: Gallery format.
	'post_gallery_frame_icon'                       => true,

	// Posts > Archive.

	'archive_template'                       => 'grid-sidebar',
	'archive_sidebar_location'               => 'standard',
	'archive_featured_entries'               => 'recent',
	'archive_featured_entries_template'      => '2of3-3v-3v-boxed',
	'archive_featured_entries_gutter'        => 'none',
	'archive_featured_entries_title'         => '',
	'archive_featured_entries_title_hide'    => 'none',
	'archive_featured_entries_time_range'    => 'all',
	'archive_featured_entries_hide_elements' => 'call_to_action',
	'archive_featured_entries_call_to_action_hide_buttons'       => '',
	'archive_header_composition'             => '01',
	'archive_header_hide_elements'           => '',
	'archive_default_filter'				 => 'newest',
	'archive_filters'					     => 'newest,oldest,most_commented',
	'archive_title'                          => '',
	'archive_title_hide'                     => 'none',
	'archive_inject_embeds'              	 => 'standard',
	'archive_highlight_items'                => 'standard',
	'archive_highlight_items_offset'         => 3,
	'archive_highlight_items_repeat'         => 5,
	'archive_posts_per_page'                 => 12,
	'archive_pagination'                     => 'infinite-scroll-on-demand',
	'archive_hide_elements'                  => 'subtitle,call_to_action',
	'archive_call_to_action_hide_buttons'    => '',
	'archive_newsletter'                     => 'standard',
	'archive_newsletter_after_post'          => 2,
	'archive_newsletter_repeat'              => 12,
	'archive_product'                     	 => 'none',
	'archive_product_after_post'          	 => 4,
	'archive_product_repeat'          	     => 12,
	'archive_product_category'     	         => '',
	'archive_ad'                             => 'standard',
	'archive_ad_after_post'                  => 6,
	'archive_ad_repeat'                      => 12,

	// Posts > Auto Load.

	'posts_auto_load_enable'                 => false,
	'posts_auto_load_in_same_category'       => false,
	'posts_auto_load_max_posts'              => '0',

	// Featured Entries.

	'featured_entries_visibility'            => 'home,single_post',
	'featured_entries_template'              => 'grid',
	'featured_entries_gutter'                => false,
	'featured_entries_above_header'          => false,
	'featured_entries_full_width'          	 => false,
	'featured_entries_img_ratio'             => '2-1',
	'featured_entries_img_title'             => false,
	'featured_entries_type'                  => 'recent',
	'featured_entries_category'              => '',
	'featured_entries_tag'                   => array( '' ),
	'featured_entries_time_range'            => 'all',
	'featured_entries_exclude_from_main_loop' => false,
	'featured_entries_size' 				 => 'xs',
	'featured_entries_number' 				 => 6,
	'featured_entries_number_bunchy'		 => 3,

	// Design > Global.

	'global_stack'                           => 'original',
	'global_icon_style'						 => 'default',
	'global_skin'                            => 'light',
	'global_layout'                          => 'stretched',
	'global_google_font_subset'              => 'latin,latin-ext',
	'global_background_color'                => '#e6e6e6',
	'global_skinmode_background_color'       => '#333333',
	'meta_theme_color'                       => '',
	'content_cs_1_accent1'                   => '#ff0036',
	'content_cs_2_background_color'          => '#ff0036',
	'content_cs_2_background2_color'         => '#ff6636',
	'content_cs_2_text1'                     => '#ffffff',
	'hot_background_color'                   => '#ff0036',
	'trending_background_color'              => '#bf0029',
	'popular_background_color'               => '#ff577b',
	'members_only_background_color'          => '#ff0036',
	'coupon_inside_background_color'          => '#ff0036',
	'hot_text_color'                   	     => '#fff',
	'trending_text_color'              	     => '#fff',
	'popular_text_color'               	     => '#fff',
	'members_only_text_color'                => '#fff',
	'coupon_inside_text_color'                => '#fff',
	'hot_optional_gradient_color'            => '',
	'trending_optional_gradient_color'       => '',
	'popular_optional_gradient_color'        => '',
	'members_only_optional_gradient_color'        => '',
	'coupon_inside_optional_gradient_color'        => '',
	'breadcrumbs'                            => 'standard',
	'breadcrumbs_ellipsis'                   => 'standard',
	'bending_cat'                            => true,
	'page_width'                             => 1182,

	// Design > Cards.
	'cards_home_content'                    => 'none',
	'cards_home_sidebar'                    => 'none',
	'cards_archive_content'                 => 'none',
	'cards_archive_sidebar'                 => 'none',
	'cards_search_content'                  => 'none',
	'cards_search_sidebar'                  => 'none',
	'cards_single_content'                  => 'none',
	'cards_single_sidebar'                  => 'none',
	'cards_single_comments'                 => 'none',

	// Design > Header.

	'header_builder'                     	 => false,
	'header_builder_use'					 => 'none',
	'header_composition'                     => 'original',
	'header_mobile_composition'              => '01',
	'header_sticky'                          => 'none',
	'header_logo_margin_bottom'              => '15',
	'header_logo_margin_top'                 => '15',
	'header_mobile_logo_margin_bottom'       => '10',
	'header_mobile_logo_margin_top'          => '10',
	'header_quicknav_margin_top'             => '15',
	'header_quicknav_margin_bottom'          => '15',
	'header_primary_nav_icons'               => 'none',
	'header_primary_nav_margin_top'          => '0',
	'header_primary_nav_margin_bottom'       => '0',
	'header_quicknav_labels'                 => 'standard',
	'header_primarynav_layout'                 => 'standard',
	'header_text_color'                      => '#000000',
	'header_accent_color'                    => '#ff0036',
	'header_background_color'                => '#ffffff',
	'header_border_color'                    => '',
	'header_navbar_background_color'         => '#ff0036',
	'header_navbar_text_color'               => '#ffffff',
	'header_navbar_accent_color'             => '#000000',
	'header_navbar_secondary_background_color'         => '#000000',
	'header_navbar_secondary_text_color'               => '#ffffff',
	'header_bg2_color'						 => '',
	'archive_header_background_color'        => '',
	'archive_header_background2_color'       => '',

	'preheader_text_color'                   => '#666666',
	'preheader_accent_color'                 => '#ff0036',
	'preheader_background_color'             => '#ffffff',
	'preheader_bg2_color'				     => '',
	'preheader_border_color'                 => '',

	// Search.
	'search_input_placeholder'               => '',
	'search_ajax'                            => true,
	'search_template'                        => 'classic-sidebar',
	'search_sidebar_location'                => 'standard',
	'search_posts_per_page'                  => 12,
	'search_pagination'                      => 'load-more',
	'search_hide_elements'                   => '',
	'search_call_to_action_hide_buttons'     => '',
	'search_inject_embeds'              	 => 'standard',

	// NSFW.
	'nsfw_enabled'                       	 => true,
	'nsfw_categories_ids'                    => 'nsfw',

	// Snax.
	'snax_header_create_button_visibility'   => 'all',
	'snax_header_create_button_type'         => 'all',
	'snax_header_create_button_label'        => esc_html__( 'Create', 'bimber' ),

	// Typography.
	'typo_selectors'		=> false,
	'typo_categories'		=> false,
	'typo_tabs'				=> false,
	'typo_button'			=> false,
	'typo_body'				=> false,
	'typo_primary_nav'		=> false,
	'typo_secondary_nav'	=> false,
	'typo_quick_nav'		=> false,
	'typo_submenus'			=> false,
	'typo_drop_toggle'      => false,
	'typo_tags'				=> false,
	'typo_meta'				=> false,
	'typo_link'				=> false,
	'typo_xl'				=> false,
	'typo_giga'				=> false,
	'typo_mega'				=> false,
	'typo_mega_2nd'			=> false,
	'typo_alpha'			=> false,
	'typo_alpha_2nd'		=> false,
	'typo_beta'				=> false,
	'typo_beta_2nd'			=> false,
	'typo_gamma'			=> false,
	'typo_gamma_2nd'		=> false,
	'typo_gamma_3rd'		=> false,
	'typo_delta'			=> false,
	'typo_delta_2nd'		=> false,
	'typo_delta_3rd'		=> false,
	'typo_epsilon'			=> false,
	'typo_epsilon_2nd'		=> false,
	'typo_epsilon_3rd'		=> false,
	'typo_zeta'				=> false,
	'typo_zeta_2nd'			=> false,
	'typo_zeta_3rd'			=> false,
	'typo_quote'			=> false,

	// Migrations.
	'migration_v54'			=> false,


	/****************************************
	 *                                      *
	 *               HEADER                 *
	 *                                      *
	 ****************************************/


	// ---
	// Header > Builder.
	// ---

	'header_builder_a_text_color'                      => '#000000',
	'header_builder_a_accent_color'                    => '#ff0036',
	'header_builder_a_background_color'                => '#ffffff',
	'header_builder_a_gradient_color'                  => '',
	'header_builder_a_border_color'                    => '',
	'header_builder_a_button_background'         	   => '#000000',
	'header_builder_a_button_text'               	   => '#ffffff',
	'header_builder_a_skinmode_text_color'             => '#ffffff',
	'header_builder_a_skinmode_accent_color'           => '#808080',
	'header_builder_a_skinmode_background_color'       => '#000000',
	'header_builder_a_skinmode_gradient_color'         => '',
	'header_builder_a_skinmode_border_color'           => '#1a1a1a',

	'header_builder_b_text_color'                      => '#000000',
	'header_builder_b_accent_color'                    => '#ff0036',
	'header_builder_b_background_color'                => '#ffffff',
	'header_builder_b_gradient_color'                  => '',
	'header_builder_b_border_color'                    => '',
	'header_builder_b_button_background'         	   => '#000000',
	'header_builder_b_button_text'               	   => '#ffffff',
	'header_builder_b_skinmode_text_color'             => '#ffffff',
	'header_builder_b_skinmode_accent_color'           => '#808080',
	'header_builder_b_skinmode_background_color'       => '#000000',
	'header_builder_b_skinmode_gradient_color'         => '',
	'header_builder_b_skinmode_border_color'           => '#1a1a1a',

	'header_builder_c_text_color'                      => '#000000',
	'header_builder_c_accent_color'                    => '#ff0036',
	'header_builder_c_background_color'                => '#ffffff',
	'header_builder_c_gradient_color'                  => '',
	'header_builder_c_border_color'                    => '',
	'header_builder_c_button_background'         	   => '#000000',
	'header_builder_c_button_text'               	   => '#ffffff',
	'header_builder_c_skinmode_text_color'             => '#ffffff',
	'header_builder_c_skinmode_accent_color'           => '#808080',
	'header_builder_c_skinmode_background_color'       => '#000000',
	'header_builder_c_skinmode_gradient_color'         => '',
	'header_builder_c_skinmode_border_color'           => '#1a1a1a',


	'canvas_sticky'                                    => '',
	'header_builder_canvas_text_color'                 => '#000000',
	'header_builder_canvas_accent_color'               => '#ff0036',
	'header_builder_canvas_background_color'           => '#ffffff',
	'header_builder_canvas_gradient_color'             => '',
	'header_builder_canvas_background_image'           => '',
	'header_builder_canvas_background_repeat'          => 'no-repeat',
	'header_builder_canvas_background_size'            => 'auto',
	'header_builder_canvas_background_position'        => 'top left',
	'header_builder_canvas_background_opacity'         => 100,
	'header_builder_canvas_button_background'          => '#ff0036',
	'header_builder_canvas_button_text'                => '#ffffff',

	'header_builder_canvas_skinmode_text_color'         => '#ffffff',
	'header_builder_canvas_skinmode_accent_color'       => '#ff0036',
	'header_builder_canvas_skinmode_background_color'   => '#000000',
	'header_builder_canvas_skinmode_border_color'       => '#1a1a1a',

	'header_submenu_background_color'           => '#ffffff',
	'header_submenu_text_color'                 => '#666666',
	'header_submenu_accent_color'               => '#ff0036',

	'header_submenu_skinmode_background_color'  => '#000000',
	'header_submenu_skinmode_text_color'        => '#999999',
	'header_submenu_skinmode_accent_color'      => '#ff0036',


	'header_builder_element_size_social_icons_full'        => 'standard',
	'header_builder_element_size_search'               	   => 'standard',
	'header_builder_element_size_create_button'            => 'g1-button-m',
	'header_builder_element_size_social_icons_dropdown'    => 'g1-drop-l',
	'header_builder_element_type_social_icons_dropdown'    => 'g1-drop-icon',
	'header_builder_element_size_search_dropdown'          => 'g1-drop-l',
	'header_builder_element_type_search_dropdown'          => 'g1-drop-icon',
	'header_builder_element_size_user_menu'                => 'g1-drop-l',
	'header_builder_element_type_user_menu'                => 'g1-drop-icon',
	'header_builder_element_size_cart'               	   => 'g1-drop-l',
	'header_builder_element_type_cart'               	   => 'g1-drop-icon',
	'header_builder_element_size_newsletter'               => 'g1-drop-l',
	'header_builder_element_type_newsletter'               => 'g1-drop-icon',
	'header_builder_element_size_mobile_menu'              => 'standard',
	'header_builder_element_label_mobile_menu'             => 'standard',
	'header_builder_element_size_skin_dropdown'            => 'g1-drop-l',
	'header_builder_element_type_skin_dropdown'            => 'g1-drop-icon',
	'header_builder_element_content_skin_dropdown'         => true,
	'header_builder_element_size_nsfw_dropdown'            => 'g1-drop-l',
	'header_builder_element_content_nsfw_dropdown'         => true,



	/****************************************
	 *                                      *
	 *               FOOTER                 *
	 *                                      *
	 ****************************************/

	// ---
	// Footer > General.
	// ---

	'footer_composition'                     => '3cols',
	'back_to_top'                            => 'standard',


	// ---
	// Footer > Colors.
	// ---

	'footer_cs_1_background_color'          => '#f2f2f2',
	'footer_cs_1_text1'                     => '#000000',
	'footer_cs_1_text2'                     => '#666666',
	'footer_cs_1_text3'                     => '#999999',
	'footer_cs_1_accent1'                   => '#ff0036',
	'footer_cs_2_background_color'          => '#ff0036',
	'footer_cs_2_text1'                     => '#ffffff',

	'footer_cs_1_background_color'          => '#f2f2f2',
	'footer_cs_1_gradient_color'            => '',
	'footer_cs_1_background_image'          => '',
	'footer_cs_1_background_repeat'         => 'no-repeat',
	'footer_cs_1_background_size'           => 'auto',
	'footer_cs_1_background_position'       => 'top left',
	'footer_cs_1_background_opacity'        => 100,

	'footer_skinmode_bg_color'              => '#000000',
	'footer_skinmode_itxt_color'            => '#ffffff',
	'footer_skinmode_rtxt_color'            => '#999999',
	'footer_skinmode_mtxt_color'            => '#666666',
	'footer_skinmode_atxt_color'            => '#ff0036',

	// ---
	// Footer > Modules.
	// ---

	//'podcast_above_footer'                      => false,
	//'podcast_above_footer_color_scheme'         => 'light',
	//'podcast_above_footer_background_color'     => '',

	'newsletter_before_footer'                   => false,
	'newsletter_before_footer_color_scheme'      => 'light',
	'newsletter_before_footer_background_color'  => '',
	'newsletter_before_footer_title'               	=> esc_html__( 'Get the best viral stories straight into your inbox!', 'bimber' ),

	'patreon_above_footer'                      => false,
	'patreon_above_footer_color_scheme'         => 'light',
	'patreon_above_footer_background_color'     => '',

	'links_above_footer'                        => false,
	'links_above_footer_color_scheme'           => 'light',
	'links_above_footer_background_color'       => '',

	'instagram_above_footer'                    => false,
	'instagram_above_footer_color_scheme'       => 'light',
	'instagram_above_footer_background_color'   => '',
	'instagram_above_footer_single_row'  	    => false,

	'social_above_footer'                       => false,
	'social_above_footer_color_scheme'          => 'dark',
	'social_above_footer_background_color'      => '',
	'social_in_footer'                          => false,

	'promoted_products_above_footer'            => false,

	'promoted_product_above_footer'             => false,

	//'footer_podcast_order'                  => 10,
	'footer_newsletter_order'               => 15,
	'footer_patreon_order'                  => 20,
	'footer_links_order'                    => 25,
	'footer_instagram_order'                => 30,
	'footer_social_order'                   => 35,
	'footer_promoted_products_order'        => 40,
	'footer_promoted_product_order'         => 45,
);
