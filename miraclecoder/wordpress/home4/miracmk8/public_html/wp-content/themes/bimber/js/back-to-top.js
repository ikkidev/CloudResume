(function ($) {

    'use strict';

    g1.backToTop = function () {
        var $scrollToTop = $('.g1-back-to-top');

        // init
        toggleVisibility($scrollToTop);

        $scrollToTop.on('click', function (e) {
            e.preventDefault();

            var multipier = 200;
            var durationRange = {
                min: 200,
                max: 1000
            };

            var winHeight = $(window).height();
            var docHeight = $(document).height();
            var proportion = Math.floor(docHeight / winHeight);

            var duration = proportion * multipier;

            if (duration < durationRange.min) {
                duration = durationRange.min;
            }

            if (duration > durationRange.max) {
                duration = durationRange.max;
            }

            $('html, body').animate({
                scrollTop: 0
            }, duration);
        });

        $(window).scroll(function() {
            window.requestAnimationFrame(function () {
                toggleVisibility($scrollToTop);
            });
        });
    };

    function toggleVisibility ($scrollToTop) {
        if ($(window).scrollTop() > 240) {
            $scrollToTop.addClass('g1-back-to-top-on').removeClass('g1-back-to-top-off');
        } else {
            $scrollToTop.addClass('g1-back-to-top-off').removeClass('g1-back-to-top-on');
        }
    }

    $(document).ready(function () {
        g1.backToTop();
    });
})(jQuery);

