<?php
/**
 * Settings Functions
 *
 * @package photomix
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

class Photomix_Settings_Page {
	private $options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_options_page(
			'Photomix Settings',
			'Photomix',
			'manage_options',
			'photomix-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		$this->options = photomix_get_options();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Settings', 'photomix' ); ?></h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields.
				settings_fields( 'photomix_options_group' );
				do_settings_sections( 'photomix-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'photomix_options_group', 					// Option group.
			'photomix_options', 						// Option name.
			array( $this, 'sanitize' ) 				// Sanitize.
		);

		add_settings_section(
			'photomix_general_settings_section',		// ID.
			__( 'General Settings', 'photomix' ),		// Title.
			array( $this, 'general_section_info' ),	// Callback.
			'photomix-settings' 						// Page.
		);

		// Format.
		add_settings_field(
			'photomix_format', 							// ID.
			__( 'Format', 'photomix' ), 				// Title.
			array( $this, 'format_callback' ), 		// Callback
			'photomix-settings', 						// Page.
			'photomix_general_settings_section' 		// Section.
		);

		// Max width/height.
		add_settings_field(
			// @todo Refactor to 'photomix_size'
			'photomix_max_dims',						// ID.
			__( 'Size', 'photomix' ),		            // Title.
			array( $this, 'max_dims_callback' ), 	    // Callback.
			'photomix-settings', 						// Page.
			'photomix_general_settings_section' 		// Section.
		);

		// Background color.
		add_settings_field(
			'photomix_background_color',				// ID.
			__( 'Background color', 'photomix' ),		// Title.
			array( $this, 'background_color_callback' ), 	// Callback.
			'photomix-settings', 						// Page.
			'photomix_general_settings_section' 		// Section.
		);

		// Shape color.
		add_settings_field(
			'photomix_shape_color',						// ID.
			__( 'Shape color', 'photomix' ),			// Title.
			array( $this, 'shape_color_callback' ), // Callback.
			'photomix-settings', 						// Page.
			'photomix_general_settings_section' 		// Section.
		);

		// Gutter .
		add_settings_field(
			'photomix_gutter',							// ID.
			__( 'Gutter', 'photomix' ),					// Title.
			array( $this, 'gutter_callback' ),		// Callback.
			'photomix-settings', 						// Page.
			'photomix_general_settings_section' 		// Section.
		);

		// Gutter color.
		add_settings_field(
			'photomix_gutter_color',					// ID.
			__( 'Gutter color', 'photomix' ),			// Title.
			array( $this, 'gutter_color_callback' ),// Callback.
			'photomix-settings', 						// Page.
			'photomix_general_settings_section' 		// Section.
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_input = array();

		// Format.
		if( isset( $input['format'] ) ) {
			$new_input['format'] = sanitize_text_field( $input['format'] );
		}

		// Max width.
		if( isset( $input['max_width'] ) ) {
			$new_input['max_width'] = absint( $input['max_width'] );
		}

		// Max height.
		if( isset( $input['max_height'] ) ) {
			$new_input['max_height'] = absint( $input['max_height'] );
		}

		// Background color.
		if( isset( $input['background_color'] ) ) {
			$new_input['background_color'] = sanitize_hex_color( $input['background_color'] );
		}

		// Shape color.
		if( isset( $input['shape_color'] ) ) {
			$new_input['shape_color'] = sanitize_hex_color( $input['shape_color'] );
		}

		// Gutter.
		if( isset( $input['gutter'] ) ) {
			$new_input['gutter'] = sanitize_text_field( $input['gutter'] );
		}

		// Gutter color.
		if( isset( $input['gutter_color'] ) ) {
			$new_input['gutter_color'] = sanitize_hex_color( $input['gutter_color'] );
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function general_section_info() {}

	/**
	 * Render 'Format' control
	 */
	public function format_callback() {
		?>
			<select id="photomix_format" name="photomix_options[format]">
				<option value="16_9"<?php selected( '16_9', $this->options['format'] ) ?>><?php esc_html_e( '16:9', 'photomix' ); ?></option>
				<option value="4_3"<?php selected( '4_3', $this->options['format'] ) ?>><?php esc_html_e( '4:3', 'photomix' ); ?></option>
			</select>
		<?php
	}

	/**
	 * Render 'Max width/height' control
	 */
	public function max_dims_callback() {
		?>
		<?php esc_html_e( 'Max Width', 'photomix' ); ?> <input class="small-text" type="number" size="5" id="photomix_max_width" name="photomix_options[max_width]" value="<?php echo esc_attr( $this->options['max_width'] ) ?>" />
		<?php esc_html_e( 'Max Height', 'photomix' ); ?> <input class="small-text" type="number" size="5" id="photomix_max_height" name="photomix_options[max_height]" value="<?php echo esc_attr( $this->options['max_height'] ) ?>" />
		<?php
	}

	/**
	 * Render 'Background color' control
	 */
	public function background_color_callback() {
		?>
		<input type="text" class="photomix-color-picker" id="photomix_background_color" name="photomix_options[background_color]" value="<?php echo esc_attr( $this->options['background_color'] ) ?>" />
		<?php
	}

	/**
	 * Render 'Shape color' control
	 */
	public function shape_color_callback() {
		?>
		<input type="text" class="photomix-color-picker" id="photomix_shape_color" name="photomix_options[shape_color]" value="<?php echo esc_attr( $this->options['shape_color'] ) ?>" />
		<?php
	}

	/**
	 * Render 'Gutter' control
	 */
	public function gutter_callback() {
		?>
		<select id="photomix_gutter" name="photomix_options[gutter]">
			<option value="standard"<?php selected( 'standard', $this->options['gutter'] ) ?>><?php esc_html_e( 'on', 'photomix' ); ?></option>
			<option value="none"<?php selected( 'none', $this->options['gutter'] ) ?>><?php esc_html_e( 'off', 'photomix' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Render 'Gutter color' control
	 */
	public function gutter_color_callback() {
		?>
		<input type="text" class="photomix-color-picker" id="photomix_gutter_color" name="photomix_options[gutter_color]" value="<?php echo esc_attr( $this->options['gutter_color'] ) ?>" />
		<?php
	}
}

// Init.
new Photomix_Settings_Page();
