<?php
/**
 * BuddyPress Activity templates
 *
 * @since 2.3.0
 * @version 6.0.0
 */
?>

	<?php bp_nouveau_before_activity_directory_content(); ?>

	<?php if ( is_user_logged_in() ) : ?>

		<?php bp_get_template_part( 'activity/post-form' ); ?>

	<?php endif; ?>

	<?php bp_nouveau_template_notices(); ?>

	<?php if ( bp_nouveau_get_temporary_setting( 'activity_dir_layout', bp_nouveau_get_appearance_settings( 'activity_dir_layout' ) ) ) : ?>
		<div class="g1-row g1-row-layout-page g1-row-padding-s">
			<div class="g1-row-background">
			</div>
			<div class="g1-row-inner">
				<div class="g1-column g1-column-1of3">
					<?php
					add_filter( 'bp_nouveau_get_directory_type_navs_class', 'bimber_bp_nouveau_get_directory_type_navs_class_vertical' );
					bp_get_template_part( 'common/nav/directory-nav' );
					remove_filter( 'bp_nouveau_get_directory_type_navs_class', 'bimber_bp_nouveau_get_directory_type_navs_class_vertical' );
					?>
				</div>
				<div class="screen-content g1-column g1-column-2of3">
					<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

					<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>

					<div id="activity-stream" class="activity" data-bp-list="activity">

						<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

					</div><!-- .activity -->

					<?php bp_nouveau_after_activity_directory_content(); ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="g1-row g1-row-layout-page g1-row-padding-s">
			<div class="g1-row-background">
			</div>
			<div class="g1-row-inner">
				<div class="g1-column">
					<?php
					add_filter( 'bp_nouveau_get_directory_type_navs_class', 'bimber_bp_nouveau_get_directory_type_navs_class_horizontal' );
					bp_get_template_part( 'common/nav/directory-nav' );
					remove_filter( 'bp_nouveau_get_directory_type_navs_class', 'bimber_bp_nouveau_get_directory_type_navs_class_horizontal' );
					?>

					<div class="screen-content">

						<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

						<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>

						<div id="activity-stream" class="activity" data-bp-list="activity">

							<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

						</div><!-- .activity -->

						<?php bp_nouveau_after_activity_directory_content(); ?>

					</div><!-- // .screen-content -->
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php bp_nouveau_after_directory_page(); ?>
