<?php
/**
 * The Template for displaying the off-canvas area.
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 5.4
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Canvas element
 */
function bimber_hb_canvas_search() {
	add_filter( 'bimber_ajax_search', '__return_false', 99 );
	get_search_form();
	remove_filter( 'bimber_ajax_search', '__return_false', 99 );
}

/**
 * Canvas element
 */
function bimber_hb_canvas_primary_menu() {
	$has_icons = bimber_get_theme_option( 'header', 'primary_nav_icons' );
	$has_icons = ( 'none' === $has_icons ) ? false : true;

	$classes = array(
		'g1-primary-nav-menu',
		'g1-menu-v',
	);

	if ( $has_icons ) {
		$classes[] = 'g1-menu-with-icons';
	}

	?>
	<!-- BEGIN .g1-primary-nav -->
	<?php
		if ( has_nav_menu( 'bimber_primary_nav' ) ) :
			wp_nav_menu( array(
				'theme_location'  => 'bimber_primary_nav',
				'container'       => 'nav',
				'container_class' => 'g1-primary-nav',
				'container_id'    => 'g1-canvas-primary-nav',
				'menu_class'      => implode( ' ', $classes ),
				'menu_id'         => 'g1-canvas-primary-nav-menu',
				'depth'           => 0,
				'walker'          => new Bimber_Side_Walker_Nav_Menu(),
			) );
		endif;
		?>
		<!-- END .g1-primary-nav -->
	<?php
}
/**
 * Canvas element
 */
function bimber_hb_canvas_secondary_menu() {
	$classes = array(
		'g1-secondary-nav-menu',
		'g1-menu-v',
	);
	?>
	<!-- BEGIN .g1-secondary-nav -->
	<?php
		if ( has_nav_menu( 'bimber_secondary_nav' ) ) :
			wp_nav_menu( array(
				'theme_location'  => 'bimber_secondary_nav',
				'container'       => 'nav',
				'container_class' => 'g1-secondary-nav',
				'container_id'    => 'g1-canvas-secondary-nav',
				'menu_class'      => implode( ' ', $classes ),
				'menu_id'         => 'g1-canvas-secondary-nav-menu',
				'depth'           => 0,
				'walker'          => new Bimber_Side_Walker_Nav_Menu(),
			) );
		endif;
		?>
		<!-- END .g1-secondary-nav -->
	<?php
}

/**
 * Canvas element
 */
function bimber_hb_canvas_create_button() {
if ( bimber_can_use_plugin( 'snax/snax.php' ) ) : ?>
	<?php if ( snax_show_create_button() ): ?>
		<?php
		$snax_class = array(
			'g1-button',
			'g1-button-m',
			'g1-button-solid',
			'snax-button',
			'snax-button-create',
		);
		$url = snax_get_frontend_submission_page_url();
		$url_type = bimber_get_theme_option( 'snax', 'header_create_button_type' );
		if ( 'ranked_list' === $url_type ) {
			$url_type = 'list&type=ranked';
		}
		if ( 'list' === $url_type ) {
			$url_type = 'list';
		}
		if ( 'classic_list' === $url_type ) {
			$url_type = 'list&type=classic';
		}
		if ( 'all' !== $url_type && 'all_dropdown' !== $url_type ) {
			if ( strpos( $url, '?' ) !== false ) {
				$url .= '&snax_format=' . $url_type;
			} else {
				$url .= '?snax_format=' . $url_type;
			}
		}
		?>
		<a class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>"
		href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( bimber_get_theme_option( 'snax', 'header_create_button_label' ) ); ?></a>
		<?php endif; ?>
<?php endif;
}

/**
 * Canvas element
 */
function bimber_hb_canvas_quick_nav() {
	get_template_part( 'template-parts/nav-quick' );
}

/**
 * Canvas element
 */
function bimber_hb_canvas_language_selector() {
	do_action( 'bimber_wpml_add_language_selector' );
}

/**
 * Canvas element
 */
function bimber_hb_canvas_social_icons_full() {
	$size = bimber_get_theme_option( 'header_builder', 'element_size_social_icons_full' );
	$html = '';
	if ( bimber_can_use_plugin( 'g1-socials/g1-socials.php' ) ) {
		$html =  do_shortcode( '[g1_socials icon_size="48" icon_color="text"]' );
	}
	if ( 'g1-socials-s' === $size ) {
		$html = str_replace( 'g1-socials-items-tpl-grid', 'g1-socials-items-tpl-grid g1-socials-s', $html );
	}
	echo $html;
}
?>

<?php
if ( is_customize_preview() ) {
	$elements = bimber_hb_get_elements();?>
	<div id="g1-hb-preview-elements-canvas">
	<?php
	foreach ( $elements as $index => $element ) :
		if ( in_array( 'canvas', $element['tabs'], true ) ){
			?>
			<div class="g1-hb-preview-canvas-element-<?php echo esc_attr( $index ); ?>">
				<?php
				call_user_func( 'bimber_hb_canvas_' . $index );
				?>
			</div>
			<?php
		}
	endforeach;
	?>
	</div>
	<?php
}
?>
<div class="g1-canvas g1-canvas-global g1-canvas-no-js">
	<div class="g1-canvas-inner">
		<div class="g1-canvas-content">
			<a class="g1-canvas-toggle" href="#"><?php echo esc_html_x( 'Close', 'button', 'bimber' ); ?></a>

			<?php
			$layout = bimber_get_theme_option( 'header_builder', '' );
			$layout = $layout['canvas'];
			foreach ( $layout[1]['cols'][1]['elements'] as $index => $element ) :
				call_user_func( 'bimber_hb_canvas_' . $element );
			endforeach;
			?>
		</div>
		<?php $bimber_bg_image = bimber_get_theme_option( 'header', 'builder_canvas_background_image' ); ?>
		<?php if ( $bimber_bg_image && function_exists( 'mace_get_lazy_load_images' ) && mace_get_lazy_load_images() ) : ?>
			<div class="g1-canvas-background" data-bg="<?php echo esc_attr( $bimber_bg_image ); ?>">
			</div>
		<?php else : ?>
			<div class="g1-canvas-background">
			</div>
		<?php endif; ?>
	</div>
</div>
