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

<div class="media-toolbar frame-create frame-wc-create">
	<div class="media-toolbar-secondary state state-create">
		<div class="media-selection">
			<div class="selection-info">
				<span class="count"><span></span> <?php esc_html_e( 'selected', 'adace' ); ?></span>
				<button type="button" class="button-link clear-selection"><?php esc_html_e( 'Clear', 'adace' ); ?></button>
			</div>
			<div class="selection-view">
				<ul tabindex="-1" class="attachments"></ul>
			</div>
		</div>
	</div>

	<div class="media-toolbar-primary search-form">
		<button type="button" data-state="create" class="state-create state state-default button media-button button-primary button-large media-button-action adace-stp-create-new" disabled="disabled"><?php esc_html_e( 'Create a new collection', 'adace' ); ?></button>
		<button type="button" data-state="update" class="state-update state button media-button button-primary button-large media-button-action adace-stp-create-new" disabled="disabled"><?php esc_html_e( 'Back to edit', 'adace' ); ?></button>
		<button type="button" data-state="single-selection" class="state-single-selection state button media-button button-primary button-large media-button-action adace-stp-insert"><?php esc_html_e( 'Insert', 'adace' ); ?></button>
	</div>
</div>