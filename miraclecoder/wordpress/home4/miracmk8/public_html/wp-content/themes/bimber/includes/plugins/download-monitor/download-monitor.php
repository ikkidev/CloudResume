<?php
/**
 * Download Monitor plugin functions
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

require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/download-monitor/customizer.php';
require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/download-monitor/download-page.php';

add_filter( 'bimber_entry_download_count',  'bimber_dm_return_post_download_count' );
add_action( 'dlm_downloading',              'bimber_dm_update_post_total_downloads', 10, 3 );
add_action( 'save_post',                    'bimber_dm_update_post_download_list' );
add_action( 'wp_enqueue_scripts',           'bimber_dlm_enqueue_head_styles', 20 );

// Default settings.
add_filter( 'bimber_post_default_settings',         'bimber_dm_post_default_settings' );
add_filter( 'bimber_archive_default_settings',      'bimber_dm_archive_settings' );

// Posts widget integration.
add_filter( 'bimber_widget_posts_defaults',                 'bimber_dm_widget_posts_defaults' );
add_filter( 'bimber_widget_posts_entry_settings',           'bimber_dm_widget_posts_settings', 10, 2 ); // Search.
add_action( 'bimber_widget_posts_hide_elements_choices',    'bimber_dm_widget_posts_hide_elements_choices', 10, 2 );
add_filter( 'bimber_widget_posts_updated_instance',         'bimber_dm_widget_posts_updated_instance', 10, 2 );

// Post collections.
add_filter( 'bimber_post_dont_miss_hide_elements_defaults',     'bimber_dm_add_downloads_choice' );
add_filter( 'bimber_post_related_hide_elements_defaults',       'bimber_dm_add_downloads_choice' );
add_filter( 'bimber_post_more_from_hide_elements_defaults',     'bimber_dm_add_downloads_choice' );

// Trending/Hot/Popular.
add_filter( 'bimber_trending_entry_settings',   'bimber_dm_special_collection_default_settings' );
add_filter( 'bimber_hot_entry_settings',        'bimber_dm_special_collection_default_settings' );
add_filter( 'bimber_popular_entry_settings',    'bimber_dm_special_collection_default_settings' );

// VC Collection element.
add_filter( 'bimber_vc_collection_params', 'bimber_dm_vc_collection_params', 11 );

// Admin page status.
add_filter( 'display_post_states',          'bimber_dm_add_display_post_states', 10, 2 );

/**
 * Return view count
 *
 * @return string
 */
function bimber_dm_return_post_download_count() {
	global $post;

	$total = 0;

	if ( $post ) {
		$total = (int) get_post_meta( $post->ID, '_bimber_dm_download_count', true );
	}

	return $total;
}

function bimber_dm_update_post_total_downloads( $download, $version, $file_path ) {
	$id = $download->get_id();

	$args = array(
		'meta_query' => array(
			array(
				'key' => '_bimber_dm_download_ids',
				'value' => sprintf( ':%d:', $id ),
				'compare' => 'LIKE',
			)
		)
	);

	$posts = get_posts($args);

	foreach( $posts as $post ) {
		bimber_dm_update_post_download_count( $post->ID );
	}
}

function bimber_dm_update_post_download_list( $post_id ) {
	// If this is just a revision, don't process content.
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	$post  = get_post( $post_id );
	$ids   = bimber_dm_get_download_ids( $post->post_content, 'download' );

	// Store as string, to allow searching.
	update_post_meta( $post_id, '_bimber_dm_download_ids', bimber_dm_arr_to_str( $ids ) );

	bimber_dm_update_post_download_count( $post->ID, $ids );
}

/**
 * Convert array to string
 *
 * @param array $ids        List of ids.
 *
 * @return string
 */
function bimber_dm_arr_to_str( $ids ) {
	return sprintf( ':%s:', implode( ':', $ids ) );
}

/**
 * Convert string to array
 *
 * @param string $str   Ids string.
 *
 * @return array
 */
function bimber_dm_str_to_arr( $str ) {
	$str = trim( $str, ':' );

	return explode( ':', $str );
}

/**
 * Parse content for download shortcodes and return found ids
 *
 * @param string $content           Content with shortcodes.
 * @param string $shortcode         Shortcode to look for.
 *
 * @return array
 */
function bimber_dm_get_download_ids( $content, $shortcode ) {
	$pattern = get_shortcode_regex();
	$ids = array();

	// Find download shortcodes.
	if (   preg_match_all( '/'. $pattern .'/s', $content, $matches ) &&
	       array_key_exists( 2, $matches ) &&
	       in_array( $shortcode, $matches[2] ) )
	{
		foreach( $matches[2] as $shortcode_index => $shortcode_name ) {
			if ( $shortcode_name === $shortcode ) {
				$shortcode_str = $matches[0][ $shortcode_index ];

				// Extract shortcode attributes.
				$shortcode_attrs_str = str_replace( $shortcode, '', $shortcode_str );
				$shortcode_attrs_str = trim( $shortcode_attrs_str, '[]' );

				// Convert attributes into array.
				$atts = shortcode_parse_atts( $shortcode_attrs_str );

				if ( ! empty( $atts['id'] ) ) {
					$ids[] = $atts['id'];
				}
			}
		}
	}

	return array_unique( $ids );
}

/**
 * Update post total download counter
 *
 * @param int          $post_id      Post id.
 * @param bool | array $ids          Optional. List of download ids.
 */
function bimber_dm_update_post_download_count( $post_id, $ids = false ) {
	if ( ! $ids ) {
		$ids_str = get_post_meta( $post_id, '_bimber_dm_download_ids', true );

		$ids = bimber_dm_str_to_arr( $ids_str );
	}

	$count = 0;

	if ( ! empty( $ids ) && is_array( $ids ) ) {
		foreach( $ids as $id ) {
			try {
				$download = download_monitor()->service( 'download_repository' )->retrieve_single( $id );

				$count += $download->get_download_count();
			} catch ( Exception $e ) {}
		}
	}
	$count = apply_filters( 'bimber_dm_update_post_download_count', $count );
	update_post_meta( $post_id, '_bimber_dm_download_count', $count );
}

/**
 * Set downloads default visibility on a single post
 *
 * @param array $settings       Settings.
 *
 * @return array
 */
function bimber_dm_post_default_settings( $settings ) {
	$settings['elements']['downloads'] = true;

	return $settings;
}

/**
 * Set downloads default visibility on special collections (Hot, Trending etc)
 *
 * @param array $settings       Settings.
 *
 * @return array
 */
function bimber_dm_special_collection_default_settings( $settings ) {
	$settings['elements']['downloads'] = false;

	return $settings;
}

/**
 * Set downloads default visibility on archives
 *
 * @param array $settings       Settings.
 *
 * @return array
 */
function bimber_dm_archive_settings( $settings ) {
	$settings['elements']['downloads'] = true;
	$settings['featured_entries']['elements']['downloads'] = true;

	return $settings;
}

/**
 * Set downloads default visibility on search results
 *
 * @param array $settings       Settings.
 *
 * @return array
 */
function bimber_dm_widget_posts_settings( $settings, $instance ) {
	if ( isset( $instance['entry_downloads'] ) ) {
		$settings['elements']['downloads'] = $instance['entry_downloads'];
	}

	return $settings;
}

/**
 * Render the "Downloads" choice in the "Hide Elements" section.
 *
 * @param array $instance       Widget instance.
 */
function bimber_dm_widget_posts_hide_elements_choices( $widget, $instance ) {
	?>
	<input class="checkbox" type="checkbox" <?php checked( $instance['entry_downloads'], false ) ?>
	       id="<?php echo esc_attr( $widget->get_field_id( 'entry_downloads' ) ); ?>"
	       name="<?php echo esc_attr( $widget->get_field_name( 'entry_downloads' ) ); ?>"/>
	<label
		for="<?php echo esc_attr( $widget->get_field_id( 'entry_downloads' ) ); ?>"><?php esc_html_e( 'Downloads', 'bimber' ); ?></label><br/>
	<?php
}

function bimber_dm_widget_posts_defaults( $defaults ) {
	$defaults['entry_downloads'] = true;

	return $defaults;
}

function bimber_dm_widget_posts_updated_instance( $instance, $new_instance ) {
	$instance['entry_downloads'] = empty( $new_instance['entry_downloads'] );

	return $instance;
}

/**
 * Add the "Downloads" entry to the choices list
 *
 * @param array $choices    Select choices.
 *
 * @return array
 */
function bimber_dm_add_downloads_choice( $choices ) {
	$choices['downloads'] = esc_html__( 'Downloads', 'bimber' );

	return $choices;
}

function bimber_dm_vc_collection_params( $params ) {
	$params[244] = array(
		'group' 		=> __( 'Item Design', 'bimber' ),
		'type' 			=> 'dropdown',
		'heading' 		=> __( 'Downloads', 'bimber' ),
		'param_name' 	=> 'show_downloads',
		'value' 		=> array(
			__( 'Show', 'bimber' )                  => 'standard',
			__( 'Show if highlighted', 'bimber' )   => 'highlighted',
			__( 'Hide', 'bimber' )                  => 'none',
		),
		'std' 			=> 'standard',
	);

	return $params;
}




/**
 * Enqueue Download Monitor Plugin integration stylesheets.
 */
function bimber_dlm_enqueue_head_styles() {
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );

	// Global styles.
	wp_enqueue_style( 'bimber-dlm', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/dlm-' . $skin . '.min.css', array(), $version );
	wp_style_add_data( 'bimber-dlm', 'rtl', 'replace' );
}

/**
 * Add a post display state for DM special pages in the page list table
 *
 * @param array   $post_states  An array of post display states.
 * @param WP_Post $post         The current post object.
 *
 * @return array
 */
function bimber_dm_add_display_post_states( $post_states, $post ) {
    if ( 'download_page' === bimber_dm_get_download_method() && bimber_dm_get_download_page_id() === $post->ID ) {
        $post_states['bimber_dm_download_landing_page'] = _x( 'Bimber, Download landing page', 'Admin page label', 'bimber' );
    }



    return $post_states;
}
