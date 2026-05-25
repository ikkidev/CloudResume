<?php

use WPGDPRC\WordPress\Plugin;

?>

<span style="color:red;">
	<?php
		/* translators: %1s: class */
        echo esc_html( sprintf( _x( 'No render method yet, please add one in %1s', 'admin', 'wp-gdpr-compliance' ), __CLASS__ ) );
	?>
</span>
