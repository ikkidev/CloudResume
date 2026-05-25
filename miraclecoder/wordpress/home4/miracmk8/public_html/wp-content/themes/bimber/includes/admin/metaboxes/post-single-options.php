<?php
/**
 * Single Post Options Metabox
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
 * Class Bimber_Post_Single_Options_Meta_Box
 */
class Bimber_Post_Single_Options_Meta_Box {

	/**
	 * Init.
	 */
	public function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Define metabox actions/filters.
	 */
	protected function setup_hooks() {
		add_action( 'add_meta_boxes',   array( $this, 'add_meta_boxes' ), 1 );
		add_action( 'save_post',        array( $this, 'save' ) );
	}

	/**
	 * Register meta box
	 *
	 * @param string $post_type             Processed post type.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( ! in_array( $post_type, bimber_get_single_post_supported_types(), true ) ) {
			return;
		}

		add_meta_box(
			'bimber_post_single_options_meta_box',
			__( 'Single Page', 'bimber' ),
			array( $this, 'render_meta_box' ),
			$post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Return meta box defuault options
	 *
	 * @return array
	 */
	public function get_defaults() {
		$defaults = array(
			'featured_entries'  => '',
			'featured_media'    => '',
			'template' 		    => '',
			'sidebar_override'  => '',
			'sidebar_location'  => '',
		);

		return $defaults;
	}

	/**
	 * Render meta box
	 *
	 * @param WP_Post $post         Current post.
	 */
	public function render_meta_box( $post ) {
		$value = get_post_meta( $post->ID, '_bimber_single_options', true );
		$value = wp_parse_args( $value, $this->get_defaults() );

		?>

		<?php wp_nonce_field( 'bimber_single_post_options', 'bimber_single_post_options_nonce' ); ?>
		<p class="description">
			<?php
			$url = admin_url( '/customize.php?autofocus[section]=bimber_posts_single_section' );;
			echo wp_kses_post( sprintf( __( 'If option is set to "inherit" it uses <a href="%s">Single Post</a> global setup.', 'bimber' ), esc_url( $url ) ) );
			?>
		</p>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="_bimber_single_options[featured_entries]">
						<?php esc_html_e( 'Global Featured Entries', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<select name="_bimber_single_options[featured_entries]">
						<option
							value=""<?php selected( $value['featured_entries'], '' ); ?>><?php esc_html_e( 'inherit', 'bimber' ); ?></option>
						<option
							value="standard"<?php selected( $value['featured_entries'], 'standard' ); ?>><?php esc_html_e( 'Show', 'bimber' ); ?></option>
						<option
							value="none"<?php selected( $value['featured_entries'], 'none' ); ?>><?php esc_html_e( 'Hide', 'bimber' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="_bimber_single_options[featured_media]">
						<?php esc_html_e( 'Featured Image', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<select name="_bimber_single_options[featured_media]">
						<option
							value=""<?php selected( $value['featured_media'], '' ); ?>><?php esc_html_e( 'inherit', 'bimber' ); ?></option>
						<option
							value="standard"<?php selected( $value['featured_media'], 'standard' ); ?>><?php esc_html_e( 'Show', 'bimber' ); ?></option>
						<option
							value="none"<?php selected( $value['featured_media'], 'none' ); ?>><?php esc_html_e( 'Hide', 'bimber' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="_bimber_single_options[template]">
						<?php esc_html_e( 'Template', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<?php
					// Empty 'inherit' option.
					$templates = array(
						'' => array(
							'label' => __( 'inherit', 'bimber' ),
							'path'  => BIMBER_ADMIN_DIR_URI . 'images/templates/post/inherit.png',
						),
					);

					$templates = array_merge( $templates, bimber_get_post_templates() );

					bimber_ui_render_image_radio( array(
						'html_name'     => '_bimber_single_options[template]',
						'options'       => $templates,
						'width'         => 136,
						'height'        => 136,
						'value'         => $value['template'],
						'img_base_url'  => BIMBER_ADMIN_DIR_URI . 'images/templates/post/',
					) );
					?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="_bimber_single_options[sidebar_override]">
						<?php esc_html_e( 'Sidebar', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<select name="_bimber_single_options[sidebar_override]">
						<option
							value=""<?php selected( $value['sidebar_override'], '' ); ?>><?php esc_html_e( 'Default', 'bimber' ); ?></option>
							<?php
							$sidebars = $GLOBALS['wp_registered_sidebars'];
							foreach ( $sidebars as $sidebar ) :?>
								<option
									value="<?php echo esc_attr( $sidebar['id'] );?>"<?php selected( $value['sidebar_override'], $sidebar['id'] ); ?>><?php echo esc_html( $sidebar['name'] ); ?></option>
							<?php endforeach;
							?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="_bimber_single_options[sidebar_location]">
						<?php esc_html_e( 'Sidebar Location', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<select name="_bimber_single_options[sidebar_location]">
						<option
							value=""<?php selected( $value['sidebar_location'], '' ); ?>><?php esc_html_e( 'inherit', 'bimber' ); ?></option>
						<option
							value="left"<?php selected( $value['sidebar_location'], 'left' ); ?>><?php echo esc_html_x( 'Left', 'sidebar location', 'bimber' ); ?></option>
						<option
							value="standard"<?php selected( $value['sidebar_location'], 'standard' ); ?>><?php echo esc_html_x( 'Right', 'sidebar location', 'bimber' ); ?></option>
					</select>
				</td>
			</tr>
		</table>

		<?php
	}

	/**
	 * Save meta box options
	 *
	 * @param int $post_id      Current post id.
	 *
	 * @return mixed
	 */
	public function save( $post_id ) {
		// Don't save data automatically via autosave feature.
		if ( $this->is_doing_autosave() ) {
			return $post_id;
		}

		// Don't save data when doing preview.
		if ( $this->is_doing_preview() ) {
			return $post_id;
		}

		// Don't save data when using Quick Edit.
		if ( $this->is_inline_edit() ) {
			return $post_id;
		}

		$post_type = bimber_htmlspecialchars( filter_input( INPUT_POST, 'post_type' ) );

		// Update options only if they are appliable.
		if ( ! in_array( $post_type, bimber_get_single_post_supported_types(), true ) ) {
			return $post_id;
		}

		// Check permissions.
		$post_type_obj = get_post_type_object( $post_type );
		if ( ! current_user_can( $post_type_obj->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		// Verify nonce.
		if ( ! check_admin_referer( 'bimber_single_post_options', 'bimber_single_post_options_nonce' ) ) {
			wp_die( esc_html__( 'Nonce incorrect!', 'bimber' ) );
		}

		if ( isset( $_POST['_bimber_single_options'] ) ) { // Input var okey.

			/*
			 * WP ignores the built in php magic quotes setting
			 * WP ignores the value of get_magic_quotes_gpc()
			 * It will always add magic quotes
			 * That's why we need to strip slashes
			 */
			$post_value = filter_input_array( INPUT_POST, array(
				'_bimber_single_options' => array(
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
			) );

			$options = $post_value['_bimber_single_options'];

			// Save only defined fileds.
			$valid_fields = $this->get_defaults();

			foreach ( $options as $field_name => $field_value ) {
				if ( ! isset( $valid_fields[ $field_name ] ) ) {
					unset( $options[ $field_name ] );
				}
			}

			update_post_meta( $post_id, '_bimber_single_options', $options );
		}
	}

	/**
	 * Check whether request is processed during autosave
	 *
	 * @return bool
	 */
	protected function is_doing_autosave() {
		return defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
	}

	/**
	 * Check whether request is an inline edit
	 *
	 * @return bool
	 */
	protected function is_inline_edit() {
		return isset( $_POST['_inline_edit'] ); // Input var okey.
	}

	/**
	 * Check whether request is processed during preview
	 *
	 * @return bool
	 */
	protected function is_doing_preview() {
		return ! empty( $_POST['wp-preview'] ); // Input var okey.
	}
}


/**
 * Quasi-singleton
 */
function bimber_post_single_options_meta_box() {
	static $instance;

	if ( ! isset( $instance ) ) {
		$instance = new Bimber_Post_Single_Options_Meta_Box();
	}

	return $instance;
}

bimber_post_single_options_meta_box();



