<?php
/**
 * The default template for displaying single post content (with sidebar).
 * This is a template part. It must be used within The Loop.
 *
 * @package Bimber_Theme 5.4
 */

$bimber_entry_data = bimber_get_template_part_data();
$bimber_elements   = $bimber_entry_data['elements'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-tpl-classic' ); ?> itemscope=""
         itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">

	<header class="entry-header">

		<?php
		if ( $bimber_elements['categories'] ) :
			bimber_render_entry_categories();
		endif;
		?>

		<?php the_title( sprintf( '<h2 class="g1-mega g1-mega-1st entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( $bimber_elements['author'] || $bimber_elements['date'] || $bimber_elements['views'] || $bimber_elements['comments_link'] ) : ?>
			<p class="entry-meta entry-meta-m <?php if ( $bimber_elements['avatar'] ) { echo sanitize_html_class( 'entry-meta-with-avatar' );}?>">
                    <span class="entry-meta-wrap">
                        <?php if ( $bimber_elements['author'] ) : ?>

	                        <span class="entry-author" itemscope="" itemprop="author"
	                              itemtype="http://schema.org/Person">
                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                                   title="<?php printf( esc_attr__( 'Posts by %s', 'bimber' ), esc_attr( get_the_author() ) ); ?>"
                                   rel="author">
	                                <?php if ( $bimber_elements['avatar'] ) : ?>
		                                <?php echo get_avatar( get_the_author_meta( 'email' ), 40 ); ?>
	                                <?php endif; ?>
	                                <strong itemprop="name"><?php the_author(); ?></strong>
                                </a>
                            </span>

                        <?php endif; ?>

	                    <?php
	                    if ( $bimber_elements['date'] ) :
		                    bimber_render_entry_date();
	                    endif;
	                    ?>
                    </span>

                    <span class="entry-meta-wrap">

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

	<?php
	if ( $bimber_elements['featured_media'] ) :
		bimber_render_entry_featured_media( array(
			'size'          => 'bimber-grid-2of3',
			'allow_video'   => true,
		) );
	endif;
	?>

	<div class="entry-summary g1-typography-xl">
		<?php the_content( __( 'Continue reading', 'bimber' ) ); ?>
	</div>
</article>
