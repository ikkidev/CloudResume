<?php
/**
 * The Template for displaying WooCommerce products.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class="attachments-browser frame-create frame-wc-create">
	<div class="media-toolbar">
		<div class="media-toolbar-secondary">
			<label for="wc-product-filters" class="screen-reader-text"><?php esc_html_e( 'Filter by category', 'adace' ); ?></label>
			<select id="wc-product-filters" class="attachment-filters">
				<option value=""><?php esc_html_e( 'All categories', 'adace' ); ?></option>

				<?php $bstp_categories = adace_stp_get_wc_categories(); ?>
				<?php foreach ( $bstp_categories as $category_id => $category_name ): ?>
					<option value="<?php echo esc_attr( $category_id ); ?>"><?php echo esc_html( $category_name ); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="spinner"></span>
		</div>
		<div class="media-toolbar-primary search-form">
			<label for="media-search-input" class="screen-reader-text"><?php esc_html_e( 'Search Products', 'adace' ); ?></label>
			<input type="search" placeholder="Search products..." class="search" disabled="disabled">
		</div>
	</div>

	<ul class="attachments"></ul>

	<div class="media-sidebar" style="display: none;">
		<h2>
			<?php esc_html_e( 'Product Details', 'adace' ); ?>
		</h2>

		<div class="attachment-info">
			<div class="thumbnail thumbnail-image"></div>
			<div class="details">
				<h4 class="title"></h4>
			</div>
		</div>
	</div>
</div>
