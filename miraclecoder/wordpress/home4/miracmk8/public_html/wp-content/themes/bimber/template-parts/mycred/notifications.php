<?php
/**
 * The Template for displaying mycred notifications.
 *
 * @package Bimber_Theme 5.3
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$data = get_query_var( 'bimber_mycred_standard_notifications' );
$duration = get_query_var( 'bimber_mycred_standard_notifications_duration' );
if ( ! is_array( $data ) ) {
	return;
}
?>

<div class="g1-mycred-notice-overlay-standard"
<?php if ( $duration > 0 ) {?>
	data-g1-mycred-notice-timeout="<?php echo esc_attr( $duration );?>"
<?php }?>
>
<?php foreach ( $data as $notice ) {
	$notice = str_replace( '<',' <', $notice );
	?>
		<div class="g1-notification-standard">
			<div class="g1-notification-standard-close"></div>
			<div class="g1-notification-standard-text"><?php echo wp_kses_post( $notice );?></div>
		</div>
	<?php
}?>
</div>
