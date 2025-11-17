<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form class="woocommerce-ordering" method="get">
	<select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>

<script>
	if (typeof jQuery === 'function') {
		(function($) {
			var $form = $('body.home form.woocommerce-ordering');

			if ($form.length === 0) {
				return;
			}

			var $tabs = $('<div class="g1-tabs">');
			var $ul = $('<ul class="g1-tab-items">');

			$form.find('select.orderby option').each(function() {
				var $option = $(this);
				var $li = $('<li class="g1-tab-item"></li>');

				if ($option.is(':selected')) {
					$li.addClass('g1-tab-item-current');
				}

				var $a = $('<a class="g1-tab">' + $option.text() + '</a>');

				$a.on('click', function(e) {
					e.preventDefault();
					$option.parent('select').val($option.val()).change();
				});

				$a.appendTo($li);
				$li.appendTo($ul);
			});

			$ul.appendTo($tabs);
			$tabs.insertBefore($form);
			$form.hide();
		})(jQuery);
	}
</script>

<?php wp_enqueue_script( 'bimber-tabs' ); ?>
