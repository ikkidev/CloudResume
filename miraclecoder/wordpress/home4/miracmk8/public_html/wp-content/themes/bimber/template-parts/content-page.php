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

			<div id="primary" class="g1-column g1-column-2of3">
				<?php
				bimber_render_entry_featured_media( array(
					'size'          => 'bimber-grid-2of3',
					'class'         => 'entry-featured-media-main',
					'use_microdata' => true,
					'apply_link'    => false,
					'allow_video'   => true,
				) );
				?>

				<div class="entry-content" <?php bimber_render_microdata( array( 'itemprop' => 'text' ) ); ?>>
					<?php
					the_content();
					wp_link_pages();
					get_template_part( 'template-parts/comments' );
					?>
				</div><!-- .entry-content -->
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>

</article><!-- #post-## -->
