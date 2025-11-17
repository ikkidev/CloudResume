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
<nav class="g1-quick-nav g1-quick-nav-top">
	<ul class="g1-quick-nav-menu">
		<?php if ( strlen( bimber_get_top_page_url() ) ) : ?>
			<li class="menu-item menu-item-type-g1-top <?php if ( bimber_is_top_page() ) { echo sanitize_html_class( 'current-menu-item' ); } ?>">
				<a href="<?php echo esc_url( bimber_get_top_page_url() ); ?>">
					<span class="entry-flag entry-flag-top"></span>
					<?php echo esc_html( bimber_get_top_page_label() ); ?>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</nav>
