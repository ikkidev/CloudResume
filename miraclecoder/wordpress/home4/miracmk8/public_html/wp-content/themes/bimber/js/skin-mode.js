/*******************
 *
 * Skin Mode
 *
 ******************/
(function ($) {

    'use strict';

    g1.skinSwitcher = function () {
        if ( typeof g1SwitchSkin === 'undefined' ) {
            return;
        }

        $('.g1-drop-the-skin').each( function() {
            var $this = $(this);

            var $skinItemId = $('meta[name="g1:skin-item-id"]');
            var skinItemId = $skinItemId.length > 0 ? $skinItemId.attr('content')  : 'g1_skin';

            if ( localStorage.getItem(skinItemId) ) {
                if ( $this.is( '.g1-drop-the-skin-light' ) ) {
                    $this.removeClass('g1-drop-nojs g1-drop-the-skin-light').addClass( 'g1-drop-the-skin-dark' );
                } else {
                    // Switch to the light mode.
                    $this.removeClass('g1-drop-nojs g1-drop-the-skin-dark').addClass( 'g1-drop-the-skin-light' );
                }
            } else {
                $this.removeClass('g1-drop-nojs');
            }
        });


        $('body').on( 'click', '.g1-drop-the-skin', function() {
            var $this = $(this);

            $this.addClass('g1-drop-the-skin-anim');

            if ( $this.is( '.g1-drop-the-skin-light' ) ) {
                // Switch to the dark skin.
                $this.removeClass('g1-drop-the-skin-light').addClass( 'g1-drop-the-skin-dark' );
                g1SwitchSkin('dark');

            } else {
                // Switch to the light skin.
                $this.removeClass('g1-drop-the-skin-dark').addClass( 'g1-drop-the-skin-light' );
                g1SwitchSkin('light');
            }
        });
    };

    $(document).ready(function () {
        g1.skinSwitcher();
    });

})(jQuery);
