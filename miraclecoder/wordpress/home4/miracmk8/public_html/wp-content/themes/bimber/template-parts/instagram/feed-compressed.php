<?php
/**
 * The Template Part for displaying instagram.
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
// Plugin active?
if ( ! bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ) {
	esc_html_e( 'Activate the G1 Socials plugin to use the Instagram module.', 'bimber' );
	return;
}

// Module enabled?
if ( ! get_option( 'g1_socials_instagram_enabled', false ) ) {
	esc_html_e( 'Instagram module disabled. Please enable it in the WP Admin > Settings > G1 Socials > Instagram.', 'bimber' );
	return;
}

$user_data = get_option( 'g1_socials_instagram_token_owner' );

if ( empty( $user_data ) ) {
	esc_html_e( 'Instagram module not configured. Please set it up in the WP Admin > Settings > G1 Socials > Instagram.', 'bimber' );
	return;
}

$username = $user_data['user']['username'];

$args     = array(
	'title'               => '',
	'username'            => $username,
	'limit'               => 8,
	'afterwidget_details' => false,
);
$instance = array(
	'before_widget' => '<div class ="instagram-section-widget">',
	'after_widget'  => '</div>',
);
?>
<div class="g1-dark g1-instagram-feed g1-instagram-feed-compressed">
	<?php the_widget( 'G1_Socials_Instagram_Widget', $args, $instance ); ?>
</div>
