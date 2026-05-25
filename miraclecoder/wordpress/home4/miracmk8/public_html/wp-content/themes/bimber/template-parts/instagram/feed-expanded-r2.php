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
	esc_html_e( 'Instagram not configured. Please set it up in the WP Admin > Settings > G1 Socials > Instagram.', 'bimber' );
	return;
}

$username    = $user_data['user']['username'];
$follow_text = get_option( 'g1_socials_instagram_follow_text' );

// Normalize.
$username = ltrim( $username, '@' );
if ( empty( $username ) ) {
	return;
}


$user_instagram_url = trailingslashit( '//instagram.com/' . trim( str_replace( '@', '', $username ) ) );

$args               = array(
	'title'               => '',
	'username'            => $username,
	'limit'               => 12,
	'afterwidget_details' => false,
);

$instance           = array(
	'before_widget' => '<div class="instagram-section-widget">',
	'after_widget'  => '</div>',
);
?>
<div class="g1-instagram-feed g1-instagram-feed-expanded g1-instagram-feed-r2">
	<?php the_widget( 'G1_Socials_Instagram_Widget', $args, $instance ); ?>

	<div class="g1-instagram-feed-overlay">
		<p class="g1-instagram-profile">
			<a  class="g1-delta g1-delta-1st g1-instagram-username"
		        href="<?php echo esc_url( $user_instagram_url ); ?>" rel="me"
		        target="_blank">&#64;<?php echo( esc_html( $username ) ); ?></a>
		</p>

		<?php if ( $follow_text ) : ?>
			<p class="g1-instagram-follow">
				<a  class="g1-button g1-button-s g1-button-simple"
			        href="<?php echo esc_url( $user_instagram_url ); ?>" rel="me"
			        target="_blank"><?php echo wp_kses_post( $follow_text ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</div>
