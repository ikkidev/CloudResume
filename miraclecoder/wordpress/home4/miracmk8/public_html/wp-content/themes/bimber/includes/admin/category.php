<?php
/**
 * Post category edit screen
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
 * Add custom fields on a post category edit screen.
 *
 * @param object $tag      Current taxonomy term object.
 * @param string $taxonomy Current taxonomy slug.
 */
function bimber_category_add_custom_fields( $tag, $taxonomy ) {
	// Header.
	$global_header_composition 		= bimber_get_theme_option( 'archive', 'header_composition', true );

	$current_term_icon                      = get_term_meta( $tag->term_id, 'bimber_term_icon', true );
	$current_term_image                     = get_term_meta( $tag->term_id, 'bimber_taxonomy_image', true );
	$current_header_composition 			= get_term_meta( $tag->term_id, 'bimber_header_composition', true );
	$current_header_bg_color 				= get_term_meta( $tag->term_id, 'bimber_header_background_color', true );
	$current_header_bg2_color 				= get_term_meta( $tag->term_id, 'bimber_header_background2_color', true );
	$current_header_text_color 				= get_term_meta( $tag->term_id, 'bimber_header_text_color', true );
	$current_header_bg_image 				= get_term_meta( $tag->term_id, 'bimber_header_background_image', true );
	$current_header_bg_size 				= get_term_meta( $tag->term_id, 'bimber_header_background_size', true );
	$current_header_bg_repeat 				= get_term_meta( $tag->term_id, 'bimber_header_background_repeat', true );
	$current_header_override_hide_elements	= get_term_meta( $tag->term_id, 'bimber_header_override_hide_elements', true );
	$current_header_hide_elements			= get_term_meta( $tag->term_id, 'bimber_header_hide_elements', true );

	// Label.
	$current_label_color 			= get_term_meta( $tag->term_id, 'bimber_label_color', true );
	$current_label_bg_color 		= get_term_meta( $tag->term_id, 'bimber_label_background_color', true );

	// Featured Entries.
	$global_fe_type 				= bimber_get_theme_option( 'archive', 'featured_entries', true );
	$global_fe_template 			= bimber_get_theme_option( 'archive', 'featured_entries_template', true );
	$global_fe_gutter 				= bimber_get_theme_option( 'archive', 'featured_entries_gutter', true );
	$global_fe_time_range 			= bimber_get_theme_option( 'archive', 'featured_entries_time_range', true );
	$global_fe_title_hide 			= bimber_get_theme_option( 'archive', 'featured_entries_title_hide', true );

	$current_fe_type 				= get_term_meta( $tag->term_id, 'bimber_featured_entries', true );
	$current_fe_template			= get_term_meta( $tag->term_id, 'bimber_featured_entries_template', true );
	$current_fe_gutter				= get_term_meta( $tag->term_id, 'bimber_featured_entries_gutter', true );
	$current_fe_title				= get_term_meta( $tag->term_id, 'bimber_featured_entries_title', true );
	$current_fe_title_hide			= get_term_meta( $tag->term_id, 'bimber_featured_entries_title_hide', true );
	$current_fe_time_range			= get_term_meta( $tag->term_id, 'bimber_featured_entries_time_range', true );

	// Main collection.
	$global_template 				= bimber_get_theme_option( 'archive', 'template', true );
	$global_sidebar_location		= bimber_get_theme_option( 'archive', 'sidebar_location', true );
	$global_inject_embeds			= bimber_get_theme_option( 'archive', 'inject_embeds', true );
	$global_title_hide 				= bimber_get_theme_option( 'archive', 'title_hide', true );
	$global_posts_per_page 			= bimber_get_theme_option( 'archive', 'posts_per_page', true );
	$global_pagination 				= bimber_get_theme_option( 'archive', 'pagination', true );
	$global_newsletter 				= bimber_get_theme_option( 'archive', 'newsletter', true );
	$global_newsletter_after_post 	= bimber_get_theme_option( 'archive', 'newsletter_after_post', true );
	$global_product 				= bimber_get_theme_option( 'archive', 'product', true );
	$global_product_after_post 		= bimber_get_theme_option( 'archive', 'product_after_post', true );
	$global_ad 						= bimber_get_theme_option( 'archive', 'ad', true );
	$global_ad_after_post 			= bimber_get_theme_option( 'archive', 'ad_after_post', true );

	$current_template				= get_term_meta( $tag->term_id, 'bimber_template', true );
	$current_sidebar_location		= get_term_meta( $tag->term_id, 'bimber_sidebar_location', true );
	$current_sidebar_override		= get_term_meta( $tag->term_id, 'bimber_sidebar_override', true );
	$current_inject_embeds			= get_term_meta( $tag->term_id, 'bimber_inject_embeds', true );
	$current_title					= get_term_meta( $tag->term_id, 'bimber_title', true );
	$current_title_hide				= get_term_meta( $tag->term_id, 'bimber_title_hide', true );
	$current_posts_per_page			= get_term_meta( $tag->term_id, 'bimber_posts_per_page', true );
	$current_pagination				= get_term_meta( $tag->term_id, 'bimber_pagination', true );
	$current_override_hide_elements	= get_term_meta( $tag->term_id, 'bimber_override_hide_elements', true );
	$current_hide_elements			= get_term_meta( $tag->term_id, 'bimber_hide_elements', true );
	$current_call_to_action_hide_buttons	    = get_term_meta( $tag->term_id, 'bimber_call_to_action_hide_buttons', true );
	$current_newsletter				= get_term_meta( $tag->term_id, 'bimber_newsletter', true );
	$current_newsletter_after_post 	= get_term_meta( $tag->term_id, 'bimber_newsletter_after_post', true );
	$current_product				= get_term_meta( $tag->term_id, 'bimber_product', true );
	$current_product_after_post 	= get_term_meta( $tag->term_id, 'bimber_product_after_post', true );
	$current_ad						= get_term_meta( $tag->term_id, 'bimber_ad', true );
	$current_ad_after_post			= get_term_meta( $tag->term_id, 'bimber_ad_after_post', true );
	?>
	<!-- Featured Entries -->
	</tbody>
</table>

<br />
<br />
<br />
<hr />
<h2><?php esc_html_e( 'Media', 'bimber' ); ?></h2>

<table class="form-table">
	<tbody>
		<tr class="form-field">
			<th scope="row">
				<label for="bimber_term_icon"><?php esc_html_e( 'Icon', 'bimber' ); ?></label>
			</th>
			<td>
				<div class="bimber-image-upload">
					<a class="button button-secondary bimber-add-image" href="#"><?php esc_html_e( 'Add Image', 'bimber' ); ?></a>

					<div class="bimber-image">
						<?php if ( ! empty( $current_term_icon ) ) :  ?>
							<?php echo wp_get_attachment_image( $current_term_icon ); ?>
						<?php endif; ?>
					</div>
					<a class="button button-secondary bimber-delete-image" href="#"><?php esc_html_e( 'Remove Image', 'bimber' ); ?></a>
					<input class="bimber-image-id" id="bimber_term_icon" name="bimber_term_icon" type="hidden" value="<?php echo esc_attr( $current_term_icon ); ?>" />
				</div>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="bimber_taxonomy_image"><?php esc_html_e( 'Image', 'bimber' ); ?></label>
			</th>
			<td>
				<div class="bimber-image-upload">
					<a class="button button-secondary bimber-add-image" href="#"><?php esc_html_e( 'Add Image', 'bimber' ); ?></a>

					<div class="bimber-image">
						<?php if ( ! empty( $current_term_image ) ) :  ?>
							<?php echo wp_get_attachment_image( $current_term_image ); ?>
						<?php endif; ?>
					</div>
					<a class="button button-secondary bimber-delete-image" href="#"><?php esc_html_e( 'Remove Image', 'bimber' ); ?></a>
					<input class="bimber-image-id" id="bimber_taxonomy_image" name="bimber_taxonomy_image" type="hidden" value="<?php echo esc_attr( $current_term_image ); ?>" />
				</div>
			</td>
		</tr>
	</tbody>
</table>

<br />
<br />
<br />
<hr />
<h2><?php esc_html_e( 'Label', 'bimber' ); ?></h2>

<table class="form-table">
	<tbody>
	<tr class="form-field term-label-color-wrap">
		<th scope="row">
			<label for="bimber_label_color"><?php esc_html_e( 'Text Color', 'bimber' ); ?></label>
		</th>
		<td>
			<input id="bimber_label_color" class="bimber-color-picker" name="bimber_label_color" type="text" value="<?php echo esc_attr( $current_label_color ); ?>" />
		</td>
	</tr>
	<tr class="form-field term-label-color-wrap">
		<th scope="row">
			<label for="bimber_label_bg_color"><?php esc_html_e( 'Background Color', 'bimber' ); ?></label>
		</th>
		<td>
			<input id="bimber_label_bg_color" class="bimber-color-picker" name="bimber_label_bg_color" type="text" value="<?php echo esc_attr( $current_label_bg_color ); ?>" />
		</td>
	</tr>
	</tbody>
</table>


<br />
<br />
<br />
<hr />
<h2>
	<?php esc_html_e( 'Header', 'bimber' ); ?>
	<a class="g1-action-edit-defaults button button-small button-secondary" href="<?php echo admin_url( 'customize.php?autofocus[section]=bimber_posts_archive_section' ); ?>" target="_blank"><?php esc_html_e( 'Edit Default Options', 'bimber' ); ?></a>
</h2>

<table class="form-table">
	<tbody>

		<tr class="form-field term-header-composition-wrap">
			<th scope="row">
				<label for="bimber_header_composition"><?php esc_html_e( 'Header Composition', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $header_compositions = bimber_get_archive_header_compositions(); ?>
				<select id="bimber_header_composition" name="bimber_header_composition">
					<option value=""<?php selected( '', $current_header_composition ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $header_compositions[ $global_header_composition ] ) ); ?></option>

					<?php foreach ( $header_compositions as $header_composition_id => $header_composition_name ) : ?>

						<option value="<?php echo esc_attr( $header_composition_id ); ?>"<?php selected( $header_composition_id, $current_header_composition ); ?>><?php echo esc_html( $header_composition_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-hide-archive-header-elements-wrap">
			<th scope="row">
				<?php esc_html_e( 'Hide Elements', 'bimber' ); ?>
			</th>
			<td>
				<select id="bimber_header_override_hide_elements" name="bimber_header_override_hide_elements">
					<option value="none"<?php selected( 'none', $current_header_override_hide_elements ); ?>><?php esc_html_e( 'Default', 'bimber' ); ?></option>
					<option value="standard"<?php selected( 'standard', $current_header_override_hide_elements ); ?>><?php esc_html_e( 'Change', 'bimber' ); ?></option>
				</select>

				<div id="bimber-hide-elements-archive-header-wrapper" style="display: <?php echo ( 'standard' === $current_header_override_hide_elements ) ? 'block' : 'none'; ?>;">
					<?php $hide_elements = bimber_get_archive_header_elements_to_hide(); ?>
					<?php $current_hide_elements_arr = ! empty( $current_header_hide_elements ) ? explode( ',', $current_header_hide_elements ) : array(); ?>

					<?php foreach ( $hide_elements as $element_id => $element_name ) : ?>
						<p>
							<input id="bimber_hide_element_<?php echo esc_attr( $element_id ); ?>" name="bimber_header_hide_elements[]" type="checkbox" value="<?php echo esc_attr( $element_id ); ?>"<?php checked( in_array( $element_id, $current_hide_elements_arr, true ) ); ?> />
							<label for="bimber_hide_element_<?php echo esc_attr( $element_id ); ?>"><?php echo esc_html( $element_name ); ?></label>
						</p>
					<?php endforeach; ?>
				</div>
			</td>
		</tr>
		<tr class="form-field term-header-bg-color-wrap">
			<th scope="row">
				<label for="bimber_header_bg_color"><?php esc_html_e( 'Background Color', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_header_bg_color" class="bimber-color-picker" name="bimber_header_bg_color" type="text" value="<?php echo esc_attr( $current_header_bg_color ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-header-bg2-color-wrap">
			<th scope="row">
				<label for="bimber_header_bg2_color"><?php esc_html_e( 'Optional Background Gradient', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_header_bg2_color" class="bimber-color-picker" name="bimber_header_bg2_color" type="text" value="<?php echo esc_attr( $current_header_bg2_color ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-header-text-color-wrap">
			<th scope="row">
				<label for="bimber_header_text_color"><?php esc_html_e( 'Optional Text Color', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_header_text_color" class="bimber-color-picker" name="bimber_header_text_color" type="text" value="<?php echo esc_attr( $current_header_text_color ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-header-bg-image-wrap">
			<th scope="row">
				<label for="bimber_header_bg_image"><?php esc_html_e( 'Background Image', 'bimber' ); ?></label>
			</th>
			<td>
				<div class="bimber-image-upload">
					<a class="button button-secondary bimber-add-image" href="#"><?php esc_html_e( 'Add Image', 'bimber' ); ?></a>

					<div class="bimber-image">
						<?php if ( ! empty( $current_header_bg_image ) ) :  ?>
							<?php echo wp_get_attachment_image( $current_header_bg_image ); ?>
						<?php endif; ?>
					</div>
					<a class="button button-secondary bimber-delete-image" href="#"><?php esc_html_e( 'Remove Image', 'bimber' ); ?></a>
					<input class="bimber-image-id" id="bimber_header_bg_image" name="bimber_header_bg_image" type="hidden" value="<?php echo esc_attr( $current_header_bg_image ); ?>" />
				</div>
			</td>
		</tr>
		<tr class="form-field term-header-bg-size-wrap">
			<th scope="row">
				<label for="bimber_header_bg_size"><?php esc_html_e( 'Background Size', 'bimber' ); ?></label>
			</th>
			<td>
				<select id="bimber_header_bg_size" name="bimber_header_bg_size">
					<option value="auto"<?php selected( 'auto', $current_header_bg_size ); ?>><?php echo esc_html_x( 'auto', 'background size option', 'bimber' ); ?></option>
					<option value="cover"<?php selected( 'cover', $current_header_bg_size ); ?>><?php echo esc_html_x( 'cover', 'background size option', 'bimber' ); ?></option>
					<option value="contain"<?php selected( 'contain', $current_header_bg_size ); ?>><?php echo esc_html_x( 'contain', 'background size option', 'bimber' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="form-field term-header-bg-repeat-wrap">
			<th scope="row">
				<label for="bimber_header_bg_repeat"><?php esc_html_e( 'Background Repeat', 'bimber' ); ?></label>
			</th>
			<td>
				<select id="bimber_header_bg_repeat" name="bimber_header_bg_repeat">
					<option value="no-repeat"<?php selected( 'no-repeat', $current_header_bg_repeat ); ?>><?php echo esc_html_x( 'no repeat', 'background repeat option', 'bimber' ); ?></option>
					<option value="repeat"<?php selected( 'repeat', $current_header_bg_repeat ); ?>><?php echo esc_html_x( 'repeat', 'background repeat option', 'bimber' ); ?></option>
					<option value="repeat-x"<?php selected( 'repeat-x', $current_header_bg_repeat ); ?>><?php echo esc_html_x( 'repeat x', 'background repeat option', 'bimber' ); ?></option>
					<option value="repeat-y"<?php selected( 'repeat-y', $current_header_bg_repeat ); ?>><?php echo esc_html_x( 'repeat y', 'background repeat option', 'bimber' ); ?></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<br />
<br />
<br />
<hr />
<h2>
	<?php esc_html_e( 'Featured Entries', 'bimber' ); ?>
	<a class="g1-action-edit-defaults button button-small button-secondary" href="<?php echo admin_url( 'customize.php?autofocus[section]=bimber_posts_archive_section' ); ?>" target="_blank"><?php esc_html_e( 'Edit Default Options', 'bimber' ); ?></a>
</h2>

<table class="form-table">
	<tbody>

		<tr class="form-field term-fe-type-wrap">
			<th scope="row">
				<label for="bimber_featured_entries"><?php esc_html_e( 'Type', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $types = bimber_get_archive_featured_entries_types(); ?>
				<select id="bimber_featured_entries" name="bimber_featured_entries">
					<option value=""<?php selected( '', $current_fe_type ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $types[ $global_fe_type ] ) ); ?></option>

					<?php foreach ( $types as $type_id => $type_name ) : ?>

						<option value="<?php echo esc_attr( $type_id ); ?>"<?php selected( $type_id, $current_fe_type ); ?>><?php echo esc_html( $type_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-fe-title-wrap">
			<th scope="row">
				<label for="bimber_featured_entries_title"><?php esc_html_e( 'Title', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_featured_entries_title" name="bimber_featured_entries_title" type="text" value="<?php echo esc_attr( $current_fe_title ); ?>" placeholder="<?php esc_html_e( 'Leave empty to use the default value', 'bimber' ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-fe-title-hide-wrap">
			<th scope="row">
				<label for="bimber_featured_entries_title_hide"><?php esc_html_e( 'Hide Title', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $fe_hide_title_options = bimber_get_yes_no_options(); ?>
				<select id="bimber_featured_entries_title_hide" name="bimber_featured_entries_title_hide">
					<option value=""<?php selected( '', $current_fe_title_hide ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $fe_hide_title_options[ $global_fe_title_hide ] ) ); ?></option>

					<?php foreach ( $fe_hide_title_options as $fe_hide_title_id => $fe_hide_title_name ) : ?>

						<option value="<?php echo esc_attr( $fe_hide_title_id ); ?>"<?php selected( $fe_hide_title_id, $current_fe_title_hide ); ?>><?php echo esc_html( $fe_hide_title_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-fe-template-wrap">
			<th scope="row">
				<label for="bimber_featured_entries_template"><?php esc_html_e( 'Template', 'bimber' ); ?></label>
			</th>
			<td>
				<?php
				// Empty 'inherit' option.
				$templates = bimber_get_archive_featured_entries_templates();
				$templates = array_merge(
					 array(
						'' => array(
							'label' => sprintf( __( 'default (%s)', 'bimber' ), $templates[ $global_fe_template ]['label'] ),
							'path'  => BIMBER_ADMIN_DIR_URI . 'images/templates/featured-entries/inherit.png',
						),
					),
					$templates
				);

				bimber_ui_render_image_radio( array(
					'html_name'     => 'bimber_featured_entries_template',
					'options'       => $templates,
					'width'         => 136,
					'height'        => 68,
					'value'         => $current_fe_template,
				) );
				?>
			</td>
		</tr>
		<tr class="form-field term-fe-gutter-wrap">
			<th scope="row">
				<label for="bimber_featured_entries_gutter"><?php esc_html_e( 'Gutter', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $gutter_types = bimber_get_yes_no_options(); ?>
				<select id="bimber_featured_entries_gutter" name="bimber_featured_entries_gutter">
					<option value=""<?php selected( '', $current_fe_gutter ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $gutter_types[ $global_fe_gutter ] ) ); ?></option>

					<?php foreach ( $gutter_types as $gutter_option_id => $gutter_option_name ) : ?>

						<option value="<?php echo esc_attr( $gutter_option_id ); ?>"<?php selected( $gutter_option_id, $current_fe_gutter ); ?>><?php echo esc_html( $gutter_option_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-fe-time-range-wrap">
			<th scope="row">
				<label for="bimber_featured_entries_time_range"><?php esc_html_e( 'Time Range', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $time_ranges = bimber_get_archive_featured_entries_time_ranges(); ?>
				<select id="bimber_featured_entries_time_range" name="bimber_featured_entries_time_range">
					<option value=""<?php selected( '', $current_fe_time_range ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $time_ranges[ $global_fe_time_range ] ) ); ?></option>

					<?php foreach ( $time_ranges as $time_range_id => $time_range ) : ?>

						<option value="<?php echo esc_attr( $time_range_id ); ?>"<?php selected( $time_range_id, $current_fe_time_range ); ?>><?php echo esc_html( $time_range ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<br />
<br />
<br />
<hr />

<h2>
	<?php esc_html_e( 'Main Collection', 'bimber' ); ?>
	<a class="g1-action-edit-defaults button button-small button-secondary" href="<?php echo admin_url( 'customize.php?autofocus[section]=bimber_posts_archive_section' ); ?>" target="_blank"><?php esc_html_e( 'Edit Default Options', 'bimber' ); ?></a>
</h2>

<table class="form-table">
	<tbody>
		<tr class="form-field term-title-wrap">
			<th scope="row">
				<label for="bimber_title"><?php esc_html_e( 'Title', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_title" name="bimber_title" type="text" value="<?php echo esc_attr( $current_title ); ?>" placeholder="<?php esc_html_e( 'Leave empty to use the default value', 'bimber' ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-title-hide-wrap">
			<th scope="row">
				<label for="bimber_title_hide"><?php esc_html_e( 'Hide Title', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $hide_title_options = bimber_get_yes_no_options(); ?>
				<select id="bimber_title_hide" name="bimber_title_hide">
					<option value=""<?php selected( '', $current_title_hide ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $hide_title_options[ $global_title_hide ] ) ); ?></option>

					<?php foreach ( $hide_title_options as $hide_title_id => $hide_title_name ) : ?>

						<option value="<?php echo esc_attr( $hide_title_id ); ?>"<?php selected( $hide_title_id, $current_title_hide ); ?>><?php echo esc_html( $hide_title_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-template-wrap">
			<th scope="row">
				<label for="bimber_template"><?php esc_html_e( 'Template', 'bimber' ); ?></label>
			</th>
			<td>
				<?php
				$templates = bimber_get_archive_templates();
				$templates = array_merge(
					array(
						'' => array(
							'label' => sprintf( __( 'default (%s)', 'bimber' ), $templates[ $global_template ]['label'] ),
							'path'  => BIMBER_ADMIN_DIR_URI . 'images/templates/archive/inherit.png',
						),
					),
					$templates
				);

				bimber_ui_render_image_radio( array(
					'html_name'     => 'bimber_template',
					'options'       => $templates,
					'width'         => 136,
					'height'        => 136,
					'value'         => $current_template,
					'img_base_url'  => BIMBER_ADMIN_DIR_URI . 'images/templates/archive/',
				) );
				?>
			</td>
		</tr>
		<tr class="form-field term-sidebar_location-wrap">
			<th scope="row">
				<label for="bimber_sidebar_location"><?php esc_html_e( 'Sidebar Location', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $sidebar_location = array(
					'left' => 'left',
					'standard' => 'right',
					); ?>
				<select id="bimber_sidebar_location" name="bimber_sidebar_location">
					<option value=""<?php selected( '', $current_sidebar_location ); ?>>
						<?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $sidebar_location[ $global_sidebar_location ] ) ); ?>
					</option>

					<?php foreach ( $sidebar_location as $sidebar_location_option_id => $sidebar_location_option_name ) : ?>

						<option value="<?php echo esc_attr( $sidebar_location_option_id ); ?>"<?php selected( $sidebar_location_option_id, $current_sidebar_location ); ?>><?php echo esc_html( $sidebar_location_option_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-sidebar_override-wrap">
			<th scope="row">
				<label for="bimber_sidebar_override"><?php esc_html_e( 'Sidebar', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $sidebar_override = $GLOBALS['wp_registered_sidebars']; ?>
				<select id="bimber_sidebar_override" name="bimber_sidebar_override">
					<option value=""<?php selected( '', $current_sidebar_override ); ?>><?php esc_html_e( 'Default', 'bimber' ); ?></option>

					<?php foreach ( $sidebar_override as $sidebar ) : ?>

						<option value="<?php echo esc_attr( $sidebar['id'] ); ?>"<?php selected( $sidebar['id'], $current_sidebar_override ); ?>><?php echo esc_html( $sidebar['name'] ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-inject_embeds-wrap">
			<th scope="row">
				<label for="bimber_inject_embeds"><?php esc_html_e( 'Inject embeds into featured media', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $inject_embeds = bimber_get_yes_no_options(); ?>
				<select id="bimber_inject_embeds" name="bimber_inject_embeds">
					<option value=""<?php selected( '', $current_inject_embeds ); ?>>
						<?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $inject_embeds[ $global_inject_embeds ] ) ); ?>
					</option>

					<?php foreach ( $inject_embeds as $inject_embeds_option_id => $inject_embeds_option_name ) : ?>

						<option value="<?php echo esc_attr( $inject_embeds_option_id ); ?>"<?php selected( $inject_embeds_option_id, $current_inject_embeds ); ?>><?php echo esc_html( $inject_embeds_option_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-posts-per-page-wrap">
			<th scope="row">
				<label for="bimber_posts_per_page"><?php esc_html_e( 'Entries per page', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_posts_per_page" name="bimber_posts_per_page" type="text" value="<?php echo esc_attr( $current_posts_per_page ); ?>" placeholder="<?php echo esc_attr( sprintf( __( 'Leave empty to use the default value (%s)', 'bimber' ), $global_posts_per_page ) ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-pagination-wrap">
			<th scope="row">
				<label for="bimber_pagination"><?php esc_html_e( 'Pagination', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $pagination_types = bimber_get_archive_pagination_types(); ?>
				<select id="bimber_pagination" name="bimber_pagination">
					<option value=""<?php selected( '', $current_pagination ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $pagination_types[ $global_pagination ] ) ); ?></option>

					<?php foreach ( $pagination_types as $pagination_type_id => $pagination_type_name ) : ?>

						<option value="<?php echo esc_attr( $pagination_type_id ); ?>"<?php selected( $pagination_type_id, $current_pagination ); ?>><?php echo esc_html( $pagination_type_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-hide-elements-wrap">
			<th scope="row">
				<?php esc_html_e( 'Hide Elements', 'bimber' ); ?>
			</th>
			<td>
				<select id="bimber_override_hide_elements" name="bimber_override_hide_elements">
					<option value="none"<?php selected( 'none', $current_override_hide_elements ); ?>><?php esc_html_e( 'Default', 'bimber' ); ?></option>
					<option value="standard"<?php selected( 'standard', $current_override_hide_elements ); ?>><?php esc_html_e( 'Change', 'bimber' ); ?></option>
				</select>

				<div id="bimber-hide-elements-wrapper" style="display: <?php echo ( 'standard' === $current_override_hide_elements ) ? 'block' : 'none'; ?>;">
					<?php $hide_elements = bimber_get_archive_elements_to_hide(); ?>
					<?php $current_hide_elements_arr = ! empty( $current_hide_elements ) ? explode( ',', $current_hide_elements ) : array(); ?>

					<?php foreach ( $hide_elements as $element_id => $element_name ) : ?>
						<p>
							<input id="bimber_hide_element_<?php echo esc_attr( $element_id ); ?>" name="bimber_hide_elements[]" type="checkbox" value="<?php echo esc_attr( $element_id ); ?>"<?php checked( in_array( $element_id, $current_hide_elements_arr, true ) ); ?> />
							<label for="bimber_hide_element_<?php echo esc_attr( $element_id ); ?>"><?php echo esc_html( $element_name ); ?></label>
						</p>
					<?php endforeach; ?>

					<p>
						<strong><?php esc_html_e( 'Call to Action - Hide Buttons', 'bimber' ); ?></strong>
					</p>
					<?php $cta_hide_elements = bimber_get_post_call_to_action_buttons(); ?>
					<?php $current_cta_hide_elements_arr = ! empty( $current_call_to_action_hide_buttons ) ? explode( ',', $current_call_to_action_hide_buttons ) : array(); ?>

					<?php foreach ( $cta_hide_elements as $element_id => $element_name ) : ?>
						<p>
							<input id="bimber_call_to_action_hide_button_<?php echo esc_attr( $element_id ); ?>" name="bimber_call_to_action_hide_buttons[]" type="checkbox" value="<?php echo esc_attr( $element_id ); ?>"<?php checked( in_array( $element_id, $current_cta_hide_elements_arr, true ) ); ?> />
							<label for="bimber_call_to_action_hide_button_<?php echo esc_attr( $element_id ); ?>"><?php echo esc_html( $element_name ); ?></label>
						</p>
					<?php endforeach; ?>
				</div>
			</td>
		</tr>
		<tr class="form-field term-newsletter-wrap">
			<th scope="row">
				<label for="bimber_newsletter"><?php esc_html_e( 'Newsletter', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $newsletter = bimber_get_archive_newsletter_options(); ?>
				<select id="bimber_newsletter" name="bimber_newsletter">
					<option value=""<?php selected( '', $current_newsletter ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $newsletter[ $global_newsletter ] ) ); ?></option>

					<?php foreach ( $newsletter as $newsletter_option_id => $newsletter_option_name ) : ?>

						<option value="<?php echo esc_attr( $newsletter_option_id ); ?>"<?php selected( $newsletter_option_id, $current_newsletter ); ?>><?php echo esc_html( $newsletter_option_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-newsletter-after-post-wrap">
			<th scope="row">
				<label for="bimber_newsletter_after_post"><?php esc_html_e( 'Inject newsletter after post', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_newsletter_after_post" name="bimber_newsletter_after_post" type="text" value="<?php echo esc_attr( $current_newsletter_after_post ); ?>" placeholder="<?php echo esc_html( sprintf( __( 'Leave empty to use the default value (%s)', 'bimber' ), $global_newsletter_after_post ) ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-ad-wrap">
			<th scope="row">
				<label for="bimber_ad"><?php esc_html_e( 'Ad', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $ad = bimber_get_archive_ad_options(); ?>
				<select id="bimber_ad" name="bimber_ad">
					<option value=""<?php selected( '', $current_ad ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $ad[ $global_ad ] ) ); ?></option>

					<?php foreach ( $ad as $ad_option_id => $ad_option_name ) : ?>

						<option value="<?php echo esc_attr( $ad_option_id ); ?>"<?php selected( $ad_option_id, $current_ad ); ?>><?php echo esc_html( $ad_option_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-ad-after-post-wrap">
			<th scope="row">
				<label for="bimber_ad_after_post"><?php esc_html_e( 'Inject ad after post', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_ad_after_post" name="bimber_ad_after_post" type="text" value="<?php echo esc_attr( $current_ad_after_post ); ?>" placeholder="<?php echo esc_html( sprintf( __( 'Leave empty to use the default value (%s)', 'bimber' ), $global_ad_after_post ) ); ?>" />
			</td>
		</tr>
		<tr class="form-field term-product-wrap">
			<th scope="row">
				<label for="bimber_product"><?php esc_html_e( 'Product', 'bimber' ); ?></label>
			</th>
			<td>
				<?php $product = bimber_get_archive_product_options(); ?>
				<select id="bimber_product" name="bimber_product">
					<option value=""<?php selected( '', $current_product ); ?>><?php echo esc_html( sprintf( __( 'default (%s)', 'bimber' ), $product[ $global_product ] ) ); ?></option>

					<?php foreach ( $product as $product_option_id => $product_option_name ) : ?>

						<option value="<?php echo esc_attr( $product_option_id ); ?>"<?php selected( $product_option_id, $current_product ); ?>><?php echo esc_html( $product_option_name ); ?></option>

					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr class="form-field term-product-after-post-wrap">
			<th scope="row">
				<label for="bimber_product_after_post"><?php esc_html_e( 'Inject product after post', 'bimber' ); ?></label>
			</th>
			<td>
				<input id="bimber_product_after_post" name="bimber_product_after_post" type="text" value="<?php echo esc_attr( $current_product_after_post ); ?>" placeholder="<?php echo esc_html( sprintf( __( 'Leave empty to use the default value (%s)', 'bimber' ), $global_product_after_post ) ); ?>" />
			</td>
		</tr>
	<?php
}

/**
 * Save custom fields on a post category edit screen.
 *
 * @param int $term_id  Term ID.
 */
function bimber_category_save_custom_fields( $term_id ) {
	$term_icon			    = bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_term_icon' ) );
	$term_image			    = bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_taxonomy_image' ) );
	$header_composition		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_composition' ) );
	$header_bg_color		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_bg_color' ) );
	$header_bg2_color		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_bg2_color' ) );
	$header_text_color		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_text_color' ) );
	$header_bg_image		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_bg_image' ) );
	$header_bg_size			= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_bg_size' ) );
	$header_bg_repeat		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_bg_repeat' ) );
	$header_override_hide_elements	= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_header_override_hide_elements' ) );

	$label_color 			= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_label_color' ) );
	$label_bg_color 		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_label_bg_color' ) );

	$fe_type 				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_featured_entries' ) );
	$fe_template			= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_featured_entries_template' ) );
	$fe_gutter				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_featured_entries_gutter' ) );
	$fe_title				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_featured_entries_title' ) );
	$fe_title_hide			= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_featured_entries_title_hide' ) );
	$fe_time_range			= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_featured_entries_time_range' ) );

	$template				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_template' ) );
	$sidebar_location		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_sidebar_location' ) );
	$sidebar_override		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_sidebar_override' ) );
	$inject_embeds		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_inject_embeds' ) );
	$title					= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_title' ) );
	$title_hide				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_title_hide' ) );
	$posts_per_page			= filter_input( INPUT_POST, 'bimber_posts_per_page', FILTER_SANITIZE_NUMBER_INT );
	$pagination				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_pagination' ) );
	$override_hide_elements	= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_override_hide_elements' ) );
	$newsletter				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_newsletter' ) );
	$newsletter_after_post	= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_newsletter_after_post' ) );
	$product				= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_product' ) );
	$product_after_post		= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_product_after_post' ) );
	$ad						= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_ad' ) );
	$ad_after_post			= bimber_htmlspecialchars( filter_input( INPUT_POST, 'bimber_ad_after_post' ) );

	// Save.
	update_term_meta( $term_id, 'bimber_term_icon', $term_icon );
	update_term_meta( $term_id, 'bimber_taxonomy_image', $term_image );
	update_term_meta( $term_id, 'bimber_header_composition', $header_composition );
	update_term_meta( $term_id, 'bimber_header_background_color', $header_bg_color );
	update_term_meta( $term_id, 'bimber_header_background2_color', $header_bg2_color );
	update_term_meta( $term_id, 'bimber_header_text_color', $header_text_color );
	update_term_meta( $term_id, 'bimber_header_background_image', $header_bg_image );
	update_term_meta( $term_id, 'bimber_header_background_size', $header_bg_size );
	update_term_meta( $term_id, 'bimber_header_background_repeat', $header_bg_repeat );
	update_term_meta( $term_id, 'bimber_header_override_hide_elements', $header_override_hide_elements );

	update_term_meta( $term_id, 'bimber_label_color', $label_color );
	update_term_meta( $term_id, 'bimber_label_background_color', $label_bg_color );

	update_term_meta( $term_id, 'bimber_featured_entries', $fe_type );
	update_term_meta( $term_id, 'bimber_featured_entries_template', $fe_template );
	update_term_meta( $term_id, 'bimber_featured_entries_gutter', $fe_gutter );
	update_term_meta( $term_id, 'bimber_featured_entries_title', $fe_title );
	update_term_meta( $term_id, 'bimber_featured_entries_title_hide', $fe_title_hide );
	update_term_meta( $term_id, 'bimber_featured_entries_time_range', $fe_time_range );

	update_term_meta( $term_id, 'bimber_template', $template );
	update_term_meta( $term_id, 'bimber_sidebar_location', $sidebar_location );
	update_term_meta( $term_id, 'bimber_sidebar_override', $sidebar_override );
	update_term_meta( $term_id, 'bimber_inject_embeds', $inject_embeds );
	update_term_meta( $term_id, 'bimber_title', $title );
	update_term_meta( $term_id, 'bimber_title_hide', $title_hide );
	update_term_meta( $term_id, 'bimber_posts_per_page', $posts_per_page );
	update_term_meta( $term_id, 'bimber_pagination', $pagination );
	update_term_meta( $term_id, 'bimber_override_hide_elements', $override_hide_elements );

	if ( 'standard' === $override_hide_elements ) {
		$hide_elements_arr = filter_input( INPUT_POST, 'bimber_hide_elements', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Normalize.
		$hide_elements_str = ! empty( $hide_elements_arr ) ? implode( ',', $hide_elements_arr ) : '';

		// CTA - Hide Elements.
		$cta_hide_elements_arr	    = filter_input( INPUT_POST, 'bimber_call_to_action_hide_buttons', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$cta_hide_elements_str = ! empty( $cta_hide_elements_arr ) ? implode( ',', $cta_hide_elements_arr ) : '';
	} else {
		$hide_elements_str = '';
		$cta_hide_elements_str = '';
	}

	update_term_meta( $term_id, 'bimber_hide_elements', $hide_elements_str );

	if ( 'standard' === $header_override_hide_elements ) {
		$hide_elements_arr = filter_input( INPUT_POST, 'bimber_header_hide_elements', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Normalize.
		$hide_elements_str = ! empty( $hide_elements_arr ) ? implode( ',', $hide_elements_arr ) : '';
	} else {
		$hide_elements_str = '';
	}
	update_term_meta( $term_id, 'bimber_header_hide_elements', $hide_elements_str );
	update_term_meta( $term_id, 'bimber_call_to_action_hide_buttons', $cta_hide_elements_str );

	update_term_meta( $term_id, 'bimber_newsletter', $newsletter );
	update_term_meta( $term_id, 'bimber_newsletter_after_post', $newsletter_after_post );
	update_term_meta( $term_id, 'bimber_product', $product );
	update_term_meta( $term_id, 'bimber_product_after_post', $product_after_post );
	update_term_meta( $term_id, 'bimber_ad', $ad );
	update_term_meta( $term_id, 'bimber_ad_after_post', $ad_after_post );
}
