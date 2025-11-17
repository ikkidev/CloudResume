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
 * Class Bimber_Widget_Featured_Author
 */
class Bimber_Widget_Featured_Author extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'bimber_featured_author_widget',                     // Base ID.
			esc_html__( 'Bimber Featured Author', 'bimber' ),    // Name
			array(                                                  // Args.
				'description' => esc_html__( 'Show a featured author member card', 'bimber' ),
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
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		echo wp_kses_post( $args['before_widget'] );

		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		if ( $instance['user_id'] > 0 ) {
			if ( bp_has_members( 'type=alphabetical&include=' . $instance['user_id'] ) ) :
				while ( bp_members() ) : bp_the_member();
				bp_get_template_part( 'members/members-loop-item' );
				endwhile;
			endif;
		}
		echo wp_kses_post( $args['after_widget'] );
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
		$instance = wp_parse_args( $instance, array( 'offset' => 0 ) );
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$old_id = isset( $instance['user_id'] ) ? $instance['user_id'] : 0;
		$users = get_users();
		?>
			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget title', 'snax' ); ?>
					:</label>
				<input class="widefat"
				       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>"><?php esc_html_e( 'User', 'snax' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this -> get_field_id( 'user_id' ) ); ?>" name="<?php echo  esc_attr( $this -> get_field_name( 'user_id' ) ); ?>" >
				<?php foreach ( $users as $user ) {
					$id = $user->ID ;
					$label = $user->user_nicename;
					echo '<option value="' . esc_attr( $id ) . '"' . selected( $id === $old_id , true, false ) . '>' . esc_html( $label ) . '</option>';
				}?>
				</select>
			</p>
		<?php
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'bimber_widget_featured_author_defaults', array(
			'user_id' => 0,
			'title'        => '',
		) );
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
		$instance = array();
		$instance['user_id'] = (int) filter_var( $new_instance['user_id'], FILTER_SANITIZE_NUMBER_INT );
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
}
