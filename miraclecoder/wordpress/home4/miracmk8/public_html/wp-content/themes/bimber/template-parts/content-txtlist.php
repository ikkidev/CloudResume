<?php
/**
 * The template part for displaying content
 *
 * @package Bimber_Theme 4.10
 */

?>
<?php
$bimber_entry_data = bimber_get_template_part_data();
$bimber_elements   = $bimber_entry_data['elements'];
?>

<article <?php post_class( 'entry-tpl-txtlist' ); ?>>
	<div class="entry-body">
		<header class="entry-header">
			<?php bimber_render_entry_title( '<h3 class="g1-epsilon g1-epsilon-1st entry-title"><a href="%1$s" rel="bookmark">', '</a></h3>' ); ?>
		</header>

		<?php
		if ( $bimber_elements['call_to_action'] && bimber_has_entry_call_to_action( $bimber_entry_data['call_to_action_hide_buttons'] ) ) {
			bimber_render_entry_cta_button( array( 'class' => 'g1-button g1-button-simple g1-button-xs' ) );
		}
		?>
	</div>
</article>
