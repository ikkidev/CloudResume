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

add_action( 'add_meta_boxes_adace_coupon', 'adace_coupon_metabox' );
/**
 * Register ad metaboxes.
 */
function adace_coupon_metabox() {
	add_meta_box(
		'adace_coupon_metabox',
		esc_html( 'Coupon Details', 'adace' ),
		'adace_coupon_metabox_render_callback'
	);
	add_meta_box(
		'adace_coupon_shortcode_metabox',
		esc_html( 'Coupon Options', 'adace' ),
		'adace_coupon_shortcode_render_callback'
	);
}

/**
 * Meta box renderer.
 *
 * @param object $post Post object.
 */
function adace_coupon_metabox_render_callback( $post ) {
	$current_discount             = get_post_meta( $post->ID, 'adace_coupon_discount', true );
	$current_discount_code        = get_post_meta( $post->ID, 'adace_coupon_discount_code', true );
	$current_time                 = current_time( 'M d, Y' );
	$current_discount_valid_from  = get_post_meta( $post->ID, 'adace_coupon_valid_from', true );
	if ( ! $current_discount_valid_from ) {
		$current_discount_valid_from = $current_time;
	}
	$current_discount_valid_until = get_post_meta( $post->ID, 'adace_coupon_valid_until', true );
	if ( ! $current_discount_valid_until ) {
		$current_discount_valid_until = $current_time;
	}
	?>
		<fieldset>
			<table class="form-table form-wrap">
					<tbody>
					<tr>
						<th scope="row"><label for="adace_coupon_discount"><?php esc_html_e( 'Discount', 'adace' ); ?></label></th>
						<td>
							<input type="text" name="adace_coupon_discount" placeholder="<?php esc_html_e( 'e.g. 25% OFF', 'adace'); ?>" class="widefat" id="adace_coupon_discount" value="<?php echo( wp_kses_post( $current_discount ) ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="adace_coupon_discount_code"><?php esc_html_e( 'Coupon Code', 'adace' ); ?></label></th>
						<td>
							<input type="text" class="widefat" name="adace_coupon_discount_code" placeholder="<?php esc_html_e( 'e.g. BLACK_FRIDAY_25_OFF', 'adace'); ?>" id="adace_coupon_discount_code" value="<?php echo( wp_kses_post( $current_discount_code ) ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="adace_coupon_valid_from"><?php esc_html_e( 'Valid From', 'adace' ); ?></label></th>
						<td>
							<div class="adace-datapicker-wrap">
								<input type="text" class="adace-datapicker" name="adace_coupon_valid_from" id="adace_coupon_valid_from" value="<?php echo( wp_kses_post( $current_discount_valid_from ) ); ?>">
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="adace_coupon_valid_until"><?php esc_html_e( 'Valid Until', 'adace' ); ?></label></th>
						<td>
							<div class="adace-datapicker-wrap">
								<input type="text" class="adace-datapicker" name="adace_coupon_valid_until" id="adace_coupon_valid_until" value="<?php echo( wp_kses_post( $current_discount_valid_until ) ); ?>">
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( 'adace_save_coupon_metabox', 'adace_save_coupon_metabox_nonce' ); ?>
		</fieldset>
	<?php
}

add_action( 'save_post', 'adace_save_coupon_metabox', 10, 1 );
/**
 * Meta box saver.
 *
 * @param string $post_id Post id.
 */
function adace_save_coupon_metabox( $post_id ) {
	// Array for sanitized data.
	$input_sanitized = array();
	// Sanitize nonce.
	if ( isset( $_POST['adace_save_coupon_metabox_nonce'] ) ) {
		$input_sanitized['nonce'] = filter_var( $_POST['adace_save_coupon_metabox_nonce'], FILTER_SANITIZE_STRING );
	} else {
		return;
	}
	// Verify that nonce.
	if ( ! wp_verify_nonce( $input_sanitized['nonce'], 'adace_save_coupon_metabox' ) ) {
		return;
	}

	if ( isset( $_POST['adace_coupon_discount'] ) ) {
		$input_sanitized['adace_coupon_discount'] = filter_var( $_POST['adace_coupon_discount'], FILTER_SANITIZE_STRING );
	} else {
		$input_sanitized['adace_coupon_discount'] = '';
	}
	update_post_meta( $post_id, 'adace_coupon_discount', $input_sanitized['adace_coupon_discount'] );

	if ( isset( $_POST['adace_coupon_discount_code'] ) ) {
		$input_sanitized['adace_coupon_discount_code'] = filter_var( $_POST['adace_coupon_discount_code'], FILTER_SANITIZE_STRING );
	} else {
		$input_sanitized['adace_coupon_discount_code'] = '';
	}
	update_post_meta( $post_id, 'adace_coupon_discount_code', $input_sanitized['adace_coupon_discount_code'] );

	if ( isset( $_POST['adace_coupon_valid_from'] ) ) {
		$input_sanitized['adace_coupon_valid_from'] = filter_var( $_POST['adace_coupon_valid_from'], FILTER_SANITIZE_STRING );
	} else {
		$input_sanitized['adace_coupon_valid_from'] = '';
	}
	update_post_meta( $post_id, 'adace_coupon_valid_from', $input_sanitized['adace_coupon_valid_from'] );

	if ( isset( $_POST['adace_coupon_valid_until'] ) ) {
		$input_sanitized['adace_coupon_valid_until'] = filter_var( $_POST['adace_coupon_valid_until'], FILTER_SANITIZE_STRING );
	} else {
		$input_sanitized['adace_coupon_valid_until'] = '';
	}
	update_post_meta( $post_id, 'adace_coupon_valid_until', $input_sanitized['adace_coupon_valid_until'] );

}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_coupon_shortcode_render_callback( $post ) {
	?>
		<fieldset id="adace-ad-settings">
			<div class="adace-general-section adace-settings-section">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="col"><?php esc_html_e( 'Shortcode', 'adace' ); ?></th>
						<th scope="col"><?php esc_html_e( 'PHP Code', 'adace' ); ?></th>
					</tr>
					<tr>
						<td>
							<input readonly type="text" value="<?php echo esc_html( '[adace_coupons ids="' . $post->ID . '"]' );?>" onclick="this.focus(); this.select()" class="code large-text">
						</td>
						<td>
							<input readonly type="text" value="<?php echo esc_html( "<?php echo do_shortcode('[adace_coupons ids=\"" . $post->ID . "\"]'); ?>" );?>" onclick="this.focus(); this.select()" class="code large-text">
						</td>
					</tr>
				</tbody>
			</table>
			</div>
		</fieldset>
	<?php
}
