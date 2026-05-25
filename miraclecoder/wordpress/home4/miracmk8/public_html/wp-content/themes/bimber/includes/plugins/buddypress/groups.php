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

add_action( 'bp_before_groups_loop', 'bimber_bp_before_groups_loop' );
add_action( 'bp_after_groups_loop', 'bimber_bp_after_groups_loop' );



/**
 * Whether or not to show the "Change Cover Image" link
 *
 * @return bool
 */
function bimber_bp_show_group_cover_image_change_link() {
	$show = bp_core_can_edit_settings() && bp_group_use_cover_image_header();

	return apply_filters( 'bimber_bp_show_group_cover_image_change_link', $show );
}

/**
 * Render the "Change Cover Image" link
 */
function bimber_bp_render_group_cover_image_change_link() {
	$group_link = bp_get_group_permalink();
	$admin_link = trailingslashit( $group_link . 'admin' );
	$link = trailingslashit( $admin_link . 'group-cover-image' );

	?>
	<a class="g1-bp-change-image" href="<?php echo esc_url( $link ); ?>" title="<?php  esc_attr_e( 'Change Cover Image', 'buddypress' ); ?>"><?php esc_html_e( 'Change Cover Image', 'buddypress' ); ?></a>
	<?php
}


/**
 * Whether or not to show the "Change Profile Photo" link
 *
 * @return bool
 */
function bimber_bp_show_group_photo_change_link() {
	$show = bp_core_can_edit_settings() && ! bp_disable_group_avatar_uploads() && buddypress()->avatar->show_avatars;

	return apply_filters( 'bimber_bp_show_group_photo_change_link', $show );
}

/**
 * Render the "Change Profile Photo" link
 */
function bimber_bp_render_group_photo_change_link() {
	$group_link = bp_get_group_permalink();
	$admin_link = trailingslashit( $group_link . 'admin' );
	$link = trailingslashit( $admin_link . 'group-avatar' );

	?>
	<a class="g1-bp-change-avatar" href="<?php echo esc_url( $link ); ?>" title="<?php esc_attr_e( 'Change Group Photo', 'buddypress' ); ?>"><?php esc_html_e( 'Change Group Photo', 'buddypress' ); ?></a>
	<?php
}



function bimber_bp_before_groups_loop() {
	add_filter( 'bp_get_group_join_button', 'bimber_bp_get_group_join_button', 10, 2 );
}

function bimber_bp_after_groups_loop() {
	remove_filter( 'bp_get_group_join_button', 'bimber_bp_get_group_join_button', 10, 2 );
}

function bimber_bp_get_group_join_button( $button, $group ) {
	$button['link_class'] .= ' g1-button g1-button-simple g1-button-xs';

	return $button;
}



add_filter( 'bp_nouveau_get_group_create_steps_classes', 'bimber_bp_nouveau_get_group_create_steps_classes' );
function bimber_bp_nouveau_get_group_create_steps_classes( $classes ) {
	$classes .= ' horizontal';

	return $classes;
}
