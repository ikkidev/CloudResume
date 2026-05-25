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

<?php
if ( bimber_show_breadcrumbs() ) :
	bimber_render_breadcrumbs();
endif;
do_action( 'bimber_before_single_content' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="" itemtype="<?php echo esc_attr( bimber_get_entry_microdata_itemtype() ); ?>">
	<div class="snax-collection-essentials">

		<?php if ( 'public' === snax_get_collection_visibility() || snax_user_is_collection_owner() || ( is_user_logged_in() && snax_is_abstract_collection() ) ) : ?>
			<?php
				bimber_render_entry_featured_media( array(
					'size'              => 'bimber_snax_collection_featured_image',
					'force_placeholder' => true,
					'use_microdata'     => false,
					'apply_link'        => false,
					'show_caption'      => false,
					'thumbnail_id'      => snax_get_collection_thumbnail_id(),
				) );
			?>
		<?php endif; ?>

		<header class="entry-header">
			<?php $snax_pt = get_post_type_object( 'snax_collection' ); ?>
			<?php if ( 'public' === snax_get_collection_visibility() || snax_user_is_collection_owner() || ( is_user_logged_in() && snax_is_abstract_collection() ) ) : ?>
				<p class="entry-before-title"><?php echo esc_html( $snax_pt->labels->singular_name ); ?> <?php snax_render_collection_visibility(); ?></p>
			<?php else : ?>
				<p class="entry-before-title"><?php echo esc_html( $snax_pt->labels->singular_name ); ?></p>
			<?php endif; ?>

			<?php bimber_render_entry_title( '<h1 class="g1-giga g1-giga-1st entry-title" itemprop="headline">', '</h1>' ); ?>
			<?php snax_collection_description(); ?>

			<?php if ( ! snax_is_collection_edit_view() && ! snax_collection_has_description() && snax_user_can_edit_collection() ) : ?>
				<p><a href="<?php echo esc_url( snax_get_collection_edit_url() ); ?>" class="snax-collection-description-edit"><?php esc_html_e( 'Add a description', 'snax' ); ?></a></p>
			<?php endif; ?>

			<?php snax_get_template_part( 'collections/meta'); ?>

			<?php if ( get_the_author_meta( 'ID' ) === get_current_user_id() ) : ?>
				<?php snax_get_template_part( 'collections/actions'); ?>
			<?php endif; ?>
		</header>
	</div>


	<div class="entry-content" itemprop="articleBody">
		<?php the_content(); ?>
		<?php wp_link_pages( array(
			'bimber_pagination' => array(
				'overview'       => 'page_links',
				'adjacent_label' => 'adjacent',
				'adjacent_style' => 'link',
				'next_post'      => 'none',
			)
		) ); ?>
	</div>
</article>
