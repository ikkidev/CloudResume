<?php
// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
get_header();
?>

	<div id="primary" class="g1-primary-max bimber-buddypress-profile">
		<div id="content" role="main">

		<div id="buddypress">


<?php
if ( bp_has_groups() ) :
	while ( bp_groups() ) :
		bp_the_group();
	?>

		<?php bp_nouveau_group_hook( 'before', 'home_content' ); ?>

		<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups" class="groups-header single-headers">

			<?php bp_nouveau_group_header_template_part(); ?>

		</div><!-- #item-header -->

		<?php if ( bp_nouveau_get_temporary_setting( 'group_nav_display', bp_nouveau_get_appearance_settings( 'group_nav_display' ) ) ) : ?>
			<div class="bp-wrap g1-row g1-row-layout-page g1-row-padding-s">
				<div class="g1-row-background">
				</div>
				<div class="g1-row-inner">
					<div class="g1-column g1-column-1of3">
						<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>
					</div>
					<div id="item-body" class="item-body g1-column g1-column-2of3">
						<?php bp_nouveau_group_template_part(); ?>
					</div>
				</div>
			</div><!-- // .bp-wrap -->
		<?php else : ?>
			<div class="bp-wrap g1-row g1-row-layout-page">
				<div class="g1-row-background">
				</div>
				<div class="g1-row-inner">
					<div class="g1-column">
						<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>

						<div id="item-body" class="item-body">

							<?php bp_nouveau_group_template_part(); ?>

						</div><!-- #item-body -->
					</div>
				</div>
			</div><!-- // .bp-wrap -->
		<?php endif; ?>

		<?php bp_nouveau_group_hook( 'after', 'home_content' ); ?>

	<?php endwhile; ?>

<?php
endif;
?>
			</div><!-- #buddypress -->
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer();
