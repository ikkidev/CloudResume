<?php
/**
 * Image actions template part
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<span class="photomix-icon photomix-flip-image" title="<?php esc_attr_e( 'Flip horizontally', 'photomix' ); ?>">Flip</span>
<span class="photomix-icon photomix-remove-image" title="<?php esc_attr_e( 'Remove', 'photomix' ); ?>">Remove</span>
<span class="photomix-icon photomix-reset-image" title="<?php esc_attr_e( 'Reset', 'photomix' ); ?>">Reset</span>
<div class="photomix-scale">
	<div class="photomix-scale-slider" style="margin:15px;"></div>
	Scale: <input class="photomix-scale-value" type="number" min="0.5" max="5" step="0.01" value="1" />
</div>
