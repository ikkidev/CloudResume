/*******************
 *
 * Featured Entries
 *
 ******************/

(function ($) {

    'use strict';

    var selectors = {
        'wrapper':  '.g1-featured',
        'items':    '.g1-featured-items',
        'item':     '.g1-featured-item',
        'prevLink': '.g1-featured-arrow-prev',
        'nextLink': '.g1-featured-arrow-next'
    };

    var classes = {
        'startPos': 'g1-featured-viewport-start',
        'endPos':   'g1-featured-viewport-end',
        'noArrows':  'g1-featured-viewport-no-overflow'
    };

    var isRTL;
    var $wrapper;   // main wrapper
    var $items;     // items wrapper, is scrollable
    var $prevLink;  // move collection left
    var $nextLink;  // move collection right

    // public
    g1.featuredEntries = function () {
        isRTL = g1.isRTL();

        $(selectors.wrapper).each(function () {
            $wrapper = $(this);
            $items = $wrapper.find(selectors.items);
            $prevLink = $wrapper.find(selectors.prevLink);
            $nextLink = $wrapper.find(selectors.nextLink);

            var singleItemWidth = $items.find(selectors.item + ':first').width();
            var moveOffset = 2 * singleItemWidth;
            var direction = isRTL ? -1 : 1;

            $prevLink.on('click', function (e) {
                e.preventDefault();

                scrollHorizontally(-direction * moveOffset);
            });

            $nextLink.on('click', function (e) {
                e.preventDefault();

                scrollHorizontally(direction * moveOffset);
            });

            $items.on('scroll', function () {
                window.requestAnimationFrame(function () {
                    updateScrollState();
                });
            });

            $wrapper.removeClass('g1-featured-no-js').addClass('g1-featured-js');

            updateScrollState();
        });
    };

    // private
    function updateScrollState () {
        var width = $items.get(0).scrollWidth;
        var overflowedWidth = $items.width();
        var scrollLeft = $items.scrollLeft();

        $wrapper.removeClass(classes.endPos + ' ' + classes.startPos);
        // we add 20px "error margin", so we don't have scroll when the last element's overflow only a tiny bit.
        // 20px is equal to the padding of the title, so the text is always in viewport.
        if ( $items[0].offsetWidth + 20 >= $items[0].scrollWidth ){
            $wrapper.addClass(classes.noArrows);
            return;
        }

        // no scroll LTR, most left RTL
        if (scrollLeft <= 0) {
            if (isRTL) {
                $wrapper.addClass(classes.endPos);
                $wrapper.removeClass(classes.startPos);
            } else {
                $wrapper.addClass(classes.startPos);
                $wrapper.removeClass(classes.endPos);
            }
        // most right LTR, no scroll RTL
        } else if (width <= scrollLeft + overflowedWidth) {
            if (isRTL) {
                $wrapper.addClass(classes.startPos);
                $wrapper.removeClass(classes.endPos);
            } else {
                $wrapper.addClass(classes.endPos);
                $wrapper.removeClass(classes.startPos);
            }
        }
    }

    function scrollHorizontally(difference) {
        var leftOffset = $items.scrollLeft();

        $items.animate(
            // properties to animate
            {
                'scrollLeft': leftOffset + difference
            },
            375,        // duration
            'swing'     // easing
        );
    }

    // Fire it up.
    $(document).ready(function () {
        g1.featuredEntries();

    });
})(jQuery);
