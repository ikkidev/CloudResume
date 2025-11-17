<?php
/**
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<div class="media-toolbar frame-edit frame-wc-edit">
	<div class="media-toolbar-secondary"></div>

	<div class="media-toolbar-primary search-form">
		<button type="button" data-state="create" class="state-create state state-default button media-button button-primary button-large media-button-action adace-stp-insert"><?php esc_html_e( 'Insert collection', 'adace' ); ?></button>
		<button type="button" data-state="update" class="state-update state button media-button button-primary button-large media-button-action adace-stp-update"><?php esc_html_e( 'Update', 'adace' ); ?></button>
	</div>
</div>