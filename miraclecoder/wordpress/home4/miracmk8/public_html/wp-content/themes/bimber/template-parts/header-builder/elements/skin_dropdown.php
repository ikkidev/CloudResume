<?php
/**
 * Template for the mode switcher (dark, light)
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

$bimber_class = array(
	'g1-drop',
	'g1-drop-nojs',
	'g1-drop-with-anim',
	'g1-drop-before',
	'g1-drop-the-skin',
	'g1-drop-the-skin-' . bimber_get_theme_option( 'global', 'skin' ),
);
$bimber_class = array_merge(
	$bimber_class,
	explode( ' ', bimber_hb_get_element_class_from_settings( 'skin_dropdown', false ) )
);
$bimber_class = array_filter( $bimber_class );
?>
<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
	<button class="g1-button-none g1-drop-toggle">
		<span class="g1-drop-toggle-icon"></span><span class="g1-drop-toggle-text"><?php esc_html_e( 'Switch skin', 'bimber' ); ?></span>
		<span class="g1-drop-toggle-arrow"></span>
	</button>
	<?php if ( bimber_get_theme_option( 'header_builder', 'element_content_skin_dropdown' ) ) : ?>
		<div class="g1-drop-content">
			<?php if ( 'dark' ===  bimber_get_theme_option( 'global', 'skin' ) ) : ?>
				<p class="g1-skinmode-desc"><?php esc_html_e( 'Switch to the light mode that\'s kinder on your eyes at day time.', 'bimber' ); ?></p>
				<p class="g1-skinmode-desc"><?php esc_html_e( 'Switch to the dark mode that\'s kinder on your eyes at night time.', 'bimber' ); ?></p>
			<?php else : ?>
				<p class="g1-skinmode-desc"><?php esc_html_e( 'Switch to the dark mode that\'s kinder on your eyes at night time.', 'bimber' ); ?></p>
				<p class="g1-skinmode-desc"><?php esc_html_e( 'Switch to the light mode that\'s kinder on your eyes at day time.', 'bimber' ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<?php wp_enqueue_script( 'bimber-skin-mode' ); ?>