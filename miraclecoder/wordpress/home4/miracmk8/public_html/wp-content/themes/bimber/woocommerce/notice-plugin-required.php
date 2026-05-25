<?php
/**
 * The Template for displaying info about missing plugin to render a product box.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<?php if ( current_user_can( 'edit_plugins' ) ) : ?>
<div class="g1-message g1-message-warning">
	<div class="g1-message-inner">
		<p><?php printf( wp_kses_post( __( 'This product cannot be displayed. The <strong>%s</strong> plugin is not activated.', 'bimber' ) ), esc_html( 'WooCommerce' ) ); ?></p>
	</div>
</div>
<?php endif; ?>
