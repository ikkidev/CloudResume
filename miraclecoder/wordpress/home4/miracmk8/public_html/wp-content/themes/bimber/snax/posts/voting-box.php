<?php
/**
 * Snax Post Voting Box Template Part
 *
 * @package snax
 * @subpackage Theme
 */
$bimber_body_class = array( 'snax-voting-container-body');
$bimber_card_style = bimber_get_theme_option( 'cards', 'single_content' );
if ( 'none' !== $bimber_card_style  ) {
	$bimber_body_class[] = 'g1-card';
	$bimber_body_class[] = 'g1-card-' . $bimber_card_style;
	$bimber_body_class[] = 'g1-card-l';
}
?>
<?php if ( snax_show_item_voting_box() ) : ?>
<div class="snax-voting-container">
	<?php bimber_render_section_title( __( 'What do you think?', 'bimber' ) ); ?>

	<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_body_class ) ); ?>">
		<?php
			snax_render_voting_box( null, 0, array(
				'class'                         => 'snax-voting-large',
				'show_member_profile_page_link' => true,
			) );
		?>
	</div>
</div>
<?php endif;
