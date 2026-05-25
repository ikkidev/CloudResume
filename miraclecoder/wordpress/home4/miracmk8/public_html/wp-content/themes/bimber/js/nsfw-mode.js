/*******************
 *
 * NSFW Mode
 *
 ******************/
(function ($) {

    'use strict';

    g1.nsfwSwitcher = function () {
        if ( typeof g1SwitchNSFW === 'undefined' ) {
            return;
        }

        $('.g1-drop-the-nsfw').each( function() {
            var $this = $(this);

            var $nsfwItemId = $('meta[name="g1:nsfw-item-id"]');
            var nsfwtemId = $nsfwItemId.length > 0 ? $nsfwItemId.attr('content')  : 'g1_nsfw_off';

            if ( localStorage.getItem(nsfwtemId) ) {
                $this.removeClass('g1-drop-the-nsfw-on').addClass( 'g1-drop-the-nsfw-off' );
            }

        } );

        $('.g1-drop-the-nsfw').on( 'click', function() {
            var $this = $(this);

            if ( $this.is( '.g1-drop-the-nsfw-on' ) ) {
                $this.removeClass('g1-drop-the-nsfw-on').addClass( 'g1-drop-the-nsfw-off' );
                g1SwitchNSFW(1);

            } else {
                $this.removeClass('g1-drop-the-nsfw-off').addClass( 'g1-drop-the-nsfw-on' );
                g1SwitchNSFW(0);
            }
        });
    };

    $(document).ready(function () {
        g1.nsfwSwitcher();
    });

})(jQuery);
