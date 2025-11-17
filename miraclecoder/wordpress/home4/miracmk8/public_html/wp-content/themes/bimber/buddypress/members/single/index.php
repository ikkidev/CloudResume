<?php
/**
 * The Template for displaying pages.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10.3
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
get_header();
?>
	<div id="primary" class="g1-primary-max bimber-buddypress-profile">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope=""
						 itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">
					<div id="buddypress">

						<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>

						<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">
							<?php bp_nouveau_member_header_template_part(); ?>
						</div><!-- #item-header -->

						<?php if ( bp_nouveau_get_temporary_setting( 'user_nav_display', bp_nouveau_get_appearance_settings( 'user_nav_display' ) ) ) : ?>
							<div class="bp-wrap g1-row g1-row-layout-page g1-row-padding-s">
								<div class="g1-row-background">
								</div>
								<div class="g1-row-inner">
								    <div class="g1-sidebar g1-sidebar-padded g1-column g1-column-1of3">
									    <?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>
									</div>
								    <div id="item-body" class="item-body g1-column g1-column-2of3">
										<?php bp_nouveau_member_template_part(); ?>
								    </div><!-- #item-body -->
								</div>
							</div><!-- // .bp-wrap -->
						<?php else : ?>
							<div class="bp-wrap g1-row g1-row-layout-page">
								<div class="g1-row-background">
								</div>
								<div class="g1-row-inner">
									<div class="g1-column">
										<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>

										<div id="item-body" class="item-body">

											<?php bp_nouveau_member_template_part(); ?>

										</div><!-- #item-body -->
									</div>
								</div>
							</div><!-- // .bp-wrap -->
						<?php endif; ?>

						<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>

					</div><!-- #buddypress -->

				</article><!-- #post-## -->

			<?php
				endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
