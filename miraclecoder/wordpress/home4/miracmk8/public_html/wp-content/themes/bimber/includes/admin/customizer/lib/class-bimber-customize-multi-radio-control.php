<?php
/**
 * WP Customizer custom control to use multiple radios
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
 * Class Bimber_Customize_Multi_Radio_Control
 */
class Bimber_Customize_Multi_Radio_Control extends WP_Customize_Control {
	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'multi-radio';

	/**
	 * Nunber of columns per row.
	 *
	 * @var int
	 */
	public $columns = 1;

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

		<?php
		$class = array(
			'g1ui-img-radio-items',
			'g1ui-img-radio-items-cols-' . $this->columns,
		);
		?>

		<ul class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $class ) ); ?>">
			<?php foreach ( $this->choices as $value => $data ) :  ?>
				<li class="g1ui-img-radio-item">
					<label>
						<input
							type="radio"
							<?php $this->link(); ?>
							name="<?php echo esc_attr( $this->id ); ?>"
							value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $this->value() ); ?>
						/>
						<?php
						if ( isset( $data['path'] ) ) {
							echo '<span><img src="' . esc_url( $data['path'] ) . '" alt="' . esc_attr( $this->id ) . '" title="' . esc_attr( $data['label'] ) . '" /></span>';
						} else {
							echo '<span>' . esc_html( $data['label'] ) . '</span>';
						}
						?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
