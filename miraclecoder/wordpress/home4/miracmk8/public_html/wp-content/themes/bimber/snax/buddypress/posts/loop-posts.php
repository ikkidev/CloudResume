<?php
/**
 * Post items Loop
 *
 * @package snax 1.11
 * @subpackage Votes
 */

?>
<?php do_action( 'snax_template_before_bp_posts_loop' ); ?>

<div class="snax-posts">
	<?php
		$bimber_class = array( 'g1-collection' );

		$bimber_tpl         = bimber_get_theme_option( 'bp', 'archive_template' );
		$bimber_tpl_sidebar = bimber_get_theme_option( 'bp', 'enable_sidebar' );

		if ( is_null( $bimber_tpl ) ) {
		    $bimber_tpl = 'grid-m';
        }

		if ( is_null( $bimber_tpl_sidebar ) ) {
		    $bimber_tpl_sidebar = 'standard';
        }

		$bimber_is_sidebar = 'standard' === $bimber_tpl_sidebar;

		$bimber_class[] = 'g1-collection-' . $bimber_tpl;

		if ( 'grid-m' === $bimber_tpl ) {
			$bimber_class[] = $bimber_is_sidebar ? 'g1-collection-columns-2' : 'g1-collection-columns-3';
		}
	?>

	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_class ) ); ?>">
		<?php
		$bimber_elements = array(
			'featured_media' => true,
			'categories'     => false,
			'shares'         => false,
			'views'          => false,
			'comments_link'  => false,
			'downloads'      => false,
			'votes'          => false,
			'subtitle'       => false,
			'summary'        => false,
			'author'         => false,
			'avatar'         => false,
			'date'           => true,
			'voting_box'     => true,
			'call_to_action' => true,
			'action_links'   => true,
		);

		bimber_set_template_part_data( array(
			'elements'                     => $bimber_elements,
			'card_style'                   => 'none',
			'call_to_action_hide_buttons'  => 'read_more',
		) );
		?>
		<div class="g1-collection-viewport">
			<ul class="g1-collection-items">
				<?php while ( snax_user_posts() ) : snax_the_post(); ?>
					<li class="g1-collection-item">
						<?php get_template_part( 'template-parts/content-' . $bimber_tpl, get_post_format() ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php bimber_reset_template_part_data(); ?>
	</div><!-- .g1-collection -->
</div>

<?php do_action( 'snax_template_after_bp_posts_loop' );

