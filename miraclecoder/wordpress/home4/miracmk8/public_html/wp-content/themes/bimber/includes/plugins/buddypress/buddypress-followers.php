<?php
/**
 * BuddyPress Followers plugin functions
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

// Translations should be loaded on "plugins_loaded" action but it's been already fired.
add_action( 'after_setup_theme',                'bimber_bp_follow_localization' );
add_action( 'bimber_bp_members_counters',       'bimber_bp_members_counter_followers', 7 );
add_filter( 'bp_follow_get_add_follow_button',  'bimber_bp_follow_get_add_follow_button', 10, 3 );
add_action( 'widgets_init',                     'bimber_bp_register_followers_widget' );
add_action( 'bp_setup_nav',                     'bimber_bp_followers_nav', 100 );
add_filter( 'bp_nav_menu',                      'bimber_bp_followers_nav_current', 100, 2 );
add_filter( 'get_user_metadata',                'bimber_bp_get_follower_metadata', 10, 4 );
add_action( 'bimber_bp_directory_members_item_actions',        'bimber_bp_follow_render_member_actions', 6 );

/**
 * Load plugin textdomain as plugin does it wrong way
 */
function bimber_bp_follow_localization() {
	return load_plugin_textdomain( 'bp-follow', false, 'buddypress-followers/languages' );
}

// Make the follow button the most important.
remove_action( 'bp_member_header_actions', 'bp_follow_add_profile_follow_button' );
add_action( 'bp_member_header_actions', 'bp_follow_add_profile_follow_button', 2 );

/**
 * Fix for /buddypress-followers/_inc/bp-follow-notifications.php at line 308.
 * Operator [] is used on string value. We have to provide array here to prevent error on PHP 7.x
 *
 * @param $ret
 * @param $object_id
 * @param $meta_key
 * @param $single
 *
 * @return array
 */
function bimber_bp_get_follower_metadata( $ret, $object_id, $meta_key, $single ) {
	if ( 'bp_follow_has_notified' === $meta_key && $single && null === $ret ) {
		$ret = array( array() );
	}

	return $ret;
}

/**
 * Add followers counter
 */
function bimber_bp_members_counter_followers() {
	$args = [
		'user_id' => bp_get_member_user_id(),
	]
	?>
	<div class="item-counters-counter">
	<div class="g1-epsilon g1-epsilon-1st item-counters-counter-value"><?php echo esc_html( count( bp_follow_get_followers( $args ) ) ); ?></div>
		<div class="g1-meta"><?php esc_html_e( 'Followers', 'bimber' ); ?></div>
	</div>
	<?php
}

/**
 * Add a "Users I'm following" widget for the logged-in user
 *
 * @subpackage Widgets
 */
class BP_Follow_Followers_Widget extends WP_Widget {
	/**
	 * Constructor.
	 */
	function __construct() {
		// Set up optional widget args
		$widget_ops = array(
			'classname'   => 'widget_bp_follow_following_widget widget buddypress',
			'description' => __( 'Show a list of member avatars of the user\'s followers.', 'bimber' )
		);

		// Set up the widget
		parent::__construct(
			false,
			__( "(BP Follow) Followers", 'bp-follow' ),
			$widget_ops
		);
	}

	/**
	 * Displays the widget.
	 */
	function widget( $args, $instance ) {
		// do not do anything if user isn't logged in

		if ( empty( $instance['max_users'] ) ) {
			$instance['max_users'] = 16;
		}

		// logged-in user isn't following anyone, so stop!
		if ( ! $following = bp_follow_get_followers( array( 'user_id' => bp_displayed_user_id() ) ) ) {
			return false;
		}

		$members_args = array(
			'include'         => $following,
			'max'             => $instance['max_users'],
			'populate_extras' => false,
		);

		$has_filter = has_filter( 'bp_before_has_members_parse_args', 'bp_nouveau_member_members_widget_overrides' );
		if ( $has_filter ) {
			remove_filter( 'bp_before_has_members_parse_args', 'bp_nouveau_member_members_widget_overrides', 10, 1 );
		}
		?>
		<?php if ( bp_has_members( $members_args ) ) : ?>
			<?php do_action( 'bp_before_followers_widget' ); ?>

			<?php echo $args['before_widget']; ?>
				<?php echo $args['before_title']; ?>
					<?php echo $instance['title']; ?>
				<?php echo $args['after_title']; ?>

				<div class="avatar-block">
					<?php while ( bp_members() ) : bp_the_member(); ?>
						<div class="item-avatar">
							<a href="<?php bp_member_permalink() ?>" title="<?php bp_member_name() ?>"><?php bp_member_avatar() ?></a>
						</div>
					<?php endwhile; ?>
				</div>
			<?php echo $args['after_widget']; ?>

			<?php do_action( 'bp_after_followers_widget' ); ?>
		<?php endif; ?>
		<?php
			if ( $has_filter ) {
				add_filter( 'bp_before_has_members_parse_args', 'bp_nouveau_member_members_widget_overrides', 10, 1 );
			}
		?>
	<?php
	}

	/**
	 * Callback to save widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['max_users'] = (int) $new_instance['max_users'];

		return $instance;
	}

	/**
	 * Widget settings form.
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'     => __( 'Followers', 'bimber' ),
			'max_users' => 16,
		) );
	?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bp-follow' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>

		<p><label for="bp-follow-widget-users-max"><?php _e( 'Max members to show:', 'bp-follow' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_users' ); ?>" name="<?php echo $this->get_field_name( 'max_users' ); ?>" type="text" value="<?php echo esc_attr( (int) $instance['max_users'] ); ?>" style="width: 30%" /></label></p>
		<p><small><?php _e( 'Note: This widget is only displayed if a member is logged in and if the logged-in user is following some users.', 'bp-follow' ); ?></small></p>

	<?php
	}
}

/**
 * Fix the navigation.
 */
function bimber_bp_followers_nav() {
	global $bp;

	$parent_nav = $bp->members->nav->get_primary( array( 'slug' => 'followers' ), false );
	$parent_nav['followers']['name'] = str_replace( '<span>', '<span class="count">', $parent_nav['followers']['name']);
	$parent_nav = $bp->members->nav->get_primary( array( 'slug' => 'following' ), false );
	$parent_nav['following']['name'] = str_replace( '<span>', '<span class="count">', $parent_nav['following']['name']);
}

/**
 * Fix the navigation current tab style.
 *
 * @return string
 */
function bimber_bp_followers_nav_current( $nav_menu, $args  ) {
	$nav_menu = explode( '<li', $nav_menu );
	foreach ( $nav_menu as $index => $item ) {
		if ( 'following' === bp_current_component() && strpos( $item, 'following-personal-li' ) > -1 ) {
			$nav_menu[ $index ] = str_replace( 'g1-tab-item', 'g1-tab-item g1-tab-item-current ', $item );
		}
		if ( 'followers' === bp_current_component() && strpos( $item, 'followers-personal-li' ) > -1 ) {
			$nav_menu[ $index ] = str_replace( 'g1-tab-item', 'g1-tab-item g1-tab-item-current', $item );
		}
	}
	$nav_menu = implode( '<li', $nav_menu );
	return $nav_menu;
}


function bimber_bp_follow_get_add_follow_button( $args, $leader_id, $follower_id ) {
	$args['must_be_logged_in'] = false;

	if ( ! is_user_logged_in() ) {
		$args['link_class'] .= ' snax-login-required';
	}

	return $args;
}

/**
 * Add Followers widget.
 */
function bimber_bp_register_followers_widget() {
	register_widget( 'BP_Follow_Followers_Widget' );
}


function bimber_bp_follow_render_member_actions() {
	// The button won't show up if a user is logged out.
	// That' why need fake (hopefully) user ID.
	$follower_id = bp_loggedin_user_id();
	$follower_id = $follower_id ? $follower_id : 999999999;

	bp_follow_add_follow_button( array(
		'leader_id'   => bp_get_member_user_id(),
		'follower_id' => $follower_id,
		'link_class'  => 'g1-button g1-button-simple g1-button-s g1-bp-action',
		'wrapper'     => '',
	) );
}


add_action( 'bp_before_members_loop',   'bimber_bp_follow_before_members_loop' );
add_action( 'bp_after_members_loop',    'bimber_bp_follow_after_members_loop' );
add_action( 'bp_before_friends_loop',   'bimber_bp_follow_before_members_loop' );
add_action( 'bp_after_friends_loop',    'bimber_bp_follow_after_members_loop' );
add_action( 'bp_before_followers_loop', 'bimber_bp_follow_before_members_loop' );
add_action( 'bp_after_followers_loop',  'bimber_bp_follow_after_members_loop' );
add_action( 'bp_before_following_loop', 'bimber_bp_follow_before_members_loop' );
add_action( 'bp_after_following_loop',  'bimber_bp_follow_after_members_loop' );

function bimber_bp_follow_before_members_loop() {
	add_filter( 'bp_follow_get_add_follow_button', 'bimber_bp_follow_listing_get_add_follow_button', 10, 3 );
}
function bimber_bp_follow_after_members_loop() {
	remove_filter( 'bp_follow_get_add_follow_button', 'bimber_bp_follow_listing_get_add_follow_button', 10, 3 );
}

function bimber_bp_follow_listing_get_add_follow_button( $button, $leader_id, $follower_id ) {
	$button['wrapper'] = 'li';
	$button['link_class'] .= ' g1-button g1-button-s g1-button-simple';

	return $button;
}
