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

// Force Nouveau Template Pack.
add_theme_support('buddypress-use-nouveau');


//require_once trailingslashit( get_parent_theme_file_path() ) . 'includes/plugins/buddypress/customizer.php';

// Members.
add_action( 'bp_member_header_actions', 'bimber_bp_member_add_button_class_filters', 1 );
add_action( 'bp_member_header_actions', 'bimber_bp_member_remove_button_class_filters', 9999 );

// XProfiles.
//add_action( 'wp_loaded', 'bimber_bp_setup_xprofile_fields' );
//add_action( 'bp_after_profile_field_content', 'bimber_bp_profile_elements', 9 );

// Groups.
//add_action( 'bp_group_header_actions', 'bimber_bp_group_add_button_class_filters', 1 );
//add_action( 'bp_group_header_actions', 'bimber_bp_group_remove_button_class_filters', 9999 );





add_filter( 'author_link', 'bimber_bp_get_author_link', 10, 3 );



add_action( 'wp', 'bimber_bp_update_last_online',9 );
//
//add_action( 'widgets_init', 'bimber_bp_widgets_init' );
//add_filter( 'bimber_author_info_box_bio', 'bimber_buddypress_use_description_for_author_info_box', 10, 2 );



// We will provide our own stylesheets.
add_action( 'wp_enqueue_scripts', 'bimber_bp_enqueue_head_styles', 20 );
add_action( 'wp_enqueue_scripts', 'bimber_bp_enqueue_scripts', 20 );
add_filter( 'bp_nouveau_css_dependencies', 'bimber_bp_nouveau_css_dependencies', 999 );




/**
 * Change the width & height of cover images.
 *
 * @param array $settings
 *
 * @return array
 */
function bimber_bp_cover_image_settings( $settings = array() ) {
	$settings['width']  = 1920;
	$settings['height'] = 360;

	return $settings;
}
add_filter( 'bp_before_members_cover_image_settings_parse_args', 'bimber_bp_cover_image_settings', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'bimber_bp_cover_image_settings', 10, 1 );

function bimber_bp_cover_image_callback( $params = array() ) {
	if ( empty( $params ) ) {
		return;
	}

	return '
        /* Cover image - Do not forget this part */
        #buddypress #header-cover-image {
            height: ' . $params["height"] . 'px;
            background-image: url(' . $params['cover_image'] . ');
        }
    ';
}

function bimber_bp_cover_image_css( $settings = array() ) {
	$settings['callback'] = 'bimber_bp_cover_image_callback';

	return $settings;
}
add_filter( 'bp_before_members_cover_image_settings_parse_args', 'bimber_bp_cover_image_css', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'bimber_bp_cover_image_css', 10, 1 );





function bimber_bp_member_add_button_class_filters() {
	add_filter( 'bp_get_add_friend_button', 			'bimber_bp_get_menu_item_button' );
	add_filter( 'bp_follow_get_add_follow_button',      'bimber_bp_get_menu_item_button' );
	add_filter( 'bp_get_send_public_message_button',    'bimber_bp_get_menu_item_button' );
	add_filter( 'bp_get_send_message_button_args',      'bimber_bp_get_menu_item_button' );
}

function bimber_bp_member_remove_button_class_filters() {
	remove_filter( 'bp_get_add_friend_button', 			'bimber_bp_get_menu_item_button' );
	remove_filter( 'bp_follow_get_add_follow_button',   'bimber_bp_get_menu_item_button' );
	remove_filter( 'bp_get_send_public_message_button', 'bimber_bp_get_menu_item_button' );
	remove_filter( 'bp_get_send_message_button_args',   'bimber_bp_get_menu_item_button' );


	remove_filter( 'bp_get_add_friend_button', 			'bimber_bp_get_solid_button' );
}

function bimber_bp_group_add_button_class_filters() {
	add_filter( 'bp_get_group_join_button', 			'bimber_bp_get_solid_button' );
}

function bimber_bp_group_remove_button_class_filters() {
	remove_filter( 'bp_get_group_join_button', 			'bimber_bp_get_solid_button' );
}

/**
 * Adjust BuddyPress button classes.
 */
function bimber_bp_get_menu_item_button( $button ) {
	if ( ! is_array( $button ) ) {
		return $button;
	}

	if ( ! isset( $button['wrapper_class'] ) ) {
		$button['wrapper_class'] = 'menu-item';
	} else {
		$button['wrapper_class'] .= ' menu-item';
	}

	return $button;
}

function bimber_bp_get_solid_button( $button ) {
	$button['link_class'] .= ' g1-button g1-button-m g1-button-simple';


//					'component'         => 'friends',
//					'must_be_logged_in' => true,
//					'block_self'        => true,
//					'wrapper_class'     => 'friendship-button pending_friend',
//					'wrapper_id'        => 'friendship-button-' . $potential_friend_id,
//					'link_href'         => wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/cancel/' . $potential_friend_id . '/', 'friends_withdraw_friendship' ),
//					'link_text'         => __( 'Cancel Friendship Request', 'buddypress' ),
//					'link_id'           => 'friend-' . $potential_friend_id,
//					'link_rel'          => 'remove',
//					'link_class'        => 'friendship-button pending_friend requested'


	// Add our special key for tracking purposes
	$button['g1'] = true;

	return $button;
}



function bimber_render_markup_before_list_items_loop() {
	echo '<div class="g1-indent">';
}

function bimber_render_markup_after_list_items_loop() {
	echo '</div>';
}

/**
 * Return current user profile url
 *
 * @param string $link          Author posts link.
 * @param int 	 $author_id		Author id.
 *
 * @return string
 */
function bimber_bp_get_author_link( $link, $author_id ) {
	$link = bp_core_get_user_domain( $author_id );

	return $link;
}





/**
 * BP actions placeholder for logged out users
 */
function bimber_bp_actions_placeholder() {
	$items = array();

	if ( function_exists( 'bp_follow_add_follow_button' ) ) {
		$items[] = _x( 'Follow', 'Button', 'bp-follow' );
	}

	if ( bp_is_active( 'friends' ) ) {
		$items[] = __( 'Add Friend', 'buddypress' );
	}

	if ( ! count( $items ) ) {
		return;
	}
	?>

	<?php bimber_bp_open_action_dropdown(); ?>
	<?php foreach ( $items as $index => $text ) : ?>
		<div class="generic-button menu-item"><button class="snax-login-required g1-button g1-button-simple g1-button-m"><?php echo esc_html( $text ); ?></button></div>
	<?php endforeach; ?>
	<?php bimber_bp_close_action_dropdown(); ?>
<?php

//	<a href="#" class="snax-login-required friendship-button not_friends add g1-button g1-button-m g1-button-simple" id="friend-1" rel="add">Public Message</a>
}

/**
 * BP group actions placeholder for logged out users
 */
function bimber_bp_group_actions_placeholder() {
	$items = array();

	global $groups_template;
	$group =& $groups_template->group;

	if ( 'public' === $group->status ) {
		$items[] = __( 'Join Group', 'buddypress' );
	}

	if ( ! count( $items ) ) {
		return;
	}
	?>
	<?php bimber_bp_open_action_dropdown(); ?>
	<?php foreach ( $items as $index => $text ) : ?>
		<div class="generic-button menu-item"><button class="snax-login-required g1-button g1-button-simple g1-button-m"><?php echo esc_html( $text ); ?></button></div>
	<?php endforeach; ?>
	<?php bimber_bp_close_action_dropdown(); ?>
	<?php
}




/**
 * Create Xprofile fields.
 */
function bimber_bp_setup_xprofile_fields() {
	if ( ! bp_is_active( 'xprofile' ) ) {
		return;
	}

	$short_id = get_option( 'bimber_bp_short_field_id', false );
	$long_id = get_option( 'bimber_bp_long_field_id', false );

	// Fallback, so we don't break the old sites.
	$short_id_by_name = xprofile_get_field_id_from_name( bimber_bp_get_short_description_field_name() );
	$long_id_by_name = xprofile_get_field_id_from_name( bimber_bp_get_long_description_field_name() );

	if ( ! $short_id && $short_id_by_name ) {
		update_option( 'bimber_bp_short_field_id', $short_id_by_name );
		$short_id = $short_id_by_name;
	}

	if ( ! $long_id && $long_id_by_name ) {
		update_option( 'bimber_bp_long_field_id', $long_id_by_name );
		$long_id = $long_id_by_name;
	}

	// Create fields if necessary.
	if ( ! $short_id ) {
		$args = array(
			'field_group_id' 	=> 1,
			'type' 				=> 'textbox',
			'name' 				=> bimber_bp_get_short_description_field_name(),
		);
		$id = xprofile_insert_field( $args );
		update_option( 'bimber_bp_short_field_id', $id );
	}

	if ( ! $long_id ) {
		$args = array(
			'field_group_id' 	=> 1,
			'type' 				=> 'textarea',
			'name' 				=> bimber_bp_get_long_description_field_name(),
		);
		$id = xprofile_insert_field( $args );
		update_option( 'bimber_bp_long_field_id', $id );
	}
}

/**
 * Short description field ID.
 */
function bimber_bp_get_short_description_field_id() {
	$short_id = get_option( 'bimber_bp_short_field_id', false );

	// Fallback.
	if ( ! $short_id ) {
		return bimber_bp_get_short_description_field_name();
	}

	return $short_id;
}

/**
 * Long description field ID.
 */
function bimber_bp_get_long_description_field_id() {
	$long_id = get_option( 'bimber_bp_long_field_id', false );

	// Fallback.
	if ( ! $long_id ) {
		return bimber_bp_get_long_description_field_name();
	}

	return $long_id;
}

/**
 * Short description field name.
 */
function bimber_bp_get_short_description_field_name() {
	return __( 'Short Description', 'bimber' );
}

/**
 * Long description field name.
 */
function bimber_bp_get_long_description_field_name() {
	return __( 'Long Description', 'bimber' );
}

/**
 * Add our fields to BP profile.
 */
function bimber_bp_profile_elements() {
	global $group;

	if ( ! $group  ) {
		return;
	}

	// Don't add our elements to groups other the the Primary (which can't be deleted).
	if ( $group->can_delete ) {
		return;
	}

	$data = get_userdata( bp_displayed_user_id() );
	$registered = $data->user_registered;
	$last_online = get_user_meta( bp_displayed_user_id(), 'bimber_last_online', true );
	if ( ! empty( $last_online ) ) {
		$since_last_check = time() - $last_online;
		if ( $since_last_check > 300 ) {
			$last_online = date( 'h:i d/m/Y', $last_online );
		} else {
			$last_online = __( 'Less than five minutes ago', 'bimber' );
		}
	} else {
		$last_online = __( 'Never', 'bimber' );
	}
	?>
	<div class="bp-widget base">
		<h2><?php esc_html_e( 'Additional info', 'bimber' ); ?></h2>
		<table class="profile-fields">
			<tbody>
				<tr>
					<td class="label">
						<?php echo esc_html__( 'Member since', 'bimber' );?>
					</td>
					<td class="data"><?php echo date( 'd/m/Y', strtotime( $registered ) );?><p>
					</p></td>
				</tr>
				<tr>
					<td class="label">
						<?php echo esc_html__( 'Last online', 'bimber' );?>
					</td>
					<td class="data"><?php echo esc_html( $last_online); ;?><p>
					</p></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Update user last online meta
 */
function bimber_bp_update_last_online() {
	$last_online = get_user_meta( get_current_user_id(), 'bimber_last_online', true );
	if ( $last_online ) {
		$since_last_check = time() - $last_online;
	} else {
		$since_last_check = 301;
	}
	if ( is_user_logged_in() && ! is_admin() && $since_last_check > 300 ) {
		update_user_meta( get_current_user_id(), 'bimber_last_online', time() );
	}
}

/**
 * Init widgets
 */
function bimber_bp_widgets_init() {
	register_widget( 'Bimber_Widget_Featured_Author' );
}

/**
 * Use long description instead of bio in author info box.
 *
 * @param string $bio Bio.
 * @param int    $user_id User id.
 * @return string
 */
function bimber_buddypress_use_description_for_author_info_box( $bio, $user_id ) {
	if ( function_exists( 'xprofile_get_field_data' ) ) {
		// Get ID by name. If user deleted that field, it won't be used.
		$long_id = xprofile_get_field_id_from_name( bimber_bp_get_long_description_field_name() );

		if ( $long_id ) {
			$long_field = xprofile_get_field( $long_id );

			if ( ! $long_field->id || (int) $long_id !== (int) $long_field->id ) {
				return $bio;
			}

			$description = xprofile_get_field_data( $long_id, $user_id );

			if ( ! empty( $description ) ) {
				$bio = $description;
			}
		}
	}

	return $bio;
}











//add_action( 'bp_directory_members_item', 'bimber_bp_render_directory_members_item_actions', 100 );
/**
 * Render member actions on BuddyPRess directory pages.
 */
function bimber_bp_render_directory_members_item_actions() {
	?>
	<div class="item-actions">
		<?php do_action( 'bimber_bp_directory_members_item_actions' ); ?>
	</div>
	<?php
}


//add_action( 'bimber_bp_directory_members_item_actions', 'bimber_buddypress_render_add_friend_button' );
function bimber_buddypress_render_add_friend_button() {
	if ( bp_is_active( 'friends' ) ) {
		$friend_id = bp_get_member_user_id();
		$friend_status = false;

		// Use proper HTML markup via filter.
		add_filter( 'bp_get_add_friend_button', 'bimber_bp_get_directory_members_item_button' );
		bp_add_friend_button($friend_id, $friend_status);
		remove_filter( 'bp_get_add_friend_button', 'bimber_bp_get_directory_members_item_button' );
	}
}

function bimber_bp_get_directory_members_item_button( $button ) {
//	$button = array(
//		'id'                => 'not_friends',
//		'component'         => 'friends',
//		'must_be_logged_in' => true,
//		'block_self'        => true,
//		'wrapper_class'     => 'friendship-button not_friends',
//		'wrapper_id'        => 'friendship-button-' . $potential_friend_id,
//		'link_href'         => wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $potential_friend_id . '/', 'friends_add_friend' ),
//		'link_text'         => __( 'Add Friend', 'buddypress' ),
//		'link_id'           => 'friend-' . $potential_friend_id,
//		'link_rel'          => 'add',
//		'link_class'        => 'friendship-button not_friends add'
//	);

	//$button['wrapper'] = '';
	$button['link_class'] .= ' g1-button g1-button-s g1-button-simple';

	return $button;
}


/**
 * Enqueue BuddyPress Plugin integration assets.
 */
function bimber_bp_enqueue_head_styles() {
	// Hook into to disable loading CSS for BuddyPress plugin integration.
	if ( ! apply_filters( 'bimber_bp_load_css', true ) ) {
		return;
	}
	$version = bimber_get_theme_version();
	$stack = bimber_get_current_stack();
	$skin = bimber_get_theme_option( 'global', 'skin' );

	$uri = trailingslashit( get_template_directory_uri() );

	wp_enqueue_style( 'bimber-buddypress', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/buddypress-' . $skin . '.min.css', array(), $version );
	wp_style_add_data( 'bimber-buddypress', 'rtl', 'replace' );

	/**
	 * Conditionally load page-specific CSS
	 *
	 * The whole CSS is divided into multiple files for performance optimization.
	 */
	$bp_load_css_conditionally = apply_filters( 'bimber_bp_load_css_conditionally', true );

	if ( !$bp_load_css_conditionally || bp_is_directory() ) {
		wp_enqueue_style( 'bimber-buddypress-directory', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/buddypress-directory-' . $skin . '.min.css', array(), $version );
		wp_style_add_data( 'bimber-buddypress-directory', 'rtl', 'replace' );
	}

	if ( !$bp_load_css_conditionally || bp_is_register_page() ) {
		wp_enqueue_style( 'bimber-buddypress-sign-in-up', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/buddypress-sign-in-up-' . $skin . '.min.css', array(), $version );
		wp_style_add_data( 'bimber-buddypress-sign-in-up', 'rtl', 'replace' );
	}

	if ( !$bp_load_css_conditionally || bp_is_user() ) {
		wp_enqueue_style( 'bimber-buddypress-single-member', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/buddypress-single-member-' . $skin . '.min.css', array(), $version );
		wp_style_add_data( 'bimber-buddypress-single-member', 'rtl', 'replace' );
	}

	if ( !$bp_load_css_conditionally || bp_is_group() ) {
		wp_enqueue_style( 'bimber-buddypress-single-group', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/buddypress-single-group-' . $skin . '.min.css', array(), $version );
		wp_style_add_data( 'bimber-buddypress-single-group', 'rtl', 'replace' );
	}

	if ( !$bp_load_css_conditionally || bp_is_activity_component() || bp_is_group_activity() ) {
		wp_enqueue_style( 'bimber-buddypress-activity', $uri . 'css/' . bimber_get_css_theme_ver_directory() . '/styles/' . $stack . '/buddypress-activity-' . $skin . '.min.css', array(), $version );
		wp_style_add_data( 'bimber-buddypress-activity', 'rtl', 'replace' );
	}
}

/**
 * Enqueue BuddyPress Plugin integration assets.
 */
function bimber_bp_enqueue_scripts() {
	// Hook into to disable loading JS for BuddyPress plugin integration.
	if ( ! apply_filters( 'bimber_bp_load_js', true ) ) {
		return;
	}
	$version = bimber_get_theme_version();
	$uri = trailingslashit( get_template_directory_uri() );

	/**
	 * Conditionally load page-specific JS
	 *
	 * The whole JS is divided into multiple files for performance optimization.
	 */
	$bp_load_js_conditionally = apply_filters( 'bimber_bp_load_js_conditionally', true );


	if ( !$bp_load_js_conditionally || is_buddypress() ) {
		//wp_enqueue_script( 'bimber-bp', $uri . 'js/bp.js', array( 'jquery', 'bimber-global' ), $version, true );
		wp_enqueue_script( 'bimber-bp-tabs', $uri . 'js/bp-tabs.js', array( 'jquery', 'bimber-global' ), $version, true );
		wp_enqueue_script( 'bimber-bp-dir-layout', $uri . 'js/bp-dir-layout.js', array( 'jquery', 'bimber-global' ), $version, true );
		// @todo Load it conditionally
		//wp_enqueue_script( 'bimber-bp-follow', $uri . 'js/bp-follow.js', array( 'jquery', 'bimber-global' ), $version, true );
	}

	if ( !$bp_load_js_conditionally || bp_is_user() ) {
		wp_enqueue_script( 'bimber-bp-item-buttons', $uri . 'js/bp-item-buttons.js', array( 'jquery', 'bimber-global' ), $version, true );
	}

	if ( !$bp_load_js_conditionally || bp_is_group() ) {
		//wp_enqueue_script( 'bimber-bp-item-buttons', $uri . 'js/bp-item-buttons.js', array( 'jquery', 'bimber-global' ), $version, true );
	}
}

function bimber_bp_nouveau_css_dependencies( $r ) {
	// Remove dashicons. We use our own beautiful icons anyway ;)
	$r = array_diff( $r, array('dashicons') );

	return $r;
}















if( ! is_admin() ) {
	// Adjust markup of the BuddyPress Member's and Group's Home sidebar
	add_action( 'dynamic_sidebar_before', 'bimber_bp_dynamic_sidebar_before', 1, 2 );
	add_action( 'dynamic_sidebar_after', 'bimber_bp_dynamic_sidebar_after', 1, 9999 );
	add_filter( 'dynamic_sidebar_params', 'bimber_bp_dynamic_sidebar_params' );
}

function bimber_bp_dynamic_sidebar_before($index, $has_widgets ) {
	if ( 'sidebar-buddypress-members' === $index && $has_widgets ) {
		$has_sidenav = bp_nouveau_get_temporary_setting( 'user_nav_display', bp_nouveau_get_appearance_settings( 'user_nav_display' ) );

		echo $has_sidenav ? '<div class="g1-collection g1-collection-columns-2 buddypress-home">' : '<div class="g1-collection g1-collection-columns-3 buddypress-home">';
		echo '<div class="g1-collection-viewport">';
		echo '<ul class="g1-collection-items">';
	} else if ( 'sidebar-buddypress-groups' === $index && $has_widgets ) {
		$has_sidenav = bp_nouveau_get_temporary_setting( 'group_nav_display', bp_nouveau_get_appearance_settings( 'group_nav_display' ) );

		echo $has_sidenav ? '<div class="g1-collection g1-collection-columns-2 buddypress-home">' : '<div class="g1-collection g1-collection-columns-3 buddypress-home">';
			echo '<div class="g1-collection-viewport">';
				echo '<ul class="g1-collection-items">';
	}
}

function bimber_bp_dynamic_sidebar_after($index, $has_widgets ) {
	if ( in_array( $index, array( 'sidebar-buddypress-members', 'sidebar-buddypress-groups' ) ) && $has_widgets ) {
				echo '</ul>';
			echo '</div>';
		echo '</div>';
	}
}

function bimber_bp_dynamic_sidebar_params( $params ) {
	if ( in_array( $params[0]['id'], array( 'sidebar-buddypress-members', 'sidebar-buddypress-groups' ) ) ) {
		$params[0]['before_widget'] = '<li class="g1-collection-item"><div class="g1-buddypress-home-item">' . $params[0]['before_widget'];
		$params[0]['after_widget'] = $params[0]['after_widget'] . '</div></li>';

		$args = bimber_get_section_title_args( array( 'widgettitle') );
		$params[0]['before_title']  = sprintf( $args['before'], implode( ' ', array_map( 'sanitize_html_class', $args['class'] ) ) );
		$params[0]['after_title']   = $args['after'];
	}

	return $params;
}





function bimber_bp_nouveau_get_directory_type_navs_class_horizontal( $classes ) {
	$classes[] = 'horizontal';

	return $classes;
}

function bimber_bp_nouveau_get_directory_type_navs_class_vertical( $classes ) {
	$classes[] = 'vertical';

	return $classes;
}






require_once BIMBER_PLUGINS_DIR . 'buddypress/members.php';
require_once BIMBER_PLUGINS_DIR . 'buddypress/groups.php';




add_filter( 'bp_nouveau_get_members_buttons', 'bimber_bp_nouveau_get_members_buttons_s', 999, 3 );
function bimber_bp_nouveau_get_members_buttons_s( $buttons, $user_id, $type ) {
	foreach ( $buttons as $id => $args ) {
		$buttons[ $id ]['button_attr']['class'] .= ' g1-button g1-button-s g1-button-simple';
	}

	return $buttons;
}
