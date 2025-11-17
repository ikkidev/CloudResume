<?php
/**
 * Single Item Content Part
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="snax">
	<?php
	snax_get_template_part( 'items/media' );?>
<div class="snax-item-nav">
	<a class="g1-button g1-button-l g1-button-wide g1-button-solid" href="<?php echo esc_attr( amp_get_permalink( snax_get_item_parent_id() ) ); ?>">
		<?php esc_html_e( 'View full list', 'snax' ); ?>
	</a>
</div>
</div>
