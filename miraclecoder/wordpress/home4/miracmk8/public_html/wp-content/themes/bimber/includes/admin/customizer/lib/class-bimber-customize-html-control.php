<?php
/**
 * WP Customizer custom control to display HTML markup.
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
 * Class Bimber_Customize_HTML_Control
 */
class Bimber_Customize_HTML_Control extends WP_Customize_Control {
	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'html';

	/**
	 * HTML control output
	 *
	 * @var string
	 */
	public $html;

	/**
	 * Enqueue control assets.
	 */
	public function enqueue() {
		wp_enqueue_style( 'bimber-customizer', BIMBER_ADMIN_DIR_URI . 'customizer/css/customizer.css' );
	}

	/**
	 * Render control HTML output
	 */
	protected function render_content() {
		if ( ! empty( $this->html ) ) :
			echo wp_kses_post( $this->html );
		endif;
	}
}
