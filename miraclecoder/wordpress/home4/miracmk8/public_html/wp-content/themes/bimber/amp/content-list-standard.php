<?php
/**
 * The template part for displaying content
 *
 * @package Bimber_Theme 5.4
 */

?>
<?php
$bimber_entry_data = bimber_get_template_part_data();
$bimber_elements   = $bimber_entry_data['elements'];
?>

<article <?php post_class( 'entry-tpl-list' ); ?>>
	<?php
	add_filter( 'the_permalink', 'bimber_amp_force_amp_permalinks', 10, 2 );
	if ( $bimber_elements['featured_media'] ) :
		bimber_render_entry_featured_media( array(
			'size'      => 'bimber-list-standard',
			'use_sizer' => false,
		) );
	endif;
	remove_filter( 'the_permalink', 'bimber_amp_force_amp_permalinks', 10, 2 );
	?>

	<div class="entry-body">
		<header class="entry-header">
			<div class="entry-before-title">
				<?php
				bimber_render_entry_stats( array(
					'share_count'   => $bimber_elements['shares'],
					'view_count'    => $bimber_elements['views'],
					'comment_count' => $bimber_elements['comments_link'],
					'class'         => 'g1-meta g1-current-background',
				) );
				?>

				<?php
				if ( $bimber_elements['categories'] ) :
					bimber_render_entry_categories();
				endif;
				?>
			</div>

			<?php the_title( sprintf( '<h3 class="g1-gamma g1-gamma-1st entry-title"><a href="%s" rel="bookmark">', esc_url( amp_get_permalink( $post->ID ) ) ), '</a></h3>' ); ?>
		</header>

		<?php if ( $bimber_elements['summary'] ) : ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $bimber_elements['author'] || $bimber_elements['date'] ) : ?>
			<footer>
				<p class="g1-meta entry-meta entry-byline <?php if ( $bimber_elements['avatar'] ) { echo sanitize_html_class( 'entry-byline-with-avatar' );}?>">
					<?php
					if ( $bimber_elements['author'] ) :
						bimber_render_entry_author( array( 'avatar' => $bimber_elements['avatar'] ) );
					endif;
					?>

					<?php
					if ( $bimber_elements['date'] ) :
						bimber_render_entry_date( array( 'use_timeago' => true ) );
					endif;
					?>
				</p>
			</footer>
		<?php endif; ?>
	</div>
</article>
