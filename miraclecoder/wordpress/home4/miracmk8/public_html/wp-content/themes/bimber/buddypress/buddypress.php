<?php
/**
 * The Template for displaying pages.
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

get_header();
?>
	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( bp_is_directory() ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope=""
			         itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">

				<?php
				// Get options.
				$bimber_options = bimber_get_page_header_options( $post->ID );

				// Prepare template part data.
				set_query_var( 'bimber_title', get_the_title() );

				if ( bimber_can_use_plugin( 'wp-subtitle/wp-subtitle.php' ) ) {
					set_query_var( 'bimber_subtitle', the_subtitle( '', '', false ) );
				}

				// Load template part.
				get_template_part( 'template-parts/page/header', $bimber_options['composition'] );
				?>

				<?php
				the_content();
				wp_link_pages();
				get_template_part( 'template-parts/comments' );
				?>

			</article><!-- #post-## -->
		<?php else : ?>
			<?php
			// Include the page content template.
			get_template_part( 'template-parts/content', 'page' );
			?>
		<?php endif; ?>

	<?php endwhile; ?>

<?php get_footer();
