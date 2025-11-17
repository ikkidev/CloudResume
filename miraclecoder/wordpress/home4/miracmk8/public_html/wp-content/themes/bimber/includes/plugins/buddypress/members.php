<?php
/**
 * BuddyPress plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'bp_directory_members_item', 'bimber_bp_members_counters', 5);
add_action( 'bimber_bp_members_counters', 'bimber_bp_members_counter_posts', 6);


/**
 * Whether or not to show the "Change Cover Image" link
 *
 * @return bool
 */
function bimber_bp_show_cover_image_change_link() {
	$show = bp_core_can_edit_settings() && bp_displayed_user_use_cover_image_header();

	return apply_filters( 'bimber_bp_show_cover_image_change_link', $show );
}

/**
 * Render the "Change Cover Image" link
 */
function bimber_bp_render_cover_image_change_link() {
	$link = bp_get_members_component_link( 'profile', 'change-cover-image' );

	?>
	<a class="g1-bp-change-image" href="<?php echo esc_url( $link ); ?>" title="<?php  esc_attr_e( 'Change Cover Image', 'buddypress' ); ?>"><?php esc_html_e( 'Change Cover Image', 'buddypress' ); ?></a>
	<?php
}


/**
 * Whether or not to show the "Change Profile Photo" link
 *
 * @return bool
 */
function bimber_bp_show_profile_photo_change_link() {
	$show = bp_core_can_edit_settings() && buddypress()->avatar->show_avatars;

	return apply_filters( 'bimber_bp_show_profile_photo_change_link', $show );
}

/**
 * Render the "Change Profile Photo" link
 */
function bimber_bp_render_profile_photo_change_link() {
	$link = bp_get_members_component_link( 'profile', 'change-avatar' );

	?>
	<a class="g1-bp-change-avatar" href="<?php echo esc_url( $link ); ?>" title="<?php esc_attr_e( 'Change Profile Photo', 'buddypress' ); ?>"><?php esc_html_e( 'Change Profile Photo', 'buddypress' ); ?></a>
	<?php
}


function bimber_bp_user_query_prev( $sql, $query ) {
	$sql['where'][] = 'u.display_name < "' . bp_get_displayed_user_fullname() . '"';
	$sql['order'] = 'DESC';

	return $sql;
}

function bimber_bp_user_query_next( $sql, $query ) {
	$sql['where'][] = 'u.display_name > "' . bp_get_displayed_user_fullname() . '"';
	$sql['order'] = 'ASC';

	return $sql;
}

function bimber_bp_get_next_user_id() {
	$user_id = 0;

	$query_args = array(
		'type'      => 'alphabetical',
		'per_page'  => 1,
	);

	add_filter( 'bp_user_query_uid_clauses', 'bimber_bp_user_query_next', 99, 2 );
	$query = new BP_User_Query( $query_args );
	remove_filter( 'bp_user_query_uid_clauses', 'bimber_bp_user_query_next', 99, 2 );

	if ( count( $query->user_ids ) ) {
		$user_id = $query->user_ids[0];
	}

	return $user_id;
}

function bimber_bp_get_prev_user_id() {
	$user_id = 0;

	$query_args = array(
		'type'      => 'alphabetical',
		'per_page'  => 1,
	);

	add_filter( 'bp_user_query_uid_clauses', 'bimber_bp_user_query_prev', 99, 2 );
	$query = new BP_User_Query( $query_args );
	remove_filter( 'bp_user_query_uid_clauses', 'bimber_bp_user_query_prev', 99, 2 );

	if ( count( $query->user_ids ) ) {
		$user_id = $query->user_ids[0];
	}

	return $user_id;
}


/**
 * Add member counters section
 */
function bimber_bp_members_counters() {
	?>
	<div class="item-counters">
		<?php do_action( 'bimber_bp_members_counters' ); ?>
	</div>
	<?php
}

/**
 * Add member posts counter
 */
function bimber_bp_members_counter_posts() {
	$post_types = apply_filters( 'bimber_default_post_types', array( 'post') );
	?>
	<div class="item-counters-counter">
		<div class="g1-epsilon g1-epsilon-1st item-counters-counter-value">
			<?php echo esc_html( count_user_posts( bp_get_member_user_id(), $post_types ) ); ?>
		</div>
		<div class="g1-meta"><?php esc_html_e( 'Posts', 'bimber' ); ?></div>
	</div>
	<?php
}