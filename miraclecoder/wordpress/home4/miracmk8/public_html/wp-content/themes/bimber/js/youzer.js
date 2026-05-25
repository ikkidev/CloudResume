/***********************
 *
 * Youzer integration
 *
 ***********************/

(function ($) {

    'use strict';

    if ( bimber_youzer.login_popup_active ) {
        window.snaxLoginRequiredHandler =  function(onClose) {
            var $popup = $( '.yz-popup-login' );

            // Show popup.
            $popup.addClass( 'yz-is-visible' );

            // Trigger onClose callback.
            if (typeof onClose === 'function') {
                $popup.on('click', function(e) {
                    if ($(e.target).is( '.yz-close-login' ) || $(e.target).is('.yz-popup-login')) {
                        onClose();
                    }
                });
            }
        };
    }

})(jQuery);