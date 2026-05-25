<?php
/**
 * New image part of the new post form
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$templates = get_posts( array(
	'posts_per_page'   => -1,
	'post_type' => snax_get_meme_template_post_type(),
	)
);
?>

<div class="g1-collection g1-collection-columns-3 snax-meme-templates">
	<div class="g1-collection-viewport">
		<ul class="g1-collection-items">
		<?php foreach ( $templates as $template ) :
			if ( has_post_thumbnail( $template ) ) : ?>
				<li	class="g1-collection-item g1-collection-item-1of3 snax-meme-template snax-meme-template-<?php echo esc_attr( $template->ID ); ?>"
				data-bimber-template="<?php echo esc_attr( $template->ID ); ?>"
				data-bimber-template-img="<?php echo esc_attr( get_post_thumbnail_id( $template ) ); ?>"
				data-bimber-template-top-text="<?php echo esc_html( get_post_meta( $template->ID, '_snax_meme_template_top_text', true ) ); ?>"
				data-bimber-template-bottom-text="<?php echo esc_html( get_post_meta( $template->ID, '_snax_meme_template_bottom_text', true ) ); ?>"
				data-bimber-template-title="<?php echo esc_html( $template->post_title ); ?>">
					<div class="snax-meme-templates-item">
						<div class="snax-meme-templates-item-image"><?php echo get_the_post_thumbnail( $template, 'medium' );?></div>
						<h2 class="g1-beta g1-beta-2nd snax-meme-templates-item-title"><?php echo esc_html( $template->post_title ); ?></h2>
					</div>
				</li>
		<?php
			endif;
		endforeach;?>
		</ul>
	</div>
</div>
