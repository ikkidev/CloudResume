<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $notice
 */

?>

<div class="wpgdprc-message wpgdprc-message--notice">
	<?php echo wp_kses($notice, \WPGDPRC\Utils\AdminHelper::getAllowedHTMLTags()) ?>
</div>
