<?php
/**
 * Facebook Comments plugin functions
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

// Disable plugin default location.
remove_filter( 'the_content', 'fbcommentbox', 100 );
remove_action( 'wp_footer', 'fbmlsetup', 100 );

// Render it again.
add_action( 'bimber_after_comments', 'bimber_render_facebook_comments', 100 );

// Ajax.
add_action( 'wp_ajax_bimber_update_fb_comment_count', 			'bimber_ajax_update_fb_comment_count' );
add_action( 'wp_ajax_nopriv_bimber_update_fb_comment_count',	'bimber_ajax_update_fb_comment_count' );
add_action( 'wp_ajax_bimber_load_fbcommentbox', 				'bimber_ajax_load_fbcommentbox' );
add_action( 'wp_ajax_nopriv_bimber_load_fbcommentbox',			'bimber_ajax_load_fbcommentbox' );

// Reister new comment type.
add_filter( 'bimber_comment_types', 'bimber_fb_register_comment_type', 12 );

// Add Facebook comments to post's global comments counter.
add_filter( 'get_comments_number', 'bimber_add_fb_comments_number');

// Subtract Facebook comments from WP type comments number.
add_filter( 'bimber_wp_comment_count', 'bimber_fb_subtract_comments_number' );

// Use the App Id in SDK url.
add_filter( 'bimber_facebook_sdk_config', 'bimber_fb_override_sdk_config' );

add_filter( 'option_fbcomments', 'bimber_fb_change_color_scheme' );

/**
 * Render Bimber's Facebook comments
 */
function bimber_render_facebook_comments() {
	$on_demand = apply_filters( 'bimber_fb_comments_on_demand', true );

	$classes = array(
		'g1-comment-type',
		'g1-comment-type-fb',
	);

	if ( $on_demand ) {
		$classes[] = 'g1-on-demand';
	}
	?>
	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>">
		<p class="g1-notice g1-notice-loading"><?php esc_html_e( 'Loading&hellip;', 'bimber' ); ?></p>

		<div class="g1-comment-count" data-bimber-fb-comment-count="<?php echo absint( bimber_get_fb_comment_count() ); ?>" data-bimber-post-id="<?php echo absint( get_the_ID() ); ?>" data-bimber-nonce="<?php echo wp_create_nonce( 'bimber-update-fb-comment-count' ); ?>">
		<span class="fb_comments_count" data-bimber-graph-api-url="<?php echo esc_url( get_permalink() ); ?>">0</span>
		</div>

		<div class="g1-comment-list">
			<?php if ( ! $on_demand ) : ?>

				<?php echo fbcommentbox(''); ?>

			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Return Facebook comment count for the post
 *
 * @param WP_Post $post			Optional. Post object or id.
 *
 * @return int
 */
function bimber_get_fb_comment_count( $post = null ) {
	$count = 0;

	$post = get_post( $post );

	if ( $post ) {
		$count = (int) get_post_meta( $post->ID, '_bimber_fb_comment_count', true );
	}

	return $count;
}

/**
 * Update Facebook comment count for the post
 *
 * @param int     $count		Value to change.
 * @param WP_Post $post			Optional. Post object or id.
 *
 * @return int
 */
function bimber_update_fb_comment_count( $count, $post = null ) {
	$post = get_post( $post );

	update_post_meta( $post->ID, '_bimber_fb_comment_count', $count );
}

/**
 * Ajax action to update current Facebook comment count
 */
function bimber_ajax_update_fb_comment_count() {
	check_ajax_referer( 'bimber-update-fb-comment-count', 'security' );

	$post_id = (int) filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );

	if ( 0 === $post_id ) {
		echo -1;
		exit;
	}

	$count = (int) filter_input( INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT );

	bimber_update_fb_comment_count( $count, $post_id );

	echo 1;
	exit;
}

/**
 * Increase number of comments by adding Facebook comments
 *
 * @param int $number		Current comments number.
 * @return int
 */
function bimber_add_fb_comments_number($number ) {
	$number += bimber_get_fb_comment_count();

	return $number;
}

/**
 * Load FB comments async
 */
function bimber_ajax_load_fbcommentbox() {
	$post_id = (int) filter_input( INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT );

	if ( 0 === $post_id ) {
		echo -1;
		exit;
	}

	global $wp_query;

	// Make is_single() works.
	$wp_query->is_single = true;

	$query = new WP_Query( array(
		'p'                 => $post_id,
		'post_type'         => 'any',
		'posts_per_page'    => 1,
	) );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) { $query->the_post();
			fbmlsetup();
			echo fbcommentbox('');
		}

		wp_reset_postdata();
	}
	exit;
}

/**
 * Register FB comment type
 *
 * @param array $types			List of types.
 *
 * @return array
 */
function bimber_fb_register_comment_type( $types ) {
	$count = bimber_get_fb_comment_count();

	$is_post = is_single();
	$is_page = is_page();

	$options = get_option( 'fbcomments', array() );

	if ( ! isset( $options['posts'] ) ) { $options['posts'] = ''; }
	if ( ! isset( $options['pages'] ) ) { $options['pages'] = ''; }

	// Skip if not enabled for the "Singular Posts".
	if ( $is_post && 'on' !== $options['posts'] ) {
		return $types;
	}

	// Skip if not enabled for the "Pages".
	if ( $is_page && 'on' !== $options['pages'] ) {
		return $types;
	}

	// FB Comments can be disabled per post, via metabox.
	if ( $is_post || $is_page ) {
		$disable_fbc = get_post_meta( get_the_ID(), '_disable_fbc', true );

		if ( $disable_fbc ) {
			return $types;
		}
	}

	if ( $count > 0 ) {
		$types['fb'] = sprintf( _x( 'Facebook <span class="count">%d</span>', 'Type of comments', 'bimber' ), $count );
	} else {
		$types['fb'] = _x( 'Facebook', 'Type of comments', 'bimber' );
	}

	return $types;
}

/**
 * Subtract Facebook comments from total number of comments
 *
 * @param int $wp_comments				Number of comments.
 *
 * @return int
 */
function bimber_fb_subtract_comments_number( $wp_comments ) {
	$fb_comments = bimber_get_fb_comment_count();

	return $wp_comments - $fb_comments;
}


/**
 * Override SDK config with FB Comments plugin settings
 *
 * @param array $config 		SDK config.
 *
 * @return array
 */
function bimber_fb_override_sdk_config( $config ) {
	$options = get_option('fbcomments');

	if ( ! empty( $options['appID'] ) ) {
		$config['appId'] = $options['appID'];
	}

	if ( ! empty( $options['language'] ) ) {
		$config['language'] = $options['language'];
	}

	return $config;
}

/**
 * Load color scheme based on theme option
 *
 * @param array $value          FB Comments options.
 *
 * @return array
 */
function bimber_fb_change_color_scheme( $value ) {
	$skin = bimber_get_theme_option( 'global', 'skin' );

	if ( in_array( $skin, array( 'light', 'dark' ) ) ) {
		$value['scheme'] = $skin;
	}

	return $value;
}
