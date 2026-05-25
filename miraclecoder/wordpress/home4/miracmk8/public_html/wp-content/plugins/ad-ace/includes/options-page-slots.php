<?php
/**
 * Options Page for Slots
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Prints out all settings sections for ads slots.
 * This is reworked do_settings_sections();
 *
 * @global $wp_settings_sections Storage array of all settings sections added to admin pages
 * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections
 *
 * @param string $page The slug name of the page whose settings sections you want to output.
 */
function adace_do_slots_settings_sections( $page ) {
	global $wp_settings_sections, $wp_settings_fields;
	if ( ! isset( $wp_settings_sections[ $page ] ) ) {
		return;
	}

	$adace_ad_slots = adace_access_ad_slots();
	$adace_ad_sections = adace_access_ad_sections();

	$adace_ad_sections[] = array(
		'slug'  => 'default',
		'label' => __( 'Various', 'adace' ),
	);
	?>

	<?php foreach ( $adace_ad_sections as $ad_section ) : ?>
		<div class="adace-ad-section">
			<h3><?php echo wp_kses_post( $ad_section['label'] ); ?></h3>
			<?php foreach ( (array) $wp_settings_sections[ $page ] as $section ) : ?>
				<?php
					$id = $section['id'];
					$id = str_replace( 'adace_slot_','',$id );
					$id = str_replace( '_section','',$id );
					$slot_section = $adace_ad_slots[ $id ]['section'];
				?>
				<?php if ( $slot_section === $ad_section['slug'] ) : ?>
					<div id="<?php echo esc_attr( $id ); ?>" class="postbox closed">
						<?php if ( $section['title'] ) : ?>
							<button type="button" class="handlediv button-link"><span class="toggle-indicator" aria-hidden="true"></span></button>
							<h2 class="hndle"><?php echo wp_kses_post( $section['title'] ); ?></h2>
						<?php  endif; ?>

						<div class="inside">
							<?php
								if ( $section['callback'] ) :
									call_user_func( $section['callback'], $section );
								endif;

								if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
									continue;
								}
							?>

							<table class="form-table adace-slot-settings">
								<?php adace_do_slots_settings_fields( $page, $section['id'] ); ?>
							</table>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
	<?php
}
/**
 * Print out the settings fields for a particular settings section
 *
 * @global $wp_settings_fields Storage array of settings fields and their pages/sections
 *
 *
 * @param string $page Slug title of the admin page who's settings fields you want to show.
 * @param string $section Slug title of the settings section who's fields you want to show.
 */
function adace_do_slots_settings_fields( $page, $section ) {
	global $wp_settings_fields;

	if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
		return;
	}

	$slot_rows = array();
	foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
		// Class
		$row = $field['args']['field_for'];
		if ( isset( $field['args']['row'] ) && ! empty( $field['args']['row'] ) ) {
			$row = $field['args']['row'];
		}
		if ( ! isset( $slot_rows[ $row ] ) ) {
			$slot_rows[ $row ] = array();
		}
		$slot_rows[ $row ][] = $field;
	}
	foreach ( $slot_rows as $row_key => $row_fields ) {

		ob_start();
		echo '<tr class="fields-row row-' . $row_key . '">';
		foreach ( $row_fields as $field ) {
			$class = '';
			if ( isset( $field['args']['class'] ) ) {
				$class = esc_attr( $field['args']['class'] );
			}
			echo '<th class="field field-' . $class . '">';
			if ( ! empty( $field['args']['label_for'] ) ) {
				echo '<label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label>';
			} else {
				echo $field['title'];
			}
			echo '</th>';
			echo '<td class="field-input ' . $class . '">';
				call_user_func( $field['callback'], $field['args'] );
			echo '</td>';
		}
		echo '</tr>';
		$row_output = ob_get_clean();
		echo( apply_filters( 'adace_options_row_' . $row_key . '_callback', $row_output, $row_key, $row_fields ) );
	}
}

add_action( 'admin_menu', 'adace_add_slots_options_sections_and_fields' );
/**
 * Add options page sections, fields and options.
 */
function adace_add_slots_options_sections_and_fields() {
	// Get registered slots.
	$adace_ad_slots = adace_access_ad_slots();
	// Check if any registed.
	if ( ! empty( $adace_ad_slots ) && is_array( $adace_ad_slots ) ) {
		// Loop through to add sections, settings and fields.
		foreach ( $adace_ad_slots as $adace_ad_slot ) {
			// Add setting.
			add_settings_section(
				'adace_slot_' . $adace_ad_slot['id'] . '_section', // section id
				'<span>' . $adace_ad_slot['name'] . '</span>', // Section title.
				function () use ( $adace_ad_slot ) {
					adace_options_slots_section_renderer_callback( $adace_ad_slot );
				}, // Section renderer callback with args pass.
				'adace_slots' // Page.
			);
			// Array of fields for setting.
			$slots_fields = array(
				'ad_id'             => esc_html__( 'Ad', 'adace' ),
				'ad_group'          => esc_html__( 'Ad Group', 'adace' ),
				'no_repeat'         => esc_html__( 'Don\'t repeat ads', 'adace' ),
				'ad_id_tablet'      => esc_html__( 'Override Ad On Tablet', 'adace' ),
				'ad_id_mobile'      => esc_html__( 'Override Ad On Mobile', 'adace' ),
				'ad_id_amp'         => esc_html__( 'Override Ad On AMP', 'adace' ),
				'is_home'           => esc_html__( 'Display on home', 'adace' ),
				'is_singular'       => esc_html__( 'Display on singular', 'adace' ),
				'is_archive'        => esc_html__( 'Display on archive', 'adace' ),
				'is_search'         => esc_html__( 'Display on search', 'adace' ),
				'is_user_logged_in' => esc_html__( 'Display for logged in users', 'adace' ),
				'is_amp'            => esc_html__( 'Display on AMP', 'adace' ),
				'width'         => array(
					'title' => esc_html__( 'Width', 'adace' ),
				),
				'alignment'         => esc_html__( 'Alignment', 'adace' ),
				'margin'            => esc_html__( 'Margin', 'adace' ),
			);
			// Allows to input custom field based on current $adace_ad_slot in loop.
			$slots_fields = apply_filters(
				'adace_options_slot_fields_filter',
				$slots_fields,
				$adace_ad_slot
			);
			foreach ( $slots_fields as $slots_field_key => $slots_field ) {
				// Args for settings field.
				$field_args = array(
					'id'       => 'slot_' . $slots_field_key, // Field ID.
					'title'    => '',
					'callback' => 'adace_options_slots_fields_renderer_callback', // Callback.
					'page'     => 'adace_slots', // Page.
					'section'  => 'adace_slot_' . $adace_ad_slot['id'] . '_section', // Section.
					'args'     => array(
						'field_for' => $slots_field_key,
						'slot'      => $adace_ad_slot,
						'class'     => esc_attr( $slots_field_key ),
						'row'       => '',
					),
				);
				// To make sure previously declared stuff works.
				if ( is_array( $slots_field ) ) {
					// Check for title.
					if ( isset( $field_args['title'] ) ) {
						$field_args['title'] = $slots_field['title'];
					}
					// Check for row.
					if ( isset( $slots_field['row'] ) ) {
						$field_args['args']['row'] = $slots_field['row'];
					}
				} elseif ( is_string( $slots_field ) ) {
					$field_args['title'] = $slots_field;
				}
				add_settings_field( $field_args['id'], $field_args['title'], $field_args['callback'], $field_args['page'], $field_args['section'], $field_args['args'] );
			}
			// Register setting.
			register_setting(
				'adace_slots', // Option group.
				'adace_slot_' . $adace_ad_slot['id'] . '_options', // Option name.
				'adace_slots_options_save_validator' // Options saving validator.
			);
		}
	}
}

/**
 * Options section renderer.
 *
 * @param array $args Slot arguments.
 */
function adace_options_slots_section_renderer_callback( $args ) {
	echo( isset( $args['desc'] ) && ! empty( $args['desc'] ) ? '<p>' . wp_kses_post( $args['desc'] ) . '</p>' : '');
}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function adace_options_slots_fields_renderer_callback( $args ) {
	// Get slot options.
	$slot_options = get_option( 'adace_slot_' . $args['slot']['id'] . '_options' );
	// Action to render outside fields. For example other plugins supported fields.
	do_action( 'adace_options_slots_field_renderer_action', $args, $slot_options );
	// Switch field.
	switch ( $args['field_for'] ) {
		case 'ad_id':
		case 'ad_id_tablet':
		case 'ad_id_mobile':
		case 'ad_id_amp':
			$ad_current = isset( $slot_options[ $args['field_for'] ] ) ? $slot_options[ $args['field_for'] ] : '';
			$ads_query_args = array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post_type'      => 'adace-ad',
			);
			$ads_query = new WP_Query( $ads_query_args );
			$has_ads = $ads_query->have_posts();


		?>
		<select class="adace-ad-select" <?php if ( ! $has_ads ) { echo esc_attr( 'hidden' ); }?> id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[<?php echo $args['field_for']?>]" name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[<?php echo $args['field_for']?>]">
			<?php if ( 'ad_id' === $args['field_for'] ) : ?>
			<option value="" <?php selected( $ad_current, '' ); ?>><?php esc_html_e( '- Disabled -', 'adace' ); ?></option>
			<option class="adace-random-option" value="-1" <?php selected( $ad_current, '-1' ); ?>><?php esc_html_e( '- Random ad -', 'adace' ); ?></option>
			<option <?php if ( ! $args['slot']['is_repeater'] ) { echo esc_attr( 'hidden' );}?>
			class="adace-group-option" value="-2" <?php selected( $ad_current, '-2' ); ?>><?php esc_html_e( '- Group -', 'adace' ); ?></option>
			<?php else : ?>
			<option value="-3" <?php selected( $ad_current, '-3' ); ?>><?php esc_html_e( '- Don\'t override -', 'adace' ); ?></option>
			<?php endif; ?>

			<?php foreach ( $ads_query->get_posts() as $ad ) : ?>
				<option value="<?php echo esc_attr( $ad->ID ); ?>" <?php selected( $ad_current, $ad->ID ); ?> data-adace-href="<?php echo esc_attr( get_edit_post_link( $ad ) ); ?>">
					<?php echo esc_html( get_the_title( $ad ) ); ?>
				</option>
			<?php endforeach; ?>
		</select>

		<a class="adace-ad-select-link button button-secondary" href="#" target="_blank"><?php esc_html_e( 'Edit Ad', 'adace' ); ?></a>

		<?php
		if ( ! $has_ads ) : ?>
			<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=adace-ad' ) ); ?>" class="button button-secondary" href="#"><?php esc_html_e( 'Create Ad', 'adace' ); ?></a>
		<?php
		endif;
		break;
		case 'ad_group':
			$group_current = isset( $slot_options['ad_group'] ) ? $slot_options['ad_group'] : '';
			$groups = get_terms( 'adace-ad-group', array(
				'hide_empty' => true,
			) );
		?>
		<select class="adace-group-select" id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[ad_group]" name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[ad_group]">
			<option value="" <?php selected( $group_current, '' ); ?>><?php esc_html_e( '- All -', 'adace' ); ?></option>
			<?php
			foreach ( $groups as $key => $value ) :
				?>
					<option value="<?php echo( esc_html( $value->slug ) ); ?>" <?php selected( $group_current, $value->slug ); ?>><?php echo( esc_html( $value->name ) ); ?></option>
				<?php
			endforeach;
			?>
		</select>
		<?php
		break;
		case 'no_repeat':
			$no_repeat_current = isset( $slot_options['no_repeat'] ) ? $slot_options['no_repeat'] : false;
		?>
		<input
			<?php if ( ! $args['slot']['is_repeater'] ) { echo esc_attr( 'disabled' );}?>
			type="checkbox"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[no_repeat]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[no_repeat]"
			value="1"
			<?php checked( 1, $no_repeat_current );?>
		/>
		<?php
		break;
		case 'is_home':
			$is_home_editable = $args['slot']['options']['is_home_editable'];
			if ( $is_home_editable ) {
				$is_home_current = isset( $slot_options['is_home'] ) ? $slot_options['is_home'] : $args['slot']['options']['is_home'];
			} else {
				$is_home_current = $args['slot']['options']['is_home'];
			}
		?>
		<input
			type="checkbox"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_home]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_home]"
			value="1"
			<?php checked( 1, $is_home_current );?>
			<?php echo( $is_home_editable ? '' : ' disabled' );  ?>
		/>
		<?php
		break;
		case 'is_search':
			$is_search_editable = $args['slot']['options']['is_search_editable'];
			if ( $is_search_editable ) {
				$is_search_current = isset( $slot_options['is_search'] ) ? $slot_options['is_search'] : $args['slot']['options']['is_search'];
			} else {
				$is_search_current = $args['slot']['options']['is_search'];
			}
		?>
		<input
			type="checkbox"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_search]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_search]"
			value="1"
			<?php checked( 1, $is_search_current ); ?>
			<?php echo( $is_search_editable ? '' : ' disabled' );  ?>
		/>
		<?php
		break;
		case 'is_singular':
			$is_singular_editable = $args['slot']['options']['is_singular_editable'];
			if ( $is_singular_editable ) {
				$is_singular_current = isset( $slot_options['is_singular'] ) ? $slot_options['is_singular'] : $args['slot']['options']['is_singular'];
			} else {
				$is_singular_current = $args['slot']['options']['is_singular'];
			}
			if ( 'default' === $is_singular_current ) {
				$is_singular_current = array_keys( adace_get_supported_post_types() );
				unset( $is_singular_current['page'] );
			}
			$supported_post_types = adace_get_supported_post_types();
			$supported_post_types = array( 'adace-none' => esc_html__( '- None -', 'adace' ) ) + $supported_post_types;
		?>
		<select
			multiple="multiple"
			style="min-width:210px;"
			class="adace-multiple-with-none"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_singular][]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_singular][]"
			<?php echo( $is_singular_editable ? '' : ' disabled' );  ?>
		>
			<?php foreach ( $supported_post_types as $post_type_slug => $post_type_name ) : ?>
				<option value="<?php echo( esc_html( $post_type_slug ) ); ?>" <?php echo( is_array( $is_singular_current ) && in_array( $post_type_slug, $is_singular_current, true ) ? 'selected="selected"' : '' ); ?>><?php echo( esc_html( $post_type_name ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		break;
		case 'is_archive':
			$is_archive_editable = $args['slot']['options']['is_archive_editable'];
			if ( $is_archive_editable ) {
				$is_archive_current = isset( $slot_options['is_archive'] ) ? $slot_options['is_archive'] : $args['slot']['options']['is_archive'];
			} else {
				$is_archive_current = $args['slot']['options']['is_archive'];
			}
			if ( 'default' === $is_archive_current ) {
				$is_archive_current = array_keys( adace_get_supported_taxonomies() );
			}
			$supported_taxonomies = adace_get_supported_taxonomies();
			$supported_taxonomies = array( 'adace-none' => esc_html__( '- None -', 'adace' ) ) + $supported_taxonomies;
		?>
		<select
			multiple="multiple"
			style="min-width:210px;"
			class="adace-multiple-with-none"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_archive][]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_archive][]"
			<?php echo( $is_archive_editable ? '' : ' disabled' );  ?>
		>
			<?php foreach ( $supported_taxonomies as $taxonomy_slug => $taxonomy_name ) : ?>
				<option value="<?php echo( esc_html( $taxonomy_slug ) ); ?>" <?php echo( is_array( $is_archive_current ) && in_array( $taxonomy_slug, $is_archive_current, true ) ? 'selected="selected"' : '' ); ?>><?php echo( esc_html( $taxonomy_name ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		break;
		case 'is_user_logged_in':
			$is_home_editable = $args['slot']['options']['is_user_logged_in_editable'];
			if ( $is_home_editable ) {
				$is_home_current = isset( $slot_options['is_user_logged_in'] ) ? $slot_options['is_user_logged_in'] : $args['slot']['options']['is_user_logged_in'];
			} else {
				$is_home_current = $args['slot']['options']['is_user_logged_in'];
			}
		?>
		<input
			type="checkbox"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_user_logged_in]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_user_logged_in]"
			value="1"
			<?php checked( 1, $is_home_current );?>
			<?php echo( $is_home_editable ? '' : ' disabled' );  ?>
		/>
		<?php
		break;
		case 'is_amp':
			$is_amp_editable = $args['slot']['options']['is_amp_editable'];
			if ( $is_amp_editable ) {
				$is_amp_current = isset( $slot_options['is_amp'] ) ? $slot_options['is_amp'] : $args['slot']['options']['is_amp'];
			} else {
				$is_amp_current = $args['slot']['options']['is_amp'];
			}
		?>
		<input
			type="checkbox"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_amp]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[is_amp]"
			value="1"
			<?php checked( 1, $is_amp_current );?>
			<?php echo( $is_amp_editable ? '' : ' disabled' );  ?>
		/>
		<?php
		break;
		case 'width':
			$min_width_editable = $args['slot']['options']['min_width_editable'];
			if ( $min_width_editable ) {
				$min_width_current = isset( $slot_options['min_width'] ) ? $slot_options['min_width'] : $args['slot']['options']['min_width'];
			} else {
				$min_width_current = $args['slot']['options']['min_width'];
			}
			$max_width_editable = $args['slot']['options']['max_width_editable'];
			if ( $max_width_editable ) {
				$max_width_current = isset( $slot_options['max_width'] ) ? $slot_options['max_width'] : $args['slot']['options']['max_width'];
			} else {
				$max_width_current = $args['slot']['options']['max_width'];
			}
		?>
		<div class="field-inside-input horizontal">
			<label for="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[min_width]"><?php esc_html_e( 'Minimum', 'adace' ); ?></label>
			<input
				class="small-text"
				type="number"
				id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[min_width]"
				name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[min_width]"
				min="0"
				max="10000"
				step="1"
				value="<?php echo( esc_html( $min_width_current ) ); ?>"
				<?php echo( $min_width_editable ? '' : ' disabled' );  ?>
			/>
		</div>
		<div class="field-inside-input horizontal">
			<label for="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[max_width]"><?php esc_html_e( 'Maximum', 'adace' ); ?></label>
			<input
				class="small-text"
				type="number"
				id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[max_width]"
				name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[max_width]"
				min="0"
				max="10000"
				step="1"
				value="<?php echo( esc_html( $max_width_current ) ); ?>"
				<?php echo( $max_width_editable ? '' : ' disabled' );  ?>
			/>
		</div>
		<?php
		break;
		case 'alignment':
			$alignment_editable = $args['slot']['options']['alignment_editable'];
			if ( $alignment_editable ) {
				$alignment_current = isset( $slot_options['alignment'] ) ? $slot_options['alignment'] : $args['slot']['options']['alignment'];
			} else {
				$alignment_current = $args['slot']['options']['alignment'];
			}
		?>
		<select
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[alignment]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[alignment]"
			<?php echo( $alignment_editable ? '' : ' disabled' );  ?>
		>
			<option value="none" <?php selected( $alignment_current, 'none' ); ?>><?php esc_html_e( 'None', 'adace' ); ?></option>
			<option value="left" <?php selected( $alignment_current, 'left' ); ?>><?php esc_html_e( 'Left', 'adace' ); ?></option>
			<option value="center" <?php selected( $alignment_current, 'center' ); ?>><?php esc_html_e( 'Center', 'adace' ); ?></option>
			<option value="right" <?php selected( $alignment_current, 'right' ); ?>><?php esc_html_e( 'Right', 'adace' ); ?></option>
		</select>
		<?php
		break;
		case 'margin':
			$margin_editable = $args['slot']['options']['margin_editable'];
			if ( $margin_editable ) {
				$margin_current = isset( $slot_options['margin'] ) ? $slot_options['margin'] : $args['slot']['options']['margin'];
			} else {
				$margin_current = $args['slot']['options']['margin'];
			}
		?>
		<input
			class="small-text"
			type="number"
			id="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[margin]"
			name="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[margin]"
			min="0"
			max="10000"
			step="1"
			value="<?php echo( esc_html( $margin_current ) ); ?>"
			<?php echo( $margin_editable ? '' : ' disabled' );  ?>
		/>
		<label for="<?php echo( 'adace_slot_' . esc_attr( $args['slot']['id'] ) . '_options' ); ?>[margin]"><?php esc_html_e( 'px', 'adace' ); ?></label>
		<?php
	}
}

/**
 * Options validator.
 *
 * @param array $input Saved options.
 * @return array Sanitised options for save.
 */
function adace_slots_options_save_validator( $input ) {
	$defaults = adace_get_slot_default_args();
	$input_sanitized = array();
	if ( isset( $input['is_home'] ) ) {
		$input_sanitized['is_home'] = filter_var( $input['is_home'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['is_home'] = false;
	}
	if ( isset( $input['is_search'] ) ) {
		$input_sanitized['is_search'] = filter_var( $input['is_search'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['is_search'] = false;
	}
	if ( isset( $input['is_singular'] ) ) {
		if ( is_array( $input['is_singular'] ) ) {
			$input_sanitized['is_singular'] = filter_var_array( $input['is_singular'], FILTER_SANITIZE_STRING );
		} else {
			$input_sanitized['is_singular'] = filter_var( $input['is_singular'], FILTER_SANITIZE_STRING );
		}
	}
	if ( isset( $input['is_archive'] ) ) {
		if ( is_array( $input['is_archive'] ) ) {
			$input_sanitized['is_archive'] = filter_var_array( $input['is_archive'], FILTER_SANITIZE_STRING );
		} else {
			$input_sanitized['is_archive'] = filter_var( $input['is_archive'], FILTER_SANITIZE_STRING );
		}
	}
	if ( isset( $input['is_user_logged_in'] ) ) {
		$input_sanitized['is_user_logged_in'] = filter_var( $input['is_user_logged_in'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['is_user_logged_in'] = false;
	}
	if ( isset( $input['is_amp'] ) ) {
		$input_sanitized['is_amp'] = filter_var( $input['is_amp'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['is_amp'] = false;
	}
	if ( isset( $input['min_width'] ) ) {
		$input_sanitized['min_width'] = intval( filter_var( $input['min_width'], FILTER_SANITIZE_NUMBER_INT ) );
	}
	if ( isset( $input['max_width'] ) ) {
		$input_sanitized['max_width'] = intval( filter_var( $input['max_width'], FILTER_SANITIZE_NUMBER_INT ) );
	}
	if ( isset( $input['alignment'] ) ) {
		$input_sanitized['alignment'] = filter_var( $input['alignment'], FILTER_SANITIZE_STRING );
	}
	if ( isset( $input['margin'] ) ) {
		$input_sanitized['margin'] = filter_var( $input['margin'], FILTER_SANITIZE_NUMBER_INT );
	}
	// Parse args.
	$input_sanitized = wp_parse_args( $input_sanitized, $defaults['options'] );
	// ad_id is not in defaults, so its added after parsing.
	if ( isset( $input['ad_id'] ) ) {
		$input_sanitized['ad_id'] = filter_var( $input['ad_id'], FILTER_SANITIZE_NUMBER_INT );
	}
	if ( isset( $input['ad_id_tablet'] ) ) {
		$input_sanitized['ad_id_tablet'] = filter_var( $input['ad_id_tablet'], FILTER_SANITIZE_NUMBER_INT );
	}
	if ( isset( $input['ad_id_mobile'] ) ) {
		$input_sanitized['ad_id_mobile'] = filter_var( $input['ad_id_mobile'], FILTER_SANITIZE_NUMBER_INT );
	}
	if ( isset( $input['ad_id_amp'] ) ) {
		$input_sanitized['ad_id_amp'] = filter_var( $input['ad_id_amp'], FILTER_SANITIZE_NUMBER_INT );
	}
	if ( isset( $input['ad_group'] ) ) {
		$input_sanitized['ad_group'] = filter_var( $input['ad_group'], FILTER_SANITIZE_STRING );
	}
	if ( isset( $input['no_repeat'] ) ) {
		$input_sanitized['no_repeat'] = filter_var( $input['no_repeat'], FILTER_VALIDATE_BOOLEAN );
	} else {
		$input_sanitized['no_repeat'] = false;
	}
	return apply_filters(
		'adace_slots_options_save_validator_filter',
		$input_sanitized, $input
	);
}
