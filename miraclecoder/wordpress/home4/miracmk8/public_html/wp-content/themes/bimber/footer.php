<?php
/**
 * The Template Part for displaying the footer.
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
?>
<?php do_action( 'bimber_above_footer' ); ?>

<?php if ( bimber_show_prefooter() ) : ?>
	<?php get_template_part( 'template-parts/prefooter/' . bimber_get_theme_option( 'footer', 'composition' ) ); ?>
<?php endif; ?>

		<div class="g1-footer g1-row g1-row-layout-page">
			<div class="g1-row-inner">
				<div class="g1-column">

					<p class="g1-footer-text"><?php bimber_render_footer_text(); ?></p>

					<?php if ( bimber_get_theme_option( 'social', 'in_footer' ) ) : ?>
						<div class="g1-footer-social">
							<?php
							if ( shortcode_exists( 'g1_socials' ) ) {
								echo do_shortcode( '[g1_socials icon_size="32" icon_color="text"]' );
							}
							?>
						</div>
					<?php endif; ?>

					<?php
					if ( has_nav_menu( 'bimber_footer_nav' ) ) :
						wp_nav_menu( array(
							'theme_location'  => 'bimber_footer_nav',
							'container'       => 'nav',
							'container_class' => 'g1-footer-nav',
							'container_id'    => 'g1-footer-nav',
							'menu_class'      => '',
							'menu_id'         => 'g1-footer-nav-menu',
							'depth'           => 0,
						) );
					endif;
					?>

					<?php get_template_part( 'template-parts/footer-stamp' ); ?>

				</div><!-- .g1-column -->
			</div>
			<div class="g1-row-background">
			</div>
		</div><!-- .g1-row -->

		<?php if ( apply_filters( 'bimber_render_back_to_top', true ) ) : ?>
			<a href="#page" class="g1-back-to-top"><?php esc_html_e( 'Back to Top', 'bimber' ); ?></a>
			<?php wp_enqueue_script( 'bimber-back-to-top' ); ?>
		<?php endif; ?>
	</div><!-- #page -->

<div class="g1-canvas-overlay">
</div>

</div><!-- .g1-body-inner -->

<div id="g1-breakpoint-desktop">
</div>

<?php get_template_part( 'template-parts/header-builder/off-canvas' ); ?>
<?php wp_footer(); ?>
</body>
</html>
