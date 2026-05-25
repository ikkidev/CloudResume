<?php
/**
 * The Template for displaying mycred notifications.
 *
 * @package Bimber_Theme 5.3.2
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$data = get_query_var( 'bimber_mycred_special_notification' );
$img_url = wp_get_attachment_image_src( $data['logo_id'], 'full' )[0];

$share_url 	= get_author_posts_url( get_current_user_id() );
$share_text	= __( "I've earned a rank! ", 'bimber' ) . $data['rank_name'];;
?>

<div class="g1-mycred-notice-overlay g1-mycred-notice-overlay-visible">
	<div class="g1-mycred-notice g1-mycred-notice-rank">
	<div class="g1-mycred-notice-close"></div>
		<div class="g1-mycred-notice-title">
			<?php if ( 'promotion' === $data['direction'] ) :?>
				<p class="g1-epsilon g1-epsilon-2nd"><?php echo esc_html__( 'Congratulations!', 'bimber' );?></p>
				<h2 class="g1-gamma g1-gamma-1st"><?php echo esc_html__( 'You have been promoted!', 'bimber' );?></h2>
				<?php else : ?>
				<p class="g1-epsilon g1-epsilon-2nd"><?php echo esc_html__( 'Congratulations!', 'bimber' );?></p>
				<h2 class="g1-gamma g1-gamma-1st"><?php echo esc_html__( 'You have been demoted!', 'bimber' );?></h2>
			<?php endif;?>

		</div>
		<div class="g1-mycred-notice-image">
			<div class="g1-mycred-notice-suburst">
				<div class="g1-mycred-notice-suburst-rays"></div>
				<div class="g1-mycred-notice-suburst-overlay"></div>
			</div>
			<img src="<?php echo esc_url( $img_url )?>">
		</div>
		<h3 class="g1-mycred-notice-name g1-delta g1-delta-1st"><?php echo esc_html( $data['rank_name'] )?></h3>
		<div class="g1-mycred-notice-shares">
			<?php
			if ( bimber_is_active_share_position( 'mycred_rank' ) ) {
				$share_networks = bimber_get_share_position_active_networks( 'mycred_rank' );

				foreach( $share_networks as $share_network ) {
					switch ( $share_network ) {
						case 'facebook':
							bimber_render_facebook_share_button( array(
								'share_url'  => $share_url,
								'share_text' => $share_text,
								'classes'    => array( 'g1-mycred-notice-share', 'g1-mycred-notice-share-facebook' ),
								'label'      => _x( 'Facebook', 'Share button', 'bimber' ),
							) );
							break;

						case 'twitter':
							bimber_render_twitter_share_button( array(
								'share_url'  => $share_url,
								'share_text' => $share_text,
								'classes'    => array( 'g1-mycred-notice-share', 'g1-mycred-notice-share-twitter' ),
								'label'      => _x( 'Twitter', 'Share button', 'bimber' ),
							) );
							break;
					}
				}
			}
			?>
		</div>
	</div>
</div>
