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
		<p><?php _e( 'This product cannot be displayed. Please check if there are products in the product injection category you selected.', 'bimber' ); ?></p>
	</div>
</div>
<?php endif; ?>
