<?php
/**
 * Header Builder template
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>
<div class="g1-hb-search-form <?php bimber_hb_get_element_class_from_settings( 'search' );?>">
	<?php get_search_form(); ?>
</div>
