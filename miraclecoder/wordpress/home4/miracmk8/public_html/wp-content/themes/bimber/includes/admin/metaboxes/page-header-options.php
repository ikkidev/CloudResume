<?php
/**
 * Page Header Options Metabox
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
 * Class Bimber_Page_Header_Options_Meta_Box
 */
class Bimber_Page_Header_Options_Meta_Box {

	private static $instance;

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new Bimber_Page_Header_Options_Meta_Box();
		}

		return self::$instance;
	}

	/**
	 * Init.
	 */
	private function __construct() {
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
	 * Return supported post type for the meta box
	 *
	 * @return array
	 */
	public function get_allowed_post_types() {
		return apply_filters( 'bimber_page_header_options_meta_box_post_type_list', array( 'page' ) );
	}

	/**
	 * Register meta box
	 *
	 * @param string $post_type             Processed post type.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( ! in_array( $post_type, $this->get_allowed_post_types(), true ) ) {
			return;
		}

		add_meta_box(
			'bimber_page_header_options_meta_box',
			__( 'Single Page Header Options', 'bimber' ),
			array( $this, 'render_meta_box' ),
			$post_type,
			'normal',
			'default'
		);
	}

	/**
	 * Return meta box defuault options
	 *
	 * @return array
	 */
	public function get_defaults() {
		$defaults = array(
			'composition'	=> '01',
			'bg_color'  	=> '',
			'bg2_color' 	=> '',
			'bg_image'		=> '',
			'bg_size' 		=> '',
			'bg_repeat' 	=> '',
		);

		return $defaults;
	}

	/**
	 * Render meta box
	 *
	 * @param WP_Post $post         Current post.
	 */
	public function render_meta_box( $post ) {
		$value = get_post_meta( $post->ID, '_bimber_page_header_options', true );
		$value = wp_parse_args( $value, $this->get_defaults() );

		?>

		<?php wp_nonce_field( 'bimber_page_header_options', 'bimber_page_header_options_nonce' ); ?>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="_bimber_page_header_options[composition]">
						<?php esc_html_e( 'Composition', 'bimber' ); ?>
					</label>
				</th>
				<td>
					<?php $compositions = bimber_get_archive_header_compositions(); ?>
					<select name="_bimber_page_header_options[composition]">
						<?php foreach ( $compositions as $composition_id => $composition_name ) : ?>

							<option value="<?php echo esc_attr( $composition_id ); ?>"<?php selected( $composition_id, $value['composition'] ); ?>><?php echo esc_html( $composition_name ); ?></option>

						<?php endforeach; ?>

					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="bimber_page_header_options_bg_color"><?php esc_html_e( 'Background Color', 'bimber' ); ?></label>
				</th>
				<td>
					<input id="bimber_page_header_options_bg_color" class="bimber-color-picker" name="_bimber_page_header_options[bg_color]" type="text" value="<?php echo esc_attr( $value['bg_color'] ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="bimber_page_header_options_bg2_color"><?php esc_html_e( 'Optional Background Gradient', 'bimber' ); ?></label>
				</th>
				<td>
					<input id="bimber_page_header_options_bg2_color" class="bimber-color-picker" name="_bimber_page_header_options[bg2_color]" type="text" value="<?php echo esc_attr( $value['bg2_color'] ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="bimber_page_header_options_bg_image"><?php esc_html_e( 'Background Image', 'bimber' ); ?></label>
				</th>
				<td>
					<div class="bimber-image-upload">
						<a class="button button-secondary bimber-add-image" href="#"><?php esc_html_e( 'Add Image', 'bimber' ); ?></a>

						<div class="bimber-image">
							<?php if ( ! empty( $value['bg_image'] ) ) :  ?>
								<?php echo wp_get_attachment_image( $value['bg_image'] ); ?>
							<?php endif; ?>
						</div>
						<a class="button button-secondary bimber-delete-image" href="#"><?php esc_html_e( 'Remove Image', 'bimber' ); ?></a>
						<input class="bimber-image-id" id="bimber_page_header_options_bg_image" name="_bimber_page_header_options[bg_image]" type="hidden" value="<?php echo esc_attr( $value['bg_image'] ); ?>" />
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="bimber_page_header_options_bg_size"><?php esc_html_e( 'Background Size', 'bimber' ); ?></label>
				</th>
				<td>
					<select id="bimber_page_header_options_bg_size" name="_bimber_page_header_options[bg_size]">
						<option value="auto"<?php selected( 'auto', $value['bg_size'] ); ?>><?php echo esc_html_x( 'auto', 'background size option', 'bimber' ); ?></option>
						<option value="cover"<?php selected( 'cover', $value['bg_size'] ); ?>><?php echo esc_html_x( 'cover', 'background size option', 'bimber' ); ?></option>
						<option value="contain"<?php selected( 'contain', $value['bg_size'] ); ?>><?php echo esc_html_x( 'contain', 'background size option', 'bimber' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="bimber_page_header_options_bg_repeat"><?php esc_html_e( 'Background Repeat', 'bimber' ); ?></label>
				</th>
				<td>
					<select id="bimber_page_header_options_bg_repeat" name="_bimber_page_header_options[bg_repeat]">
						<option value="no-repeat"<?php selected( 'no-repeat', $value['bg_repeat'] ); ?>><?php echo esc_html_x( 'no repeat', 'background repeat option', 'bimber' ); ?></option>
						<option value="repeat"<?php selected( 'repeat', $value['bg_repeat'] ); ?>><?php echo esc_html_x( 'repeat', 'background repeat option', 'bimber' ); ?></option>
						<option value="repeat-x"<?php selected( 'repeat-x', $value['bg_repeat'] ); ?>><?php echo esc_html_x( 'repeat x', 'background repeat option', 'bimber' ); ?></option>
						<option value="repeat-y"<?php selected( 'repeat-y', $value['bg_repeat'] ); ?>><?php echo esc_html_x( 'repeat y', 'background repeat option', 'bimber' ); ?></option>
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
		if ( ! in_array( $post_type, $this->get_allowed_post_types(), true ) ) {
			return $post_id;
		}

		// Check permissions.
		$post_type_obj = get_post_type_object( $post_type );
		if ( ! current_user_can( $post_type_obj->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		// Verify nonce.
		if ( ! check_admin_referer( 'bimber_page_header_options', 'bimber_page_header_options_nonce' ) ) {
			wp_die( esc_html__( 'Nonce incorrect!', 'bimber' ) );
		}

		if ( isset( $_POST['_bimber_page_header_options'] ) ) { // Input var okey.

			/*
			 * WP ignores the built in php magic quotes setting
			 * WP ignores the value of get_magic_quotes_gpc()
			 * It will always add magic quotes
			 * That's why we need to strip slashes
			 */
			$post_value = filter_input_array( INPUT_POST, array(
				'_bimber_page_header_options' => array(
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
			) );

			$options = $post_value['_bimber_page_header_options'];

			// Save only defined fields.
			$valid_fields = $this->get_defaults();

			foreach ( $options as $field_name => $field_value ) {
				if ( ! isset( $valid_fields[ $field_name ] ) ) {
					unset( $options[ $field_name ] );
				}
			}

			update_post_meta( $post_id, '_bimber_page_header_options', $options );
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
 * Return metabox instance.
 */
function bimber_page_header_options_meta_box() {
	return Bimber_Page_Header_Options_Meta_Box::get_instance();
}

// Load.
bimber_page_header_options_meta_box();
