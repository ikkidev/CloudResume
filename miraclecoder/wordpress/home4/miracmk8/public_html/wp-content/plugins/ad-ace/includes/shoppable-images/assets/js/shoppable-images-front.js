/**************************
 *
 * document ready functions
 *
 *************************/

(function ($) {

    'use strict';

    $(document).ready(function () {
		$('.shoppable-image').each(function() {
			shoppable_image($(this));
		});

		$('body').on('shoppableImages', function(e, $content) {
			$content.find('.shoppable-image').each(function() {
				shoppable_image($(this));
			});
		});
    });

    var shoppable_image = function (ThisImagemap) {
		if (ThisImagemap.data('aceShoppableImageLoaded')) {
			return;
		}

		var
		Map     = ThisImagemap.find('.shoppable-image-map'),
		HideBtn = ThisImagemap.find('.shoppable-image-hideshow-btn');
		new Waypoint({
			element: ThisImagemap,
			handler: function (direction) {
				if (direction === 'down') {
					ThisImagemap.addClass('pins-visible');
				} else {
					ThisImagemap.removeClass('pins-visible');
				}
			},
			offset : '80%'
		});
		HideBtn.click(function(e){
			e.preventDefault();
			Map.toggleClass('hide-pins');
		});
		shoppable_image_resize(ThisImagemap);
		$(window).resize(function () {
			shoppable_image_resize(ThisImagemap);
		});

		ThisImagemap.data('aceShoppableImageLoaded', true);
	};

	var shoppable_image_resize = function(ThisImagemap){
		var
		Pins = ThisImagemap.find('.shoppable-image-pin');
		Pins.each(function(){
			var
			ThisPinBody = $(this).find('.shoppable-image-pin-body');
			ThisPinBody.css({ "margin-left": "auto"  });
			var
			BodyRect = ThisPinBody[0].getBoundingClientRect(),
			OffsetLeft = BodyRect.x,
			OffsetRight = ( BodyRect.x + BodyRect.width ) - window.innerWidth;
			if( OffsetLeft < 0 ) {
				ThisPinBody.css({ "margin-left": -OffsetLeft + 'px' });
			}
			if ( OffsetRight > 0 ) {
				ThisPinBody.css({ "margin-left": -OffsetRight + 'px' });
			}
		});
	};

})(jQuery);
