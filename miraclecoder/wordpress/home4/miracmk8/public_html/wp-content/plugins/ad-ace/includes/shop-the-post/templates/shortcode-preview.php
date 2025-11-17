<?php
/**
 * Template for the Shop The Post shortcode preview.
 *
 * @package AdAce
 */
?>

<span contentEditable="false" style="float: left;"><strong><?php esc_html_e( 'Shop The Post Collection', 'adace' ); ?></strong></span>
<span contentEditable="false" style="float: right;"><a href="#" class="bstp-remove"><?php esc_html_e( 'X', 'adace' ); ?></a></span>
<br/>
<br/>

<span contentEditable="false" style="display: block; margin-bottom: 10px; cursor: pointer;">
	<?php foreach( $ids_arr as $id ): ?>

		<?php echo get_the_post_thumbnail( $id, 'thumbnail' ); ?>

	<?php endforeach; ?>
</span>

<span contentEditable="false"><a href="#" class="bstp-edit"><?php esc_html_e( 'Edit', 'adace' ); ?></a></span>
