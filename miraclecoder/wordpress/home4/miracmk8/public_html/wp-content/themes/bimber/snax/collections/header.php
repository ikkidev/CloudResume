<?php
/**
 * Snax Collection Header Template.
 *
 * @package snax
 * @subpackage Collections
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<header class="snax-collections-header">
	<?php if ( strlen( $snax_collections_title ) ) : ?>
		<?php echo do_shortcode( '[bimber_title size="' . $snax_collections_title_size . '" align="' . $snax_collections_title_align . '" class="snax-collections-title"]' . $snax_collections_title . '[/bimber_title]' ); ?>
	<?php endif; ?>
</header>
