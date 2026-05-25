<?php

/**
 * @var string $name
 * @var string $label
 */

use WPGDPRC\Utils\AdminHelper;

if ( empty( $id ) ) {
	$id = sanitize_key( $name );
}
$checked = ! empty( $checked ) ? 'checked="checked"' : '';

?>

<p class="wpgdprc-checkbox
<?php
if ( ! empty( $class ) ) {
	echo esc_attr( $class );}
?>
">
	<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="1" <?php echo esc_attr( $checked ); ?> />
	<label for="<?php echo esc_attr( $id ); ?>">
		<?php echo wp_kses( $label, AdminHelper::getAllowedHTMLTags() ); ?>
	</label>
</p>
