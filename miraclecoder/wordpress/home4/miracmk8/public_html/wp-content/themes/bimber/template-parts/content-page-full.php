<?php
/**
 * The template used for displaying page content
 *
 * @package Bimber_Theme 4.10.2
 */

?>
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

	<div <?php bimber_render_page_body_class(); ?>>
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">
			<div class="g1-column">
				<?php
				bimber_render_entry_featured_media( array(
					'size'          => 'bimber-classic-1of1',
					'class'         => 'entry-featured-media-main',
					'use_microdata' => true,
					'apply_link'    => false,
				) );
				?>

				<div class="entry-content" itemprop="text">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
					<?php get_template_part( 'template-parts/comments' ); ?>
				</div><!-- .entry-content -->
			</div>
		</div>
	</div>

</article><!-- #post-## -->
