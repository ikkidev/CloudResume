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

<div class="attachments-browser frame-edit frame-wc-edit">
	<div class="media-toolbar">
		<div class="media-toolbar-secondary">
			<span class="spinner"></span>
			<div class="instructions"><?php esc_html_e( 'Drag and drop to reorder items.', 'adace' ); ?></div>
		</div>
		<div class="media-toolbar-primary search-form">
		</div>
	</div>

	<ul class="attachments ui-sortable">

	</ul>

	<div class="media-sidebar"></div>
</div>
