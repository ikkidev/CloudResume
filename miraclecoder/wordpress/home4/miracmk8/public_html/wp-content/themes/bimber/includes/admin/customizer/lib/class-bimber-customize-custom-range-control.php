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
class Bimber_Customize_Custom_Range_Control extends WP_Customize_Control {

	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'range';

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$description_id = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
				?>
				<?php if ( ! empty( $this->label ) ) : ?>
					<label
						for="<?php echo esc_attr( $input_id ); ?>"
						class="customize-control-title">
							<?php echo esc_html( $this->label ); ?>
							<span
								title="Undo"
								class="dashicons dashicons-image-rotate g1-range-undo-icon" 
								data-g1-default="<?php echo esc_attr( $this->setting->default );?>">
							</span>
					</label>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description">
						<?php echo $this->description; ?>
					</span>
				<?php endif; ?>
				<input
					class="g1-custom-range-control-slider"
					id="<?php echo esc_attr( $input_id ); ?>"
					type="<?php echo esc_attr( $this->type ); ?>"
					<?php echo $describedby_attr; ?>
					<?php $this->input_attrs(); ?>
					<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
						value="<?php echo esc_attr( $this->value() ); ?>"
					<?php endif; ?>
					<?php $this->link(); ?>
					/>
				<input
					class="g1-custom-range-control-field small-text"
					type="number"
					<?php $this->input_attrs(); ?>
					<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
						value="<?php echo esc_attr( $this->value() ); ?>"
					<?php endif; ?>
					/>
				<?php
	}
}
