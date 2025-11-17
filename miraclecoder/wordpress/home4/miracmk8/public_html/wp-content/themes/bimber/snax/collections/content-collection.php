<?php
/**
 * Snax Collection Template Part
 *
 * @package snax 1.11
 * @subpackage Theme
 */

?>

<div class="snax snax-post-container">
	<?php
	if ( snax_is_collection_edit_view() && snax_user_can_edit_collection() ) {
		snax_get_template_part( 'collections/form-edit' );
	} else {
        snax_get_template_part( 'collections/loop-items' );
	}
	?>
</div>
