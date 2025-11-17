<?php
/**
 * Single Item Content Part
 *
 * @package snax
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( apply_filters( 'bimber_use_bunchy_list', false ) ) {
	get_template_part( 'snax/content-item-bunchy' );
	return;
}
?>

<article <?php post_class( 'snax-item' ); ?>>
	<header class="snax-item-header">
		<?php snax_render_item_title(); ?>
		<?php snax_render_item_parent(); ?>
	</header>

	<div class="snax-item-box">
		<?php snax_get_template_part( 'items/media' ); ?>

		<p class="snax-item-meta">
			<?php snax_render_item_author(); ?>
			<?php snax_render_item_date(); ?>
		</p>

		<div class="snax-item-actions">
			<?php snax_render_voting_box(null, 0, 'snax-voting-s'); ?>
			<?php snax_render_item_share(); ?>
			<?php
			if ( function_exists( 'snax_render_item_referral_link' ) ) {
				snax_render_item_referral_link();
			} ?>
			<?php snax_render_item_action_links(); ?>
		</div>

		<?php snax_render_comments_box(); ?>
	</div>
</article>
