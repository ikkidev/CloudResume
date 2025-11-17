<?php
/**
 *  The template for displaying featured entries
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_type = bimber_get_theme_option( 'featured_entries', 'type' );
$bimber_query = bimber_get_global_featured_entries_query();

$bimber_title = esc_html__( 'Latest stories', 'bimber' );
switch ( $bimber_type ) {
	case 'most_viewed':
		$bimber_title = esc_html__( 'Most viewed stories', 'bimber' );
		break;
	case 'most_shared':
		$bimber_title = esc_html__( 'Most shared stories', 'bimber' );
		break;
}

$bimber_row_class = array(
	'g1-featured-row',
);

if ( bimber_get_theme_option( 'featured_entries', 'above_header' ) ) {
	$bimber_row_class[] = 'g1-featured-row-before-header';
}

$bimber_class = array(
	'g1-featured',
	'g1-featured-' . $bimber_query->query['posts_per_page'],
	'g1-featured-start',
);
if ( bimber_get_theme_option( 'featured_entries', 'gutter' ) ) {
	$bimber_class[] = 'g1-featured-with-gutter';
}
?>

<?php if ( $bimber_query->have_posts() ) : ?>
<aside class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_row_class ) ); ?>">
	<h2 class="g1-zeta g1-zeta-2nd g1-featured-title"><?php echo wp_kses_post( $bimber_title ); ?></h2>

		<div class="g1-featured g1-featured-items-bunchy g1-featured-items-bunchy-<?php echo esc_attr( $bimber_query->query['posts_per_page'] );?>">
			<ul class="g1-featured-items">
				<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>

					<li class="g1-featured-item">
						<?php get_template_part( 'template-parts/content-tile-standard' ); ?>
					</li>

				<?php endwhile;wp_reset_postdata(); ?>
			</ul>
		</div>
</aside>
<?php endif;
