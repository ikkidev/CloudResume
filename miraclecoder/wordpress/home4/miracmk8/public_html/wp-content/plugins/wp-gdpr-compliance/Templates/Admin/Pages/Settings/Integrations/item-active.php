<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var bool $enabled
 */

$class = ! empty( $enabled ) ? '' : 'is-hidden';

?>
<span class="wpgdprc-label wpgdprc-label--success <?php echo esc_attr( $class ); ?>">
	<?php echo esc_html_x( 'Active', 'admin', 'wp-gdpr-compliance' ); ?>
</span>
