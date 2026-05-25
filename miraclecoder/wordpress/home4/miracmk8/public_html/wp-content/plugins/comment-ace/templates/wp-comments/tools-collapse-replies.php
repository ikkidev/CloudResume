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
<button class="cace-button-reset cace-toggle-replies cace-toggle-replies-expand" type="button" title="<?php esc_attr_e( 'Collapse All Replies', 'cace' ); ?>"<?php disabled( ! collapse_replies() ); ?>><?php esc_html_e( 'Expand All', 'cace' ); ?></button>
<button class="cace-button-reset cace-toggle-replies cace-toggle-replies-collapse" type="button" title="<?php esc_attr_e( 'Expand All Replies', 'cace' ); ?>"<?php disabled( collapse_replies() ); ?>><?php esc_html_e( 'Collapse All', 'cace' ); ?></button>
