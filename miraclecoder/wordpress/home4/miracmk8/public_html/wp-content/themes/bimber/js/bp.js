/********************************
 *
 * BuddyPress input placeholders
 *
 ********************************/

(function ($) {
    'use strict';

    $( 'input#bp-login-widget-user-login' ).attr( 'placeholder', $( 'label[for="bp-login-widget-user-login"]' ).text() );
    $( 'input#bp-login-widget-user-pass' ).attr( 'placeholder', $( 'label[for="bp-login-widget-user-pass"]' ).text() );

})(jQuery);