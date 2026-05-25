<?php
/**
 * BuddyPress - Groups Loop
 *
 * @since 3.0.0
 * @version 7.0.0
 */

bp_nouveau_before_loop(); ?>

<?php if ( bp_get_current_group_directory_type() ) : ?>
	<p class="current-group-type"><?php bp_current_group_directory_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<?php bp_nouveau_pagination( 'top' ); ?>
	<ul id="groups-list" class="<?php bp_nouveau_loop_classes(); ?>">

		<?php
		$bimber_template = 'list';
		if ( bp_nouveau_loop_is_grid() ) {
			$bimber_mapping  = array( 'l', 'm', 's' );
			$bimber_cols     = bp_nouveau_loop_get_grid_columns();
			$bimber_template = 'card-' . $bimber_mapping[ $bimber_cols - 2 ];
		}
		?>
		<?php while ( bp_groups() ) : bp_the_group(); ?>

			<li <?php bp_group_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups">

				<?php get_template_part( 'buddypress/groups/groups-loop-item-' . $bimber_template ); ?>

			</li>

		<?php endwhile; ?>

	</ul>

	<?php bp_nouveau_pagination( 'bottom' ); ?>

<?php else : ?>

	<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>

<?php endif; ?>

<?php
bp_nouveau_after_loop();
