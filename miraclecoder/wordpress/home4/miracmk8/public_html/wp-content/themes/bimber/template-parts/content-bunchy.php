<?php
/**
 * The default template for displaying single post content (with sidebar).
 * This is a template part. It must be used within The Loop.
 *
 * @package Bimber_Theme 5.4
 */

$bimber_entry_data = bimber_get_template_part_data();
$bimber_elements   = $bimber_entry_data['elements'];

$bimber_card_style = $bimber_entry_data['card_style'];

$bimber_class = array(
	'entry-tpl-index',
	'entry-tpl-index-stickies',
);
if ( 'none' !== $bimber_card_style ) {
	$bimber_class[] = 'g1-card';
	$bimber_class[] = 'g1-card-' . $bimber_card_style;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $bimber_class ); ?>>
	<div class="entry-box">
		<div class="entry-before-title">
			<?php
				bimber_render_entry_stats( array(
					'share_count'       => $bimber_elements['shares'],
					'view_count'        => $bimber_elements['views'],
					'comment_count'     => $bimber_elements['comments_link'],
					'download_count'    => $bimber_elements['downloads'],
					'vote_count'        => $bimber_elements['votes'],
					'class'             => 'g1-meta',
				) );
			?>

			<?php
				if ( $bimber_elements['categories'] ) :
					bimber_render_entry_categories();
				endif;
			?>
		</div>

		<header class="entry-header">
			<?php bimber_render_entry_title( '<h3 class="g1-alpha g1-alpha-1st entry-title" itemprop="headline"><a href="%1$s" rel="bookmark">', '</a></h3>' ); ?>
		</header>

		<?php if ( bimber_can_use_plugin( 'snax/snax.php' ) ) : ?>
			<?php get_template_part( 'template-parts/snax-bar-post' ); ?>
		<?php endif; ?>

		<?php bimber_render_entry_flags(); ?>

		<?php
		if ( $bimber_elements['featured_media'] ) :
			bimber_render_entry_featured_media( array(
				'size' => 'bimber-index',
				'use_ellipsis'  => true,
				'allow_video'   => true,
				'allow_gif'     => true,
			) );
		endif;
		?>


		<?php if ( $bimber_elements['author'] && $bimber_elements['avatar'] ) : ?>
			<p class="g1-meta entry-byline entry-byline-with-avatar">
		<?php else : ?>
			<p class="g1-meta entry-byline">
		<?php endif; ?>

			<?php if ( $bimber_elements['author'] || $bimber_elements['date'] ) : ?>
				<?php
				if ( $bimber_elements['author'] ) :
					bimber_render_entry_author( array(
						'avatar'      => $bimber_elements['avatar'],
						'avatar_size' => 30,
					) );
				endif;
				?>

				<?php
				if ( $bimber_elements['date'] ) :
					bimber_render_entry_date();
				endif;
				?>
			<?php endif; ?>
		</p>

		<div class="entry-body">
			<?php if ( $bimber_elements['summary'] ) : ?>
				<div class="entry-summary g1-typography-xl">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>


		</div>
	</div>

	<?php if ( isset( $bimber_elements['voting_box'] ) && $bimber_elements['voting_box'] ) : ?>
		<div class="entry-actions snax">
			<?php do_action( 'bimber_entry_voting_box' ); ?>
		</div>
	<?php endif; ?>
</article>
