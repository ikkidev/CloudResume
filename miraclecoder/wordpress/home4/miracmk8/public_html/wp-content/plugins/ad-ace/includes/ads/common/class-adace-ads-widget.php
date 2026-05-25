<?php
/**
 * Ads widget
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

add_action( 'widgets_init', 'adace_register_ads_widget' );
/**
 * Ads widget register function
 */
function adace_register_ads_widget() {
	register_widget( 'Adace_Ads_Widget' );
}

/**
 * Ads widget class
 */
class Adace_Ads_Widget extends WP_widget {

	public function get_default_args() {
		return array(
			'ad_id'         => -1,
			'ad_group'         => '',
			'title'         => esc_html__( 'Ad', 'adace' ),
			'title_display' => 'hide',
		);
	}

	/**
	 * Widget contruct.
	 */
	function __construct() {
		parent::__construct(
			'adace_ads_widget',
			esc_html__( 'AdAce Ads', 'adace' ),
			array(
				'description' => esc_html__( 'Displays Google AdSense or any other ad.', 'adace' ),
			)
		);
	}

	/**
	 * Widget form.
	 *
	 * @param array $instance Current widget settings.
	 */
	public function form( $instance ) {
		$defaults = $this->get_default_args();
		$instance = wp_parse_args( $instance, $defaults );

		$ads_choices = array(
			'' => esc_html__( '- None -', 'adace' ),
			'-1' => esc_html__( '- Random ad -', 'adace' ),
		);
		$ads = adace_get_all_ads();
		if ( ! empty( $ads ) ) {
			foreach ( $ads as $ad ) {
				$ads_choices[ $ad->ID ] = $ad->post_title;
			}
		}

		$groups_choices = array(
			'' => esc_html__( '- All -', 'adace' ),
		);
		$groups = get_terms( 'adace-ad-group', array(
			'hide_empty' => true,
		) );
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $group ) {
				$groups_choices[ $group->slug ] = $group->name;
			}
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'adace' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_display' ) ); ?>"><?php esc_html_e( 'Display title:', 'adace' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this -> get_field_id( 'title_display' ) ); ?>" name="<?php echo  esc_attr( $this -> get_field_name( 'title_display' ) ); ?>" >
				<option value="show" <?php selected( 'show', $instance['title_display'], true )?> ><?php esc_html_e( 'Show', 'adace' );?></option>'
				<option value="hide" <?php selected( 'hide', $instance['title_display'], true )?> ><?php esc_html_e( 'Hide', 'adace' );?></option>'
				<option value="transparent" <?php selected( 'transparent', $instance['title_display'], true  )?> ><?php esc_html_e( 'Transparent', 'adace' );?></option>'
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'ad_id' ) ); ?>"><?php esc_html_e( 'Ads:', 'adace' ); ?></label>
			<select class="widefat adace-widget-select-ad" id="<?php echo esc_attr( $this -> get_field_id( 'ad_id' ) ); ?>" name="<?php echo  esc_attr( $this -> get_field_name( 'ad_id' ) ); ?>" >
				<?php
				foreach ( $ads_choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $value === (int) $instance['ad_id'], true, false ) . '>' . esc_html( $label ) . '</option>';
				}
				?>
			</select>
		</p>
		<p class="adace-widget-select-group">
			<label for="<?php echo esc_attr( $this->get_field_id( 'ad_group' ) ); ?>"><?php esc_html_e( 'Group:', 'adace' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this -> get_field_id( 'ad_group' ) ); ?>" name="<?php echo  esc_attr( $this -> get_field_name( 'ad_group' ) ); ?>" >
				<?php
				foreach ( $groups_choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $value === $instance['ad_group'], true, false ) . '>' . esc_html( $label ) . '</option>';
				}
				?>
			</select>
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
		$instance = array();
		$instance['ad_id'] = filter_var( $new_instance['ad_id'], FILTER_SANITIZE_NUMBER_INT );
		$instance['ad_group'] = filter_var( $new_instance['ad_group'], FILTER_SANITIZE_STRING );
		$instance['title'] = filter_var( $new_instance['title'], FILTER_SANITIZE_STRING );
		$instance['title_display'] = filter_var( $new_instance['title_display'], FILTER_SANITIZE_STRING );
		return $instance;
	}

	/**
	 * Widget output.
	 *
	 * @param array $args Widget args from registration point.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {

		if ( ! apply_filters( 'adace_display_widget',  true, $instance ) ) {
			return;
		}

		$defaults = $this->get_default_args();
		$instance = wp_parse_args( $instance, $defaults );

		// Get settings.
		$ad_id = $instance['ad_id'];
		if ( '-1' === $ad_id ) {
			if ( ! empty( $instance['ad_group'] ) ) {
				$ads = adace_get_all_ads( $instance['ad_group'] );
			} else {
				$ads = adace_get_all_ads( );
			}
			$ads = array_filter( $ads, function( $ad ) {
				$general_settings = get_post_meta( $ad->ID, 'adace_general', true );
				return ! $general_settings['adace_exclude_from_random'];
			});
			$ad = $ads[ array_rand( $ads ) ];
			$ad_id = $ad->ID;
		}
		$slot_id = 'adace-widget-' . $ad_id;
		if ( adace_disable_ads_per_post( $slot_id ) ) {
			return;
		}
		$html = adace_capture_ad_standard_template( $ad_id, $slot_id );
		$title = apply_filters( 'widget_title', $instance['title'] );

		// Echo all widget elements.
		ob_start();
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) && 'hide' !== $instance['title_display'] ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		echo( $html );
		echo wp_kses_post( $args['after_widget'] );
		$html = ob_get_clean();

		if ( 'transparent' === $instance['title_display'] ) {
			$html = str_replace( 'widgettitle', 'widgettile adace-transparent', $html );
		}
		echo apply_filters( 'adace_widget_output', $html, $instance );
	}
}
