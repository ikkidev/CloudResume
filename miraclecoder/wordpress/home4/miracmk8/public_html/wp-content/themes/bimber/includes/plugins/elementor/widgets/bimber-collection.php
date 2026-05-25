<?php
class Elementor_Bimber_Collection_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'bimber_collection';
	}

	public function get_title() {
		return __( 'Bimber Collection', 'bimber' );
	}

	public function get_icon() {
		return 'fa fa-th';
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

		$collections = array();
		foreach ( bimber_get_collection_templates() as $slug => $atts ) {
			$collections[ $slug ] = array(
				'icon'  => $atts['path'],
				'title' => $atts['label'],
			);
		}


		$this->add_control(
			'template',
			[
				'label'         => __( 'Template', 'bimber' ),
				'label_block'   => true,
				'description' 	=> __( 'Select display style for items.', 'bimber' ),
				'type'          => 'bimber_radio',
				'options'       => $collections,
				'default'       => 'grid-standard',
			]
		);


		$this->add_control(
			'card_style',
			[
				'label'     => __( 'Card Style', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'none'      => __( 'None', 'bimber' ),
					'solid'     => __( 'Solid', 'bimber' ),
					'simple'    => __( 'Simple', 'bimber' ),
					'subtle'    => __( 'Subtle', 'bimber' ),
				),
				'default'   => 'none',
			]
		);

		$this->add_control(
			'columns',
			[
				'label'     => __( 'Columns', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					1 => '1',
					2 => '2',
					3 => '3',
					4 => '4',
				),
				'default'   => 3,
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
				'description' 	=> __( 'Leave empty to use the default value.', 'bimber' ),
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
					'giga'  => __( 'Giga Heading', 'bimber' ),
					'mega'  => __( 'Mega Heading', 'bimber' ),
					'h1'    => __( 'H1 Heading', 'bimber' ),
					'h2'    => __( 'H2 Heading', 'bimber' ),
					'h3'    => __( 'H3 Heading', 'bimber' ),
					'h4'    => __( 'H4 Heading', 'bimber' ),
					'h5'    => __( 'H5 Heading', 'bimber' ),
					'h6'    => __( 'H6 Heading', 'bimber' ),
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

		// Type.
		$this->add_control(
			'type',
			[
				'label'         => __( 'Type', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::SELECT,
				'options'       => array(
					'recent'        => __( 'Recent', 'bimber' ),
					'most_shared'   => __( 'Most Shared', 'bimber' ),
					'most_viewed'   => __( 'Most Viewed', 'bimber' ),
				),
				'default'       => 'recent',
			]
		);

		// Time Range.
		$this->add_control(
			'time_range',
			[
				'label'         => __( 'Time Range', 'bimber' ),
				'description' 	=> __( 'Narrow posts to a specific period of time.', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::SELECT,
				'options'       => array(
					'all'           => __( 'All time', 'bimber' ),
					'month'         => __( 'Last 30 days', 'bimber' ),
					'week'          => __( 'Last 7 days', 'bimber' ),
					'day'           => __( 'Last 24 hours', 'bimber' ),
				),
				'default'       => 'all',
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

		// Offset.
		$this->add_control(
			'offset',
			[
				'label'         => __( 'Offset', 'bimber' ),
				'description' 	=> __( 'Number of posts to displace or pass over.', 'bimber' ),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => '',
			]
		);

		// Category.
		$this->add_control(
			'category',
			[
				'label'         => __( 'Filter by Category', 'bimber' ),
				'label_block'   => true,
				'type'          => 'bimber_terms',
				'taxonomy'      => 'category',
				'default'       => '',
			]
		);

		// Tag.
		$this->add_control(
			'post_tag',
			[
				'label'         => __( 'Filter by Tag', 'bimber' ),
				'label_block'   => true,
				'type'          => 'bimber_terms',
				'taxonomy'      => 'post_tag',
				'default'       => '',
			]
		);

		// Post Format.
		$this->add_control(
			'post_format',
			[
				'label'         => __( 'Filter by Post Format', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => '',
			]
		);

		// Author.
		$this->add_control(
			'author',
			[
				'label'         => __( 'Filter by Author', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => '',
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
			'show_featured_media',
			[
				'label'     => __( 'Featured Media', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->add_control(
			'show_shares',
			[
				'label'     => __( 'Shares', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->add_control(
			'show_views',
			[
				'label'     => __( 'Views', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->add_control(
			'show_comments_link',
			[
				'label'     => __( 'Comments Link', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// @todo
		// Shouldn't it be optional only if Snax Voting is enabled?
		$this->add_control(
			'show_votes',
			[
				'label'     => __( 'Votes', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->add_control(
			'show_categories',
			[
				'label'     => __( 'Categories', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		$this->add_control(
			'show_subtitle',
			[
				'label'     => __( 'Subtitle', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Summary.
		$this->add_control(
			'show_summary',
			[
				'label'     => __( 'Summary', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Author.
		$this->add_control(
			'show_author',
			[
				'label'     => __( 'Author', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Avatar,
		$this->add_control(
			'show_avatar',
			[
				'label'     => __( 'Avatar', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Date.
		$this->add_control(
			'show_date',
			[
				'label'     => __( 'Date', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Voting Box.
		$this->add_control(
			'show_voting_box',
			[
				'label'     => __( 'Voting Box', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Call to Action.
		$this->add_control(
			'show_call_to_action',
			[
				'label'     => __( 'Call to Action', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					'highlighted'   => __( 'Show if highlighted', 'bimber' ),
					'none'          => __( 'Hide', 'bimber' ),
				),
				'default'   => 'standard',
			]
		);

		// Call to Action - Hide Buttons.
		$this->add_control(
			'call_to_action_hide_buttons',
			[
				'label'         => __( 'Call to Action - Hide Buttons', 'bimber' ),
				'label_block'   => true,
				'type'          => \Elementor\Controls_Manager::TEXT,
				'default'       => '',
			]
		);


		// Accion links.
		$this->add_control(
			'show_action_links',
			[
				'label'     => __( 'Action Links', 'bimber' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'standard'      => __( 'Show', 'bimber' ),
					//'highlighted'   => __( 'Show if highlighted', 'bimber' ), // @todo It's not available?
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
			'title',
			'title_size',
			'title_align',
			'title_show',
			'template',
			'card_style',
			'columns',
			'type',
			'time_range',
			'max',
			'offset',
			'category',
			'post_tag',
			'post_format',
			'snax_format',
			'author',
			'show_featured_media',
			'show_subtitle',
			'show_shares',
			'show_votes',
			'show_views',
			'show_downloads',
			'show_comments_link',
			'show_categories',
			'show_summary',
			'show_author',
			'show_avatar',
			'show_date',
			'show_voting_box',
			'show_call_to_action',
			'show_action_links',
			'call_to_action_hide_buttons',
		);

		// Build shortcode.
		$shortcode = '';
		$shortcode .= '[bimber_collection';
		foreach( $atts as $attr ) {
			if ( isset( $settings[ $attr ] ) && strlen( $settings[ $attr ] ) ) {
				$shortcode .= ' ' . $attr . '="' . $settings[ $attr ] . '"';
			}
		}
		$shortcode .= '][/bimber_collection]';

		echo do_shortcode( $shortcode );
	}
}