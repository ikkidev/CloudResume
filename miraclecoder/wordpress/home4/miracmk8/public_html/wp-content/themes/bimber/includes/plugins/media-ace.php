<?php
/**
 * Media Ace plugin integration
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

add_filter( 'bimber_entry_featured_media_args',             'bimber_mace_dont_apply_link_on_media' );
add_action( 'bimber_before_capture_entry_featured_media',   'bimber_mace_allow_gif_conversion' );
add_action( 'bimber_after_capture_entry_featured_media',    'bimber_mace_disallow_gif_conversion' );
add_action( 'bimber_gutenberg_before_render_block',         'bimber_mace_disable_lazy_load' );
add_filter( 'bimber_post_video_length',                     'bimber_mace_post_video_length', 10, 2 );
add_action( 'bimber_before_render_entry_featured_media',    'bimber_mace_disable_featured_media_lazy_load', 10, 1 );
add_action( 'bimber_after_render_entry_featured_media',     'bimber_mace_enable_featured_media_embed_lazy_load', 10, 1 );

add_filter( 'mace_get_used_image_sizes',                    'bimber_mace_filter_used_image_sizes' );

add_action( 'mace_gallery_share_buttons',                   'bimber_mace_gallery_share_buttons' );

/**
 * Override default featured media arguments
 *
 * @param array $args   Arguments.
 *
 * @return array
 */
function bimber_mace_dont_apply_link_on_media( $args ) {
	if ( $args['allow_video'] ) {
		$mp4_version = mace_get_gif_mp4_version( get_post_thumbnail_id() );

		if ( $mp4_version ) {
			$args['apply_link'] = false;
		}
	}

	return $args;
}

/**
 * Allow GIF to MP4 conversion
 *
 * @param array $args       Arguments.
 */
function bimber_mace_allow_gif_conversion( $args ) {
	if ( $args['allow_video'] ) {
		add_filter( 'post_thumbnail_html', 'bimber_mace_replace_gif_thumbnail_to_mp4_video' , 10, 4 );
	}
}

/**
 * Disallow GIF to MP4 conversion
 *
 * @param array $args       Arguments.
 */
function bimber_mace_disallow_gif_conversion( $args ) {
	if ( $args['allow_video'] ) {
		remove_filter( 'post_thumbnail_html', 'bimber_mace_replace_gif_thumbnail_to_mp4_video' , 10, 4 );
	}
}

/**
 * Replaces GIF images with mp4 version in post thumbnails
 *
 * @param string $html              HTML.
 * @param int    $post_id           Post id.
 *
 * @return string
 */
function bimber_mace_replace_gif_thumbnail_to_mp4_video( $html, $post_id, $post_thumbnail_id, $size ) {
	$html = mace_replace_gif_with_shortcode( $html, $post_thumbnail_id );

	$override_content_width = is_single() ? 758 : false;

	// Change content width.
	if ( $override_content_width ) {
		global $content_width;

		$orig_content_width = $content_width;
		$content_width = $override_content_width;
	}

	$html = do_shortcode( $html );

	// Restore.
	if ( $override_content_width ) {
		$content_width = $orig_content_width;
	}

	return $html;
}

function bimber_mace_enable_featured_media_embed_lazy_load() {
	remove_filter( 'mace_lazy_load_embed', '__return_false', 99 );
}

function bimber_mace_disable_featured_media_lazy_load( $args ) {
	// Disable for the main media.
	if ( ! empty( $args['class'] ) && false !== strpos( $args['class'], 'entry-featured-media-main' ) ) {
		add_filter( 'mace_lazy_load_embed', '__return_false', 99 );
	}
}

function bimber_mace_disable_lazy_load() {
	remove_filter( 'wp_get_attachment_image_attributes', 'mace_lazy_load_attachment', 10 );
	remove_filter( 'the_content', 'mace_lazy_load_content_image' );
}


/**
 *
 *
 * @param int     $length   Video length.
 * @param WP_Post $post     Post object.
 *
 * @return
 */
function bimber_mace_post_video_length( $length, $post ) {
    if ( ! is_object( $post ) ) {
        return $length;
    }

	// If length not set.
	if ( $length <= 0 ) {
		$length = (int) get_post_meta( $post->ID, '_mace_video_length', true );
	}

	return $length;
}


/**
 * Calculate used image sizes based on theme options, post settings, etc.
 *
 * @return array Array of image sizes.
 */
function bimber_mace_calculate_used_image_sizes() {
	$r = array();

	$template_map = array(
		'grid'              => array( 'bimber-grid-standard' ),
		'grid-sidebar'      => array( 'bimber-grid-standard' ),
		'grid-s'            => array( 'bimber-grid-s' ),
		'grid-l'            => array( 'bimber-grid-l' ),
		'grid-l-sidebars'   => array( 'bimber-grid-l' ),
		'grid-fancy'        => array( 'bimber-grid-fancy' ),
		'1-sidebar-bunchy'  => array( 'bimber-grid-2of3' ),
		'masonry-stretched' => array( 'bimber-grid-masonry' ),
		'list'              => array( 'bimber-list-standard' ),
		'list-sidebar'      => array( 'bimber-list-standard' ),
		'list-s'            => array( 'bimber-list-s' ),
		'list-s-sidebar'    => array( 'bimber-list-s' ),
		'list-fancy'        => array( 'bimber-list-fancy' ),
		'upvote'            => array( 'bimber-list-s' ),
		'upvote-sidebar'    => array( 'bimber-list-s' ),
		'zigzag'            => array( 'bimber-zigzag' ),
		'zigzag-s'          => array( 'bimber-zigzag-s' ),
		'stream'            => array( 'bimber-stream' ),
		'stream-sidebar'    => array( 'bimber-stream' ),
	);



	// Process shortcodes.
	global $wpdb;
	$table_name = "{$wpdb->prefix}posts";

	foreach ( $template_map as $template => $sizes ) {
		$wpdb->get_results( $wpdb->prepare( "
	        SELECT id
	        FROM $table_name
	        WHERE post_content REGEXP %s",
			'\[bimber_collection template="' . preg_quote( $template ) . '"'
		) );

		if ( $wpdb->num_rows ) {
			//var_dump( $template );
			$r = array_merge( $r, $sizes );
		}
	}



	// Process widgets.




	// Process global featured entries.
	$template = bimber_get_theme_option( 'featured_entries', 'template' );
	switch( $template ) {
		case 'list':
			$r[] = 'bimber-list-xs';
			break;

		case 'grid':
			$ratio = bimber_get_theme_option( 'featured_entries', 'img_ratio' );
			$r[] = ('2-1' === $ratio ) ? 'bimber-grid-xs' : 'bimber-grid-xs-ratio-' . $ratio;
			break;

		default:
			break;
	}



	// Process home options.
	$settings = bimber_get_home_settings();

	// Main collection.
	if ( isset( $template_map[ $settings['template'] ] ) ) {
		$r = array_merge( $r, $template_map[ $settings['template'] ] );
	}

	// Featured entries.
	if ( isset( $template_map[ $settings['featured_entries_template'] ] ) ) {
		$r = array_merge( $r, $template_map[ $settings['featured_entries_template'] ] );
	}



	// Process global archive options.
	$settings = bimber_get_archive_settings();

	// Main collection.
	if ( isset( $template_map[ $settings['template'] ] ) ) {
		$r = array_merge( $r, $template_map[ $settings['template'] ] );
	}

	// Featured entries.
	if ( isset( $template_map[ $settings['featured_entries_template'] ] ) ) {
		$r = array_merge( $r, $template_map[ $settings['featured_entries_template'] ] );
	}



	// Process options per single category/tag/taxonomy.
	$terms = get_terms( array(
		'taxonomy'      => array( 'post_tag', 'category' ),
		'hide_empty'    => false,
		'fields'        => 'ids',
	) );

	foreach ( $terms as $term_id ) {
		$term_template = get_term_meta( $term_id, 'bimber_template', true );

		if ( isset( $template_map[ $term_template ] ) ) {
			$r = array_merge( $r, $template_map[ $term_template ] );
		}
	}



	$post_templates = bimber_get_post_templates();

	// Process global single post options.
	$post_global_settings = bimber_get_post_settings();


	if ( isset( $post_templates[ $post_global_settings['template'] ] ) ) {
		$r = array_merge( $r, $post_templates[ $post_global_settings['template'] ]['image_sizes'] );
	}

	// Process options per single post.
	foreach ( $post_templates as $post_template => $args ) {
		$query = new WP_Query( array(
			'post_type' => 'post',
			'meta_key'  => '_bimber_single_options',
			'meta_query' => array(
				'key'       => '_bimber_single_options',
				'value'     => '"' . $post_template . '"',
				'compare'   => 'LIKE',
			),
			'fields'        => 'ids',
		));

		if ( $query->have_posts() ) {
			$r = array_merge( $r, $post_templates[ $post_template ]['image_sizes'] );
		}
	}

	// Main collection.
	if ( isset( $template_map[ $settings['template'] ] ) ) {
		$r = array_merge( $r, $template_map[ $settings['template'] ] );
	}











	// Remove duplicates.
	$r = array_unique($r);

	// Add retina sizes.
	foreach ( $r as $image_size ) {
		$r[] = $image_size . '-2x';
	}

	return $r;
}


function bimber_mace_filter_used_image_sizes( $r ) {
	$r = array_merge( $r, bimber_mace_calculate_used_image_sizes() );

	return $r;
}

/**
 * Render Gallery share buttons
 */
function bimber_mace_gallery_share_buttons() {
	if ( ! bimber_shares_enabled() ) {
		return;
	}

	if ( ! bimber_is_active_share_position( 'mace_gallery' ) ) {
		return;
	}

	$share_networks = bimber_get_share_position_active_networks( 'mace_gallery' );

	foreach( $share_networks as $share_network ) {
		switch ( $share_network ) {
			case 'facebook':
				bimber_render_facebook_share_button( array(
					'share_url'   => 'mace_replace_noesc_shortlink',
					'share_text'  => 'mace_replace_noesc_title',
					'classes'     => array( 'g1-gallery-share', 'g1-gallery-share-fb' ),
				) );
				break;

			case 'twitter':
				bimber_render_twitter_share_button( array(
					'share_url'   => 'mace_replace_shortlink',
					'share_text'  => 'mace_replace_title',
					'classes'     => array( 'g1-gallery-share', 'g1-gallery-share-twitter' ),
				) );
				break;

			case 'pinterest':
				bimber_render_pinterest_share_button( array(
					'share_url'   => 'mace_replace_shortlink',
					'share_text'  => 'mace_replace_title',
					'share_media' => 'mace_replace_image_url',
					'classes'     => array( 'g1-gallery-share', 'g1-gallery-share-pinterest' ),
				) );
				break;
		}
	}
}


add_filter( 'bimber_buddypress_members_cover_image', 'bimber_mace_lazy_load_image' );
function bimber_mace_lazy_load_image( $img ){
	if ( mace_get_lazy_load_images() ) {
		$img = mace_lazy_load_content_image( $img );
	}

	return $img;
}