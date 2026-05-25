<?php
/**
 * BuddyPress - Members Loop
 *
 * @since 3.0.0
 * @version 6.0.0
 */

bp_nouveau_before_loop(); ?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<?php bp_nouveau_pagination( 'top' ); ?>

	<ul id="members-list" class="<?php bp_nouveau_loop_classes(); ?>">
	<?php
	$bimber_template = 'list';
	if ( bp_nouveau_loop_is_grid() ) {
		$bimber_mapping  = array( 'l', 'm', 's' );
		$bimber_cols     = bp_nouveau_loop_get_grid_columns();
		$bimber_template = 'card-' . $bimber_mapping[ $bimber_cols - 2 ];
	}
	?>

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_member_user_id(); ?>" data-bp-item-component="members">

			<?php get_template_part( 'buddypress/members/members-loop-item-' . $bimber_template ); ?>

		</li>

	<?php endwhile; ?>

	</ul>

	<?php bp_nouveau_pagination( 'bottom' ); ?>

<?php
else :

	bp_nouveau_user_feedback( 'members-loop-none' );

endif;
?>

<?php bp_nouveau_after_loop(); ?>
