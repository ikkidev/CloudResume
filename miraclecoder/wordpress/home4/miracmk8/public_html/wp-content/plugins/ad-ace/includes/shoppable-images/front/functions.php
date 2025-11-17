<?php
/**
 * Functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

add_action( 'wp_enqueue_scripts', 'adace_shoppable_image_front_enqueue_styles' );
/**
* Register Front Styles
*/
function adace_shoppable_image_front_enqueue_styles() {
	$ver = adace_get_plugin_version();

	wp_enqueue_style( 'shoppable-images-css', trailingslashit( adace_get_plugin_url() ) . 'assets/css/shoppable-images-front.min.css', array(), $ver );
}

add_action( 'wp_enqueue_scripts', 'adace_shoppable_image_front_enqueue_scripts' );
/**
* Register Front Styles
*/
function adace_shoppable_image_front_enqueue_scripts() {
	$ver = adace_get_plugin_version();

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'shoppable-images-js', trailingslashit( adace_get_plugin_url() ) . 'includes/shoppable-images/assets/js/shoppable-images-front.js', array( 'jquery' ), $ver, false );
}

add_filter( 'adace_amp_add_shoppable_image_styles', 'adace_amp_add_shoppable_image_styles' );
/**
 * Add shoppable CSS to amp styles.
 *
 * @param string $styles  Styles string.
 * @return string
 */
function adace_amp_add_shoppable_image_styles( $styles ) {
	$shoppable_styles = file_get_contents( trailingslashit( adace_get_plugin_url() ) . 'assets/css/shoppable-images-front.amp.min.css' );
	if ( ! empty( $shoppable_styles ) ) {
		$styles = $styles . $shoppable_styles;
	}
	return $styles;
}

add_shortcode( 'adace_shoppable_image', 'adace_shoppable_image_shortcode' );
/**
* Image Map (shortcode).
*
* @param array $atts Shortcode attributes.
* @return string HTML
*/
function adace_shoppable_image_shortcode( $atts ) {
	// Fill shortcode atts.
	$atts_filled = shortcode_atts(
		array(
			'attachment'         => '',
		),
		$atts,
		'adace_shoppable-image'
	);
	// Sanitize shortcode atts.
	$image_url   = filter_var( $atts_filled['attachment'], FILTER_SANITIZE_NUMBER_INT );
	$shoppable_image_id = filter_var( $atts_filled['attachment'], FILTER_SANITIZE_STRING );
	// Get Pins Shortcodes array.
	$attachment_pins = get_post_meta( $atts_filled['attachment'], 'adace_attachment_pins', true );
	// Get Options
	$animate_pins = get_option( 'adace_shoppable_images_animate_pins', adace_options_get_defaults( 'adace_shoppable_images_animate_pins' ) );
	// Check if any pins are added.
	if ( empty( $attachment_pins ) ) {
		return '';
	}
	// Sort array on output.
	foreach ( $attachment_pins as $key => $value ) {
		$sort_data[ $key ] = $value['pos_y'];
	}
	array_multisort( $sort_data, SORT_ASC, $attachment_pins );
	// Shortcode output capture.
	ob_start();
	?>
	<div id="shoppable-image-<?php echo( esc_attr( $shoppable_image_id ) ); ?>" class="shoppable-image"><style scoped="scoped">
			<?php foreach ( $attachment_pins as $pin_key => $pin ) : ?>
				#shoppable-image-<?php echo( esc_attr( $shoppable_image_id ) ); ?> .shoppable-image-pin-<?php echo( esc_attr( $pin_key ) ); ?>{
					<?php echo( esc_html( 'left:' . $pin['pos_x'] . '%;top:' . $pin['pos_y'] . '%;' ) ); ?>
				}
			<?php endforeach; ?>
		</style>
		<div class="shoppable-image-map">
			<img class="shoppable-image-image" src="<?php echo esc_url( wp_get_attachment_image_url( $atts_filled['attachment'], 'original' ) ); ?>" alt="" />
			<?php foreach ( $attachment_pins as $pin_key => $pin ) : ?>
				<div class="shoppable-image-pin shoppable-image-pin-<?php echo( esc_attr( $pin_key ) ); ?>">
					<?php if ( '1' === $animate_pins ) : ?>
						<span class="shoppable-image-pin-pulse"></span>
					<?php endif; ?>
					<span class="shoppable-image-pin-icon"></span>
					<div class="shoppable-image-pin-body">
						<?php
						if ( 'custom_product' === $pin['type'] ) {
							if ( ! empty( $pin['name'] ) ) {
								echo( wp_kses_post( '<h5 class="g1-delta g1-delta-1st pin-name">' . $pin['name'] . '</h5>' ) );
							}
							if ( ! empty( $pin['price'] ) ) {
								echo( wp_kses_post( '<p class="pin-price">' . $pin['price'] . '</p>' ) );
							}
							if ( ! empty( $pin['url'] ) ) {
								echo( wp_kses_post( '<a class="g1-button g1-button-s g1-button-simple pin-url" href="' . $pin['url'] . '" target="_blank">' . esc_html__( 'Buy from here', 'adace' ) . '</a>' ) );
							}
						}
						if ( 'woocommerce' === $pin['type'] && adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
							$pin_product   = wc_get_product( $pin['woocommerce_id'] );

							if ( $pin_product ) {
								$pin_name      = $pin_product->get_name();
								$pin_price     = $pin_product->get_price_html();
								$product_type  = $pin_product->get_type();
								$product_thumb = get_the_post_thumbnail_url( $pin['woocommerce_id'], 'woocommerce_thumbnail' );
								if ( 'external' !== $product_type ) {
									$product_permalink = $pin_product->get_permalink();
									$product_target    = '_self';
								} else {
									$product_external_url = $pin_product->get_product_url();
									$product_permalink    = empty( $product_external_url ) ? $pin_product->get_permalink() : $product_external_url;
									$product_target       = '_blank';
								}
								if ( ! empty( $product_thumb ) ) {
									echo( wp_kses_post( '<p class="pin-thumb"><img width="120" height="120" src="' . esc_url( $product_thumb ) . '" alt="" /></p>' ) );
								}
								if ( ! empty( $pin_name ) ) {
									echo( wp_kses_post( '<h5 class="g1-delta g1-delta-1st pin-name">' . $pin_name . '</h5>' ) );
								}
								if ( ! empty( $pin_price ) ) {
									echo( wp_kses_post( '<p class="pin-price">' . $pin_price . '</p>' ) );
								}
								if ( ! empty( $product_permalink ) ) {
									echo( wp_kses_post( '<a class="g1-button g1-button-s g1-button-simple pin-url" href="' . $product_permalink . '" target="' . $product_target . '" target="_blank">' . esc_html__( 'Buy from here', 'adace' ) . '</a>' ) );
								}
							}
						}
						?>
					</div>
				</div>
			<?php endforeach; ?>
			<a href="#" class="shoppable-image-hideshow-btn">
				<span class="hideshow-label"><?php esc_html_e( 'Pins', 'adace' ); ?></span>
				<div class="hideshow-track"><span class="hideshow-dot"></span></div>
			</a>
		</div>
		<ol class="shoppable-image-items">
			<?php foreach ( $attachment_pins as $pin_key => $pin ) : ?>
				<li class="shoppable-image-item">
					<?php
					if ( 'custom_product' === $pin['type'] ) {
						if ( ! empty( $pin['name'] ) && ! empty( $pin['url'] ) ) {
							echo( wp_kses_post( '<a  href="' . $pin['url'] . '" target="_blank">' . $pin['name'] . '</a>' ) );
						}
					}
					if ( 'woocommerce' === $pin['type'] && adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
						$pin_product = wc_get_product( $pin['woocommerce_id'] );

						if ( $pin_product ) {
							$pin_name = $pin_product->get_name();
							$product_type         = $pin_product->get_type();
							$product_external_url = $pin_product->get_product_url();
							if ( 'external' !== $product_type || empty( $product_external_url ) ) {
								$product_permalink  = $pin_product->get_permalink();
							} else {
								$product_permalink  = $product_external_url;
							}
							if ( ! empty( $pin_name ) && ! empty( $product_permalink ) ) {
								echo( wp_kses_post( '<a  href="' . $product_permalink . '" target="_blank">' . $pin_name . '</a>' ) );
							} else {
								echo( wp_kses_post( '<span>' . $pin_name . '</span>' ) );
							}
						}
					}
					?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
	<?php
	$adace_shoppable_image_shortcode_output = ob_get_clean();
	return apply_filters( 'adace_shoppable_image_shortcode', $adace_shoppable_image_shortcode_output );
}
