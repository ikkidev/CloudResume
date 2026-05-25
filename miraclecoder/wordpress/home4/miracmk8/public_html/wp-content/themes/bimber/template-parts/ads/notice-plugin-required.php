<?php
/**
 * The Template for displaying info about missing plugin to render an ad box.
 *
 * @package Bimber_Theme 5.0
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
$slot_name = get_query_var( 'plugin_required_notice_slot_id' );
?>

<?php if ( current_user_can( 'edit_plugins' ) ) : ?>
	<div class="g1-message g1-message-warning">
		<div class="g1-message-inner">
			<p><?php printf( wp_kses_post( __( 'The %s ad cannot be displayed. The <strong>%s</strong> plugin is not activated.', 'bimber' ) ), $slot_name, esc_html( 'AdAce' ) ); ?></p>
		</div>
	</div>
<?php endif;
