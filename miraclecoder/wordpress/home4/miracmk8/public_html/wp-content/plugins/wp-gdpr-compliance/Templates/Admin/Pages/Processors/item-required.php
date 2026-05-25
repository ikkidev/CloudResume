<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var bool $required
 */

?>
<?php if ( empty( $required ) ) : ?>
	<span class="wpgdprc-label"><?php echo esc_html_x( 'Not required', 'admin', 'wp-gdpr-compliance' ); ?></span>
<?php else : ?>
	<span class="wpgdprc-label wpgdprc-label--success"><?php echo esc_html_x( 'Required', 'admin', 'wp-gdpr-compliance' ); ?></span>
<?php endif; ?>
