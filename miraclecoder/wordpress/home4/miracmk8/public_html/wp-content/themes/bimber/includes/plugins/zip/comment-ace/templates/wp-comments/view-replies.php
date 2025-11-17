<?php
/**
 * "View Replies" button template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<script id="cace-view-replies-tpl" type="text/template">
    <button class="cace-view-replies cace-button-reset"><?php esc_html_e( 'View replies (%d)', 'cace' ); ?></button>
</script>
