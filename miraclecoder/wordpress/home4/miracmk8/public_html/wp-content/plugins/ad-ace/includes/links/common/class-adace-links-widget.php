<?php
/**
 * Links widget
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

add_action( 'widgets_init', 'adace_register_links_widget' );
/**
 * Links widget register function
 */
function adace_register_links_widget() {
	register_widget( 'Adace_Links_Widget' );
}

/**
 * Links widget class
 */
class Adace_Links_Widget extends WP_widget {
	/**
	 * Widget contruct.
	 */
	function __construct() {
		parent::__construct(
			'adace_links_widget',
			esc_html__( 'AdAce Links', 'adace' ),
			array(
				'description' => esc_html__( 'Widget that display links in nice fashion.', 'adace' ),
			)
		);
	}

		/**
		 * Get default arguments
		 *
		 * @return array
		 */
		public function get_default_args() {
			return apply_filters( 'adace_links_widget_defaults', array(
				'title'       => esc_html__( 'Favourite links', 'adace' ),
				'disclosure'  => 1,
				'category'    => '',
				'simple'      => '',
				'transparent' => '',
				'highlighted' => 2,
			) );
		}

	/**
	 * Widget form.
	 *
	 * @param array $instance Current widget settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		// Prep array for return.
		$categories_choices     = array();
		// Lets make small doggies cry and add this empty choice.
		$categories_choices[''] = esc_html__( '- None -', 'adace' );
		// Get terms and loop to add them to choices.
		$categories             = get_terms( array(
			'taxonomy'   => 'adace_link_category',
			'hide_empty' => true,
		) );
		foreach ( $categories as $category_obj ) {
			$categories_choices[ $category_obj -> slug ] = $category_obj-> name;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adace' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
		</p>
		<p>
			<input
			class="checkbox" type="checkbox" <?php checked( $instance['disclosure'], true ) ?>
			id="<?php echo esc_attr( $this->get_field_id( 'disclosure' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'disclosure' ) ); ?>"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'disclosure' ) ); ?>"><?php esc_html_e( 'Show affiliate disclosure', 'adace' ); ?> </label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Categories:', 'adace' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this -> get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
				<?php
				foreach ( $categories_choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $value === $instance['category'], true, false ) . '>' . esc_html( $label ) . '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['simple'], true ) ?>
				   id="<?php echo esc_attr( $this->get_field_id( 'simple' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'simple' ) ); ?>"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'simple' ) ); ?>"><?php esc_html_e( 'Simple list, don\'t show featured image.', 'adace' ); ?> </label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['transparent'], true ) ?>
				   id="<?php echo esc_attr( $this->get_field_id( 'transparent' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'transparent' ) ); ?>"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'transparent' ) ); ?>"><?php esc_html_e( 'Use semi-transparent images', 'adace' ); ?> </label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'highlighted' ) ); ?>" name="<?php echo  esc_attr( $this->get_field_name( 'highlighted' ) ); ?>" type="number" class="small-text" size="3" min="0" max="50" value="<?php echo esc_html( $instance['highlighted'] ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'highlighted' ) ); ?>"><?php esc_html_e( 'Number of highlighted links.', 'adace' ); ?></label>
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
		$instance                = array();
		$instance['title']       = filter_var( $new_instance['title'], FILTER_SANITIZE_STRING );
		$instance['disclosure']  = isset( $new_instance['disclosure'] );
		$instance['category']    = filter_var( $new_instance['category'], FILTER_SANITIZE_STRING );
		$instance['simple']      = isset( $new_instance['simple'] );
		$instance['transparent'] = isset( $new_instance['transparent'] );
		$instance['highlighted'] = filter_var( $new_instance['highlighted'], FILTER_SANITIZE_NUMBER_INT );
		return $instance;
	}

	/**
	 * Widget output.
	 *
	 * @param array $args Widget args from registration point.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		// Get settings.
		$title       = apply_filters( 'widget_title', $instance['title'] );
		$disclosure  = $instance['disclosure'];
		$disclosure_text = get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );
		$category    = $instance['category'];
		$simple      = $instance['simple'];
		$transparent = $instance['transparent'];
		$highlighted = $instance['highlighted'];
		// Get list.
		$links_list = adace_get_links_list( $category, $simple, $highlighted, $transparent );
		if ( empty( $links_list ) ) {
			return;
		}
		// Echo all widget elements.
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		// @todo Remove .g1- classes and add them through a theme.
		if ( $disclosure && ! empty( $disclosure_text ) ) {
			echo wp_kses_post( '<p class="adace-disclosure g1-meta g1-meta-s">' . html_entity_decode( $disclosure_text ) . '</p>' );
		}
		echo( wp_kses_post( $links_list ) );
		echo wp_kses_post( $args['after_widget'] );
	}
}
