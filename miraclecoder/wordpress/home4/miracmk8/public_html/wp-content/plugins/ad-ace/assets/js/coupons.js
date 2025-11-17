/* global jQuery */
/* global document */

(function($) {

	'use strict';

	var copyCoupon = function() {
		var
		Coupons = $('.adace-coupon-wrap');
		Coupons.each(function(){
			if ( $(this).hasClass('active') ){
				return;
			} else {
				$(this).addClass('active');
			}
			var
			CopyBtn        = $(this).find('.coupon-copy'),
			CopyAction     = $(this).find('.coupon-action'),
			CopyActionText = CopyAction.text(),
			CopyCode       = $(this).find('.coupon-code'),
			CopyCodeText   = CopyCode.text();
			CopyBtn.click(function(e){
				e.preventDefault();

				// Create element for this pin.
				var CopyArea = $('<input class="copy-area" style="visibillity:hidden;" value="' + CopyCodeText + '" />');

				// Copy code
				CopyCode.prepend(CopyArea);

				CopyArea = $('.copy-area');

				CopyArea.select();

				try {
					document.execCommand('copy');
				} catch (err) {
					alert( 'This feature is not supported in your browser, please copy the coupon manually.');
					$('.adace-coupon-code').select();
				}

				CopyArea.blur().remove();

				if( CopyBtn.hasClass('copied') ){
					return;
				}

				CopyBtn.addClass('copied blink');

				setTimeout(function(){
					CopyAction.html(CopyAction.data('copied'));
					CopyBtn.removeClass('blink');
				}, 375);

				setTimeout(function(){
					CopyBtn.addClass('blink');
				}, 4625);

				setTimeout(function(){
					CopyAction.html(CopyActionText);
					CopyBtn.removeClass('copied blink');
				}, 5000);
			});
		});
	};

	$(document).ready(function(){
		copyCoupon();
	});

})(jQuery);
