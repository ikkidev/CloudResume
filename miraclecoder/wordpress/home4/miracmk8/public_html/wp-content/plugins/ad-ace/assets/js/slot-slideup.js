/* global jQuery */
/* global document */

(function($) {

	'use strict';

	var createCookie = function (name, value, time) {
        var expires;

        if (time) {
            var date = new Date();
            var ms = time;

            if (typeof time === 'object') {
                ms = time.value;

                switch (time.type) {
                    case 'days':
                        ms = ms * 24 * 60 * 60 * 1000;
                        break;
                }
            }

            date.setTime(date.getTime() + ms);
            expires = '; expires=' + date.toGMTString();
        }
        else {
            expires = '';
        }

        document.cookie = name + '=' + value + expires + '; path=/';
	};
	var readCookie = function (name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');

        for (var i = 0; i < ca.length; i += 1) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }

            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }

        return null;
    };

	var slideUp = function() {
		var
	 	$slideUpSlotWrap = $('.adace-slideup-slot-wrap'),
		$slideUpCloserBtn = $slideUpSlotWrap.find('.adace-slideup-slot-closer'),
		SlideupCookie = readCookie('adace_slideup_disabled');
		$slideUpCloserBtn.on('click', function(e) {
			e.preventDefault();
			$slideUpSlotWrap.addClass('hidden');
			createCookie('adace_slideup_disabled', 1, 24 * 60 * 60 * 1000);
		});

		if ( SlideupCookie ) {
            $slideUpSlotWrap.remove();
        }
	};

    $(document).ready(function(){
        slideUp();
    });

})(jQuery);
