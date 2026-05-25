<?php
/**
 * WP Customizer custom control to use tag selection box
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


/**
 * Class Bimber_Customize_Tag_Select_Control
 */
class Bimber_Customize_Typography_Control extends WP_Customize_Control {

	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'typography';

	/**
	 * Enqueue scripts.
	 */
	public function enqueue() {
		wp_enqueue_script( 'rangeslider', BIMBER_ADMIN_DIR_URI . 'customizer/js/rangeslider/rangeslider.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'rangeslider', BIMBER_ADMIN_DIR_URI . 'customizer/js/rangeslider/rangeslider.css' );
		wp_enqueue_script( 'bimber-customizer', BIMBER_ADMIN_DIR_URI . 'customizer/js/customizer.js', array( 'jquery', 'rangeslider' ), false, true );
	}

	/**
	 * Render range input
	 *
	 * @param string $sub_field_name	Sub field slug.
	 * @param int 	 $min				Min.
	 * @param int 	 $max				Max.
	 * @param int 	 $value				Value.
	 * @param int 	 $step  			Step.
	 */
	private function render_range_sub_field( $sub_field_name, $min, $max, $value, $step ) {
		?>
		<input
			class="g1-typo-setting-range"
			type="range"
			min = "<?php echo esc_attr( $min ); ?>"
			max = "<?php echo esc_attr( $max ); ?>"
			step = "<?php echo esc_attr( $step ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			/>
			<input class="g1-typo-setting-input g1-typo-setting-input-<?php echo sanitize_html_class( $sub_field_name );?> g1-typo-setting-range-value"
			type="number"
			min = "<?php echo esc_attr( $min ); ?>"
			max = "<?php echo esc_attr( $max ); ?>"
			step = "<?php echo esc_attr( $step ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			>
	<?php
	}

	/**
	 * Render select
	 *
	 * @param string $sub_field_name	Sub field slug.
	 * @param array  $choices			Choices.
	 * @param int 	 $the_value			Value.
	 */
	private function render_select_sub_field( $sub_field_name, $choices, $the_value ) {
		?>
		<select class="g1-typo-setting-input g1-typo-setting-input-<?php echo sanitize_html_class( $sub_field_name );?>">
			<?php
			foreach ( $choices as $value => $label ) {
				echo '<option value="' . esc_attr( $value ) . '"' . selected( $value, $the_value, true , true, false ) . '>' . esc_html( $label ) . '</option>';
			}
			?>
		</select>
	<?php
	}

	/**
	 * Render font picker
	 *
	 * @param string $sub_field_name	Sub field slug.
	 * @param int 	 $the_value			Value.
	 */
	private function render_font_picker( $sub_field_name, $the_value ) {
		$choices = bimber_bending_cat_get_available_font_families();
		?>
		<select class="<?php echo sanitize_html_class( $sub_field_name );?> g1-typo-setting-input g1-typo-setting-input-font-family">
		<option value="g1-none" <?php selected( 'g1-none', $the_value, true , true, false )?>></option>
			<?php
			foreach ( $choices as $group_slug => $group ) {
				echo '<optgroup label="' . esc_html( $group['label'] ) . '">';
				foreach ( $group['fonts'] as $family => $args ) {
					$variants = implode( ',',$args['variants'] );
					$variant_names = implode( ',',$args['variant_names'] );?>
					<option
						data-g1-font-variants="<?php echo esc_attr( $variants );?>"
						data-g1-font-variant-names="<?php echo esc_attr( $variant_names );?>"
						value="<?php echo esc_attr( $family );?>"
						<?php selected( $family, $the_value, true , true, false )?>>
						<?php echo esc_html( $family ) ?>
						</option>';
					<?php
				}
				echo '</optgroup>';
			}
			?>
		</select>
	<?php
	}

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$default = 0;
		$value = $this->value();
		if ( $value ) {
			$values = json_decode( $value, true );
		} else {
			$values = array();
		}
		$selector_active = ! empty( $values );
		$sub_fields = bimber_bending_cat_get_typography_cutomization_options();
		$selector = apply_filters( 'bimber_typo_control_selector_' . $this->id, $this->input_attrs['selector'] );
		?>
		<span class="customize-control-title"
			data-g1-selector-active="<?php echo esc_attr( $selector_active === true ? 'on' : 'off' );?>"
			data-g1-selector="<?php echo esc_attr( $selector );?>">
			<label>
				<?php echo esc_html( $this->label ); ?>
			</label>
			<span class="g1-typo-toggle"></span>
		</span>
		<ul class="g1-typo-tabs <?php if ( $selector_active ) { echo sanitize_html_class( 'g1-typo-settings-active' );}?>">
			<li data-g1-tab="desktop" class="g1-typo-tab selected" ><?php echo esc_html__( 'Desktop', 'bimber' )?></li>
			<li data-g1-tab="tablet" class="g1-typo-tab"><?php echo esc_html__( 'Tablet', 'bimber' )?></li>
			<li data-g1-tab="mobile" class="g1-typo-tab"><?php echo esc_html__( 'Mobile', 'bimber' )?></li>
		</ul>
		<ul class="g1-typo-settings <?php if ( $selector_active ) { echo sanitize_html_class( 'g1-typo-settings-active' );}?>">
		<?php
		foreach ( $sub_fields as $sub_field => $args ) {
			if ( ! in_array( $sub_field, $this->input_attrs['attributes'], true ) ) {
				continue;
			};
			if ( isset( $this->input_attrs['attribute_args'] ) && array_key_exists( $sub_field, $this->input_attrs['attribute_args'] ) ) {
				$args = wp_parse_args( $this->input_attrs['attribute_args'][ $sub_field ], $args );
			}
			$tab = 'all' === $args['media-query'] ? 'desktop' : $args['media-query'];
			$sub_field_id = $input_id . '-' . $sub_field;
			$value = isset( $values[ $sub_field ] ) ? $values[ $sub_field ] : $args['default'];
		?>
			<li id="<?php echo esc_attr( $sub_field_id . '-active' );?>"
				class="g1-typo-setting
				<?php 
				if ( isset( $args['input-class'] ) ) {
					echo sanitize_html_class( $args['input-class'] );
				}?>
				g1-typo-setting-<?php echo sanitize_html_class( $sub_field ) . ' ';?>
				g1-typo-setting-tab-<?php echo sanitize_html_class( $tab ) . ' ';?>"
				data-g1-sub-field-name="<?php echo sanitize_html_class( $sub_field );?>">
				<label class="g1-customizer-typo-title">
					<?php echo esc_html( $args['label'] ); ?>
					<span
						title="Undo"
						class="dashicons dashicons-image-rotate g1-typo-undo-icon"
						data-g1-default="<?php echo esc_attr( $value );?>">
					</span>
				</label>
				<?php
				switch ( $args['type'] ) {
					case 'range':
						$this->render_range_sub_field( $sub_field, $args['min'], $args['max'], $value, $args['step'] );
						break;
					case 'select':
						$this->render_select_sub_field( $sub_field, $args['choices'], $value );
						break;
					case 'font-picker':
						$this->render_font_picker( $sub_field, $value );
					default:
						break;
				}
					?>
			</li>
		<?php
		}
		?>
		</ul>
		<a href="#" class="g1-typo-remove"><?php echo esc_html__( 'Remove', 'bimber' )?></a>
		<input class="g1-typo-final-value" type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $value );?>" id="<?php echo esc_attr( $input_id . '-active' );?>"/>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span
				class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
		<?php endif; ?>
		<?php
	}


}
