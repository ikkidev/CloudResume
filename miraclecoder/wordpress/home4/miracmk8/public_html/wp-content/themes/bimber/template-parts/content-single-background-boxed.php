<?php
/**
 * The default template for displaying single post content (with sidebar).
 * This is a template part. It must be used within The Loop.
 *
 * @package Bimber_Theme 5.4
 */

$bimber_entry_data = bimber_get_template_part_data();
$bimber_elements   = $bimber_entry_data['elements'];
$media_id = uniqid();
do_action( 'bimber_before_single_content' );
?>



<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-tpl-background-boxed' ); ?> itemscope="" itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">
	<?php get_template_part( 'template-parts/post/sponsor-top', 'row' ); ?>

	<div class="g1-row g1-row-layout-page entry-media-row entry-media-row-04">
		<?php
		$bimber_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$bimber_background['image'] = is_array( $bimber_image ) ? $bimber_image[0] : '';
		?>
		<style>
			#g1-background-media-<?php echo esc_attr( $media_id );?> {
				background-image: url( <?php echo esc_url( $bimber_background['image'] ); ?> );
			}
		</style>

		<div class="g1-row-inner">
			<div class="g1-column">

				<div class="g1-row g1-row-1">
					<div class="g1-row-inner">
						<div class="g1-column g1-dark">

							<?php
							if ( bimber_show_breadcrumbs() ) :
								bimber_render_breadcrumbs();
							endif;
							?>

						</div>
					</div>
					<div class="g1-row-background">
					</div>
				</div><!-- .g1-row -->

				<div class="g1-row g1-row-2">
					<div class="g1-row-inner">

						<div class="g1-column g1-column-2of3 g1-dark">
							<header class="entry-header">
								<div class="entry-before-title">
									<?php
									if ( $bimber_elements['categories'] ) :
										bimber_render_entry_categories( array(
											'use_microdata' => true,
										) );
									endif;
									?>

									<?php bimber_render_entry_flags( array( 'show_collections' => false, 'show_reactions_single' => true ) ); ?>
								</div>

								<?php bimber_render_entry_title( '<h1 class="g1-mega g1-mega-1st entry-title" itemprop="headline">', '</h1>' ); ?>

								<?php
								if ( bimber_can_use_plugin( 'wp-subtitle/wp-subtitle.php' ) ) :
									the_subtitle( '<h2 class="entry-subtitle g1-gamma g1-gamma-3rd" itemprop="description">', '</h2>' );
								endif;
								?>
							</header>
						</div>

						<div class="g1-column g1-column-1of3 g1-dark">
							<?php bimber_render_entry_featured_media_caption(); ?>
						</div>

					</div>

					<div class="g1-row-background">
					</div>

				</div><!-- .g1-row -->

			</div>
		</div>

		<div class="g1-row-background">
		<div class="g1-row-background-media" id="g1-background-media-<?php echo esc_attr( $media_id );?>"></div>
		</div>

		<span class="g1-microdata" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
			<meta itemprop="url" content="<?php echo esc_url( $bimber_image[0] ); ?>" />
			<meta itemprop="width" content="<?php echo intval( $bimber_image[1] ); ?>" />
			<meta itemprop="height" content="<?php echo intval( $bimber_image[2] ); ?>" />
		</span>
	</div><!-- g1-row -->

	<div class="g1-row g1-row-layout-page g1-row-padding-m">
		<div class="g1-row-background">
		</div>
		<div class="g1-row-inner">

			<div class="g1-column g1-column-2of3">
				<div <?php bimber_render_entry_inner_class(); ?>>
					<header class="entry-header">
						<?php if ( $bimber_elements['author'] || $bimber_elements['date'] || $bimber_elements['views'] || $bimber_elements['comments_link'] ) : ?>
							<p class="g1-meta g1-meta-m entry-meta entry-meta-m">
					<span class="entry-byline entry-byline-m <?php if ( $bimber_elements['avatar'] ) { echo sanitize_html_class( 'entry-byline-with-avatar' );}?>">
							<?php
							if ( $bimber_elements['author'] ) :
								bimber_render_entry_author( array(
									'avatar'        => $bimber_elements['avatar'],
									'avatar_size'   => 40,
									'use_microdata' => true,
								) );
							endif;
							?>

							<?php
							if ( $bimber_elements['date'] ) :
								bimber_render_entry_date( array( 'use_microdata' => true ) );
							endif;
							?>
					</span>

					<span class="entry-stats entry-stats-m">

						<?php
						if ( $bimber_elements['views'] ) :
							bimber_render_entry_view_count();
						endif;
						?>

						<?php
						if ( $bimber_elements['votes'] ) :
							bimber_render_entry_vote_count();
						endif;
						?>

						<?php
						if ( $bimber_elements['downloads'] ) :
							bimber_render_entry_download_count();
						endif;
						?>

						<?php
						if ( $bimber_elements['comments_link'] ) :
							bimber_render_entry_comments_link();
						endif;
						?>
					</span>
							</p>
						<?php endif; ?>

						<?php bimber_render_top_share_buttons(); ?>
					</header>


					<div class="g1-content-narrow g1-typography-xl entry-content" itemprop="articleBody">
						<?php the_content(); ?>
					</div>
				</div><!-- .todo -->

				<div class="entry-after-content">
					<?php
					do_action( 'bimber_after_single_content', array(
						'layout'    => 'with-sidebar',
						'elements'  => $bimber_elements,
					) );
					?>
				</div>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div><!-- g1-row -->
</article>

<?php
if ( bimber_is_post_flyin_nav_enabled() ) :
	get_template_part( 'template-parts/post/flyin-next-prev' );
endif;