<?php
/**
 * Template for the Shop the Post modal.
 *
 * @package AceAce
 */
?>

<div class="adace-stp-modal-container">
	<div class="media-modal wp-core-ui">
		<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php _e( 'Close media panel', 'adace' ); ?></span></span></button>

		<div class="media-modal-content">
			<div class="media-frame wp-core-ui">
				<div class="media-frame-menu">
					<div class="media-menu frame-wc-create">
							<a href="#" class="state state-create media-menu-item adace-stp-create active"><?php esc_html_e( 'Create Collection', 'adace' ); ?></a>
					</div>
					<div class="media-menu frame-wc-edit">
						<a href="#" class="state state-create state-default media-menu-item adace-stp-cancel"><?php esc_html_e( '&#8592; Cancel Collection', 'adace' ); ?></a>
						<a href="#" class="state state-update media-menu-item adace-stp-close"><?php esc_html_e( '&#8592; Cancel Collection', 'adace' ); ?></a>
						<div class="separator"></div>
						<a href="#" class="media-menu-item adace-stp-edit active"><?php esc_html_e( 'Edit Collection', 'adace' ); ?></a>
						<a href="#" class="media-menu-item adace-stp-add"><?php esc_html_e( 'Add to Collection', 'adace' ); ?></a>

					</div>
				</div>
				<div class="media-frame-title">
					<div class="frame-wc-create">
						<h1 class="state state-create"><?php esc_html_e( 'Create Collection', 'adace' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
						<h1 class="state state-single-selection"><?php esc_html_e( 'Select Product', 'adace' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
					</div>
					<div class="frame-wc-edit">
						<h1><?php esc_html_e( 'Edit Collection', 'adace' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
					</div>
				</div>

				<div class="media-frame-router">
					<div class="frame-wc-create media-router active">
						<a href="#" class="media-menu-item active"><?php esc_html_e( 'WooCommerce Products', 'adace' ); ?></a>
					</div>
				</div>
				<div class="media-frame-content">
					<?php include( trailingslashit( dirname( __FILE__ ) ) . 'wc-create-content.php' ); ?>
					<?php include( trailingslashit( dirname( __FILE__ ) ) . 'wc-edit-content.php' ); ?>
				</div>

				<div class="media-frame-toolbar">
					<?php include( trailingslashit( dirname( __FILE__ ) ) . 'wc-create-toolbar.php' ); ?>
					<?php include( trailingslashit( dirname( __FILE__ ) ) . 'wc-edit-toolbar.php' ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="media-modal-backdrop"></div>
</div>
