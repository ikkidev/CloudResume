(function ($, i18n) {

    'use strict';

    var selectors = {
        'items':        '> li:not(.g1-drop):not(.g1-delimiter):not(li.selected):not(li.current)'
    };

    var classes = {
        'hidden':       'hidden'
    };

    var isRTL = g1.isRTL();

    g1.bpProfileNav = function () {
        // Define reverse function as jQuery plugin.
        $.fn.g1Reverse = [].reverse;

        $('#object-nav.horizontal > ul').each(function() {
            var $ul             = $(this);
            var $liMore         = $('<li class="g1-drop g1-drop-before">');
            var $liMoreToggle   = $('<a class="g1-drop-toggle" href="#">' + i18n.more_link + '<span class="g1-drop-toggle-arrow"></span></a>');
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

            $liMoreContent.append($liMoreSubmenu);
            $liMore.append($liMoreContent);
            $liDelimiter.toggleClass(classes.hidden);
            $ul.addClass('g1-dropable');
        });
    };

    $(document).ready(function () {
        g1.bpProfileNav();

        var hide_after = 5;
        $('.bp-navs.vertical > ul > li:eq(' + hide_after + ')').each( function(){
            var $this = $(this);

            var $button = $('<button type="button" class="g1-button g1-button-xs g1-button-subtle">Show All</button>');
            $button.on('click', function(){
                $(this).parent().remove();
            });

            var $li = $('<li class="csstodo-bp-more"></li>');
            $li.append( $button )
            $this.before( $li );
        } );
    });

})(jQuery, g1.config.i18n.bp_profile_nav);
