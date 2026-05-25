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
class Bimber_Customize_Typography_Selector_Control extends WP_Customize_Control {

	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'typography-selectors';

	/**
	 * Enqueue scripts.
	 */
	public function enqueue() {
		wp_enqueue_script( 'bimber-customizer', BIMBER_ADMIN_DIR_URI . 'customizer/js/customizer.js', array( 'jquery', 'rangeslider' ), false, true );
		add_action( 'customize_controls_print_footer_scripts',  array( $this, 'render_modal' ) );
	}

	public function render_modal() {?>
		<div class="g1-typo-selectors-modal">
			<div class="g1-typo-selectors-modal-header">
				<a href="#" class="g1-typo-selectors-modal-button g1-typo-selectors-modal-button-cancel"><?php echo esc_html__( 'Cancel', 'bimber' )?></a>
				<button class="g1-typo-selectors-modal-button g1-typo-selectors-modal-button-add button add-new-menu-item"><?php echo esc_html__( 'Add', 'bimber' )?></button>
			</div>
			<ul class="g1-typo-selectors-modal-list">
			</ul>
		</div>
		<?php
	}

	/**
	 * Render the control's content.
	 */
	protected function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$default = 0;
		$value = $this->value();
		?>
		<button class="g1-typo-selectors-button button add-new-menu-item"><?php echo esc_html__( 'Add New Control', 'bimber' )?></button>
		<input class="g1-typo-selectors-final-value" type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $value );?>" id="<?php echo esc_attr( $input_id . '-active' );?>"/>
		<?php
	}

}
