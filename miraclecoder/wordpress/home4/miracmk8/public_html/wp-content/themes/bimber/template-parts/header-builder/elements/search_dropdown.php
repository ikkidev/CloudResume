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
	<div class="g1-drop g1-drop-with-anim g1-drop-before g1-drop-the-search  <?php bimber_hb_get_element_class_from_settings( 'search_dropdown' );?>">
		<a class="g1-drop-toggle" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>">
			<span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text"><?php esc_html_e( 'Search', 'bimber' ); ?></span>
			<span class="g1-drop-toggle-arrow"></span>
		</a>
		<div class="g1-drop-content">
			<?php get_search_form(); ?>
		</div>
	</div>
