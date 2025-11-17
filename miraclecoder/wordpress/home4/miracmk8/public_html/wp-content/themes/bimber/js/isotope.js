/*****************
 *
 * Isotope
 *
 *****************/
(function ($) {

    'use strict';

    var selectors = {
        'grid':     '.g1-collection-masonry .g1-collection-items'
    };

    var $grid;

    g1.isotope = function () {
        if (!$.fn.isotope) {
            return;
        }

        $grid = $(selectors.grid);

        if (!$grid.length) {
            return;
        }

        $grid.imagesLoaded( function() {
            $grid.isotope({
                itemSelector:   '.g1-collection-item',
                layoutMode:     'masonry',
                percentPosition: true,
                originLeft:     !$(document.body).hasClass('rtl')
            });
        });

        $('body').on( 'g1NewContentLoaded', function(e, $addedItems) {
            $('.g1-collection-masonry .g1-injected-unit', $addedItems).on( 'DOMSubtreeModified', function() {
                g1.resizeIsotope();
            });
            $grid.isotope('appended', $addedItems);
        });
        $('.g1-collection-masonry .g1-injected-unit').on( 'DOMSubtreeModified', function() {
            g1.resizeIsotope();
        });
    };

    // we cheat isotope into thinking that the browser is resized. we can't do this by event alone so we temporarily change the size of the container.
    g1.resizeIsotope = function() {
        $('.g1-collection-masonry .g1-collection-items').width($('.g1-collection-masonry .g1-collection-items').width()-1);
        window.dispatchEvent(new Event('resize'));
        setTimeout(function() {
            $('.g1-collection-masonry .g1-collection-items').width($('.g1-collection-masonry .g1-collection-items').width()+1);
        }, 1000);
    };

    g1.reLayout = function() {
        $grid.isotope('layout');
    };

    // Fire it up.
    $(document).ready(function () {
        g1.isotope();
    });

    // trigger on window load
    $( window ).load( function() {
        g1.reLayout();
    });

})(jQuery);
