<?php
/**
 * Template for displaying Top page.
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

$bimber_collections = bimber_get_top_page_collections();
?>

<?php if ( ! empty( $bimber_collections ) ) : ?>

	<?php
	$bimber_current_collection = bimber_get_top_page_current_collection( key( $bimber_collections ) );
	?>

	<!-- Navigation -->
	<nav class="g1-quick-nav g1-quick-nav-tabs">
		<ul class="g1-quick-nav-menu">

		<?php foreach ( $bimber_collections as $bimber_collection_id => $bimber_collection ) : ?>
			<?php
			$bimber_classes = array(
				'menu-item',
				'menu-item-type-g1-' . $bimber_collection_id,
			);

			if ( $bimber_collection_id === $bimber_current_collection ) {
				$bimber_classes[] = 'current-menu-item';
			}
			?>

			<li class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_classes ) ); ?>">
				<a href="<?php echo esc_url( $bimber_collection['url'] ); ?>">
					<span class="entry-flag entry-flag-<?php echo sanitize_html_class( $bimber_collection_id ); ?>"></span>
					<?php echo esc_html( $bimber_collection['label'] ); ?>
				</a>
			</li>

		<?php endforeach; ?>

		</ul>
	</nav>

	<!-- Collection -->
	<?php get_template_part( 'template-parts/collection-' . $bimber_current_collection ); ?>

<?php else : ?>
	<?php echo esc_html_x( 'All collections are disabled.', 'Top page', 'bimber' ); ?>
<?php endif; ?>
