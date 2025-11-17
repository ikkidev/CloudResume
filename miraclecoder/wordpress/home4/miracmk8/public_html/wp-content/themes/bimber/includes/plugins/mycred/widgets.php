<?php
/**
 * MyCred widgets
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

class Bimber_MyCred_Widget extends WP_Widget {

	/**
	 * The total number of displayed widgets
	 *
	 * @var int
	 */
	static $counter = 0;

	/**
	 * Constructor.
	 */
	function __construct() {
		// Set up optional widget args
		$widget_ops = array(
			'classname'   => 'widget_bimber_mycred widget',
			'description' => 'Display MyCred points for current user or displayed user(in BP profiles)',
		);

		// Set up the widget
		parent::__construct(
			false,
			'(Bimber) MyCred Points',
			$widget_ops
		);

		self::$counter ++;
	}

	/**
	 * Render widget
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance of widget.
	 */
	public function widget( $args, $instance ) {
		$user_id = $this->get_user_id();
		if ( 0 === $user_id ) {
			return;
		}

		$instance = wp_parse_args( $instance, $this->get_default_args() );
		$title = apply_filters( 'widget_title', $instance['title'] );
		// HTML id.
		if ( empty( $instance['id'] ) ) {
			$instance['id'] = 'bimber-mycred-widget-' . self::$counter;
		}
		// HTML class.
		$classes   = explode( ' ', $instance['class'] );
		$classes[] = 'bimber-mycred-widget';
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}


		$view_all_url = $this->get_view_all_url( $user_id );

		set_query_var( 'bimber_mycred_widget_user_id', $user_id );
		set_query_var( 'bimber_mycred_widget_id', $instance['id'] );
		set_query_var( 'bimber_mycred_widget_classes', $classes );
		set_query_var( 'bimber_mycred_widget_view_all_url', $view_all_url );
		get_template_part( 'template-parts/widget-mycred-points' );
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Get user id to use.
	 *
	 * @return int
	 */
	protected function get_user_id() {
		$id = 0;
		// On BP profile page?
		if ( function_exists( 'bp_get_displayed_user' ) && $user = bp_get_displayed_user() ) {
			$id =  $user->id;
		}
		if ( is_user_logged_in() && ! $id ) {
			$id = get_current_user_id();
		}
		return $id;
	}

	/**
	 * Points history url
	 *
	 * @param int $user_id
	 * @return string
	 */
	protected function get_view_all_url( $user_id ) {
		if ( ! $user_id ) {
			return '';
		}

		$mycred = mycred();
		if ( empty( $mycred->core['buddypress']['history_location'] ) ) {
			return '';
		}


		if ( ! isset( $mycred->buddypress['history_url'] ) || empty( $mycred->buddypress['history_url'] ) ) {
			return '';
		}

		return bp_core_get_user_domain( $user_id ) . $mycred->buddypress['history_url'];
	}

	/**
	 * Render form
	 *
	 * @param array $instance Instance of widget.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		?>
		<div class="bimber_mycred_widget">
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'bimber' ); ?>
					:</label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $instance['title'] ); ?>">
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php esc_html_e( 'HTML id attribute (optional)', 'bimber' ); ?>
					:</label>
				<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"
				       value="<?php echo esc_attr( $instance['id'] ) ?>"/>
			</p>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'HTML class(es) attribute (optional)', 'bimber' ); ?>
					:</label>
				<input class="widefat" type="text"
				       name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
				       id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
				       value="<?php echo esc_attr( $instance['class'] ) ?>"/>
			</p>
		</div>
		<?php
	}

	/**
	 * Update widget
	 *
	 * @param array $new_instance New instance.
	 * @param array $old_instance Old instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']                = strip_tags( $new_instance['title'] );
		$instance['id']                   = sanitize_html_class( $new_instance['id'] );
		$instance['class']                = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $new_instance['class'] ) ) );
		return $instance;
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'wyr_widget_latest_reactions_defaults', array(
			'title'                => esc_html__( 'Points', 'bimber' ),
			'id'                   => '',
			'class'                => '',
		) );
	}
}

add_action( 'widgets_init', 'bimber_add_mycred_widget' );

/**
 * Add mycred widget.
 */
function bimber_add_mycred_widget() {
	return register_widget( 'Bimber_MyCred_Widget' );
}
