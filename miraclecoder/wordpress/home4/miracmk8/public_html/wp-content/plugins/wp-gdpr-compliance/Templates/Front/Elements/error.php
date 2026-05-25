<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $message
 */

?>

<div class="wpgdprc-message wpgdprc-message--error">
	<p>
		<?php
			/* translators: %1s: error message */
			echo wp_kses(sprintf( __( '<strong>ERROR</strong>: %1s', 'wp-gdpr-compliance' ), $message ), \WPGDPRC\Utils\AdminHelper::getAllAllowedSvgTags() );
		?>
	</p>
</div>
