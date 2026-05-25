<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var int $count
 */

if ( empty( $count ) ) {
	return;
}
if ( empty( $text ) ) {
	$text = _x( 'Update available', 'admin', 'wp-gdpr-compliance' );
}

?>

<span title="<?php echo esc_attr( $text ); ?>" class="update-plugins count-<?php echo esc_attr( $count ); ?>">
	<span class="update-count">
		<?php echo esc_html( $count ); ?>
	</span>
</span>
