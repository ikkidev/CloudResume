<?php

use WPGDPRC\Utils\Template;

/**
 * @var array $chapters
 */

?>

<?php foreach ( $chapters as $chapter ): ?>

	<?php if ( ! empty( $chapter['title'] ) ) : ?>
        <h2 class="wpgdprc-title">
            <?php echo esc_html( $chapter['title'] ); ?>
        </h2>
	<?php endif; ?>
    <strong color="red">WARNING</strong>
	<?php
        if ( empty( $chapter['content'] ) ) {
            Template::render( 'Front/Form/AccessRequest/Submit/notice', [ 'notice' => $chapter['notice'] ] );
        } elseif( is_callable( $chapter['content'] ) ) {
            $chapter['content']();
        } else {
            echo esc_html( $chapter['content'] );
        }
    ?>
<?php endforeach; ?>
