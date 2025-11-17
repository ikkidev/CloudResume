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
class Bimber_Customize_Custom_Radio_Control extends WP_Customize_Control {

	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'radio';

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$description_id = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
		if ( empty( $this->choices ) ) {
			return;
		}

		$name = '_customize-radio-' . $this->id;
		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description ; ?></span>
		<?php endif; ?>

		<?php foreach ( $this->choices as $value => $label ) : ?>
			<span class="customize-inside-control-row <?php if ( isset( $this->input_attrs['row-class'] ) ) { echo esc_attr( $this->input_attrs['row-class'] ); } ?>">
				<input
					id="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"
					type="radio"
					<?php echo $describedby_attr; ?>
					value="<?php echo esc_attr( $value ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					<?php $this->link(); ?>
					<?php checked( $this->value(), $value ); ?>
					/>
				<label for="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"><?php echo esc_html( $label ); ?></label>
			</span>
		<?php endforeach;
	}
}
