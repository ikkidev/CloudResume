<?php
/**
 * Recent Podcast Widget
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package AdAce
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'widgets_init', 'adace_register_adace_get_my_widget' );
/**
 * Get My widget register function.
 */
function adace_register_adace_get_my_widget() {
	register_widget( 'Adace_Get_My_Widget' );
}

/**
 * About me widget class.
 */
class Adace_Get_My_Widget extends WP_widget {
	/**
	 * Widget contruct.
	 */
	function __construct() {
		parent::__construct(
			'adace_get_my_widget',
			esc_html__( 'AdAce Favourite Products', 'adace' ),
			array(
				'description' => esc_html__( 'Show in fancy way desirable products to Your visitors!', 'adace' ),
			)
		);
	}

	/**
	 * Get default arguments
	 *
	 * @return array
	 */
	public function get_default_args() {
		return apply_filters( 'adace_get_my_widget_defaults', array(
			'title'                  => esc_html__( 'Get my must-haves', 'adace' ),
			'disclosure'             => '',
			'type'                   => 'embed',
			'embed_code'             => '',
			'woo_number_of_products' => 5,
			'woo_category'           => '',
			'woo_orderby'            => 'date',
			'woo_order'              => 'ASC',
		) );
	}

	/**
	 * Widget contruct.
	 *
	 * @param array $instance Current widget settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );
		// Prep array for return.
		$categories_choices     = array();
		// Lets make small doggies cry and add this empty choice.
		$categories_choices[''] = esc_html__( '- All -', 'adace' );
		// Get terms and loop to add them to choices.
		$categories             = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
		) );
		foreach ( $categories as $category_obj ) {
			$categories_choices[ $category_obj->slug ] = $category_obj->name;
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'adace' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_html( $instance['title'] ); ?>"
			/>
		</p>
		<p>
			<input
			class="checkbox" type="checkbox" <?php checked( $instance['disclosure'], true ) ?>
			id="<?php echo esc_attr( $this->get_field_id( 'disclosure' ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( 'disclosure' ) ); ?>"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'disclosure' ) ); ?>"><?php esc_html_e( 'Show affiliate disclosure', 'adace' ); ?> </label>
		</p>
		<div>
			<fieldset
				class="adace-widget-header"
				name="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"
				id="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
			>
				<span class="header-label"><?php esc_html_e( 'Type:', 'adace' ); ?></span>
				<label>
					<input
						type="radio"
						name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
						class="adace-get-my-widget-type embed"
						<?php checked( 'embed', $instance['type'], true ); ?>
						value="embed"
					/>
					<span><?php esc_html_e( 'Embed Code', 'adace' ); ?></span>
				</label>
				<label>
					<input
						type="radio"
						name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>"
						class="adace-get-my-widget-type woocommerce"
						<?php checked( 'woocommerce', $instance['type'], true ); ?>
						<?php echo( esc_attr( adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ? '' : 'disabled' ) ); ?>
						value="woocommerce"
					/>
					<span><?php esc_html_e( 'WooCommerce Products', 'adace' ); ?></span>
				</label>
			</fieldset>
		</div>
		<p class="adace-widget-tab embed <?php echo( esc_attr( 'embed' === $instance['type'] ? 'current' : '' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'embed_code' ) ); ?>">
				<?php esc_html_e( 'Embed Code:', 'adace' ); ?>
			</label>
			<textarea
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'embed_code' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'embed_code' ) ); ?>"
				rows="6"
			><?php echo filter_var( $instance['embed_code'] ); ?></textarea>
		</p>
		<p class="adace-widget-tab woocommerce <?php echo( esc_attr( 'woocommerce' === $instance['type'] ? 'current' : '' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'woo_number_of_products' ) ); ?>">
				<?php esc_html_e( 'Number of products to show', 'adace' ); ?>
			</label>
			<input
				class="widefat "
				id="<?php echo esc_attr( $this->get_field_id( 'woo_number_of_products' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'woo_number_of_products' ) ); ?>"
				type="number"
				step="1"
				min="1"
				max=""
				value="<?php echo( esc_html( $instance['woo_number_of_products'] ) ); ?>"
			>
		</p>
		<p class="adace-widget-tab woocommerce <?php echo( esc_attr( 'woocommerce' === $instance['type'] ? 'current' : '' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'woo_category' ) ); ?>"><?php esc_html_e( 'Categories:', 'adace' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'woo_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'woo_category' ) ); ?>">
				<?php
				foreach ( $categories_choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $value === $instance['woo_category'], true, false ) . '>' . esc_html( $label ) . '</option>';
				}
				?>
			</select>
		</p>
		<p class="adace-widget-tab woocommerce <?php echo( esc_attr( 'woocommerce' === $instance['type'] ? 'current' : '' ) ); ?>">
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'woo_orderby' ) ); ?>"
			>
				<?php esc_html_e( 'Order by', 'adace' ); ?>
			</label>
			<select
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'woo_orderby' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'woo_orderby' ) ); ?>"
			>
				<option value="date" <?php selected( 'date', $instance['woo_orderby'], true ); ?>><?php esc_html_e( 'Date', 'adace' ); ?></option>
				<option value="rand" <?php selected( 'rand', $instance['woo_orderby'], true ); ?>><?php esc_html_e( 'Random', 'adace' ); ?></option>
				<option value="title" <?php selected( 'title', $instance['woo_orderby'], true ); ?>><?php esc_html_e( 'Title', 'adace' ); ?></option>
			</select>
		</p>
		<p class="adace-widget-tab woocommerce <?php echo( esc_attr( 'woocommerce' === $instance['type'] ? 'current' : '' ) ); ?>">
			<label
				for="<?php echo esc_attr( $this->get_field_id( 'woo_order' ) ); ?>">
				<?php esc_html_e( 'Order', 'adace' ); ?>
			</label>
			<select
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'woo_order' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'woo_order' ) ); ?>"
			>
				<option value="asc" <?php selected( 'asc', $instance['woo_order'], true ); ?>><?php esc_html_e( 'ASC', 'adace' ); ?></option>
				<option value="desc" <?php selected( 'desc', $instance['woo_order'], true ); ?>><?php esc_html_e( 'DESC', 'adace' ); ?></option>
			</select>
		</p>
		<?php
	}

	/**
	 * Widget saving.
	 *
	 * @param array $new_instance Current widget settings form output.
	 * @param array $old_instance Old widget settings.
	 */
	public function update( $new_instance, $old_instance ) {
		// Sanitize input.
		$instance                           = array();
		$instance['title']                  = filter_var( $new_instance['title'], FILTER_SANITIZE_STRING );
		$instance['disclosure']             = isset( $new_instance['disclosure'] );
		$instance['type']                   = filter_var( $new_instance['type'], FILTER_SANITIZE_STRING );
		$instance['embed_code']             = filter_var( $new_instance['embed_code'], FILTER_UNSAFE_RAW );
		$instance['woo_number_of_products'] = filter_var( $new_instance['woo_number_of_products'], FILTER_SANITIZE_STRING );
		$instance['woo_category']           = filter_var( $new_instance['woo_category'], FILTER_SANITIZE_STRING );
		$instance['woo_orderby']            = filter_var( $new_instance['woo_orderby'], FILTER_SANITIZE_STRING );
		$instance['woo_order']              = filter_var( $new_instance['woo_order'], FILTER_SANITIZE_STRING );
		return $instance;
	}


	/**
	 * Query the products and return them.
	 * @param  array $args
	 * @param  array $instance
	 * @return WP_Query
	 */
	public function get_products( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->get_default_args() );

		$query_args = array(
			'posts_per_page' => $instance['woo_number_of_products'],
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'no_found_rows'  => 1,
			'order'          => $instance['woo_order'],
			'meta_query'     => array(),
			'tax_query'      => array(
				'relation' => 'AND',
			),
		);
		switch ( $instance['woo_orderby'] ) {
			case 'rand' :
				$query_args['orderby']  = 'rand';
				break;
			case 'title' :
				$query_args['orderby']  = 'title';
				break;
			default :
				$query_args['orderby']  = 'date';
		}
		if ( ! empty( $instance['woo_category'] ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $instance['woo_category'],
			);
		}

		return new WP_Query( apply_filters( 'adace_get_my_widget_products_query_args', $query_args ) );
	}

	/**
	 * Widget output.
	 *
	 * @param array $args Widget args from registration point.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		// Get settings.
		$instance        = wp_parse_args( $instance, $this->get_default_args() );
		$title           = apply_filters( 'widget_title', $instance['title'] );
		$disclosure      = $instance['disclosure'];
		$disclosure_text = get_option( 'adace_disclosure_text', adace_options_get_defaults( 'adace_disclosure_text' ) );
		$type            = $instance['type'];
		if ( ! adace_can_use_plugin( 'woocommerce/woocommerce.php' ) ) {
			$type = 'embed';
		}
		// Echo all widget elements.
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		// @todo Remove .g1- classes and add them through a theme.
		if ( $disclosure && ! empty( $disclosure_text ) ) {
			echo wp_kses_post( '<p class="adace-disclosure g1-meta g1-meta-s">' . html_entity_decode( $disclosure_text ) . '</p>' );
		}
		if ( 'woocommerce' === $type ) {
			if ( ( $products = $this->get_products( $args, $instance ) ) && $products->have_posts() ) {

				echo wp_kses_post( apply_filters( 'woocommerce_before_widget_product_list', '<ul class="product_list_widget">' ) );

				$template_args = array(
					'widget_id'   => $args['widget_id'],
					'show_rating' => false,
				);

				while ( $products->have_posts() ) {
					$products->the_post();
					wc_get_template( 'content-widget-product.php', $template_args );
				}

				echo wp_kses_post( apply_filters( 'woocommerce_after_widget_product_list', '</ul>' ) );
			}

			wp_reset_postdata();
		}
		if ( 'embed' === $type ) {
			echo filter_var( '<div class="adace_get_my_widget_embed">' . do_shortcode( $instance['embed_code'] ) . '</div>' );
		}
		echo wp_kses_post( $args['after_widget'] );
	}
}
