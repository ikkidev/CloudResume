<?php
/**
 * WP Customizer custom control to use number input HTML field with sortable capabilities
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
 * Class Bimber_Customize_Sortable_Control
 */
class Bimber_Customize_Sortable_Control extends WP_Customize_Control {

	/**
	 * Type of the control
	 *
	 * @var string
	 */
	public $type = 'sortable';

	/**
	 * List of controls to sort
	 *
	 * @var array
	 */
	public $sortable_controls = array();

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @since 3.4.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string $id Control ID.
	 * @param array $args See parent constructor doc comment for more details.
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render control HTML output
	 */
	public function render_content() {
		if ( ! empty( $this->label ) ) {
			echo wp_kses_post( sprintf( '<span class="customize-control-title">%s</span>', $this->label ) );
		}

		if ( ! empty( $this->description ) ) {
			echo wp_kses_post( sprintf( '<span class="description customize-control-description">%s</span>', $this->description ) );
		}

		$sorted = array();

		foreach ( $this->sortable_controls as $control_id => $control_label ) {
			$setting = $this->manager->get_setting( bimber_get_theme_id() . '[' . $control_id . ']' );

			if ( ! $setting ) {
				continue;
			}

			$order = $setting->value();

			// If already set, user next index.
			if ( isset( $sorted[ $order ] ) ) {
				$order ++;
			}

			$sorted[ $order ] = $control_id;
		}

		ksort( $sorted );
		?>
		<ul class="g1-customizer-sortable">
			<?php foreach ( $sorted as $control_id ): ?>
				<li class="g1-customizer-sortable-control"
				    data-bimber-setting-link="<?php echo esc_attr( bimber_get_theme_id() . '[' . $control_id . ']' ); ?>">
					<?php echo esc_html( $this->sortable_controls[ $control_id ] ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
