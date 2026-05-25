<?php
/**
 * Multi checkbox form field
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( bimber_can_use_plugin( 'js_composer/js_composer.php' ) && function_exists( 'vc_add_shortcode_param' ) ) {
	vc_add_shortcode_param( 'multi_checkbox', 'vc_multi_checkbox_form_field' );
}

function vc_multi_checkbox_form_field( $settings, $value ) {
	$value_arr = $value ? explode( ',', $value ) : array();
	ob_start();
	?>
	<div class="bimber-multi-checkbox-group">
		<?php foreach( $settings['value'] as $field_label => $field_id ) : ?>
			<?php if ( ! $field_id ) continue; ?>

			<?php $checked = in_array( $field_id, $value_arr, true ) ? ' checked="checked"' : ''; ?>

			<label>
				<input class="wpb_vc_param_value multi-checkbox-field" type="checkbox" value="<?php echo esc_attr( $field_id ) ?>" onchange="bimberMultiCheckbox(this)"<?php echo $checked; ?> /> <?php echo esc_html( $field_label ); ?>
			</label>

		<?php endforeach; ?>

		<input name="<?php echo $settings['param_name']; ?>" class="wpb_vc_param_value" type="hidden" value="<?php echo esc_attr( $value ); ?>" />
	</div>
	<script type="text/javascript">
		(function($) {
			window.bimberMultiCheckbox = function(element) {
				var $control = $(element).parents('.bimber-multi-checkbox-group');
				var $hidden = $control.find('input[type=hidden]');
				var selected = [];
				$control.find('input[type=checkbox]:checked').each(function() {
					selected.push($(this).val());
				});
				$hidden.val(selected.join(','));
			};
		})(jQuery);
	</script>
	<?php
	$out = ob_get_clean();

	return $out;
}
