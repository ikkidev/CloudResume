<?php

/**
 * User Details
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

	<?php do_action( 'bbp_template_before_user_details' ); ?>

	<div id="bbp-single-user-details">
		<div id="bbp-user-navigation">
			<ul class="g1-tab-items">
				<li class="g1-tab-item <?php if ( bbp_is_single_user_profile() ) :?>current g1-tab-item-current<?php endif; ?>">
					<span class="vcard bbp-user-profile-link">
						<a class="g1-tab url fn n" href="<?php bbp_user_profile_url(); ?>" title="<?php printf( esc_attr__( "%s's Profile", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>" rel="me"><?php _e( 'Profile', 'bbpress' ); ?></a>
					</span>
				</li>

				<li class="g1-tab-item <?php if ( bbp_is_single_user_topics() ) :?>current g1-tab-item-current<?php endif; ?>">
					<span class='bbp-user-topics-created-link'>
						<a class="g1-tab" href="<?php bbp_user_topics_created_url(); ?>" title="<?php printf( esc_attr__( "%s's Topics Started", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Topics Started', 'bbpress' ); ?></a>
					</span>
				</li>

				<li class="g1-tab-item <?php if ( bbp_is_single_user_replies() ) :?>current g1-tab-item-current<?php endif; ?>">
					<span class='bbp-user-replies-created-link'>
						<a class="g1-tab" href="<?php bbp_user_replies_created_url(); ?>" title="<?php printf( esc_attr__( "%s's Replies Created", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Replies Created', 'bbpress' ); ?></a>
					</span>
				</li>

				<?php if ( bbp_is_favorites_active() ) : ?>
					<li class="g1-tab-item <?php if ( bbp_is_favorites() ) :?>current g1-tab-item-current<?php endif; ?>">
						<span class="bbp-user-favorites-link">
							<a class="g1-tab" href="<?php bbp_favorites_permalink(); ?>" title="<?php printf( esc_attr__( "%s's Favorites", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Favorites', 'bbpress' ); ?></a>
						</span>
					</li>
				<?php endif; ?>

				<?php if ( bbp_is_user_home() || current_user_can( 'edit_users' ) ) : ?>

					<?php if ( bbp_is_subscriptions_active() ) : ?>
						<li class="g1-tab-item <?php if ( bbp_is_subscriptions() ) :?>current g1-tab-item-current<?php endif; ?>">
							<span class="bbp-user-subscriptions-link">
								<a class="g1-tab" href="<?php bbp_subscriptions_permalink(); ?>" title="<?php printf( esc_attr__( "%s's Subscriptions", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Subscriptions', 'bbpress' ); ?></a>
							</span>
						</li>
					<?php endif; ?>

					<li class="g1-tab-item <?php if ( bbp_is_single_user_edit() ) :?>current g1-tab-item-current<?php endif; ?>">
						<span class="bbp-user-edit-link">
							<a class="g1-tab" href="<?php bbp_user_profile_edit_url(); ?>" title="<?php printf( esc_attr__( "Edit %s's Profile", 'bbpress' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php _e( 'Edit', 'bbpress' ); ?></a>
						</span>
					</li>

				<?php endif; ?>

			</ul>
		</div><!-- #bbp-user-navigation -->
	</div><!-- #bbp-single-user-details -->

	<?php do_action( 'bbp_template_after_user_details' ); ?>
