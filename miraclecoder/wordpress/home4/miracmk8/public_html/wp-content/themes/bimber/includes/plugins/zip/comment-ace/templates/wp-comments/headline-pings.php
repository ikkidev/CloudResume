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
<h2 class="cace-comments-headline"><?php echo esc_html( sprintf( _n( 'One Ping', '%1$s Pings & Trackbacks', $args['ping_count'], 'cace' ), number_format_i18n( $args['ping_count'] ) ) ); ?></h2>