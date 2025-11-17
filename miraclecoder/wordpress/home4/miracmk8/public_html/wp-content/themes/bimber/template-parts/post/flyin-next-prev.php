<?php
/**
 * The Template Part for displaying "next|prev links".
 *
 * For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme 4.10
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

global $post;
?>
<?php $post = get_previous_post(); ?>
<?php if ( ! empty( $post ) ) :
	$post = apply_filters( 'wpml_object_id', $post->ID, 'post', true );
	setup_postdata( $post );
	?>
	<a class="g1-teaser g1-teaser-prev" href="<?php echo esc_url( get_permalink() ); ?>">
		<div class="g1-teaser-arrow"></div>
		<article <?php post_class( 'entry-tpl-listxxs' ); ?>>
			<?php
			bimber_render_entry_featured_media( array(
				'size'       => 'thumbnail',
				'apply_link' => false,
			) );
			?>

			<header class="entry-header">
				<?php bimber_render_entry_title( '<h3 class="g1-epsilon g1-epsilon-1st entry-title">', '</h3>' ); ?>
			</header>
		</article>
	</a>
<?php endif; ?>
<?php wp_reset_postdata(); ?>

<?php $post = get_next_post(); ?>
<?php if ( ! empty( $post ) ) :
	$post = apply_filters( 'wpml_object_id', $post->ID, 'post', true );
	setup_postdata( $post ); ?>
	<a class="g1-teaser g1-teaser-next" href="<?php echo esc_url( get_permalink() ); ?>">
		<div class="g1-teaser-arrow"></div>
		<article <?php post_class( 'entry-tpl-listxxs' ); ?>>
			<?php
			bimber_render_entry_featured_media( array(
				'size'       => 'thumbnail',
				'apply_link' => false,
			) );
			?>

			<header class="entry-header">
				<?php bimber_render_entry_title( '<h3 class="g1-epsilon g1-epsilon-1st entry-title">', '</h3>' ); ?>
			</header>
		</article>
	</a>
<?php endif; ?>
<?php
wp_reset_postdata();
