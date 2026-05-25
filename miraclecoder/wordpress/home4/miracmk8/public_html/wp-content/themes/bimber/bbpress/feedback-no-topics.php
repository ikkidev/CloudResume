<?php

/**
 * No Topics Feedback Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>
<div class="bbp-template-notice g1-empty">
	<div class="g1-empty-icon">
		<?php bimber_render_svg( 'empty-state-default', 'default' ); ?>
	</div>

	<p class="g1-empty-title"><strong><?php esc_html_e( 'No Topics', 'bbpress' ); ?></strong></p>

	<p class="g1-empty-desc"><?php esc_html_e( 'Oh, bother! No topics were found here.', 'bbpress' ); ?></p>
</div>


