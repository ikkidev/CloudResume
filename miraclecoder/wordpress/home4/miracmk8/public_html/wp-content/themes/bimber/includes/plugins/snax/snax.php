<?php
/**
 * Snax plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/snax/voting.php';
require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/snax/pending-home-filter.php';

add_action( 'snax_setup_theme', 'bimber_snax_setup' );	// On plugin activation.

add_action( 'after_switch_theme',                   'bimber_snax_setup' ); // On theme activation.

// It's not optimal way but it's the only one.
// We can't hook into plugin activation because the hook process performs an instant redirect after it fires.
// We can use recommended workaround (add_option()) but it's exaclty the same, in case of performance.
add_action( 'admin_init',                           'bimber_snax_setup' ); // On plugin activation.

add_filter( 'snax_get_collection_item_image_size',  'bimber_snax_get_collection_item_image_size' );

add_filter( 'bimber_show_prefooter',                'bimber_snax_hide_on_frontend_submission_page' );

add_action( 'wp_loaded',                            'bimber_snax_setup_header_elements' );

add_action( 'wp_enqueue_scripts',                   'bimber_snax_enqueue_head_styles', 20 );

// Change the location on success submissions notes.
remove_filter( 'the_content',                       'snax_item_prepend_notes' );
add_action( 'bimber_before_content_theme_area',     'bimber_snax_item_render_notes' );
remove_filter( 'the_content',                       'snax_post_prepend_notes' );
add_action( 'bimber_before_content_theme_area',     'bimber_snax_post_render_notes' );

//add_filter( 'quads_has_ad',                         'bimber_snax_hide_ad_before_content_theme_area', 10, 2 );
//remove_action( 'snax_before_item_media',            'snax_item_render_notes' );
//add_action( 'bimber_before_content_theme_area',     'snax_item_render_notes' );

// Embed width.
add_action( 'snax_before_card_media',               'snax_embed_change_content_width' );
add_action( 'snax_after_card_media',                'snax_embed_revert_content_width' );

add_filter( 'snax_capture_item_position_args',      'bimber_snax_capture_item_position_args' );
add_filter( 'snax_widget_cta_options',              'bimber_snax_widget_cta_options' );
add_action( 'snax_before_widget_cta_title',         'bimber_snax_before_widget_cta_title' );
add_filter( 'snax_show_create_button', 				'bimber_snax_show_create_button' );

// Custom post types: Quizzes, Polls.
add_filter( 'bimber_posts_archive_post_types',      'bimber_snax_posts_archive_post_types' );
add_filter( 'bimber_related_entries_post_types',    'bimber_snax_posts_archive_post_types' );
add_action( 'pre_get_posts',                        'bimber_snax_add_cpt_to_queries' );
add_filter( 'get_previous_post_join',               'bimber_snax_get_adjacent_post_join', 11 );
add_filter( 'get_next_post_join',                   'bimber_snax_get_adjacent_post_join', 11 );
add_filter( 'get_previous_post_where',              'bimber_snax_add_cpt_to_next_prev_nav', 10, 5 );
add_filter( 'get_next_post_where',                  'bimber_snax_add_cpt_to_next_prev_nav', 10, 5 );

// SEO by Yoast title.
add_filter( 'wpseo_opengraph_title',                'snax_replace_title_placeholder' );

// Stop Snax from loading FB SDK, Bimber will do that if requested.
remove_action( 'snax_enqueue_fb_sdk', 				'snax_enqueue_fb_sdk' );

add_filter( 'bimber_vc_collection_params',			'snax_register_vc_format_filter' );
add_filter( 'bimber_vc_featured_collection_params',	'snax_register_vc_format_filter' );

add_filter( 'bimber_collection_shortcode_query_args',	'snax_apply_snax_format_query_filter' );
add_filter( 'bimber_featured_posts_query_args',			'snax_apply_snax_format_query_filter' );

add_filter( 'bimber_single_post_supported_types', 		'bimber_snax_add_snax_single_post_types_support' );

add_filter( 'bimber_wpp_query_post_types', 'bimber_snax_add_snax_post_types_to_popular_posts_query' );

add_filter( 'single_template',		'snax_ignore_disable_default_featured_media',20, 1 );
add_filter( 'the_content',			'bimber_snax_cut_embedly_scripts', 9999, 1);

// Auto load next post.
add_action( 'wp_loaded',    'bimber_snax_dont_auto_load_complex_formats' );
add_filter( 'the_content',	'bimber_snax_cta_for_auto_loaded_complext_formats', 10, 1);

add_filter( 'bimber_get_post_gallery_media_count', 		'snax_get_post_gallery_media_count', 10, 2 );
add_filter( 'bimber_get_post_format_for_icon',		 	'snax_force_gallery_format_icon', 10, 2 );

add_action( 'loop_start',	'snax_force_disabled_featured_image_in_meta', 9999 );

// the condition doesn't work inside the callback.
if ( ! is_admin() ) {
	add_action( 'pre_get_posts', 'bimber_woocommerce_add_snax_items_to_search_results' );
}

// Auto load next post.
add_filter( 'bimber_load_embeds_on_archives', 	'bimber_snax_block_embed_in_collection_for_cpt', 10,1 );

add_filter( 'bimber_archive_filters', 'bimber_snax_add_most_voted_filter', 10, 1 );
add_action( 'bimber_apply_archive_filter_most_upvotes', 'bimber_snax_apply_archive_filter_most_upvotes', 10, 1 );

add_filter( 'snax_show_item_share', 'bimber_snax_disable_itemshare_with_microshare', 10, 1 );

add_action( 'wsl_render_auth_widget_end', 'bimber_snax_wpsl_gdpr' );
add_action( 'snax_gdpr_consent_text',   'bimber_snax_wpsl_gdpr_text' );

add_action( 'snax_post_added',                  'bimber_snax_disable_fake_views', 10, 2 );

add_filter( 'bimber_most_voted_query_args',    'bimber_snax_get_most_voted_query_args', 10, 2 );

add_filter( 'bimber_most_voted_posts', 'bimber_snax_most_voted_posts', 10, 4 );

add_action( 'bimber_snax_most_voted_posts_calculated', 'bimber_snax_log_lists_generation', 10, 3 );

add_filter( 'bimber_entry_cta_button_label', 'bimber_snax_entry_cta_button_label', 10, 1 );

add_filter( 'bimber_post_call_to_action_buttons', 'bimber_snax_post_call_to_action_buttons', 10, 1 );

add_filter( 'bimber_has_entry_call_to_action', 'bimber_snax_has_entry_call_to_action', 10, 2 );;

add_filter( 'bimber_entry_action_links', 'bimber_snax_entry_action_links', 10, 1 );
add_filter( 'snax_entry_action_links_args', 'bimber_snax_entry_action_links_args', 999 );
add_filter( 'snax_item_action_links_args', 'bimber_snax_item_action_links_args' );


// BuddyPress plugin integration.
add_filter( 'snax_template_before_bp_posts_loop', 'bimber_snax_template_before_bp_posts_loop' );
add_filter( 'snax_template_after_bp_posts_loop', 'bimber_snax_template_after_bp_posts_loop' );

add_filter( 'bimber_featured_media_embeds_supported_format', 'bimber_snax_allow_entry_featured_media_embeds' );

add_filter( 'snax_item_comments_args', 'bimber_snax_item_comments_args' );

add_filter( 'bimber_sidebar', 'bimber_snax_sidebar', 11 );

add_filter( 'snax_bp_register_page_with_sidebar', 'bimber_snax_bp_register_page_with_sidebar' );

add_filter( 'bimber_fake_views_post_types', 'bimber_snax_fake_views_post_types' );

add_action( 'bimber_after_home_filters_applied', 'bimber_snax_after_home_filters_applied', 10, 2 );

function bimber_snax_after_home_filters_applied( $query, $filter ) {
    if ( 'random' === $filter ) {
        $types = $query->get( 'post_type' );

        if ( ! is_array( $types ) ) {
            $types = array( $types );
        }

        if ( function_exists( 'snax_get_quiz_post_type' ) ) {
            $types[] = snax_get_quiz_post_type();
            $types[] = snax_get_poll_post_type();

            $query->set('post_type', $types );
        }
    }
}

/**
 * Fake views metabox support for Quiz/Poll
 *
 * @param array $post_types     Allowed post types.
 *
 * @return array
 */
function bimber_snax_fake_views_post_types( $post_types ) {
    $post_types[] = snax_get_quiz_post_type();
    $post_types[] = snax_get_poll_post_type();

    return $post_types;
}

function bimber_snax_bp_register_page_with_sidebar( $with ) {
	$with = 'standard' === bimber_get_theme_option( 'bp', 'enable_sidebar' );

	return $with;
}

function bimber_snax_sidebar( $sidebar ) {
	if ( is_tax( snax_get_snax_format_taxonomy_slug() ) ) {
		$object = get_queried_object();

		// Load default template for our taxonomy but leave External Product with WC template.
		if ( $object->slug !== 'extproduct' ) {
			$sidebar = 'post_archive';
		}
	}

	return $sidebar;
}


function bimber_snax_item_comments_args( $args ) {
	$args['callback'] = 'bimber_wp_list_comments_callback';

	return $args;
}

function bimber_snax_allow_entry_featured_media_embeds( $supported ) {
	$snax_format = snax_get_format();

	if ( $snax_format && in_array( $snax_format, array( 'embed', 'audio', 'video' ) ) ) {
		$supported = true;
	}

	return $supported;
}


function bimber_snax_entry_action_links( $links ) {
	if ( function_exists( 'snax_report_post_abuse' ) && snax_report_post_abuse() ) {
		$links['report_post'] = snax_post_report_link( array(
			'g1-button-none',
		) );
	}

	$is_collections_allowed_post_type = function_exists( 'snax_collections_get_post_types' ) && in_array( get_post_type(), snax_collections_get_post_types() );

	if ( function_exists( 'snax_get_abstract_collections' ) && $is_collections_allowed_post_type ) {
		$configs = snax_get_abstract_collections();

		foreach( $configs as $collection_slug => $collection_config ) {
			if ( ! snax_is_abstract_collection_activated( $collection_slug ) ) {
				continue;
			}

			if ( 'auto' === $collection_config['add_criteria'] ) {
				continue;
			}

			$link_id = sprintf( 'snax_collection_%s', $collection_slug );

			$links[ $link_id ] = snax_render_add_to_collection_button(
				$collection_slug,
				$collection_config['add_to_label'],
				snax_get_abstract_collection_url( $collection_slug ),
				array(
					'g1-button-none',
					'snax-action',
				)
			);
		}

		// Custom collection.
		if ( snax_is_custom_collection_activated() ) {
			$custom_config  = snax_get_custom_collection_config();
			$custom_slug    = $custom_config['slug'];

			$links[ 'snax_collection_' . $custom_slug ] = snax_render_add_to_collection_button(
				$custom_slug,
				$custom_config['add_to_label'],
				snax_get_custom_collection_url(),
				array(
					'g1-button-none',
					'snax-action'
				)
			);
		}
	}

	return $links;
}

/**
 * Change the CTA button visibility for Snax formats
 *
 * @param bool  $has                Visibility flag.
 * @param array $buttons_arr        Allowed button list.
 *
 * @return bool
 */
function bimber_snax_has_entry_call_to_action( $has, $buttons_arr ) {
	// Quiz.
	if ( snax_is_quiz() ) {
		$has = ! in_array( snax_get_quiz_post_type(), $buttons_arr );
	}

	// Poll.
	if ( snax_is_poll() ) {
		$has = ! in_array( snax_get_poll_post_type(), $buttons_arr );
	}

	// Open List.
	if ( snax_is_post_open_list() ) {
		$has = ! in_array( 'open_list', $buttons_arr );
	}

	// Gallery.
	if ( snax_is_format( 'gallery' ) ) {
		$has = ! in_array( 'view_gallery', $buttons_arr );
	}

	return $has;
}

/**
 * Register Snax formats as custom buttons
 *
 * @param array $choices        Button list.
 *
 * @return array
 */
function bimber_snax_post_call_to_action_buttons( $choices ) {
	if ( function_exists( 'snax_get_quiz_post_type' ) ) {
		$choices[ snax_get_quiz_post_type() ] = esc_html__( 'Play the Quiz', 'bimber' );
	}

	if ( function_exists( 'snax_get_poll_post_type' ) ) {
		$choices[ snax_get_poll_post_type() ] = esc_html__( 'Take the Poll', 'bimber' );
	}

	$choices[ 'open_list' ] = esc_html__( 'Add to List', 'bimber' );

	return $choices;
}

/**
 * Change CTA label for Snax formats
 *
 * @param string $label     Button label.
 *
 * @return string
 */
function bimber_snax_entry_cta_button_label( $label ) {
	// Quiz.
	if ( snax_is_quiz() ) {
		$label = esc_html__( 'Play the Quiz', 'bimber' );
	}

	// Poll.
	if ( snax_is_poll() ) {
		$label = esc_html__( 'Take the Poll', 'bimber' );
	}

	// Gallery.
	if ( snax_is_format( 'gallery' ) ) {
		$label = esc_html__( 'View Gallery', 'bimber' );
	}

	// Open List.
	if ( snax_is_post_open_list() ) {
		$label = esc_html__( 'Add to List', 'bimber' );
	}

	return $label;
}

/**
 * Adjust theme for Snax
 */
function bimber_snax_setup() {
	if ( get_option( 'snax_setup_done', false ) ) {
		return;
	}

	// Change Frontend Submission page template.
	$front_page_id 	= snax_get_frontend_submission_page_id();

	if ( $front_page_id ) {
		update_post_meta( $front_page_id, '_wp_page_template', 'g1-template-page-full.php' );
	}

	update_option( 'snax_setup_done', true );
}

/**
 * Adjust the image size used inside snax collection
 *
 * @param string $image_size Image size.
 *
 * @return string
 */
function bimber_snax_get_collection_item_image_size($image_size ) {
	if ( has_image_size( 'bimber-grid-fancy' ) ) {
		$image_size = 'bimber-grid-fancy';
	}

	return $image_size;
}

/**
 * Hide the prefooter on the frontend submission page
 *
 * @param bool $show Whether or not to show the prefooter.
 *
 * @return bool
 */
function bimber_snax_hide_prefooter( $show ) {
	$frontend_submission_page = snax_get_frontend_submission_page_id();
	if ( is_page( $frontend_submission_page ) && ! empty( $frontend_submission_page ) ) {
		$show = false;
	}

	return $show;
}

/**
 * Hide the primary nav menu on the frontend submission page
 *
 * @param bool   $has_nav_menu Whether or not a menu is assigned to nav location.
 * @param string $location Nav location.
 *
 * @return bool
 */
function bimber_snax_hide_nav_menus( $has_nav_menu, $location ) {
	$locations = array(
		'bimber_primary_nav',
		'bimber_secondary_nav',
	);
	$frontend_submission_page = snax_get_frontend_submission_page_id();
	if ( in_array( $location, $locations ) && is_page( snax_get_frontend_submission_page_id() ) && ! empty( $frontend_submission_page ) ) {
		$has_nav_menu = false;
	}

	return $has_nav_menu;
}



/**
 * Hide ad before the content theme area, after snax item submission
 *
 * @param bool   $bool Whether or not an ad is assigned to ad location.
 * @param string $location Ad location.
 *
 * @return bool
 */
function bimber_snax_hide_ad_before_content_theme_area($bool, $location ) {
	if ( 'bimber_before_content_theme_area' === $location && snax_item_submitted() ) {
		$bool = false;
	}

	return $bool;
}

function snax_embed_change_content_width() {
	global $content_width;
	global $snax_old_content_width;

	// Store original value.
	$snax_old_content_width = $content_width;

	// Overide.
	$content_width = 758;
}

function snax_embed_revert_content_width() {
	global $content_width;
	global $snax_old_content_width;

	// Restore.
	$content_width = $snax_old_content_width;
}

/**
 * Hide an element on the frontend submission page
 *
 * @param bool $show Whether or not to show an element.
 *
 * @return bool
 */
function bimber_snax_hide_on_frontend_submission_page( $show ) {
	$frontend_submission_page = snax_get_frontend_submission_page_id();
	if ( is_page( $frontend_submission_page ) && ! empty( $frontend_submission_page ) ) {
		$show = false;
	}

	return $show;
}

function bimber_snax_capture_item_position_args( $args ) {
	$args['prefix'] = '#';
	$args['suffix'] = ' ';

	return $args;
}

function bimber_snax_widget_cta_options( $args ) {
	$args['classname'] .= ' g1-box g1-box-tpl-frame';

	return $args;
}

function bimber_snax_before_widget_cta_title() {
	echo '<i class="g1-box-icon"></i>';
	echo '<div class="g1-box-inner">';

}

/**
 * Render item notes
 */
function bimber_snax_item_render_notes() {
	snax_item_render_notes();
}

/**
 * Render post notes
 */
function bimber_snax_post_render_notes() {
	snax_post_render_notes();
}

function bimber_snax_setup_header_elements() {
	if ( 'simple' === bimber_get_theme_option( 'snax', 'header_type' ) ) {
		add_filter( 'bimber_show_quick_nav_menu',           'bimber_snax_hide_on_frontend_submission_page' );
		add_filter( 'bimber_show_navbar_searchform',        'bimber_snax_hide_on_frontend_submission_page' );
		add_filter( 'bimber_show_navbar_socials',           'bimber_snax_hide_on_frontend_submission_page' );
		add_filter( 'bimber_show_preheader_socials',        'bimber_snax_hide_on_frontend_submission_page' );

		add_filter( 'has_nav_menu',                         'bimber_snax_hide_nav_menus', 10, 2 );
	}
}

function bimber_snax_show_create_button( $show ) {
	$visibility = bimber_get_theme_option( 'snax', 'header_create_button_visibility' );

	if ( 'none' === $visibility || ( 'logged_in' === $visibility && ! is_user_logged_in() ) ) {
		$show = false;
	}

	return $show;
}

/**
 * Add Quiz/Poll post type to regular "posts"
 *
 * @param array $types      Allowed post types.
 *
 * @return array
 */
function bimber_snax_posts_archive_post_types( $types ) {
	if ( function_exists( 'snax_get_quiz_post_type' ) ) {
		$types[] = snax_get_quiz_post_type();
	}

	if ( function_exists( 'snax_get_poll_post_type' ) ) {
		$types[] = snax_get_poll_post_type();
	}

	return $types;
}

/**
 * Add Quiz/Poll post type to regular "posts"
 *
 * @param WP_Query $query			WP Query object.
 */
function bimber_snax_add_cpt_to_queries( $query ) {
    if ( ! function_exists( 'snax_get_quiz_post_type' ) || ! function_exists( 'snax_get_poll_post_type' ) ) {
        return;
    }

    if ( is_admin() ) {
        return;
    }

    // Skip for pages.
    if ( $query->is_page() ) {
        return;
    }

    // Skip for attachments.
    if ( $query->is_attachment() ) {
        return;
    }

    // Skip for custom post types.
    // Only "posts" page (is_home) has to be changed.
    if ( $query->is_post_type_archive() ) {
        return;
    }

    // Skip for custom taxonomy. If a taxonomy has to support our CPTs, that should be done via register_taxonomy() call.
    // Support for our CPTs should be added, for both "category" and "post_tag", during taxonomies registration as well,
    // but to not add another hook for that, we can change that here.
    // is-tax() is for custom taxonomies only, doesn't cover is_category() and is_tag(), so the code below affects only those taxes.
    if ( $query->is_tax() ) {
        return;
    }

    if ( apply_filters( 'bimber_skip_quizzes_for_query', false, $query ) ) {
        return;
    }

    if ( apply_filters( 'bimber_skip_polls_for_query', false, $query ) ) {
        return;
    }

    $post_type = $query->get( 'post_type' );

    // Normalize.
    $post_type = ( '' === $post_type ) ? array( 'post' ) : (array) $post_type;

    // Skip if query is not for "post" type.
    if ( ! in_array( 'post', $post_type, true ) ) {
        return;
    }

    $post_type[] = snax_get_quiz_post_type();
    $post_type[] = snax_get_poll_post_type();

    $query->set( 'post_type', $post_type );
}

function bimber_snax_add_cpt_to_next_prev_nav( $where_clause, $in_same_term, $excluded_terms, $taxonomy, $post ) {
	if ( function_exists( 'snax_get_quiz_post_type' ) && function_exists( 'snax_get_poll_post_type' ) ) {
		$quiz_type  = snax_get_quiz_post_type();
		$poll_type  = snax_get_poll_post_type();

		$where_clause = str_replace( "p.post_type = 'post'", "p.post_type IN ('post', '$quiz_type', '$poll_type')", $where_clause );
		$where_clause = str_replace( "p.post_type = '$quiz_type'", "p.post_type IN ('post', '$quiz_type', '$poll_type')", $where_clause );
		$where_clause = str_replace( "p.post_type = '$poll_type'", "p.post_type IN ('post', '$quiz_type', '$poll_type')", $where_clause );

        if ( bimber_can_use_plugin( 'sitepress-multilingual-cms/sitepress.php' ) ) {
            global $sitepress;

            if ( $sitepress && false === strpos( $where_clause, 'language_code' ) ) {
                global $wpdb;

                $where_clause .= $wpdb->prepare( " AND (language_code = '%s' )", $sitepress->get_current_language() );
            }
        }
	}

	return $where_clause;
}

function bimber_snax_get_adjacent_post_join( $join_clause ) {
    // Should be a part of the WPML integration but as the Snax CPTs breaks the WPML, let's leave it here.
    if ( ! bimber_can_use_plugin( 'sitepress-multilingual-cms/sitepress.php' ) ) {
        return $join_clause;
    }

    $post_type = get_query_var( 'post_type' );

    if ( ! $post_type ) {
        $post_type = get_post_type();
    }

    if ( ! $post_type ) {
        $post_type = 'post';
    }

    // Skip is post_type is a string. Means we didn't add Quiz/Poll CPTs to the query.
    if ( ! is_array( $post_type ) ) {
        return $join_clause;
    }

    $cache_key   = md5( wp_json_encode( array( $post_type, $join_clause ) ) );
    $cache_group = 'adjacent_post_join';
    $join_cached = wp_cache_get( $cache_key, $cache_group );

    // Use cached version if set.
    if ( $join_cached ) {
        return $join_cached;
    }

    global $sitepress;

    if ( ! $sitepress ) {
        return $join_clause;
    }

    $translatable_post_types = array();

    foreach ( $post_type as $post_type_to_check ) {
        if ( $sitepress->is_translated_post_type( $post_type_to_check ) ) {
            $translatable_post_types[] = $post_type_to_check;
        }
    }
    if ( ! empty( $translatable_post_types ) ) {
        global $wpdb;

        $IN_arr = array();

        foreach ( $translatable_post_types as $translatable_post_type ) {
            $IN_arr[] = "'post_$translatable_post_type'";
        }

        $join_clause .=" JOIN {$wpdb->prefix}icl_translations wpml_translations ON wpml_translations.element_id = p.ID AND wpml_translations.element_type IN (" . implode( ',', $IN_arr ) . ")";
    }

    return $join_clause;
}

function snax_register_vc_format_filter( $params ) {
    if ( ! function_exists( 'snax_get_active_formats' ) ) {
        return $params;
    }

	$active_formats = snax_get_active_formats();
	$vc_filter_value = array(
		'' => '', // Default value.
	);

	foreach ( $active_formats as $format_id => $format_config ) {
		$format_label = $format_config['labels']['name'];

		$vc_filter_value[ $format_label ] = $format_id;
	}

	$snax_format_config = array(
		'group' 		=> __( 'Data', 'bimber' ),
		'type' 			=> 'multi_checkbox',
		'heading' 		=> __( 'Filter by Snax format', 'bimber' ),
		'param_name' 	=> 'snax_format',
		'value' 		=> $vc_filter_value,
	);

	// Add filter after standard WP formats filter.
//	$after_index = false;
//
//	foreach ($params as $index => $param_arr ) {
//		if ( 'post_format' === $param_arr['param_name'] ) {
//			$after_index = $index;
//			break;
//		}
//	}
//
//	if ( false !== $after_index ) {
//		array_splice( $params, $after_index + 1, 0, array( $snax_format_config ) );
//	}

	$params[175] =  $snax_format_config;


	return $params;
}

function snax_apply_snax_format_query_filter( $query_args ) {
	if ( ! empty( $query_args['snax_format'] ) ) {
		$format = $query_args['snax_format'];

		// Remove from WP Query args.
		unset( $query_args['snax_format'] );

		if ( ! is_array( $format ) ) {
			$format = explode( ',', $format );
		}

		$query_args['tax_query'] = array(
			array(
				'taxonomy' 	=> snax_get_snax_format_taxonomy_slug(),
				'field' 	=> 'slug',
				'terms'		=> $format,
			)
		);
	}

	return $query_args;
}

/**
 * Add single post support for Snax's custom post types
 *
 * @param array $types      Allowed types.
 *
 * @return array
 */
function bimber_snax_add_snax_single_post_types_support( $types ) {
	$types[] = snax_get_quiz_post_type();
	$types[] = snax_get_poll_post_type();

	return $types;
}

/**
 * Add snax post types to most popular posts query
 *
 * @param string $post_types  Post types list.
 * @return string
 */
function bimber_snax_add_snax_post_types_to_popular_posts_query( $post_types ) {
	$post_types .= ',snax_quiz,snax_poll';

	return $post_types;
}

/**
 * Force display featured media on 'overlay' and 'background'
 *
 * @param  string $template Template.
 *
 * @return string
 */
function snax_ignore_disable_default_featured_media( $template ) {
	if ( strpos( $template, 'overlay' ) || strpos( $template, 'background' ) ) {
		add_filter( 'snax_disable_default_featured_media','__return_false' );
	}

	return $template;
}

/**
 * Change media upload form agruments
 *
 * @param arr $args  Media upload settings.
 * @return arr
 */
function bimber_snax_media_upload_form_args( $args ) {
	$args['classes']['select_files_button'] 	= array( 'snax-plupload-browse-button','g1-button','g1-button-m','g1-button-solid' );
	$args['classes']['get_by_url_button'] 		= array( 'snax-load-image-from-url-button','g1-button','g1-button-m','g1-button-solid' );
	$args['classes']['get_by_url_back_button'] 	= array( 'snax-load-image-from-url-button','g1-button','g1-button-m','g1-button-solid' );
	return $args;
}

/**
 * Check whether to show bar for current snax item
 *
 * @return bool
 */
function bimber_snax_show_item_bar() {
	$show = is_singular( 'snax_item' );

	return apply_filters( 'bimber_snax_show_item_bar', $show );
}

/**
 * Checkc whether to show snax bar for current post
 *
 * @return bool
 */
function bimber_snax_show_post_bar() {
	if ( 'bunchy' !== bimber_get_current_stack() ) {
		return false;
	}
	$show = snax_is_post_open_list( );

	return apply_filters( 'bimber_snax_show_post_bar', $show );
}

/**
 * Add Snax gallery to gallery count.
 *
 * @param  int     $count  Items count.
 * @param  WP_Post $post   Post.
 * @return int
 */
function snax_get_post_gallery_media_count( $count, $post ) {
	if ( snax_is_format( 'gallery', $post ) ) {
		$count += snax_get_post_submission_count( $post );
	}
	return $count;
}

/**
 * Force the gallery format icon for Snax galleries
 *
 * @param 	str     $format  Post format.
 * @param 	WP_Post $post   Post.
 * @return 	str
 */
function snax_force_gallery_format_icon( $format, $post ) {
	if ( snax_is_format( 'gallery', $post ) ) {
		$format = 'gallery';
	}
	return $format;
}

/**
 * Force image into microdata when it's disabled by Snax
 *
 * @param WP_Query $query  Query object.
 */
function snax_force_disabled_featured_image_in_meta( $query ) {
	$force = has_filter( 'get_post_metadata', 'snax_skip_post_thumbnail' );
	if ( $force ) {
		add_filter( 'bimber_force_missing_image', '__return_true' );
	}
}

/**
 * Add product snax_item type to search results archive page
 *
 * @param WP_Query $query			WP Query object.
 */
function bimber_woocommerce_add_snax_items_to_search_results( $query ) {
	$is_bbpress = false;
	if ( function_exists( 'is_bbpress' ) && isset($query->query['post_type']) ) {
		$is_bbpress = 'reply' === $query->query['post_type'];
		if ( is_array( $query->query['post_type'] ) ) {
			$is_bbpress = in_array( 'reply', $query->query['post_type']);
		}
	}
	if ( $query->is_search() && ! $is_bbpress ) {
		$post_type = $query->get( 'post_type' );
		$post_type = ( '' === $post_type ) ? array( 'post' ) : (array) $post_type;
		$post_type[] = 'snax_item';
		$query->set( 'post_type', $post_type );
	}
}

/** Content filter to cut embedly script from the list. We'll later add it via JS.
 *
 * @param str $content  The content.
 * @return str
 */
function bimber_snax_cut_embedly_scripts( $content ) {
    if (  ! function_exists( 'snax_is_embedly_enabled' )) {
        return $content;
    }

    if ( ! snax_is_embedly_enabled() ) {
        return $content;
    }

	$embedly_script = apply_filters( 'snax_embedly_script_code', '<script async src="//cdn.embedly.com/widgets/platform.js" charset="UTF-8"></script>' );
	if ( snax_is_format( 'list' ) && substr_count( $content, $embedly_script ) > 0 ) {
		$content = str_replace( $embedly_script, '', $content );
		$content .= '<div class="bimber-snax-embedly-script-placeholder"></div>';
	}

	return $content;
}

/**
 * Disable embed on collection instread of thumbnail for custom post types(quizz etc.)
 *
 * @param bool $enable  Wheter to allow embed in collection.
 * @return bool
 */
function bimber_snax_block_embed_in_collection_for_cpt( $enable ) {
	if ( 'snax_quiz' === get_post_type() || 'snax_poll' === get_post_type() ) {
		return false;
	}
	return $enable;
}

/**
 * Add most voted archive filter
 *
 * @param  array $archive_filters  Archive filters.
 * @return array
 */
function bimber_snax_add_most_voted_filter( $archive_filters ) {
	$archive_filters['most_upvotes'] = __( 'Most Upvoted', 'bimber' );

	return $archive_filters;
}

/**
 * Apply the archive filter to the query.
 *
 * @param WP_Query $query Archive main query.
 */
function bimber_snax_apply_archive_filter_most_upvotes( $query ) {
	$query->set( 'orderby','meta_value_num' );
	$query->set( 'order','DESC' );
	$query->set( 'meta_key','_snax_vote_score' );
}

//add_action( 'pre_get_posts', 'bimber_snax_include_all_posts_in_most_upvotes_results' );

/**
 * @param WP_Query $query       Query object.
 */
function bimber_snax_include_all_posts_in_most_upvotes_results( $query ) {
	if ( ! $query->is_main_query() ) {
		return;
	}

	$order = $query->get('order');

	if ( ! isset( $order ) ) {
		return;
	}

	if ( 'most_upvotes' !== $order ) {
		return;
	}

	add_filter( 'get_meta_sql', 'bimber_snax_include_posts_without_meta_key_set' );
}

function bimber_snax_include_posts_without_meta_key_set( $clauses ) {
	// It's executed too many times. We have to limit it just to main query and remove after that.

	$clauses['join'] = str_replace( 'INNER JOIN', 'LEFT JOIN', $clauses['join'] ) . $clauses['where'];
	$clauses['where'] = '';

	return $clauses;
}

/**
 * Add meme links to stream memes.
 * @todo It's not used anywhere
 */
function bimber_snax_add_meme_links_to_stream() {
	snax_render_meme_recaption();
	snax_render_meme_see_similar();
}

/**
 * Disable item share when microshare is disabled.
 *
 * @param  bool $bool	Whether to show item share.
 * @return bool
 */
function bimber_snax_disable_itemshare_with_microshare( $bool ) {
	if ( ! bimber_microshares_enabled() ) {
		$bool = false;
	}
	return $bool;
}

/**
 * Add GDPR consent to WPSL form.
 */
function bimber_snax_wpsl_gdpr() {
	$g1_theme_options   = get_option( bimber_get_theme_options_id() );

	if( stripos( $_SERVER['SCRIPT_NAME'], 'wp-login' ) > -1 ) {
		return;
	}

	if ( ! bimber_can_use_plugin( 'wp-gdpr-compliance/wp-gdpr-compliance.php' ) ) {
		return;
	}

	if ( ! isset( $g1_theme_options['gdpr_enabled'] ) || 'on' !== $g1_theme_options['gdpr_enabled'] || is_admin() ) {
		return;
	}

	$consent = isset( $g1_theme_options['gdpr_wpsl_consent'] ) ? $g1_theme_options['gdpr_wpsl_consent'] : '';
	$page = get_option( 'wpgdprc_settings_privacy_policy_page' ) ;
	if ( $page ) {
		$page_link = '<a href="' . get_page_link( $page ) . '">' . get_the_title( $page ) . '</a>';
		$consent = str_replace( '%privacy_policy%', $page_link, $consent );
	}
	?>
	<p>
		<label class="snax-wpsl-gdpr-consent"><input type="checkbox" /><?php echo wp_kses_post( $consent );?></label>
	</p>
<?php
}

/**
 * Add GDPR consent text to Snax login form tab.
 */
function bimber_snax_wpsl_gdpr_text() {
	$g1_theme_options   = get_option( bimber_get_theme_options_id() );

	$consent = isset( $g1_theme_options['gdpr_wpsl_consent'] ) ? $g1_theme_options['gdpr_wpsl_consent'] : '';
	$page = get_option( 'wpgdprc_settings_privacy_policy_page' ) ;
	if ( $page ) {
		$page_link = '<a href="' . get_page_link( $page ) . '">' . get_the_title( $page ) . '</a>';
		$consent = str_replace( '%privacy_policy%', $page_link, $consent );
	}
	?>
	<?php echo wp_kses_post( $consent );?>
<?php
}

function bimber_snax_disable_fake_views( $post_id, $type ) {
	if ( 'standard' === bimber_get_theme_option( 'posts', 'fake_view_disable_for_new' ) ) {
		update_post_meta( $post_id, '_bimber_fake_view_count', 0 );
	}
}

function bimber_snax_dont_auto_load_complex_formats() {
    if ( bimber_is_auto_load() ) {
        // Hide origin info.
        add_filter( 'snax_show_origin', '__return_false' );

        add_filter( 'snax_render_poll', '__return_false' );
        add_filter( 'snax_render_quiz', '__return_false' );
    }
}

/**
 * Add Call to action to auto loaded quizzes and polls
 *
 * @param string $the_content  Post content.
 * @return string
 */
function bimber_snax_cta_for_auto_loaded_complext_formats( $the_content ) {
	if ( ! function_exists( 'snax_get_quiz_post_type' ) || ! function_exists( 'snax_get_poll_post_type' ) ) {
		return $the_content;
	}
	if ( ! bimber_is_auto_load() ) {
		return $the_content;
	}

	$type = get_post_type();

	if ( snax_get_quiz_post_type() === $type ) {
		$label = _x( 'Play quiz in new window', 'Snax integration', 'bimber' );
	}
	if ( snax_get_poll_post_type() === $type ) {
        $label = _x( 'Take poll in new window', 'Snax integration', 'bimber' );
	}

	if ( snax_get_quiz_post_type() === $type || snax_get_poll_post_type() === $type ) {
		$cta = '<a href="' . get_the_permalink() . '" class="g1-auto-load-button g1-arrow g1-arrow-right g1-arrow-xl g1-arrow-solid" target="_blank">' . $label . '</a>';

		$cta = '<p>' . $cta . '</p>';

		$the_content = $the_content . $cta;
	}

	return $the_content;
}

/**
 * Get query arguments for the most shared collection
 *
 * @param array $query_args Query arguments.
 *
 * @return array
 */
function bimber_snax_get_most_voted_query_args( $query_args ) {
	$defaults = array(
		'posts_per_page'      => 10,
		'ignore_sticky_posts' => true,
		'meta_key'            => '_snax_vote_score',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		// This way we can be sure that only "shared" posts will be used.
		'meta_query'          => array(
			array(
				'key'     => '_snax_vote_score',
				'compare' => '>',
				'value'   => 0,
			),
		),
	);

	$query_args = wp_parse_args( $query_args, $defaults );

	return $query_args;
}

add_action( 'snax_widget_cta_before_after_widget', 'bimber_snax_add_cta_background' );

/**
 * Add CTA background.
 */
function bimber_snax_add_cta_background() {
	echo '</div>'; // END .g1-box-inner
	echo '<div class="g1-box-background g1-current-background"></div>';
}

function bimber_snax_most_voted_posts( $ids, $date_range, $limit, $type ) {
	global $wpdb;

	$table_name = $wpdb->prefix . snax_get_votes_table_name();

	$where_clause = '';

	if ( 'month' === $date_range ) {
		$where_clause = 'WHERE v.date > (DATE_SUB(CURDATE(), INTERVAL 1 MONTH))';
	}

	if ( 'day' === $date_range ) {
		$where_clause = 'WHERE v.date > (DATE_SUB(CURDATE(), INTERVAL 1 DAY))';
	}

	$res = $wpdb->get_results(
		"
		SELECT
			v.post_id,
			SUM(v.vote) score
		FROM
			$table_name v
		$where_clause
		GROUP BY
			v.post_id
		ORDER BY
			score DESC
		LIMIT $limit
		"
	);

	$posts = array();

	if ( ! empty( $res ) ) {
		foreach ( $res as $post ) {
			$ids[] = $post->post_id;

			$posts[ $post->post_id ] = $post->score;
		}
	}

	do_action( 'bimber_snax_most_voted_posts_calculated', $posts, $type, $date_range );

	return $ids;
}

/**
 * Log generation stats for the Popular/Hot/Trending lists
 *
 * @param array  $posts         List of WPP posts.
 * @param string $list_type     List type (popular, hot, etc).
 */
function bimber_snax_log_lists_generation( $posts, $list_type, $time_range ) {
	$log_types = array( 'popular', 'hot', 'trending' );

	if ( in_array( $list_type, $log_types ) ) {
		$log = array(
			'generated_at'  => current_time('F j, Y, g:i a'),
			'posts'         => $posts,
			'time_range'    => $time_range,
			'ordered_by'    => esc_html__( 'Votes', 'bimber' ),
		);

		$transient_name = sprintf( 'bimber_%s_list_log', $list_type );
		$expiration = 60 * 60; // 1 hour.

		set_transient( $transient_name, $log, $expiration );
	}
}





add_filter( 'snax_collection_item_action_links_args', 'bimber_snax_action_links_args' );
function bimber_snax_action_links_args( $args ) {
	// Adjust the HTML markup.
	$args['before'] = str_replace('<ul class="snax-action-links', '<ul class="snax-action-links sub-menu', $args['before'] );
	$args['before'] = str_replace('<li>', '<li class="menu-item">', $args['before'] );
	$args['sep'] = str_replace('<li>', '<li class="menu-item">', $args['sep'] );

	return $args;
}


add_action( 'bimber_get_image_sizes', 'bimber_snax_add_image_sizes' );
function bimber_snax_add_image_sizes( $r ) {
	// @todo Add only if Collections module is activated.
	$r['bimber_snax_collection_featured_image'] = array( 220, 220, true, true );

	return $r;
}





/**
 * Adjust markup of snax item action links.
 *
 * @param $args
 *
 * @return mixed
 */
function bimber_snax_item_action_links_args( $args ) {
	$args['before'] = str_replace( '<ul class="snax-action-links', '<ul class="snax-action-links sub-menu', $args['before'] );
	$args['before'] = str_replace( '<li>', '<li class="menu-item">', $args['before'] );
	$args['sep'] = str_replace( '<li>', '<li class="menu-item">', $args['sep'] );

	return $args;
}


function bimber_snax_entry_action_links_args( $args ) {
	$args['before'] = '<div class="snax-actions"><p>';
	$args['after'] = '</p></div>';
	$args['sep'] = '';

	foreach( $args['links'] as $k => $v ) {
		$args['links'][ $k ] = str_replace( 'class="', 'class="g1-button g1-button-s g1-button-subtle ', $v );
	}

	return $args;
}






function bimber_snax_template_before_bp_posts_loop() {
	add_filter( 'bimber_entry_action_links', 'bimber_snax_entry_actions_links_edit' );
}

function bimber_snax_template_after_bp_posts_loop() {
	remove_filter( 'bimber_entry_action_links', 'bimber_snax_entry_actions_links_edit' );
}

function bimber_snax_entry_actions_links_edit( $links) {
	if ( function_exists( 'snax_get_post_edit_link' ) ) {
		$links['edit'] = snax_get_post_edit_link();
	}

	if ( function_exists( 'snax_get_post_delete_link' ) ) {
		$links['delete'] = snax_get_post_delete_link();
	}

	return $links;
}



add_filter( 'bimber_default_post_types', 'bimber_snax_default_post_types' );
function bimber_snax_default_post_types( $r ) {
	$r[] = snax_get_quiz_post_type();
	$r[] = snax_get_poll_post_type();



	return $r;
}




/**
 * Enqueue Snax Plugin integration assets.
 */
function bimber_snax_enqueue_head_styles() {
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );
	$uri .= 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/snax-extra-' . $skin . '.min.css';

	wp_enqueue_style( 'bimber-snax-extra', $uri, array(), $version );
	wp_style_add_data( 'bimber-snax-extra', 'rtl', 'replace' );
}


add_filter( 'bimber_get_todo_class', 'bimber_snax_get_todo_class', 99 );
function bimber_snax_get_todo_class( $r ) {
	// @todo-wesoly
	// Refactor the second logic check.
	if ( snax_is_frontend_submission_page() && !isset( $_GET['snax_format'] ) ) {
		$r = array_diff( $r, array(
			'g1-card',
			'g1-card-solid',
			'g1-card-simple',
			'g1-card-subtle'
		) );
	}
	return $r;
}

