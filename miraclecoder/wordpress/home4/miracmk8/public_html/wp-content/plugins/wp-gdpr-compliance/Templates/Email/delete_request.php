<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $site_link
 * @var string $admin_link
 */

?>

<?php
	/* translators: %1s: site link */
echo wp_kses_post(sprintf( __( 'You have received a new anonymize request on %1s.', 'wp-gdpr-compliance' ), $site_link ));
?>
<br /><br />
<?php
	/* translators: %1s: admin link */
echo wp_kses_post(sprintf( __( 'You can manage this request in the admin panel: %1s', 'wp-gdpr-compliance' ), $admin_link ));
?>
