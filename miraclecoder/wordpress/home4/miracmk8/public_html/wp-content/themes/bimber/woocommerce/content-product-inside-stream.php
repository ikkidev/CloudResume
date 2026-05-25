<?php
/**
 * The template part for displaying a product inside a stream collection.
 *
 * @package Bimber_Theme 4.10
 */
?>

<li class="g1-collection-item  woocommerce">
	<?php if ( bimber_can_use_plugin( 'woocommerce/woocommerce.php' ) ) : ?>

		<?php
		$bimber_entry_data      = bimber_get_template_part_data();
		$bimber_elements        = $bimber_entry_data['elements'];
		$bimber_product_query   = bimber_wc_get_injected_product_query();

		$bimber_elements['action_links'] = $bimber_elements['action_links'] && bimber_has_entry_action_links();
		?>
		<?php if ( $bimber_product_query->have_posts() ) : ?>
			<?php while ( $bimber_product_query->have_posts() ) : $bimber_product_query->the_post(); ?>

				<aside class="g1-product g1-product-horizontal entry-tpl-stream">

					<header class="entry-header">
						<div class="entry-before-title">
							<?php
							if ( $bimber_elements['categories'] ) :
								bimber_render_product_categories( array(
									'class' => 'entry-categories-solid',
								) );
							endif;
							?>

							<?php
							add_filter( 'bimber_get_entry_flags', 'bimber_wc_get_entry_flags' );
							bimber_render_entry_flags();
							remove_filter( 'bimber_get_entry_flags', 'bimber_wc_get_entry_flags' );
							?>
						</div>

						<?php the_title( sprintf( '<h2 class="g1-alpha g1-alpha-1st entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

						<div class="entry-after-title">
							<?php
							bimber_render_entry_stats( array(
								'share_count'       => false,
								'view_count'        => $bimber_elements['views'],
								'comment_count'     => false,
								'download_count'    => false,
								'vote_count'        => $bimber_elements['votes'],
							) );
							?>
						</div>
					</header>

					<?php
					if ( $bimber_elements['featured_media'] ) :
						bimber_render_entry_featured_media( array(
							'size'          => 'bimber-stream',
							'use_ellipsis'  => true,
						) );
					endif;
					?>

					<div class="entry-body">
						<?php if ( $bimber_elements['summary'] ) : ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="entry-todome g1-dropable snax">
						<?php woocommerce_template_loop_price(); ?>
						<?php woocommerce_template_loop_add_to_cart(); ?>

                        <?php
						if ( isset( $bimber_elements['voting_box'] ) && $bimber_elements['voting_box'] ) {
							do_action( 'bimber_entry_voting_box', 's' );
						}
						?>

						<?php
						if ( $bimber_elements['action_links'] ) :
							bimber_render_entry_action_links();
						endif;
						?>
					</div>

				</aside>

			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>

		<?php else : ?>

			<?php get_template_part( 'woocommerce/notice-no-products' ); ?>

		<?php endif; ?>

	<?php else : ?>

		<?php get_template_part( 'woocommerce/notice-plugin-required' ); ?>

	<?php endif;  ?>

</li>
