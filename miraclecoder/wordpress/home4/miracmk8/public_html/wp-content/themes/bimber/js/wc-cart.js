(function ($) {

    'use strict';

    // Update WooCommerce mini cart.

    // Trigger event
    $( document.body ).on( 'adding_to_cart', function() {
        $('.g1-drop-toggle-badge').removeClass('g1-drop-toggle-badge-animate');
    } );

    $( document.body ).on( 'added_to_cart removed_from_cart', function() {
        // the event is called BEFORE the cart AJAX comes back so we need a small timeout or we'll get the wrong count.
        setTimeout(function() {
            var $drop = $('.g1-drop-the-cart');

            var count = parseInt( $drop.find( '.cart_list').data('g1-cart-count'), 10 );
            if ( count > 0 ) {
                $drop.find('.g1-drop-toggle-badge').removeClass('g1-drop-toggle-badge-hidden').addClass('g1-drop-toggle-badge-animate').text(count);
            } else {
                $drop.find('.g1-drop-toggle-badge').addClass('g1-drop-toggle-badge-hidden').text(count);
            }
        }, 500);
    } );

    // Add our custom class to the link "View Cart" after product was added to the cart.
    // The "View Cart" link is located on product list, under the "Add to Cart" button.
    $(document.body).on('wc_cart_button_updated', function (e, $button) {
        $button.next('a.added_to_cart').addClass('g1-link g1-link-right');
    });

})(jQuery);
