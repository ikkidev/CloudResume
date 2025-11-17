<?php
/**
 * The template part for displaying the footer stamp
 *
 * @package Bimber_Theme 4.10
 */

?>
<?php
$bimber_stamp            = bimber_get_footer_stamp();
$bimber_stamp_label      = bimber_get_theme_option( 'footer', 'stamp_label' );
$bimber_stamp_label_hide = bimber_get_theme_option( 'footer', 'stamp_label_hide' );
if ( ! $bimber_stamp && ! $bimber_stamp_label ) {
	return;
}

$bimber_stamp_label_class = array(
	'g1-footer-stamp-label',
);
if ( $bimber_stamp_label_hide ) {
	$bimber_stamp_label_class[] = 'screen-reader-text';
}
?>

<?php if ( ! empty( $bimber_stamp ) ) : ?>
	<?php if ( $bimber_stamp_label_hide ) : ?>
		<a class="g1-footer-stamp" href="<?php echo esc_url( bimber_get_theme_option( 'footer', 'stamp_url' ) ); ?>" title="<?php echo esc_attr( $bimber_stamp_label ); ?>">
	<?php else: ?>
		<a class="g1-footer-stamp" href="<?php echo esc_url( bimber_get_theme_option( 'footer', 'stamp_url' ) ); ?>">
	<?php endif; ?>
		<?php
		printf(
			'<img class="g1-footer-stamp-icon" width="%d" height="%d" src="%s" loading="lazy" %s alt="" />',
			absint( $bimber_stamp['width'] ),
			absint( $bimber_stamp['height'] ),
			esc_url( $bimber_stamp['src'] ),
			isset( $bimber_stamp['srcset'] ) ? sprintf( 'srcset="%s"', esc_url( $bimber_stamp['srcset'] ) . ' 2x' ) : ''
		);
		?>
		<?php if ( strlen( $bimber_stamp_label ) ) : ?>
			<span class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $bimber_stamp_label_class ) ); ?>"><?php echo esc_html( $bimber_stamp_label ); ?></span>
		<?php endif; ?>
	</a>
<?php endif;
