<?php
/**
 * WooCommerce plugin functions for post meta
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 * @subpackage Related Products.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'manage_adace_coupon_posts_columns',         'adace_coupons_admin_columns' );
add_action( 'manage_adace_coupon_posts_custom_column',   'adace_coupons_admin_columns_data', 10, 2 );

/**
 * Register custom column headers
 *
 * @param array $columns    List of columns.
 *
 * @return mixed            Modified colum list.
 */
function adace_coupons_admin_columns( $columns ) {
	if ( ! isset( $columns['coupons_shortcode'] ) ) {
		$columns['coupons_shortcode'] = esc_html__( 'Shortcode', 'adace' );
	}
	return $columns;
}


/**
 * Render custom column value
 *
 * @param string $column         Column name.
 * @param int    $post_id        Post id.
 */
function adace_coupons_admin_columns_data( $column, $post_id ) {
	$screen = get_current_screen();
	if ( ! is_object( $screen ) ) {
		return;
	}
	if ( 'coupons_shortcode' === $column ) {
		?>
			<input class="widefat" readonly type="text" value="<?php echo esc_html( '[adace_coupons ids="' . $post_id . '"]' );?>" onclick="this.focus(); this.select()">
		<?php
	}
}
