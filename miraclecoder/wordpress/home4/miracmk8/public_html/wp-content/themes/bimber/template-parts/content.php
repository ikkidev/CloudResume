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
);
if ( 'none' !== $bimber_card_style ) {
	$bimber_class[] = 'g1-card';
	$bimber_class[] = 'g1-card-' . $bimber_card_style;
}

// Normalize.
$bimber_elements['call_to_action'] = $bimber_elements['call_to_action'] && bimber_has_entry_call_to_action( $bimber_entry_data['call_to_action_hide_buttons'] );

if ( ! isset( $bimber_elements['voting_box'] )  ) {
	$bimber_elements['voting_box'] = false;
}

$bimber_elements['action_links'] = $bimber_elements['action_links'] && bimber_has_entry_action_links();

// Shortcuts.
$bimber_elements['byline'] = $bimber_elements['author'] || $bimber_elements['date'];
$bimber_elements['todome'] = $bimber_elements['call_to_action'] || $bimber_elements['voting_box'] || $bimber_elements['action_links'];
?>

<article <?php post_class( $bimber_class ); ?>>
	<header class="entry-header">
		<div class="entry-before-title">
			<?php
			if ( $bimber_elements['categories'] ) :
				bimber_render_entry_categories();
			endif;
			?>

			<?php bimber_render_entry_flags( array( 'show_collections' => false ) ); ?>
		</div>

		<?php bimber_render_entry_title( '<h2 class="g1-mega g1-mega-1st entry-title" itemprop="headline"><a href="%1$s" rel="bookmark">', '</a></h2>' ); ?>
		<?php
		if ( $bimber_elements['subtitle'] ) :
			bimber_render_entry_subtitle( '<p class="entry-subtitle g1-gamma g1-gamma-3rd">', '</p>' );
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

	</header>

	<?php
	if ( $bimber_elements['featured_media'] ) :
		bimber_render_entry_featured_media( array(
			'size'          => 'bimber-grid-2of3',
			'allow_video'   => true,
			'allow_gif'     => true,
		) );
	endif;
	?>

	<?php if ( $bimber_elements['summary'] ) : ?>
		<div class="g1-content-narrow g1-typography-xl entry-summary">
		<?php if( strpos( get_the_content(), 'more-link' ) === false ) {
			remove_filter( 'get_the_excerpt', 'bimber_excerpt_more' );
			the_excerpt();
			add_filter( 'get_the_excerpt', 'bimber_excerpt_more' );
		} else {
			the_content( __( 'Continue reading', 'bimber' ) );
		}
		?>
		</div>
	<?php endif; ?>

	<?php if ( $bimber_elements['todome'] ) : ?>
		<div class="entry-todome g1-dropable snax">
			<?php
			if ( $bimber_elements['call_to_action'] ) :
				bimber_render_entry_cta_button( array( 'class' => 'g1-button g1-button-simple g1-button-m' ) );
			endif;
			?>

			<?php
			if ( $bimber_elements['voting_box'] ) :
				do_action( 'bimber_entry_voting_box', 'm' );
			endif;
			?>

			<?php
			if ( $bimber_elements['action_links'] ) :
				bimber_render_entry_action_links();
			endif;
			?>

			<?php bimber_render_mini_share_buttons(); ?>
		</div>
	<?php endif; ?>

</article>
