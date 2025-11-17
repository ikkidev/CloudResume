<?php
/**
 * The Template for displaying info about missing plugin to render an ad box.
 *
 * @package Bimber_Theme 4.10
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
			<p><?php printf( __( 'The %s ad cannot be displayed here due to one of following reasons', 'bimber' ), '<strong>' . esc_html( $slot_name ) . '</strong>' ); ?>:</p>
			<ul>
				<li>
                    <?php esc_html_e( 'the slot has no ad assigned', 'bimber' ); ?>.
                    <a href="<?php echo esc_url( admin_url( 'options-general.php?page=adace_options' ) ); ?>" target="_blank"><?php echo esc_html_x( 'Assign', 'Ads', 'bimber' ); ?></a>
                </li>
				<li>
                    <?php esc_html_e( 'the maximum number of ads on this page has been reached', 'bimber' ); ?>.
                    <a href="<?php echo esc_url( admin_url( 'options-general.php?page=adace_options&tab=adace_general' ) ); ?>" target="_blank"><?php echo esc_html_x( 'Check', 'Ads', 'bimber' ); ?></a>
                </li>
			</ul>
		</div>
	</div>
<?php endif;
