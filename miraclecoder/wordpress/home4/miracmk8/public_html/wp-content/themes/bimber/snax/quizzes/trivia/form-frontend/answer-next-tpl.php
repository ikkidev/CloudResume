<?php
/**
 * Next answer template part
 *
 * @package snax
 * @subpackage Forms
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="quizzard-answer quizzard-next-answer">
	<div class="quizzard-answer-header">
	</div>

	<div class="quizzard-answer-media quizzard-answer-without-media">
	</div>

	<div class="quizzard-answer-body">
		<input type="text" class="quizzard-answer-title" placeholder="<?php echo esc_html_x( 'Enter next answer&hellip;', 'Placeholder', 'snax' ); ?>" />
		<button class="g1-button g1-button-simple button button-disabled quizzard-add"><?php echo esc_html_e( 'Add', 'snax' ); ?></button>
	</div>
</div>
