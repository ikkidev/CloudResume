<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Element Category Control
 */
class Bimber_Radio_Control extends Base_Data_Control {

	/**
	 * Get text control type.
	 *
	 * Retrieve the control type, in this case `text`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'bimber_radio';
	}

	/**
	 * Render choose control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{value}}' );
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<ul class="elementor-g1-radio-items">
					<# _.each( data.options, function( options, value ) { #>
					<li class="elementor-g1-radio-item">
						<label class="tooltip-target" for="<?php echo $control_uid; ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
							<input id="<?php echo $control_uid; ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}"<# if ( data.controlValue == value ) { #>checked="checked"<# } #>>
							<span class=""><img src="{{ options.icon }}" alt=""/></span>
						</label>
					</li>
					<# } ); #>
				</ul>
			</div>
		</div>

		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
		<?php
	}

	/**
	 * Get choose control default settings.
	 *
	 * Retrieve the default settings of the choose control. Used to return the
	 * default settings while initializing the choose control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
			'toggle' => true,
		];
	}
}
