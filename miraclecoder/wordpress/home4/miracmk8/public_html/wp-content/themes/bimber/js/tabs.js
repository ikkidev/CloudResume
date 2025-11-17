(function ($, i18n) {

    'use strict';

    var selectors = {
        'items':        '> li:not(.g1-drop):not(.g1-delimiter):not(li.g1-tab-item-current)'
    };

    var classes = {
        'hidden':       'hidden'
    };

    var isRTL = g1.isRTL();

    g1.tabs = function () {
        // Define reverse function as jQuery plugin.
        $.fn.g1Reverse = [].reverse;

        $('.g1-tabs > .g1-tab-items').each(function() {
            var $ul             = $(this);
            var $liMore         = $('<li class="g1-drop g1-drop-before g1-drop-the-more">');
            var $liMoreToggle   = $('<a class="g1-drop-toggle" href="#"><i class="g1-drop-toggle-icon"></i><span class="g1-drop-toggle-text>"' + i18n.more_link + '</i><span class="g1-drop-toggle-arrow"></span></a>');
            var $liMoreContent  = $('<div class="g1-drop-content"></div>' );
            var $liMoreSubmenu  = $('<ul class="sub-menu"></ul>');
            var $liDelimiter    = $('<li class="g1-delimiter">');

            var maxWidth        = $ul.width() - 40;
            if ($ul.prop('scrollWidth') <= $ul.width() ) {
                return;
            }

            $liMore.
                append($liMoreToggle);
            $ul.
                append($liMore).
                append($liDelimiter);

            $ul.find(selectors.items).g1Reverse().each(function(index) {
                var $this = $(this);

                if ( isRTL) {
                    if ( $liMore.position().left < 0) {
                        // Adjust HTML markup.
                        $this.addClass('menu-item');

                        $liMoreSubmenu.prepend( $this);
                    } else if (0 === index) {
                        $liMore.toggleClass(classes.hidden);
                        $liDelimiter.toggleClass(classes.hidden);

                        return false;
                    } else {
                        if ( $liDelimiter.position().left < 0 ) {
                            // Adjust HTML markup.
                            $this.addClass('menu-item');

                            $liMoreSubmenu.prepend( $this);
                        }
                    }
                } else {
                    if ( $liMore.position().left > maxWidth) {
                        // Adjust HTML markup.
                        $this.addClass('menu-item');

                        $liMoreSubmenu.prepend( $this);
                    } else if (0 === index) {
                        $liMore.toggleClass(classes.hidden);
                        $liDelimiter.toggleClass(classes.hidden);

                        return false;
                    } else {
                        if ( $liDelimiter.position().left > maxWidth ) {
                            // Adjust HTML markup.
                            $this.addClass('menu-item');

                            $liMoreSubmenu.prepend( $this);
                        }
                    }
                }
            });

            $liMoreSubmenu.find('.g1-tab').removeClass('g1-tab');
            $liMoreContent.append($liMoreSubmenu);
            $liMore.append($liMoreContent);
            $liDelimiter.toggleClass(classes.hidden);
            $ul.addClass('g1-dropable');


        });
    };

    $(document).ready(function () {
        g1.tabs();
    });

})(jQuery, g1.config.i18n.bp_profile_nav);
