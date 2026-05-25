/*****************
 *
 * Collection Ticker
 *
 *****************/

(function ($) {

    'use strict';

    g1.collectionTicker = function () {
        $('.g1-collection-ticker.g1-collection-no-js').each(function() {
            var $collection = $(this);
            $collection.removeClass('g1-collection-no-js');

            $collection.find('.g1-collection-items').each(function() {
                var $collectionItems = $(this);

                $collectionItems.on( 'ready.flickity', function() {
                    $collectionItems.find('.flickity-prev-next-button').addClass('g1-button g1-button-simple g1-button-xs');
                });

                $collectionItems.flickity({
                    wrapAround:         true,
                    autoPlay:           5000,
                    cellAlign:          'left',
                    prevNextButtons:    true,
                    pageDots:           false
                });
            });
        });
    };

    // Fire it up.
    $(document).ready(function () {
        g1.collectionTicker();
    });
})(jQuery);
