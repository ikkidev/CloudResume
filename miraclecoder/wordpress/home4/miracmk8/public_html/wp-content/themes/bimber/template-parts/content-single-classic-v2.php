<?php
/**
 * The default template for displaying single post content (with sidebar).
 * This is a template part. It must be used within The Loop.
 *
 * @package Bimber_Theme 5.4
 *
 */

$bimber_entry_data = bimber_get_template_part_data();
$bimber_elements   = $bimber_entry_data['elements'];
?>

<?php do_action( 'bimber_before_single_content' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-tpl-classic-v2' ); ?> itemscope="" itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">
	<div <?php bimber_render_entry_inner_class(); ?>>
		<?php get_template_part( 'template-parts/post/sponsor-top' ); ?>

		<header class="entry-header entry-header-02">
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

			<?php if ( $bimber_elements['author'] || $bimber_elements['date'] || $bimber_elements['views'] || $bimber_elements['comments_link'] ) : ?>
				<p class="g1-meta g1-meta-m entry-meta entry-meta-m">
					<span class="entry-byline entry-byline-m <?php if ( $bimber_elements['avatar'] ) { echo sanitize_html_class( 'entry-byline-with-avatar' );} ?>">
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
								bimber_render_entry_date( array(
									'use_microdata' => true,
									'is_single'     => true,
								) );
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
		</header>

		<div class="g1-wrapper-with-stickies">
			<div class="entry-essentials">
				<?php
				if ( bimber_show_entry_featured_media( $bimber_elements['featured_media'] ) ) :
					bimber_render_entry_featured_media( array(
						'size'          => 'bimber-grid-2of3',
						'class'         => 'entry-featured-media-main',
						'use_microdata' => true,
						'use_nsfw'      => false,
						'apply_link'    => false,
						'show_caption'  => true,
						'allow_video'   => in_array( get_post_format(), apply_filters( 'bimber_single_featured_media_allow_video',  array() ) ),
						'allow_gif'     => true,
					) );
				endif;
				?>

				<div class="entry-content g1-typography-xl" itemprop="articleBody">
					<?php the_content(); ?>
				</div>
			</div>

			<div class="entry-actions">
				<?php bimber_render_side_share_buttons(); ?>
			</div>
		</div>
	</div><!-- .todo -->

	<div class="entry-after-content">
		<?php
		remove_action( 'bimber_after_single_content', 'bimber_render_bottom_share_buttons', bimber_get_theme_option( 'post', 'bottom_share_buttons_order' ) );

		do_action( 'bimber_after_single_content', array(
			'layout' => 'classic',
			'elements'  => $bimber_elements,
		) );
		?>
	</div>
</article>

<?php
if ( bimber_is_post_flyin_nav_enabled() ) :
	get_template_part( 'template-parts/post/flyin-next-prev' );
endif;
