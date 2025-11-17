<?php
/**
 * Sticky Start Point Widget
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
 * Class Bimber_Widget_Sticky_Start_Point
 */
class Bimber_Widget_Sticky_Start_Point extends WP_Widget {

	public static $defaults = array(
		'offset'     => 0,
		'height'     => 0,
		'widgets_nb' => 0,
	);

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'bimber_sticky_start_point_widget',                     // Base ID.
			esc_html__( 'Bimber Sticky Start Point', 'bimber' ),    // Name
			array(                                                  // Args.
				'description' => esc_html__( 'Use this widget to define place where sticky elements starts', 'bimber' ),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		/**
		 * 1. offset
		 *
		 *      Defines offset (in px) from the browser top edge where the widget should stuck
		 *
		 * 2. height
		 *
		 *      = 0
		 *          All subsequent sidebar widgets should be in one container (float together while scrolling).
		 *          The widgets_nb value is irrelevant when height = 0.
		 *          This is a default value, it keeps backwards compatibility.
		 *
		 *      > 0
		 *          Every single widget is inside its onw container (with defined height). Widget floats within that container while scrolling.
		 *
		 * 3. widgets_nb
		 *
		 *      Defines how many subsequent widgets should be sticky. Works only is height > 0.
		 *      Default value is 0, it keeps backwards compatibility.
		 */

		$instance = wp_parse_args( $instance, self::$defaults );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php esc_html_e( 'Offset', 'bimber' ); ?>:</label>
			<input
				class="widefat"
			    type="text" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>"
			    id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"
			    value="<?php echo esc_attr( $instance['offset'] ) ?>" />
			<small><?php esc_html_e( 'If you have some sticky elements on your site (e.g. menu), they can overlap on sticky sidebar. Set offset here to move the sidebar down.', 'bimber' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Sticky widget container height (in px)', 'bimber' ); ?>:</label>
			<input
				class="widefat"
				type="text" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>"
				id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"
				value="<?php echo esc_attr( $instance['height'] ) ?>" />
			<small><?php esc_html_e( 'Space inside which a sticky widget can float while scrolling (e.g. 1000). 0 - all subsequent widgets float together', 'bimber' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widgets_nb' ) ); ?>"><?php esc_html_e( 'Number of subsequent widgets to be sticky', 'bimber' ); ?>:</label>
			<input
				class="widefat"
				type="text" name="<?php echo esc_attr( $this->get_field_name( 'widgets_nb' ) ); ?>"
				id="<?php echo esc_attr( $this->get_field_id( 'widgets_nb' ) ); ?>"
				value="<?php echo esc_attr( $instance['widgets_nb'] ) ?>" />
		</p>
		<small><?php esc_html_e( '0 - all subsequent widgets will be sticky', 'bimber' ); ?></small>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array(
			'offset'     => ! empty( $new_instance['offset'] ) ? strip_tags( $new_instance['offset'] ) : 0,
			'height'     => ! empty( $new_instance['height'] ) ? strip_tags( $new_instance['height'] ) : 0,
			'widgets_nb' => ! empty( $new_instance['widgets_nb'] ) ? strip_tags( $new_instance['widgets_nb'] ) : 0,
		);

		return $instance;
	}
}
