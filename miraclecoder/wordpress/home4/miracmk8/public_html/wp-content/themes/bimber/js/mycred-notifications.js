/**************************
 *
 * MyCred Notifications
 * (images, video)
 *
 **************************/

(function ($) {

    'use strict';

    g1.myCredNotifications = function () {
        var setTimeoutForFirstNotification = function() {
            if ( $('.g1-mycred-notice-overlay-standard').attr('data-g1-mycred-notice-timeout') && $('.g1-notification-standard').length > 0) {
                var timeout = $('.g1-mycred-notice-overlay-standard').attr('data-g1-mycred-notice-timeout');
                var firstNotification = $('.g1-notification-standard')[0];
                setTimeout(function() {
                    firstNotification.remove();
                    setTimeoutForFirstNotification();
                }, timeout * 1000);
            }
        };

        var bindStandardNotificationEvents = function() {
            if ($('.g1-mycred-notice-overlay').length > 0 || $('.g1-mycred-notice-overlay-standard').length < 1) {
                return;
            }
            $('.g1-notification-standard-close').on('click', function (e) {
                $(this).closest('.g1-notification-standard').remove();
                setTimeoutForFirstNotification();
            });
            setTimeoutForFirstNotification();
        };

        $('.g1-mycred-notice-close').on('click', function (e) {
            var $that = $(this);
            $that.closest('.g1-mycred-notice-overlay').removeClass('g1-mycred-notice-overlay-visible');
            setTimeout(function(){
                $that.closest('g1-mycred-notice-overlay').remove();
                bindStandardNotificationEvents();
            }, 375);
        });

        $('.g1-mycred-notice-overlay').on('click', function (e) {
            var $that = $(this);
            $that.closest('.g1-mycred-notice-overlay').removeClass('g1-mycred-notice-overlay-visible');
            setTimeout(function(){
                $that.remove();
                bindStandardNotificationEvents();
            }, 375);
        }).children().click(function(e) {
            if (!$(e.target).hasClass('g1-mycred-notice-close') && !$(e.target).hasClass('g1-mycred-notice-share')){
                return false;
            }
        });

        bindStandardNotificationEvents();

        var noticesTop = $('#wpadminbar').outerHeight() + $('.g1-sticky-top-wrapper').outerHeight();
        $('.g1-mycred-notice-overlay-standard').css('top', noticesTop);
    };


    // Fire it up.
    $(document).ready(function () {
        g1.myCredNotifications();
    });
})(jQuery);
