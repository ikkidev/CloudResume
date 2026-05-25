<?php
/**
 * The Template for displaying collection.
 *
 * @package Bimber_Theme 5.4
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

$bimber_data = bimber_get_template_part_data();
$bimber_query = $bimber_data['query'];
$bimber_card_style = $bimber_data['card_style'];
$bimber_columns = $bimber_data['columns'];

$bimber_classes = array(
	'g1-collection',
	'g1-collection-grid',
	'g1-collection-columns-' . $bimber_columns,
);

if ( 'none' !== $bimber_card_style ) {
	$bimber_classes[] = 'g1-collection-with-cards';
}
?>
<div class="<?php echo  implode( ' ', array_map('sanitize_html_class', $bimber_classes) ); ?>">
	<?php
		bimber_set_template_part_data( $bimber_data );
		get_template_part( 'template-parts/collection/header' );
		bimber_reset_template_part_data();
	?>

	<?php if ( $bimber_query->have_posts() ) : ?>
		<div class="g1-collection-viewport">
			<ul class="g1-collection-items">
				<?php while ( $bimber_query->have_posts() ) : $bimber_query->the_post(); ?>
					<li class="g1-collection-item">
						<?php get_template_part( 'template-parts/content-grid-standard', get_post_format() ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php endif; ?>
</div><!-- .g1-collection -->