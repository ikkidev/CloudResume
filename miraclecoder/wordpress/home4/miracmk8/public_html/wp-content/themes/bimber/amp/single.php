<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<?php echo bimber_get_theme_option( 'tracking_code', 'amp_head' );?>
	<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>

	<style amp-custom>
		<?php ob_start(); ?>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
		<?php $style = ob_get_clean();
		// Minify CSS to keep below 50k.
		$style = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $style);
		$style = str_replace(': ', ':', $style);
		$style = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $style);
		echo $style;?>
	</style>
</head>

<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">

<?php get_template_part( 'amp/header'); ?>

<div class="g1-row g1-row-layout-page g1-row-padding-m">
	<div class="g1-row-inner">
		<div class="g1-column">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php
				$bimber_post_settings = bimber_get_post_settings();
				$bimber_elements = $bimber_post_settings['elements'];
				?>

				<article class="entry-tpl-classic amp-wp-article">
					<header class="entry-header amp-wp-article-header">

						<?php
						if ( $bimber_elements['categories'] ) :
							bimber_render_entry_categories( array(
								'use_microdata' => true,
							) );
						endif;
						?>

						<h1 class="g1-mega g1-mega-1st entry-title amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h1>

						<?php
						if ( bimber_can_use_plugin( 'wp-subtitle/wp-subtitle.php' ) ) :
							the_subtitle( '<h2 class="entry-subtitle g1-gamma g1-gamma-3rd" itemprop="description">', '</h2>' );
						endif;
						?>

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
											bimber_render_entry_date( array(
												'use_microdata' => true,
												'use_timeago'   => true
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
					<?php if ( ! has_filter( 'get_post_metadata', 'snax_skip_post_thumbnail' ) && bimber_show_entry_featured_media( $bimber_elements['featured_media'] ) ) {
						$this->load_parts( array( 'featured-image' ) );
					} ?>

					<?php 
					ob_start();
					bimber_render_top_share_buttons();
					$shares = ob_get_clean();
					$shares = bimber_amp_sanitize_shares_html( $shares );
					echo $shares;
					?>

					<div class="entry-content g1-typography-xl">
						<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses ?>
					</div>

					<?php
					remove_filter( 'get_post_metadata', 'snax_skip_post_thumbnail', 99, 4 );
					do_action( 'bimber_amp_after_single_content', array(
						'layout' => 'with-sidebar',
						'elements'  => $bimber_elements,
					) );
					?>

					<footer class="amp-wp-article-footer">
						<p class="g1-switch-to-non-amp">
							<?php 
							printf(
								'<a rel="noamphtml" class="g1-button g1-button-m g1-button-solid" href="%s">' . esc_html__( 'See the full version of this page', 'bimber' ) . '</a>',
							    esc_url( amp_remove_endpoint( amp_get_current_url() ) )
							);
							?>
						</p>
					</footer>
				</article>
			<?php endwhile; ?>

		</div>
	</div>
</div>

<?php get_template_part( 'amp/footer' ); ?>

<?php do_action( 'amp_post_template_footer', $this ); ?>

<?php get_template_part( 'amp/off-canvas' ); ?>

</body>
</html>
