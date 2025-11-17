
/********************************
 *
 * BuddyPress Follow Button
 *
 ********************************/

(function ($) {

    'use strict';

    var follow = function( scope ) {
        var $link   = scope;
        var uid    = $link.attr('id');
        var nonce  = $link.attr('href');
        var action = '';

        uid    = uid.split('-');
        action = uid[0];
        uid    = uid[1];

        nonce = nonce.split('?_wpnonce=');
        nonce = nonce[1].split('&');
        nonce = nonce[0];

        $.post( ajaxurl, {
                action: 'bp_' + action,
                'uid': uid,
                '_wpnonce': nonce
            },
            function(response) {
                var $newLink = $(response);

                var classStr = $link.attr('class');

                // Strip follow/unfollow class.
                classStr = classStr.replace( action + ' ', '' );

                $newLink.addClass(classStr);

                $link.replaceWith($newLink);
            });
    };

    g1.bpFollow = function () {
        $('body').on('click', '.g1-bp-action.follow,.g1-bp-action.unfollow', function() {
            follow( $(this) );

            return false;
        });
    };


    $(document).ready(function () {
        g1.bpFollow();
    });

})(jQuery);
