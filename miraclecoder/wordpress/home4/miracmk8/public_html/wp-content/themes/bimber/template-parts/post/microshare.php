<?php
/**
 *  The template for displaying microshares
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.3
 */


// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<?php
$share_url   = bimber_get_share_url();
$share_text  = get_the_title();
$share_media = 'bimber_share_media_url_placeholder';
?>
<div class="bimber-microshare-item-share">
	<a class="bimber-microshare-item-share-toggle" href="#"><?php esc_html_e( 'Share', 'bimber' ); ?></a>
	<div class="bimber-microshare-item-share-content">
		<?php
		$share_networks = bimber_get_share_position_active_networks( 'microshares' );

		foreach( $share_networks as $share_network ) {
			switch ( $share_network ) {
				case 'facebook':
					bimber_render_facebook_share_button( array(
						'share_url'     => $share_url,
						'share_text'    => $share_text,
						'classes'       => array( 'bimber-microshare', 'bimber-microshare-facebook' ),
					) );
					break;

				case 'twitter':
					bimber_render_twitter_share_button( array(
						'share_url'     => $share_url,
						'share_text'    => $share_text,
						'classes'       => array( 'bimber-microshare', 'bimber-microshare-twitter' ),
					) );
					break;

				case 'pinterest':
					bimber_render_pinterest_share_button( array(
						'share_url'     => $share_url,
						'share_text'    => $share_text,
						'share_media'   => $share_media,
						'classes'       => array( 'bimber-microshare', 'bimber-microshare-pinterest' ),
					) );
					break;
			}
		}
		?>
	</div>
</div>
