<?php
/**
 * The Template for displaying search results for AJAX request.
 *
 * @package Bimber_Theme 4.10.2
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
require_once BIMBER_FRONT_DIR . 'functions.php' ;
/**
 * @var WP_Query
 */
global $bimber_ajax_search_query;
?>

<?php if ( $bimber_ajax_search_query->have_posts() ) : ?>
	<ul>
		<?php $i = 0; while( $bimber_ajax_search_query->have_posts() ) : $bimber_ajax_search_query->the_post(); ?>
			<li>
				<?php
				$bimber_settings = array(
					'elements' => array(
						'featured_media' => true,
					),
				);

				bimber_set_template_part_data( $bimber_settings );

				get_template_part( 'template-parts/content-list-xxs' );

				bimber_reset_template_part_data();
				?>
			</li>
			<?php
			if ( ++$i >= 5 ) {
				break;
			}
			?>
		<?php endwhile; ?>
	</ul>

	<?php if ( $bimber_ajax_search_query->post_count > 5 ) : ?>
		<p class="g1-searches-all-results">
			<a class="bimber-see-all-results" href="#"><?php esc_html_e( 'View all results', 'bimber' ); ?></a>
		</p>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
<?php else : ?>
	<p class="no-results">
		<?php esc_html_e( 'Apologies, but no results were found.', 'bimber' ); ?>
	</p>
<?php endif; ?>