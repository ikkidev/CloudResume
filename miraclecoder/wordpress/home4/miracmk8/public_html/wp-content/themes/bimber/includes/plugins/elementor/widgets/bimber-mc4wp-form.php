<?php
class Elementor_Bimber_MC4WP_Form_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bimber_mc4wp_form';
	}

	public function get_title() {
		return __( 'Bimber Newsletter Form', 'bimber' );
	}

	public function get_icon() {
		return 'fa fa-envelope';
	}

	public function get_categories() {
		return [ 'bimber' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'design_section',
			[
				'label' => __( 'Design', 'bimber' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'template',
			[
				'label'     => __( 'Template', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => bimber_mc4wp_customizer_get_template_choices(),
				'default'   => 'box-vertical',
			]
		);

		$this->add_control(
			'title',
			[
				'label'         => __( 'Title', 'bimber' ),
				'label_block'   => true,
				'description' 	=> __( 'Leave empty to use the default value.', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default' 		=> esc_html__( 'Get the best viral stories straight into your inbox!', 'bimber' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'         => __( 'Subtitle', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default' 		=> '',
			]
		);

		$this->add_control(
			'avatar_id',
			[
				'label'         => __( 'Avatar', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::MEDIA,

			]
		);

		$this->add_control(
			'background_image_id',
			[
				'label'         => __( 'Background Image', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::MEDIA,
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array(
			'template',
			'title',
			'subtitle',
			'avatar_id',
			'background_image_id',
		);

		// Normalize.
		if ( isset( $settings['avatar_id'] ) ) {
			$settings['avatar_id'] = $settings['avatar_id']['id'];
		}

		if ( isset( $settings['background_image_id'] ) ) {
			$settings['background_image_id'] = $settings['background_image_id']['id'];
		}

		// Build shortcode.
		$shortcode = '';
		$shortcode .= '[bimber_mc4wp_form';
		foreach( $atts as $attr ) {
			if ( isset( $settings[ $attr ] ) && strlen( $settings[ $attr ] ) ) {
				$shortcode .= ' ' . $attr . '="' . $settings[ $attr ] . '"';
			}
		}
		$shortcode .= '][/bimber_mc4wp_form]';

		echo do_shortcode( $shortcode );
	}
}