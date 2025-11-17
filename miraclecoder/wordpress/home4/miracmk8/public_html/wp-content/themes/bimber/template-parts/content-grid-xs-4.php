<?php
/**
 * The template part for displaying content
 *
 * @package Bimber_Theme 4.10
 */

$bimber_media_ratio = get_query_var( 'bimber_media_ratio', '2-1' );

$bimber_media_size = 'bimber-grid-xs';
if ( '2-1' !== $bimber_media_ratio ) {
	$bimber_media_size = 'bimber-grid-xs-ratio-' . $bimber_media_ratio;
}
?>

<article <?php post_class( 'entry-tpl-gridxs' ); ?>>
	<?php
	bimber_render_open_list_badge();
	bimber_render_entry_featured_media( array(
		'size' => $bimber_media_size,
	) );
	?>

	<header class="entry-header">
		<?php bimber_render_entry_title( '<h3 class="g1-epsilon g1-epsilon-1st entry-title"><a href="%1$s" rel="bookmark">', '</a></h3>' ); ?>
	</header>
</article>
