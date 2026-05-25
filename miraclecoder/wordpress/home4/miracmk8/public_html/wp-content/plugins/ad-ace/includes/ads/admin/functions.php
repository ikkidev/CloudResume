<?php
/**
 * Admin common functions
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
add_action( 'admin_menu', 'adace_ads_admin_menu' );

add_filter( 'manage_adace-ad_posts_columns',         'adace_custom_admin_columns' );
add_action( 'manage_adace-ad_posts_custom_column',   'adace_custom_admin_columns_data', 10, 2 );

/**
 * Register custom column headers
 *
 * @param array $columns    List of columns.
 *
 * @return mixed            Modified colum list.
 */
function adace_custom_admin_columns( $columns ) {
	if ( ! isset( $columns['featured_image'] ) ) {
		$columns['featured_image'] = esc_html__( 'Featured Image', 'adace' );
	}
	if ( ! isset( $columns['ad_shortcode'] ) ) {
		$columns['ad_shortcode'] = esc_html__( 'Shortcode', 'adace' );
	}
	return $columns;
}


/**
 * Render custom column value
 *
 * @param string $column         Column name.
 * @param int    $post_id        Post id.
 */
function adace_custom_admin_columns_data( $column, $post_id ) {
	$screen = get_current_screen();
	$post_meta = get_post_meta( $post_id, 'adace_custom', true );
	if ( ! is_array( $post_meta ) || ! is_object( $screen ) ) {
		return;
	}
	if ( 'featured_image' === $column && 'adace-ad' === $screen->post_type ) {
		$image_src = wp_get_attachment_image_src( $post_meta['adace_ad_image'], 'thumbnail');
		$image = $image_src[0];
		if ( ! empty( $image ) ) {
			echo wp_kses_post( '<img src="' . $image . '">' );
		}
	}

	if ( 'ad_shortcode' === $column && 'adace-ad' === $screen->post_type ) {?>
		<input class="widefat" readonly type="text" value="<?php echo esc_html( adace_get_shortcode_for_ad( $post_id ) );?>" onclick="this.focus(); this.select()"><?php
	}
}


/**
 * Add some shortcuts to the admin menu.
 */
function adace_ads_admin_menu() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

	global $submenu;
	$submenu['edit.php?post_type=adace-ad'][] = array(
		'Settings',
		'manage_options',
		'options-general.php?page=adace_options'
	);
}
