<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $email
 * @var string $site_link
 * @var string $delete_link
 * @var string $request_link
 */

?>

<?php
	/* translators: %1s: site link */
echo wp_kses_post(sprintf( __( 'You have requested to access your data on %1s.', 'wp-gdpr-compliance' ), $site_link ));
?>
<br /><br />
<?php
	/* translators: %1$1s: delete link %2$2s: email */
echo wp_kses_post(sprintf( __( 'Please visit this %1$1s to view the data linked to the email address %2$2s.', 'wp-gdpr-compliance' ), $delete_link, $email ));
?>
<br /><br />
<?php esc_html_e( 'This page is available for 24 hours and can only be reached from the same device, IP address and browser session you requested from.', 'wp-gdpr-compliance' ); ?>
<br /><br />
<?php
	/* translators: %1s: request link */
echo wp_kses_post(sprintf( __( 'If your link is invalid you can fill in a new request after 24 hours: %1s.', 'wp-gdpr-compliance' ), $request_link ));
?>
