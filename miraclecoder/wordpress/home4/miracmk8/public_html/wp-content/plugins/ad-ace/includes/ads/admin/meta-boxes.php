<?php
/**
 * Meta boxes
 *
 * @package AdAce
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'add_meta_boxes_adace-ad', 'adace_add_meta_boxes' );
add_action( 'edit_form_after_title', 'adace_add_ad_edit_tabs' );
$post_types = adace_get_supported_post_types();
foreach ( $post_types as $key => $value ) {
	add_action( 'add_meta_boxes_' . $key, 'adace_add_post_meta_boxes' );
}

/**
 * Register ad metaboxes.
 */
function adace_add_meta_boxes() {
	add_meta_box(
		'adace_ad_meta_box_general',
		esc_html( 'Ad Options', 'adace' ),
		'adace_meta_box_render_general_callback'
	);
	add_meta_box(
		'adace_ad_meta_box_adsense',
		esc_html( 'AdSense', 'adace' ),
		'adace_meta_box_render_adsense_callback'
	);
	add_meta_box(
		'adace_ad_meta_box_custom',
		esc_html( 'Custom Ad', 'adace' ),
		'adace_meta_box_render_custom_callback'
	);
	add_meta_box(
		'adace_ad_meta_box_disable',
		esc_html( 'Disable on devices', 'adace' ),
		'adace_meta_box_render_disable_callback'
	);
}
/**
 * Register adace options metaboxe.
 */
function adace_add_post_meta_boxes() {
	add_meta_box(
		'adace_options_meta_box',
		esc_html( 'AdAce Options', 'adace' ),
		'adace_options_meta_box_render_callback'
	);
}

/**
 * Render single ad edit tabs
 *
 * @param WP_Post $post  The post.
 */
function adace_add_ad_edit_tabs( $post ) {
	$post_type = get_post_type( $post );
	if ( 'adace-ad' !== $post_type ) {
		return;
	}
	$settings = get_post_meta( $post -> ID, 'adace_general', true );
	if ( ! is_array( $settings ) ) {
		$settings = array();
		$settings['adace_ad_type'] = 'custom';
	}
?>

	<h2 class="adace-tab-wrapper nav-tab-wrapper wp-clearfix">
		<a href="#" class="nav-tab adace-nav-tab-custom <?php if ( 'custom' === $settings['adace_ad_type'] ) {echo sanitize_html_class( 'nav-tab-active' );} ?>">Custom Ad</a>
		<a href="#" class="nav-tab adace-nav-tab-adsense <?php if ( 'adsense' === $settings['adace_ad_type'] ) {echo sanitize_html_class( 'nav-tab-active' );} ?> ">AdSense</a>
	</h2>
	<?php
}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_meta_box_render_general_callback( $post ) {
	$settings	  								= get_post_meta( $post -> ID, 'adace_general', true );
	$settings = wp_parse_args( $settings, adace_get_default_general_ad_settings() );

	?>
		<fieldset id="adace-ad-settings">
			<div class="adace-general-section adace-settings-section">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="col"><?php esc_html_e( 'Shortcode', 'adace' ); ?></th>
						<th scope="col"><?php esc_html_e( 'PHP Code', 'adace' ); ?></th>
					</tr>
					<tr>
						<td>
							<input readonly type="text" value="<?php echo esc_html( adace_get_shortcode_for_ad( $post->ID ) );?>" onclick="this.focus(); this.select()" class="code large-text">
						</td>
						<td>
							<input readonly type="text" value="<?php echo esc_html( adace_get_php_shortcode_for_ad( $post->ID ) );?>" onclick="this.focus(); this.select()" class="code large-text">
						</td>
					</tr>
					<tr hidden>
						<th scope="row"><?php esc_html_e( 'Ad Type', 'adace' ); ?></th>
						<td>
							<select id="adace_ad_type" name="adace_ad_type">
								<option value="adsense" <?php selected( $settings['adace_ad_type'] , 'adsense' ); ?>><?php esc_html_e( 'AdSense', 'adace' ); ?></option>
								<option value="custom" <?php selected( $settings['adace_ad_type'] , 'custom' ); ?>><?php esc_html_e( 'Custom', 'adace' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<input name="adace_exclude_from_random" id="adace_exclude_from_random" type="checkbox" <?php checked( $settings['adace_exclude_from_random'] ); ?> />
							<?php esc_html_e( 'Do not use as a random ad', 'adace' ); ?>
						</th>
					</tr>
				</tbody>
			</table>
			</div>
			<?php wp_nonce_field( adace_get_plugin_basename(),'adace_save_ad_meta_nonce' ); ?>
		</fieldset>
	<?php
}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_meta_box_render_adsense_callback( $post ) {

	$adsense	  								= get_post_meta( $post -> ID, 'adace_adsense', true );
	$adsense = wp_parse_args( $adsense, adace_get_default_adsense_ad_settings() );
	?>
		<fieldset id="adace-ad-settings">

			<div  class="adace-adsense-section adace-settings-section">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Paste AdSense code to extract automatically', 'adace' ); ?></th>
						<td>
							<textarea type="text" style="width:50%;" name="adace_adsense_paste" id="adace_adsense_paste" class="large-text" ></textarea>
						</td>
					</tr>
					<tr>
						<th scope="col"><?php esc_html_e( 'Ad Slot ID', 'adace' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Publisher ID', 'adace' ); ?></th>
					</tr>
					<tr>
						<td>
							<input type="text" style="width:75%;" name="adace_adsense_slot" id="adace_adsense_slot" value="<?php echo( esc_attr( $adsense['adace_adsense_slot'] ) ); ?>">
						</td>
						<td>
							<input type="text" style="width:75%;" name="adace_adsense_pub" id="adace_adsense_pub" value="<?php echo( esc_attr( $adsense['adace_adsense_pub'] ) ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="col"><?php esc_html_e( 'Type', 'adace' ); ?></th>
						<th scope="col"><span class="adace_adsense_format"><?php esc_html_e( 'Format', 'adace' ); ?></span></th>
						<th scope="col"></th>
					</tr>
					<tr>
						<td>
							<select id="adace_adsense_type" name="adace_adsense_type">
								<option value="fixed" <?php selected( $adsense['adace_adsense_type'], 'fixed' ); ?>><?php esc_html_e( 'Fixed size', 'adace' ); ?></option>
								<option value="responsive" <?php selected( $adsense['adace_adsense_type'], 'responsive' ); ?>><?php esc_html_e( 'Responsive', 'adace' ); ?></option>
							</select>
						</td>
						<td class="adace_adsense_format">
							<select id="adace_adsense_format" name="adace_adsense_format">
								<option value="auto" <?php selected( $adsense['adace_adsense_format'], 'auto' ); ?>><?php esc_html_e( 'Auto', 'adace' ); ?></option>
								<option value="fluid" <?php selected( $adsense['adace_adsense_format'], 'fluid' ); ?>><?php esc_html_e( 'Fluid', 'adace' ); ?></option>
								<option value="vertical" <?php selected( $adsense['adace_adsense_format'], 'vertical' ); ?>><?php esc_html_e( 'Vertical', 'adace' ); ?></option>
								<option value="horizontal" <?php selected( $adsense['adace_adsense_format'], 'horizontal' ); ?>><?php esc_html_e( 'Horizontal', 'adace' ); ?></option>
								<option value="rectangle" <?php selected( $adsense['adace_adsense_format'], 'rectangle' ); ?>><?php esc_html_e( 'Rectangle', 'adace' ); ?></option>
							</select>
						</td>
					</tr>
					<tr class="adace_adsense_size">
						<th scope="col"></th>
						<th scope="col"><?php esc_html_e( 'Width', 'adace' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Height', 'adace' ); ?></th>
					</tr>
					<tr class="adace_adsense_size">
						<td>
						</td>
						<td>
							<input class="small-text" type="number" style="width:50%;" name="adace_adsense_width" id="adace_adsense_width" value="<?php echo( intval( $adsense['adace_adsense_width'] ) ); ?>">
						</td>
						<td>
							<input class="small-text" type="number" style="width:50%;" name="adace_adsense_height" id="adace_adsense_height" value="<?php echo( intval( $adsense['adace_adsense_height'] ) ); ?>">
						</td>
					</tr>
					<?php adace_render_breakpoint_markup( 'desktop', $adsense );?>
					<?php adace_render_breakpoint_markup( 'landscape', $adsense );?>
					<?php adace_render_breakpoint_markup( 'portrait', $adsense );?>
					<?php adace_render_breakpoint_markup( 'phone', $adsense );?>

				</tbody>
			</table>
			</div>

		</fieldset>
	<?php
}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_meta_box_render_custom_callback( $post ) {

	$custom	 							 = get_post_meta( $post -> ID, 'adace_custom', true );
	$custom                              = wp_parse_args( $custom, adace_get_default_custom_ad_settings() );
	?>
		<fieldset id="adace-ad-settings">

			<div class="adace-custom-section adace-settings-section">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Image', 'adace' ); ?></th>
						<td>
							<div class="adace-image-upload">
								<a class="adace button-secondary adace-add-image" href="#"><?php esc_html_e( 'Add Image', 'adace' ); ?></a>
								<div class="adace-image">
									<?php if ( ! empty( $custom['adace_ad_image'] ) ) :  ?>
										<?php echo wp_get_attachment_image( $custom['adace_ad_image'] , 'medium' ); ?>
									<?php endif; ?>
								</div>
								<a class="button button-secondary adace-delete-image" href="#"><?php esc_html_e( 'Remove Image', 'adace' ); ?></a>
								<input class="adace-image-id" id="adace_ad_image" name="adace_ad_image" type="hidden" value="<?php echo esc_attr( $custom['adace_ad_image'] ); ?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Image HDPI', 'adace' ); ?></th>
						<td>
							<div class="adace-image-upload">
								<a class="adace button-secondary adace-add-image" href="#"><?php esc_html_e( 'Add Image', 'adace' ); ?></a>
								<div class="adace-image">
									<?php if ( ! empty( $custom['adace_ad_image_retina'] ) ) :  ?>
										<?php echo wp_get_attachment_image( $custom['adace_ad_image_retina'] , 'medium' ); ?>
									<?php endif; ?>
								</div>
								<a class="button button-secondary adace-delete-image" href="#"><?php esc_html_e( 'Remove Image', 'adace' ); ?></a>
								<input class="adace-image-id" id="adace_ad_image_retina" name="adace_ad_image_retina" type="hidden" value="<?php echo esc_attr( $custom['adace_ad_image_retina'] ); ?>" />
							</div>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><div class="description">An image for High DPI screen (like Retina) should be twice as big</div></td>
					</tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Link To', 'adace' ); ?></th>
                        <td>
                            <?php
                            $wc_disabled = ! adace_can_use_plugin( 'woocommerce/woocommerce.php' );
                            $wc_disabled_info = $wc_disabled ? sprintf( esc_html_x( 'Activate %s plugin to use that option', 'Link Type Disabled Info', 'adace' ), 'WooCommerce' ) : '';

                            if ( ! $wc_disabled && adace_ad_free_get_wp_product_id() <= 0 ) {
                                $wc_disabled_info = sprintf( esc_html_x( 'WooCommerce product not selected. Go to the %s', 'Link Type Disabled Info', 'adace' ), '<a href="'. esc_url( admin_url( 'options-general.php?page=adace_options&tab=adace_ad_free' ) ) .'" target="_blank">'. esc_html__( 'Settings', 'adace' ) .'</a>' );
                            }

                            $rcp_disabled = ! adace_can_use_plugin( 'restrict-content-pro/restrict-content-pro.php' );;
                            $rcp_disabled_info = $rcp_disabled ? sprintf( esc_html_x( 'Activate %s plugin to use that option', 'Link Type Disabled Info', 'adace' ), 'Restrict Content Pro' ) : '';
                            ?>
                            <select style="width:100%;" name="adace_ad_link_type" id="adace_ad_link_type">
                                <option value=""<?php selected( '', $custom['adace_ad_link_type'] ); ?>><?php echo esc_html_x( 'Custom Link', 'Custom Ad Type', 'adace' ); ?></option>
                                <option value="login"<?php selected( 'login', $custom['adace_ad_link_type'] ); ?>><?php echo esc_html_x( 'Login Page or Popup', 'Custom Ad Type', 'adace' ); ?></option>
                                <option value="wc_free_ad_product"<?php selected( 'wc_free_ad_product', $custom['adace_ad_link_type'] ); ?>><?php echo esc_html_x( 'Ad Free Product (WooCommerce)', 'Custom Ad Type', 'adace' ); ?></option>
                                <option value="rcp_register"<?php selected( 'rcp_register', $custom['adace_ad_link_type'] ); ?>><?php echo esc_html_x( 'Register page (Restrict Content Pro)', 'Custom Ad Type', 'adace' ); ?></option>
                            </select>
                            <?php if ( $wc_disabled_info ): ?>
                            <div class="adace-link-to-notice notice notice-warning inline" style="display: none;" data-ref-ad-link-type="wc_free_ad_product">
                                <p>
                                    <?php echo $wc_disabled_info; ?>
                                </p>
                            </div>
                            <?php endif; ?>

                            <?php if ( $rcp_disabled_info ): ?>
                            <div class="adace-link-to-notice notice notice-warning inline" style="display: none;" data-ref-ad-link-type="rcp_register">
                                <p>
                                    <?php echo $rcp_disabled_info; ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Custom Link', 'adace' ); ?></th>
						<td>
							<input type="text" style="width:100%;" name="adace_ad_link" id="adace_ad_link" value="<?php echo( esc_url( $custom['adace_ad_link'] ) ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Code', 'adace' ); ?></th>
						<td>
							<textarea style="width:100%;" rows="10" name="adace_ad_content" id="adace_ad_content"><?php echo( wp_kses_post( $custom['adace_ad_content'] ) ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Run code asynchronously', 'adace' ); ?></th>
						<td>
							<input type="checkbox" name="adace_ad_content_async" id="adace_ad_content_async" value="standard"<?php checked( 'standard', $custom['adace_ad_content_async'] ) ?>>
                            <p class="description">
                                <?php esc_html_e( 'Uncheck if your ad to run requires page "onLoad" event. When unchecked, the "Display on devices" options below have no effect! Leave checked if you\'re not sure.', 'adace' ); ?>
                            </p>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
		</fieldset>
	<?php
}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_meta_box_render_disable_callback( $post ) {
	$settings	  								= get_post_meta( $post -> ID, 'adace_general', true );
	$settings = wp_parse_args( $settings, adace_get_default_general_ad_settings() );

	?>
		<fieldset id="adace-ad-settings">

			<div class="adace-disable-section adace-settings-section">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable on desktop', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_desktop" id="adace_disable_desktop" type="checkbox" <?php checked( $settings['adace_disable_desktop'] ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable on tablet landscape', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_landscape" id="adace_disable_landscape" type="checkbox" <?php checked( $settings['adace_disable_landscape'] ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable on tablet portrait', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_portrait" id="adace_disable_portrait" type="checkbox" <?php checked( $settings['adace_disable_portrait'] ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable on phone', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_phone" id="adace_disable_phone" type="checkbox" <?php checked( $settings['adace_disable_phone'] ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable on AMP', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_amp" id="adace_disable_amp" type="checkbox" <?php checked( $settings['adace_disable_amp'] ); ?> />
						</td>
					</tr>
				</tbody>
			</table>
			</div>

		</fieldset>
	<?php
}
/**
 * Generate admin markup for a breakpoint
 *
 * @param str   $breakpoint Breakpoint name.
 * @param array $adsense    Ad adsense settings.
 */
function adace_render_breakpoint_markup( $breakpoint, $adsense ) {
?>

					<tr class="adace_adsense_size">
						<th scope="col"><?php esc_html_e( 'Use cusom size for', 'adace' ); ?> <?php adace_render_breakpoint_name( $breakpoint ); ?></th>
						<th scope="col"><?php esc_html_e( 'Width', 'adace' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Height', 'adace' ); ?></th>
					</tr>
					<tr class="adace_adsense_size">
						<td>
							<input name="adace_adsense_use_size_<?php echo esc_attr($breakpoint);?>" id="adace_adsense_use_size_<?php echo esc_attr($breakpoint);?>" type="checkbox"
							<?php checked( $adsense[ 'adace_adsense_use_size_' . $breakpoint ] ); ?> />
						</td>
						<td class="adace_adsense_size_<?php echo esc_attr($breakpoint);?>">
							<input class="small-text" type="number" style="width:50%;" name="adace_adsense_width_<?php echo esc_attr($breakpoint);?>" id="adace_adsense_width_<?php echo esc_attr($breakpoint);?>" value="<?php echo( intval( $adsense['adace_adsense_width_' . $breakpoint ] ) ); ?>">
						</td>
						<td class="adace_adsense_size_<?php echo esc_attr($breakpoint);?>">
							<input class="small-text" type="number" style="width:50%;" name="adace_adsense_height_<?php echo esc_attr($breakpoint);?>" id="adace_adsense_height_<?php echo esc_attr($breakpoint);?>" value="<?php echo( intval( $adsense['adace_adsense_height_' . $breakpoint ] ) ); ?>">
						</td>
					</tr>
<?php
}

/**
 * Generate admin markup for a breakpoint
 *
 * @param str $breakpoint Breakpoint name.
 */
function adace_render_breakpoint_name( $breakpoint ) {
	switch ( $breakpoint ) {
		case 'phone':
			esc_html_e( 'phone', 'adace' );
			break;
		case 'portrait':
			esc_html_e( 'tablet portrait', 'adace' );
			break;
		case 'landscape':
			esc_html_e( 'tablet landscape', 'adace' );
			break;
		case 'desktop':
			esc_html_e( 'desktop', 'adace' );
			break;
		break;
	}
}

add_action( 'save_post', 'adace_meta_boxes_data_save' );
/**
 * Meta box saver.
 *
 * @param string $post_id Post id.
 */
function adace_meta_boxes_data_save( $post_id ) {
    // Nonce sent?
    $nonce = filter_input( INPUT_POST, 'adace_save_ad_meta_nonce', FILTER_SANITIZE_STRING );

    if ( ! $nonce ) {
        return;
    }

    // Verify that nonce.
    if ( ! wp_verify_nonce( $nonce, adace_get_plugin_basename() ) ) {
        return;
    }

	// Sanitize args.
	$args = filter_input_array( INPUT_POST,
		array(
			'adace_ad_type'			 	=> FILTER_SANITIZE_STRING,
			'adace_exclude_from_random'	=> FILTER_VALIDATE_BOOLEAN,
			'adace_adsense_pub'			=> FILTER_SANITIZE_STRING,
			'adace_adsense_slot'		=> FILTER_SANITIZE_STRING,
			'adace_adsense_type'		=> FILTER_SANITIZE_STRING,
			'adace_adsense_format'		=> FILTER_SANITIZE_STRING,
			'adace_adsense_width'		=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_height'		=> FILTER_SANITIZE_NUMBER_INT,
			'post_type'                	=> FILTER_SANITIZE_STRING,
			'adace_ad_image'           	=> FILTER_SANITIZE_STRING,
			'adace_ad_image_retina'     => FILTER_SANITIZE_STRING,
			'adace_ad_link'            	=> FILTER_SANITIZE_URL,
			'adace_ad_link_type'        => FILTER_SANITIZE_STRING,
			'adace_ad_content'         	=> FILTER_SANITIZE_SPECIAL_CHARS,
			'adace_ad_content_async'   	=> FILTER_SANITIZE_STRING,
			'adace_disable_desktop'		=> FILTER_VALIDATE_BOOLEAN,
			'adace_disable_landscape'	=> FILTER_VALIDATE_BOOLEAN,
			'adace_disable_portrait'	=> FILTER_VALIDATE_BOOLEAN,
			'adace_disable_phone'		=> FILTER_VALIDATE_BOOLEAN,
			'adace_disable_amp'			=> FILTER_VALIDATE_BOOLEAN,
			'adace_adsense_use_size_desktop'	=> FILTER_VALIDATE_BOOLEAN,
			'adace_adsense_use_size_landscape'	=> FILTER_VALIDATE_BOOLEAN,
			'adace_adsense_use_size_portrait'	=> FILTER_VALIDATE_BOOLEAN,
			'adace_adsense_use_size_phone' 		=> FILTER_VALIDATE_BOOLEAN,
			'adace_adsense_width_desktop'		=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_width_landscape' 	=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_width_portrait'	 	=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_width_phone'	  		=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_height_desktop'	 	=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_height_landscape'	=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_height_portrait' 	=> FILTER_SANITIZE_NUMBER_INT,
			'adace_adsense_height_phone'  		=> FILTER_SANITIZE_NUMBER_INT,
		)
	);

	// Check if post_type is correct.
	if ( 'adace-ad' !== $args['post_type'] ) {
		return;
	}
	// If user can edit this type.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$general = array(
		'adace_ad_type' 			=> $args['adace_ad_type'],
		'adace_exclude_from_random' => $args['adace_exclude_from_random'],
		'adace_disable_desktop' 	=> $args['adace_disable_desktop'],
		'adace_disable_landscape' 	=> $args['adace_disable_landscape'],
		'adace_disable_portrait' 	=> $args['adace_disable_portrait'],
		'adace_disable_phone' 		=> $args['adace_disable_phone'],
		'adace_disable_amp' 		=> $args['adace_disable_amp'],
	);
	update_post_meta( $post_id, 'adace_general', $general );

	$adsense = array(
		'adace_adsense_pub' 				=> $args['adace_adsense_pub'],
		'adace_adsense_slot' 				=> $args['adace_adsense_slot'],
		'adace_adsense_type' 				=> $args['adace_adsense_type'],
		'adace_adsense_format' 				=> $args['adace_adsense_format'],
		'adace_adsense_width' 				=> $args['adace_adsense_width'],
		'adace_adsense_height'	 			=> $args['adace_adsense_height'],
		'adace_adsense_use_size_desktop'	=> $args['adace_adsense_use_size_desktop'],
		'adace_adsense_use_size_landscape'	=> $args['adace_adsense_use_size_landscape'],
		'adace_adsense_use_size_portrait'	=> $args['adace_adsense_use_size_portrait'],
		'adace_adsense_use_size_phone' 		=> $args['adace_adsense_use_size_phone'],
		'adace_adsense_width_desktop'		=> $args['adace_adsense_width_desktop'],
		'adace_adsense_width_landscape' 	=> $args['adace_adsense_width_landscape'],
		'adace_adsense_width_portrait'	 	=> $args['adace_adsense_width_portrait'],
		'adace_adsense_width_phone'	  		=> $args['adace_adsense_width_phone'],
		'adace_adsense_height_desktop'	 	=> $args['adace_adsense_height_desktop'],
		'adace_adsense_height_landscape'	=> $args['adace_adsense_height_landscape'],
		'adace_adsense_height_portrait' 	=> $args['adace_adsense_height_portrait'],
		'adace_adsense_height_phone'  		=> $args['adace_adsense_height_phone'],
	);
	update_post_meta( $post_id, 'adace_adsense', $adsense );

	$custom = array(
		'adace_ad_image_retina'	=> $args['adace_ad_image_retina'],
		'adace_ad_image'			=> $args['adace_ad_image'],
		'adace_ad_link'				=> $args['adace_ad_link'],
		'adace_ad_link_type'	    => $args['adace_ad_link_type'],
		'adace_ad_content'			=> $args['adace_ad_content'],
		'adace_ad_content_async'    => $args['adace_ad_content_async'],
	);
	update_post_meta( $post_id, 'adace_custom', $custom );
}

/**
 * Meta box renderer.
 *
 * @param object $post Post.
 */
function adace_options_meta_box_render_callback( $post ) {
	$disable				= get_post_meta( $post -> ID, 'adace_disable', true );
	if ( is_array( $disable ) ) {
		$disable_ad_all_slots	= $disable['adace_disable_all_slots'];
		$disable_ad_slots  		= $disable['adace_disable_slots'];
		$disable_ad_widgets 	= $disable['adace_disable_widgets'];
		$disable_ad_shortcodes 	= $disable['adace_disable_shortcodes'];
	} else {
		$disable_ad_all_slots	= false;
		$disable_ad_slots  		= false;
		$disable_ad_widgets 	= false;
		$disable_ad_shortcodes 	= false;
	}
	$adace_ad_slots = adace_access_ad_slots();
?>
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable all ad slots', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_all_slots" id="adace_disable_all_slots" type="checkbox" <?php checked( $disable_ad_all_slots ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable ad widgets', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_widgets" id="adace_disable_widgets" type="checkbox" <?php checked( $disable_ad_widgets ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable all ad shortcodes', 'adace' ); ?></th>
						<td>
							<input name="adace_disable_shortcodes" id="adace_disable_shortcodes" type="checkbox" <?php checked( $disable_ad_shortcodes ); ?> />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Disable ad slots:', 'adace' ); ?></th>
						<td>
							<?php
							$adace_ad_sections = adace_access_ad_sections();
							$adace_ad_sections[] = array(
								'slug' => 'default',
								'label' => __( 'Various', 'adace' ),
							);
							foreach ( $adace_ad_sections as $ad_section ) :?>
								<div class="adace-post-ad-section">
								<h3><?php echo wp_kses_post( $ad_section['label'] ); ?></h3>
								<?php
								foreach ( $adace_ad_slots as $slot ) :
									$slot_id 	= $slot['id'];
									if ( $adace_ad_slots[ $slot_id ]['section'] === $ad_section['slug']) :
										if ( isset( $disable_ad_slots[ $slot_id ] ) ) {
											$checked = $disable_ad_slots[ $slot_id ];
										} else {
											$checked = false;
										}
										$name 		= 'adace_disable_' . $slot_id;
										$label 		= $slot['name'];?>
										<p>
										<label for="<?php echo esc_attr( $name ); ?>">
										<input 	name="adace_disable_slot-<?php echo esc_attr( $slot_id ); ?>"
												id="<?php echo esc_attr( $name ); ?>"
												type="checkbox" <?php checked( $checked ); ?> />
												<?php echo esc_html( $label ); ?>
										</label></p>
									<?php endif; ?>
								<?php endforeach; ?>
								</div>
							<?php endforeach; ?>

						</td>
					</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( adace_get_plugin_basename(),'adace_save_ad_options_nonce' ); ?>
		</fieldset>
<?php
}

add_action( 'save_post', 'adace_post_meta_box_data_save' );
/**
 * Meta box saver.
 *
 * @param string $post_id Post id.
 */
function adace_post_meta_box_data_save( $post_id ) {
	// Sanitize args.
	$disable_ad_all_slots	= filter_input( INPUT_POST, 'adace_disable_all_slots', FILTER_VALIDATE_BOOLEAN );
	$disable_ad_widgets 	= filter_input( INPUT_POST, 'adace_disable_widgets', FILTER_VALIDATE_BOOLEAN );
	$disable_ad_shortcodes 	= filter_input( INPUT_POST, 'adace_disable_shortcodes', FILTER_VALIDATE_BOOLEAN );
	$adace_ad_slots = adace_access_ad_slots();;
	$disable_ad_slots  		= array();
	foreach ( $adace_ad_slots as $slot ) {
		$disable_ad_slots[ $slot['id'] ] = filter_input( INPUT_POST, 'adace_disable_slot-' . $slot['id'], FILTER_VALIDATE_BOOLEAN );
	}

	$nonce 		= filter_input( INPUT_POST, 'adace_save_ad_options_nonce', FILTER_SANITIZE_STRING );
	$post_type 	= filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
	// Verify that nonce.
	if ( ! wp_verify_nonce( $nonce, adace_get_plugin_basename() ) ) {
		return;
	}
	// Check if post_type is correct.
	if ( ! array_key_exists( $post_type, adace_get_supported_post_types() ) ) {
		return;
	}
	// If user can edit this type.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$disable = array(
		'adace_disable_all_slots' => $disable_ad_all_slots,
		'adace_disable_slots' => $disable_ad_slots,
		'adace_disable_widgets' => $disable_ad_widgets,
		'adace_disable_shortcodes' => $disable_ad_shortcodes,
		);

	update_post_meta( $post_id, 'adace_disable', $disable );
}

/**
 * Default general ad settings
 *
 * @return array
 */
function adace_get_default_general_ad_settings() {
	$settings = array();
	$settings['adace_ad_type'] 						= 'custom';
	$settings['adace_exclude_from_random'] 			= false;
	$settings['adace_disable_desktop'] 				= false;
	$settings['adace_disable_landscape'] 			= false;
	$settings['adace_disable_portrait'] 			= false;
	$settings['adace_disable_phone'] 				= false;
	$settings['adace_disable_amp'] 					= false;
	return $settings;
}

/**
 * Default general adsense settings
 *
 * @return array
 */
function adace_get_default_adsense_ad_settings() {
	$adsense = array();
	$adsense['adace_adsense_pub']		  			= '';
	$adsense['adace_adsense_slot']		  			= '';
	$adsense['adace_adsense_type']		  			= 'fixed';
	$adsense['adace_adsense_format']	  			= 'auto';
	$adsense['adace_adsense_width']		  			= 0;
	$adsense['adace_adsense_height']				= 0;
	$adsense['adace_adsense_use_size_desktop'] 		= false;
	$adsense['adace_adsense_use_size_landscape'] 	= false;
	$adsense['adace_adsense_use_size_portrait'] 	= false;
	$adsense['adace_adsense_use_size_phone']  		= false;
	$adsense['adace_adsense_width_desktop'] 		= 0;
	$adsense['adace_adsense_width_landscape'] 		= 0;
	$adsense['adace_adsense_width_portrait'] 		= 0;
	$adsense['adace_adsense_width_phone']  			= 0;
	$adsense['adace_adsense_height_desktop'] 		= 0;
	$adsense['adace_adsense_height_landscape'] 		= 0;
	$adsense['adace_adsense_height_portrait'] 		= 0;
	$adsense['adace_adsense_height_phone']  		= 0;
	return $adsense;
}

/**
 * Default general custom ad settings
 *
 * @return array
 */
function adace_get_default_custom_ad_settings() {
	$custom = array();
	$custom['adace_ad_image']   					= '';
	$custom['adace_ad_image_retina']   				= '';
	$custom['adace_ad_link']    					= '';
	$custom['adace_ad_link_type']    				= '';
	$custom['adace_ad_content'] 					= '';
	$custom['adace_ad_content_async']				= 'standard';
	return $custom;
}
