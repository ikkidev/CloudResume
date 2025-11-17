<?php
/**
 * WordPress comments template.
 *
 * @package Commentace
 */

namespace Commentace;

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<h2 class="cace-comments-headline"><?php esc_html_e( 'Comments', 'cace' ); ?></h2>