<?php
/**
 * BP Nouveau Component's  filters template.
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>
<?php
$bimber_component = bp_nouveau_current_object();
$bimber_component = $bimber_component['object'];
?>
<div class="g1-button-group g1-bp-dir-layout" data-component="<?php echo esc_attr( $bimber_component );?>">
	<p class="bp-screen-reader-text">Change Layout</p>
	<div class="g1-button-group-buttons" role="group">
		<?php
		$bimber_current = (int) bp_nouveau_get_temporary_setting( $bimber_component . '_layout', bp_nouveau_get_appearance_settings( $bimber_component . '_layout' ) );
		$bimber_current = (int) get_query_var( 'bp-members-layout', $bimber_current );
		?>
		<?php for ( $i = 1; $i < 5; $i++ ) : ?>
			<button data-cols="<?php echo absint( $i ); ?>" class="g1-button g1-button-simple g1-button-m<?php if ( $i === $bimber_current ) echo ' g1-button-current'; ?>">
				<?php bimber_render_svg( 'bp-layout', $i, array( 'width' => 16, 'height' => 16 ) ); ?>
			</button>
		<?php endfor; ?>
	</div>
</div>
<?php wp_enqueue_script( 'bimber-bp-dir-layout' );
