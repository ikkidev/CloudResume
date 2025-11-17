<?php
/**
 * Gallery
 *
 * @package media-ace
 * @subpackage Templates
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

global $mace_gallery_data;
$mace_gallery_attachments  = $mace_gallery_data['attachments'];

if ( empty( $mace_gallery_attachments ) ) {
	esc_html_e( 'Empty gallery. Add some images.', 'mace' );
	return;
}

$images = wp_json_encode( mace_get_gallery_content( $mace_gallery_attachments ) );
$title = isset( $mace_gallery_data['attr']['mace_title'] ) ? $title = $mace_gallery_data['attr']['mace_title'] : '';
$gallery_id = 'mace-gallery-' . get_the_ID() . '-' . mace_get_gallery_counter();
$share_link = bimber_get_share_url() . '#' . $gallery_id;

?>
<figure class="mace-gallery-teaser"
	id="<?php echo esc_attr( $gallery_id );?>"
	data-g1-gallery-title="<?php echo esc_html( $title ); ?>"
	data-g1-gallery="<?php echo htmlentities( $images, ENT_QUOTES, 'UTF-8' ); ?>"
	data-g1-share-shortlink="<?php echo esc_url( $share_link );?>"
	>
	<a class="mace-gallery-teaser-poster">
		<?php echo wp_get_attachment_image( $mace_gallery_attachments[0]->ID, 'bimber-grid-standard-2x' );?>
		<span class="mace-gallery-teaser-button">
			<span class="g1-epsilon g1-epsilon-1st mace-gallery-teaser-button-text"><?php echo esc_html__( 'View Gallery', 'mace' );?></span>
			<span class="g1-meta mace-gallery-teaser-button-counter">
				<?php echo esc_html( sprintf( _n( '%s image', '%s images', count( $mace_gallery_attachments ), 'mace' ), count( $mace_gallery_attachments ) ) ); ?>
			</span>
		</span>
	</a>
</figure>
