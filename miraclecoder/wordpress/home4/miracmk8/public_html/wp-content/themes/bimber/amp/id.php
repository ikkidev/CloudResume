<?php
/**
 * The template part for displaying the site identification inside the header.
 *
 * @package Bimber_Theme 5.4
 */

?>

<div class="g1-id">
	<?php
	$bimber_site_title = get_bloginfo( 'name' );
	$bimber_logo       = bimber_get_small_logo();
	?>
	<?php if ( is_front_page() ) : ?>
	<h1 class="g1-mega g1-mega-1st site-title">
		<?php else : ?>
		<p class="g1-mega g1-mega-1st site-title">
			<?php endif; ?>

			<a class="<?php echo sanitize_html_class( ! empty( $bimber_logo ) ? 'g1-logo-wrapper' : '' ); ?>"
			   href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( ! empty( $bimber_logo ) ) : ?>
					<?php
					printf(
						'<amp-img class="g1-logo g1-logo-default" width="%d" height="%d" src="%s" %s alt="%s" layout="responsive"/></amp-img>',
						absint( $bimber_logo['width'] ),
						absint( $bimber_logo['height'] ),
						esc_url( $bimber_logo['src'] ),
						isset( $bimber_logo['srcset'] ) ? sprintf( 'srcset="%s"', esc_attr( $bimber_logo['srcset'] ) ) : '',
						esc_attr( $bimber_site_title )
					);
					?>
				<?php else : ?>
					<?php echo wp_kses_post( $bimber_site_title ); ?>
				<?php endif; ?>
			</a>

			<?php if ( is_front_page() ) : ?>
	</h1>
<?php else : ?>
	</p>
<?php endif; ?>

	<?php if ( bimber_get_theme_option( 'branding', 'show_tagline' ) ) : ?>
		<p class="g1-delta g1-delta-3rd site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
	<?php endif; ?>
</div>
