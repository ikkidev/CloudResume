<?php
/**
 * Patreon Widget
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package adace_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'widgets_init', 'adace_register_patreon_widget' );
/**
 * About me widget register function.
 */
function adace_register_patreon_widget() {
	register_widget( 'Adace_Patreon_Widget' );
}

/**
 * Patreon widget class.
 */
class Adace_Patreon_Widget extends WP_Widget {
	/**
	 * Widget contruct.
	 */
	function __construct() {
		$widget_ops = apply_filters( 'adace_patreon_widget_options', array(
			'description'   => esc_html__( 'Promote your Patreon page.', 'adace' ),
			'classname'     => 'widget_adace_patreon',
		) );

		parent::__construct( 'adace_patreon_widget', esc_html__( 'AdAce Patreon', 'adace' ), $widget_ops );
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'adace_widget_patreon_defaults', array(
			'title' => esc_html__( 'Patreon', 'adace' ),
		) );
	}

	/**
	 * Widget contruct.
	 *
	 * @param array $instance Current widget settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adace' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
		</p>
		<?php
	}

	/**
	 * Widget saving.
	 *
	 * @param array $new_instance Current widget settings form output.
	 * @param array $old_instance Old widget settings.
	 */
	public function update( $new_instance, $old_instance ) {
		// Sanitize input.
		$instance              = array();
		$instance['title']     = filter_var( $new_instance['title'], FILTER_SANITIZE_STRING );
		return $instance;
	}

	/**
	 * Widget output.
	 *
	 * @param array $args Widget args from registration point.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		// Get settings.
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		$title = apply_filters( 'widget_title', $instance['title'] );
		// Echo all widget elements.
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		$patreon_label = get_option( 'adace_patreon_label', adace_options_get_defaults( 'adace_patreon_label' ) );
		$patreon_title = get_option( 'adace_patreon_title', adace_options_get_defaults( 'adace_patreon_title' ) );
		$patreon_link  = get_option( 'adace_patreon_link', adace_options_get_defaults( 'adace_patreon_link' ) );
		set_query_var( 'adace_patreon_label', $patreon_label );
		set_query_var( 'adace_patreon_link', $patreon_link );
		set_query_var( 'adace_patreon_title', $patreon_title );
		adace_get_template_part( 'widget-patreon' );
		echo wp_kses_post( $args['after_widget'] );
	}
}
