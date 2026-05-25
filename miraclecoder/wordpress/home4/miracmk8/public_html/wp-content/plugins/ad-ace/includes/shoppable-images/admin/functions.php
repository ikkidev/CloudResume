<?php
/**
 * Functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */


add_action( 'admin_enqueue_scripts', 'adace_shoppable_images_admin_enqueue_styles' );
/**
* Enqueue admin CSS
*/
function adace_shoppable_images_admin_enqueue_styles( $hook ) {
	$ver = adace_get_plugin_version();

	wp_enqueue_style( 'adace-shoppable_images', trailingslashit( adace_get_plugin_url() ) . 'includes/shoppable-images/assets/css/shoppable-images-admin.css', array(), $ver, 'screen' );
}

add_action( 'admin_enqueue_scripts', 'adace_shoppable_images_admin_enqueue_scripts' );
/**
* Enqueue admin JS
*
* @param string $hook Page hook.
*/
function adace_shoppable_images_admin_enqueue_scripts( $hook ) {
	// General variables.
	$ajax_variables = array(
		'Endpoint'       => esc_url( admin_url( 'admin-ajax.php' ) ),
		'Nonce'          => wp_create_nonce( 'adace_shoppable_images_nonce' ),
		'GetPinsAction'  => 'adace_get_attachment_pins',
		'SavePinsAction' => 'adace_save_attachment_pins',
		'AttachmentID'   => get_the_id(),
	);
	if ( in_array( $hook, array( 'post.php' ), true ) && 'attachment' === get_post_type() ) {
        if ( ! current_user_can( 'edit_post', get_the_ID() ) ) {
            return;
        }

		$ver = adace_get_plugin_version();

        $config = array(
            'ajax_vars' => $ajax_variables,
            'i18n'      => array(
                'add_pin'               => _x( 'Add Pin', 'Shoppable Images Admin UI', 'ad-ce' ),
                'save_pins'             => _x( 'Save Pins', 'Shoppable Images Admin UI', 'ad-ce' ),
                'delete_pin'            => _x( 'Delete Pin', 'Shoppable Images Admin UI', 'ad-ce' ),
                'edit_pins'             => _x( 'Edit Pins', 'Shoppable Images Admin UI', 'ad-ce' ),
                'pin_nr'                => _x( 'Pin #', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_type'          => _x( 'Product Type', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_type_wc'       => _x( 'WooCommerce', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_type_custom'   => _x( 'Custom', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_name'          => _x( 'Product Name', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_url'           => _x( 'Product URL', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_price'         => _x( 'Product Price', 'Shoppable Images Admin UI', 'ad-ce' ),
                'product_id'            => _x( 'Prodcut ID', 'Shoppable Images Admin UI', 'ad-ce' ),
            ),
        );

		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'adace-shoppable_images', trailingslashit( adace_get_plugin_url() ) . 'includes/shoppable-images/assets/js/shoppable-images-admin.js', array( 'jquery' ), $ver, true );
		wp_localize_script( 'adace-shoppable_images', 'adace_shoppable_images', $config );
	}
}

add_action( 'wp_ajax_adace_get_attachment_pins', 'adace_get_attachment_pins' );
add_action( 'wp_ajax_nopriv_adace_get_attachment_pins', 'adace_get_attachment_pins' );
/**
* Load Attachment Pins
*/
function adace_get_attachment_pins() {
	// Array with answer for JS.
	$answer = array();
	// Sanitize function input.
	$args = filter_input_array( INPUT_POST,
		array(
			'Nonce'        => FILTER_SANITIZE_STRING,
			'AttachmentID' => FILTER_SANITIZE_NUMBER_INT,
		)
	);
	// Verify data.
	if ( false === $args || null === $args ) {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'No data loaded.', 'adace' );
		echo( wp_json_encode( $answer ) );
		exit;
	}
	// Verify nonce.
	if ( wp_verify_nonce( $args['Nonce'], 'adace_shoppable_images_nonce' ) === false ) {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'Nonce is incorrect.', 'adace' );
		echo( wp_json_encode( $answer ) );
		exit;
	}
	// Verify user.
	if ( ! current_user_can( 'edit_posts' ) ) {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'User can\'t do this.', 'adace' );
		echo( wp_json_encode( $answer ) );
		exit;
	}
	// Get attachment pins.
	$answer['status'] = 'success';
	$attachment_pins = get_post_meta( $args['AttachmentID'], 'adace_attachment_pins', true );
	if ( false === $attachment_pins || empty( $attachment_pins ) ) {
		$answer['pins'] = false;
	} else {
		foreach ( $attachment_pins as $key => $pin ) {
			if ( $pin['woocommerce_id'] ) {
				$product              = wc_get_product( $pin['woocommerce_id'] );
				$product_price        = $product->get_price_html();
				$product_type         = $product->get_type();
				if ( 'external' !== $product_type ) {
					$product_permalink  = $product->get_permalink();
				} else {
					$product_permalink = $product->get_product_url();
				}
				$attachment_pins[ $key ]['woo_title'] = get_the_title( $pin['woocommerce_id'] );
				$attachment_pins[ $key ]['woo_price'] = $product_price;
				$attachment_pins[ $key ]['woo_permalink'] = $product_permalink;
				$attachment_pins[ $key ]['woo_thumb'] = get_the_post_thumbnail_url( $pin['woocommerce_id'], 'shop_thumbnail' );
			}
		}
		$answer['pins'] = $attachment_pins;
	}
	echo( wp_json_encode( $answer ) );
	exit;
}

add_action( 'wp_ajax_adace_save_attachment_pins', 'adace_save_attachment_pins' );
add_action( 'wp_ajax_nopriv_adace_save_attachment_pins', 'adace_save_attachment_pins' );
/**
* Save Attachment Pins
*/
function adace_save_attachment_pins() {
	// Array with answer for JS.
	$answer = array();
	// Sanitize function input.
	$args = filter_input_array( INPUT_POST,
		array(
			'Nonce'        => FILTER_SANITIZE_STRING,
			'AttachmentID' => FILTER_SANITIZE_INT,
		)
	);
	// Verify data.
	if ( false === $args || null === $args ) {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'No data loaded.', 'adace' );
		echo( wp_json_encode( $answer ) );
		exit;
	}
	// Verify nonce.
	if ( wp_verify_nonce( $args['Nonce'], 'adace_shoppable_images_nonce' ) === false ) {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'Nonce is incorrect.', 'adace' );
		echo( wp_json_encode( $answer ) );
		exit;
	}
	// Verify user.
	if ( ! current_user_can( 'edit_posts' ) ) {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'User can\'t do this.', 'adace' );
		echo( wp_json_encode( $answer ) );
		exit;
	}
	if ( is_array( $_POST['Pins'] ) ) {
		foreach ( wp_unslash( $_POST['Pins'] ) as $pin_key => $pin ) {
			$args['Pins'][ $pin_key ] = filter_var_array( $pin, array(
				'pos_x'          => FILTER_SANITIZE_FLOAT,
				'pos_y'          => FILTER_SANITIZE_FLOAT,
				'content'        => FILTER_SANITIZE_STRING,
				'type'           => FILTER_SANITIZE_STRING,
				'name'           => FILTER_SANITIZE_STRING,
				'price'          => FILTER_SANITIZE_STRING,
				'url'            => FILTER_SANITIZE_URL,
				'woocommerce_id' => FILTER_SANITIZE_NUMBER_INT,
			));
		}
	} elseif ( null === $args['Pins'] ) {
		$answer['status'] = 'success';
		$answer['message'] = esc_html__( 'Nothing to save, delete meta.', 'adace' );
		delete_post_meta( $args['AttachmentID'], 'adace_attachment_pins' );
		echo( wp_json_encode( $args ) );
		exit;
	}

	$save_is_success = update_post_meta( $args['AttachmentID'], 'adace_attachment_pins', $args['Pins'] );
	if ( true === $save_is_success ) {
		$answer['status'] = 'success';
		$answer['message'] = esc_html__( 'Saved!', 'adace' );
	} else {
		$answer['status'] = 'failure';
		$answer['message'] = esc_html__( 'Save failed!', 'adace' );
	}
	echo( wp_json_encode( $answer ) );
	exit;
}
