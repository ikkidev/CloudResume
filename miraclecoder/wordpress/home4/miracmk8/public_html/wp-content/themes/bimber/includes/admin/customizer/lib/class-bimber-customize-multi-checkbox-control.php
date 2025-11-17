<?php
/**
 * WP Customizer custom control to use multiple checkboxes
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
 * Class Bimber_Customize_Multi_Checkbox_Control
 */
class Bimber_Customize_Multi_Checkbox_Control extends WP_Customize_Control {
	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'multi-checkbox';

	/**
	 * Enqueu control assets
	 */
	public function enqueue() {
		wp_enqueue_script( 'bimber-customizer', BIMBER_ADMIN_DIR_URI . 'customizer/js/customizer.js', array( 'jquery' ), false, true );
	}

	/**
	 * Render control HTML output
	 */
	protected function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		?>

		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span
				class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
		<?php endif; ?>

		<?php $values = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

		<ul>
			<?php foreach ( $this->choices as $value => $label ) :  ?>
				<li>
					<label>
						<input type="checkbox"
						       value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $values, true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>

		<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $values ) ); ?>"/>
		<?php
	}
}
