<?php
/**
 * The Template for displaying collection.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_data = bimber_get_template_part_data();
$bimber_query = $bimber_data['query'];
$bimber_title = $bimber_data['title'];
$bimber_title_size = $bimber_data['title_size'];
$bimber_title_align = $bimber_data['title_align'];
$bimber_title_show = $bimber_data['title_show'];

$bimber_author = false;
if ( $bimber_query->get('author_name') ) {
	$bimber_author = get_user_by( 'login', $bimber_query->get('author_name') );
}

// Automatic title if not provided.
if ( empty( $bimber_title ) ) {
	if ( $bimber_author ) {
		$bimber_title = $bimber_author->display_name;
	}
}
?>
<div class="g1-collection-header">
	<?php if ( ! empty( $bimber_title ) && 'none' !== $bimber_title_show ) : ?>
		<?php if ( $bimber_author ) : ?>
			<?php
			printf(
				'<a class="g1-collection-author-link" href="%s" title="%s">%s</a>',
				esc_url( get_author_posts_url( $bimber_author->ID ) ),
				esc_attr( sprintf( __( 'Posts by %s', 'bimber' ), $bimber_author->display_name ) ),
				get_avatar( $bimber_author->user_email, 30 )
			);
			?>
		<?php endif; ?>
		<?php echo do_shortcode( '[bimber_title size="' . $bimber_title_size . '" align="' . $bimber_title_align . '" class="g1-collection-title"]' . $bimber_title . '[/bimber_title]' ); ?>
	<?php endif; ?>

	<?php if ( function_exists( 'bp_follow_add_follow_button' ) && $bimber_author ) : ?>
		<?php
		// The button won't show up if a user is logged out.
		// That' why need fake (hopefully) user ID.
		$bimber_follower_id = bp_loggedin_user_id();
		$bimber_follower_id = $bimber_follower_id ? $bimber_follower_id : 999999999;

		bp_follow_add_follow_button( array(
			'leader_id'     => $bimber_author->ID,
			'follower_id'   => $bimber_follower_id,
			'link_class'    => 'g1-button g1-button-simple g1-button-s g1-bp-action',
			'wrapper'       => '',
		) );
		?>
	<?php endif; ?>
</div>
