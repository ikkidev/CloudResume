<?php
class Elementor_Bimber_Categories_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bimber_categories';
	}

	public function get_title() {
		return __( 'Bimber Categories', 'bimber' );
	}

	public function get_icon() {
		return 'fa fa-folder';
	}

	public function get_categories() {
		return [ 'bimber' ];
	}

	protected function _register_controls() {
		$this->_register_design_controls();
		$this->_register_title_controls();
		$this->_register_data_controls();
		$this->_register_item_design_controls();
	}

	protected function _register_design_controls() {
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
				'options'   => array(
					'tiles'         => 'tiles',
					'icongrid'      => 'icongrid',
				),
				'default'   => 'tiles',
			]
		);

		$this->end_controls_section();
	}

	protected function _register_title_controls() {
		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title', 'bimber' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_show',
			[
				'label'         => __( 'Show', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::SELECT,
				'options'       => array(
					'none'            => __( 'No', 'bimber' ),
					'standard'        => __( 'Yes', 'bimber' ),
				),
				'default'       => 'standard',
			]
		);

		$this->add_control(
			'title',
			[
				'label'         => __( 'Title', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => '',
			]
		);

		$this->add_control(
			'title_size',
			[
				'label'         => __( 'Size', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::SELECT,
				'options'       => array(
					'h1' => __( 'H1 Heading', 'bimber' ),
					'h2' => __( 'H2 Heading', 'bimber' ),
					'h3' => __( 'H3 Heading', 'bimber' ),
					'h4' => __( 'H4 Heading', 'bimber' ),
					'h5' => __( 'H5 Heading', 'bimber' ),
					'h6' => __( 'H6 Heading', 'bimber' ),
					'giga' => __( 'Giga Heading', 'bimber' ),
					'mega' => __( 'Mega Heading', 'bimber' ),
				),
				'default'       => 'h4',
			]
		);

		$this->add_control(
			'title_align',
			[
				'label'         => __( 'Align', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::SELECT,
				'options'       => array(
					''              => __( 'Default', 'bimber' ),
					'center'        => __( 'Center', 'bimber' ),
				),
				'default'       => '',
			]
		);

		$this->end_controls_section();
	}


	protected function _register_data_controls() {
		$this->start_controls_section(
			'data_section',
			[
				'label' => __( 'Data', 'bimber' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Include.
		$this->add_control(
			'include',
			[
				'label'         => __( 'Include', 'bimber' ),
				'description'   => __( 'A comma-separated list of category ids or slugs.', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => '',
			]
		);

		// Order by.
		$this->add_control(
			'orderby',
			[
				'label'         => __( 'Order by', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::SELECT,
				'options'       => array(
					'name'        => __( 'Name', 'bimber' ),
					'count'       => __( 'Number of Entries', 'bimber' ),
					'include'     => __( 'Matche the order of the Include', 'bimber' ),
				),
				'default'       => 'name',
			]
		);


		// Total Items.
		$this->add_control(
			'max',
			[
				'label'         => __( 'Total Items', 'bimber' ),
				'description' 	=> __( 'Set max limit for items or enter -1 to display all.', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => 6,
			]
		);

		$this->end_controls_section();
	}

	protected function _register_item_design_controls() {
		$this->start_controls_section(
			'item_design_section',
			[
				'label' => __( 'Item Design', 'bimber' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label'     => __( 'Icon', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'     => __( 'Number of Entries', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array(
			'template',
			'max',
			'include',
			'orderby',
			'title',
			'title_show',
			'title_size',
			'title_align',
			'show_icon',
			'show_count',
		);

		// Build shortcode.
		$shortcode = '';
		$shortcode .= '[bimber_categories';
		foreach( $atts as $attr ) {
			if ( isset( $settings[ $attr ] ) && strlen( $settings[ $attr ] ) ) {
				$shortcode .= ' ' . $attr . '="' . $settings[ $attr ] . '"';
			}
		}
		$shortcode .= '][/bimber_categories]';

		echo do_shortcode( $shortcode );
	}
}