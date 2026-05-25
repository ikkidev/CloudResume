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
	vc_add_shortcode_param( 'image_radio', 'vc_image_radio_form_field' );
}

function vc_image_radio_form_field( $settings, $current_value ) {
	if ( empty( $settings ) ) {
		return;
	}
	$class = array(
		'g1ui-img-radio-items',
	);
	ob_start();
	?>
	<div class="bimber-image-radio-group">
		<ul class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $class ) ); ?>">
			<?php foreach ( $settings['value'] as $value => $data ) :  ?>
				<li class="g1ui-img-radio-item">
					<label>
						<input
							name="<?php echo $settings['param_name']; ?>-radio"
							type="radio"
							value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $current_value ); ?>
							onchange="bimberImageRadio(this)"
						/>
						<?php
						if ( isset( $data['path'] ) ) {
							echo '<span><img src="' . esc_url( $data['path'] ) . '" title="' . esc_attr( $data['label'] ) . '" /></span>';
						} else {
							echo '<span>' . esc_html( $data['label'] ) . '</span>';
						}
						?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<input name="<?php echo $settings['param_name']; ?>" class="wpb_vc_param_value" type="hidden" value="<?php echo esc_attr( $current_value ); ?>" />
	</div>
	<script type="text/javascript">
		(function($) {
			window.bimberImageRadio = function(element) {
				var $control = $(element).parents('.bimber-image-radio-group');
				var $hidden = $control.find('input[type=hidden]');
				var selected = '';
				$control.find('input[type=radio]:checked').each(function() {
					selected = $(this).val();
				});
				$hidden.val(selected);
			};
		})(jQuery);
	</script>
	<style>
		.g1ui-img-radio-items-cols-3{
			display: flex;
			flex-wrap: wrap;
		}
		.g1ui-img-radio-items-cols-3  .g1ui-img-radio-item {
			width: 33.333%;
		}


		.g1ui-img-radio-item span {
			display: block;
			border-width: 1px;

			border-style: solid;
			border-color: #ccc;

			opacity: 0.666;
			filter: grayscale(100%);
		}

		.g1ui-img-radio-item img {
			display: block;
			margin: 0 auto;
		}

		.g1ui-img-radio-item input[type=radio] {
			display: none;
		}

		.g1ui-img-radio-item:hover input[type=radio] + span,
		.g1ui-img-radio-item input[type=radio]:checked + span {
			background-color: #fff;
			opacity: 1;
			filter: none;
		}
	</style>
	<?php
	$out = ob_get_clean();

	return $out;
}
