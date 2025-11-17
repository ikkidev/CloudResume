<?php
/**
 *  The template for displaying featured entries
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

$bimber_type = bimber_get_theme_option( 'featured_entries', 'type' );

if ( 'none' === $bimber_type ) {
	return;
}

$bimber_row_class = array();

if ( bimber_get_theme_option( 'featured_entries', 'above_header' ) ) {
	$bimber_row_class[] = 'g1-featured-row-before-header';
}

$bimber_template = bimber_get_theme_option( 'featured_entries', 'template' );
$class = array(
	'g1-row-inner'
);
if ( bimber_get_theme_option( 'featured_entries', 'full_width' ) ) {
	$class[] = 'g1-featured-full-width';
}
if ( 'bunchy' === $bimber_template ) {
	get_template_part( 'template-parts/collection-featured-bunchy' );
	return;
}
$bimber_query = bimber_get_global_featured_entries_query();
$bimber_template = bimber_get_theme_option( 'featured_entries', 'full_width' );

?>

<?php if ( $bimber_query->have_posts() ) : ?>
	<aside <?php bimber_render_global_featured_class( $bimber_row_class ); ?>>
		<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $class ) ); ?>">
			<div class="g1-column">
				<?php
				$bimber_title = esc_html__( 'Latest stories', 'bimber' );
				switch ( $bimber_type ) {
					case 'most_viewed':
						$bimber_title = esc_html__( 'Most viewed stories', 'bimber' );
						break;

					case 'most_shared':
						$bimber_title = esc_html__( 'Most shared stories', 'bimber' );
						break;
				}
				$bimber_size_class = '';
				$bimber_template = bimber_get_theme_option( 'featured_entries', 'template' );
				switch ( $bimber_template ) {
					case 'list':
						$bimber_template = 'template-parts/content-list-xs';
						$bimber_size_class = '.g1-featured-3';
						break;

					case 'grid':
						$bimber_size = bimber_get_theme_option( 'featured_entries', 'size' );
						if ( 'xs' === $bimber_size ) {
							$bimber_size_class = '.g1-featured-6';
						}
						if ( 'xs-4' === $bimber_size ) {
							$bimber_size_class = '.g1-featured-4';
						}
						if ( 'xs-5' === $bimber_size ) {
							$bimber_size_class = '.g1-featured-5';
						}
						$bimber_template = 'template-parts/content-grid-' . $bimber_size;
						break;
					default:
						$bimber_size_class = '.g1-featured-6';
						$bimber_template = 'template-parts/content-grid-xs';
						break;
				}

				$bimber_class = array(
					'g1-featured',
					'g1-featured-no-js',
					$bimber_size_class,
					'g1-featured-start',
				);

				if ( bimber_get_theme_option( 'featured_entries', 'gutter' ) ) {
					$bimber_class[] = 'g1-featured-with-gutter';
				}

				if ( bimber_get_theme_option( 'featured_entries', 'img_title' ) ) {
					$bimber_class[] = 'g1-featured-without-title';
				}
				?>

				<h2 class="g1-zeta g1-zeta-2nd g1-featured-title"><?php echo wp_kses_post( $bimber_title ); ?></h2>

				<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
					<ul class="g1-featured-items">
						<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>

							<li class="g1-featured-item <?php echo sanitize_html_class( $bimber_size_class );?>">
								<?php
									set_query_var( 'bimber_media_ratio', bimber_get_theme_option( 'featured_entries', 'img_ratio' ) );
									get_template_part( $bimber_template, get_post_format() );
								?>
							</li>

						<?php endwhile; wp_reset_postdata(); ?>
					</ul>

					<a href="#" class="g1-featured-arrow g1-featured-arrow-prev"><?php esc_html_e( 'Previous', 'bimber' ) ?></a>
					<a href="#" class="g1-featured-arrow g1-featured-arrow-next"><?php esc_html_e( 'Next', 'bimber' ) ?></a>
					<div class="g1-featured-fade g1-featured-fade-before"></div>
					<div class="g1-featured-fade g1-featured-fade-after"></div>
				</div>
			</div>
		</div>
		<div class="g1-row-background">
		</div>
	</aside>
<?php else : ?>
	<aside class="g1-row g1-row-layout-page g1-featured-row">
		<div class="g1-row-inner">
			<div class="g1-column">
				<div class="g1-featured-no-results">
					<p>
						<?php esc_html_e( 'No featured entries match the criteria.', 'bimber' ); ?><br/>
						<?php esc_html_e( 'For more information please refer to the documentation.', 'bimber' ); ?>
					</p>
				</div>
			</div>
		</div>
		<div class="g1-row-background">
		</div>
	</aside>
<?php endif;

wp_enqueue_script( 'bimber-featured-entries' );