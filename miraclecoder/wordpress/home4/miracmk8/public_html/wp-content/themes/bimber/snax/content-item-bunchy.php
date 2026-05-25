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
?>

<article <?php post_class( 'snax-item entry-tpl-index entry-tpl-index-stickies' ); ?>>
	<div class="entry-box">

		<header class="snax-item-header">
			<?php snax_render_item_title(); ?>
		</header>

		<?php snax_get_template_part( 'items/media' ); ?>

		<?php snax_render_item_referral_link(); ?>

		<p class="snax-item-meta">
			<?php snax_render_item_author(); ?>
			<?php snax_render_item_date(); ?>
		</p>
	</div><!-- .snax-item-box -->

	<div class="snax-item-actions entry-actions">
		<?php snax_render_voting_box(); ?>
		<?php snax_render_item_share(); ?>
		<?php snax_render_item_action_links(); ?>
	</div>

	<?php snax_render_comments_box(); ?>
</article>
