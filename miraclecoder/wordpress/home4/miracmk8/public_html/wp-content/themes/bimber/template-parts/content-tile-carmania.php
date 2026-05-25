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

<article <?php post_class( 'entry-tpl-tile entry-tpl-tile-xl g1-dark' ); ?>>
	<?php
		bimber_render_open_list_badge();
		if ( $bimber_elements['featured_media'] ) :
			bimber_render_entry_featured_media( array(
				'size'              => 'bimber-tile-carmania',
				'force_placeholder' => true,
			) );
		endif;
	?>

	<div class="entry-body">
		<header class="entry-header">
			<div class="entry-before-title">
				<?php
				bimber_render_entry_stats( array(
					'share_count'       => $bimber_elements['shares'],
					'view_count'        => $bimber_elements['views'],
					'comment_count'     => $bimber_elements['comments_link'],
					'download_count'    => $bimber_elements['downloads'],
					'vote_count'        => $bimber_elements['votes'],
					'class'             => 'g1-meta'
				) );
				?>

				<?php
				if ( $bimber_elements['categories'] ) :
					bimber_render_entry_categories();
				endif;
				?>
			</div>

			<?php bimber_render_entry_title( '<h3 class="g1-gamma g1-gamma-1st entry-title"><a href="%1$s" rel="bookmark">', '</a></h3>' ); ?>
		</header>
	</div>
</article>
