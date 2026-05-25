<?php
/**
 * Template for displaying a success note after submitting new item
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="snax-note snax-note-success g1-row g1-row-layout-page">
	<div class="g1-row-inner">
		<div class="g1-column">
			<div class="snax-note-icon"></div>

			<?php if ( 1 === snax_get_user_item_count() ) : ?>
				<h2 class="snax-note-title"><?php esc_html_e( 'You\'ve submitted your first item!', 'snax' ); ?></h2>
			<?php else : ?>
				<h2 class="snax-note-title"><?php esc_html_e( 'Thank you for submitting!', 'snax' ); ?></h2>
			<?php endif; ?>

			<p>
				<?php if ( snax_is_item_pending_for_review() ) : ?>
					<?php esc_html_e( 'Your item is awaiting moderation.', 'snax' ); ?>

					<?php
					$user_item_page_url = snax_get_user_pending_items_page();

					if ( ! empty( $user_item_page_url ) ) {
						printf( wp_kses_post( __( 'You can check the status of your submission on <a href="%s">your profile page</a>.', 'snax' ) ), esc_url( $user_item_page_url ) );
					}
					?>
				<?php else : ?>
					<?php
					$user_item_page_url = snax_get_user_approved_items_page();

					if ( ! empty( $user_item_page_url ) ) {
						printf( wp_kses_post( __( 'You can view all your submissions on <a href="%s">your profile page</a>.', 'snax' ) ), esc_url( $user_item_page_url ) );
					}
					?>
				<?php endif; ?>
			</p>
		</div>
	</div>
</div><!-- .g1-row -->
