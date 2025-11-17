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

// Set defaults.
$args = wp_parse_args(
	$args,
	array(
		'bimber_header'          => 'normal',
	)
);

$bimber_h1_tag          = ( 'normal' === $args['bimber_header'] && is_front_page() ) ? true : false;
$bimber_site_title      = get_bloginfo( 'name' );
$bimber_logo            = bimber_get_logo( $args['bimber_header'] );
$bimber_logo_inverted   = bimber_get_logo_inverted( $args['bimber_header'] );
$bimber_lazy_loading    = function_exists( 'mace_get_lazy_load_images' ) && mace_get_lazy_load_images();
?>
<div class="g1-id g1-id-desktop">
	<?php if ( $bimber_h1_tag ) : ?>
		<h1 class="g1-mega g1-mega-1st site-title">
	<?php else : ?>
		<p class="g1-mega g1-mega-1st site-title">
	<?php endif; ?>

			<a class="<?php echo sanitize_html_class( ! empty( $bimber_logo ) ? 'g1-logo-wrapper' : '' ); ?>"
			   href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( ! empty( $bimber_logo ) ) : ?>
					<picture class="g1-logo g1-logo-default">
						<source media="<?php echo esc_attr( $bimber_logo['media'] ); ?>" srcset="<?php echo esc_attr( $bimber_logo['srcset'] ); ?>">
						<source media="<?php echo esc_attr( $bimber_logo['_media'] ); ?>" srcset="<?php echo esc_attr( $bimber_logo['_srcset'] ); ?>">
						<img
							src="<?php echo esc_url($bimber_logo['src']); ?>"
							width="<?php echo absint( $bimber_logo['width'] ); ?>"
							height="<?php echo absint( $bimber_logo['height'] ); ?>"
							alt="<?php echo esc_attr( $bimber_site_title ); ?>" />
					</picture>

					<?php if ( ! empty( $bimber_logo_inverted ) ) : ?>
						<picture class="g1-logo g1-logo-inverted">
							<source id="g1-logo-inverted-source" media="<?php echo esc_attr( $bimber_logo['media'] ); ?>" srcset="<?php echo esc_attr( $bimber_logo_inverted['srcset'] ); ?>" data-srcset="<?php echo esc_attr($bimber_logo_inverted['data-srcset']); ?>">
							<source media="<?php echo esc_attr( $bimber_logo['_media'] ); ?>" srcset="<?php echo esc_attr( $bimber_logo_inverted['_srcset'] ); ?>">
							<img
								id="g1-logo-inverted-img"
								class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_logo_inverted['class'] ) ); ?>"
								src="<?php echo esc_url($bimber_logo_inverted['src'] ); ?>"
								data-src="<?php echo esc_attr($bimber_logo_inverted['data-src'] ); ?>"
								width="<?php echo absint( $bimber_logo_inverted['width'] ); ?>"
								height="<?php echo absint( $bimber_logo_inverted['height'] ); ?>"
								alt="" />
						</picture>
					<?php endif; ?>
				<?php else : ?>
					<?php echo wp_kses_post( $bimber_site_title ); ?>
				<?php endif; ?>
			</a>

	<?php if ( $bimber_h1_tag ) : ?>
		</h1>
	<?php else : ?>
		</p>
	<?php endif; ?>

    <?php if ( $bimber_lazy_loading ) : ?>
        <script>
            try {
                if ( localStorage.getItem(skinItemId ) ) {
                    var _g1;
                    _g1 = document.getElementById('g1-logo-inverted-img');
                    _g1.classList.remove('lazyload');
                    _g1.setAttribute('src', _g1.getAttribute('data-src') );

                    _g1 = document.getElementById('g1-logo-inverted-source');
                    _g1.setAttribute('srcset', _g1.getAttribute('data-srcset'));
                }
            } catch(e) {}
        </script>
    <?php endif; ?>

	<?php if ( bimber_get_theme_option( 'branding', 'show_tagline' ) ) : ?>
		<p class="g1-delta g1-delta-3rd site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
	<?php endif; ?>
</div>