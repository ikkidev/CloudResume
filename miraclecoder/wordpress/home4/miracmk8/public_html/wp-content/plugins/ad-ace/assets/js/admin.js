/* global jQuery */
/* global document */

(function($) {

	'use strict';

	$(document).ready(function(){
		$('.adace-image-upload').each(function() {
			imageUploadControl($(this));
		});

		hideElements();
		clearSponsorLogoOnPublish();
		manageSlotsToggles();
		initAdMetabox();
		initAdLink();
		initMultipleSelect();
		shopThePostMetabox();
		shoppableImages();
		dataPickers();
		metaBoxTabs();
		widgetTabs();
		widgetSelect();
		productAutocomplete();
	});



	// "Edit Ad" button.
	$(document).ready(function(){
		var handleSelect = function() {
			var $select = $(this);

			$select.find(":selected").each(function() {
				var $option = $(this);
				if (typeof $option.data('adace-href') !== 'undefined') {
					$select.next('.adace-ad-select-link')
						.prop('href', $option.data( 'adace-href'))
						.show();
				} else {
					$select.next('.adace-ad-select-link').hide();
				}
			} )
		};


		$('.adace-ad-select').each(function() {
			var $select = $(this);

			$select.change( handleSelect);
			$select.trigger('change');
		});
	});

	// Disabled / Enabled slot.
	$(document).ready(function(){
		$('#adace_slots-form .postbox').each(function() {
			var $this = $(this);

			if ( $this.find('select.adace-ad-select:first').val() ) {
				$this.addClass('adace-with-ad');
			} else {
				$this.addClass('adace-without-ad');
			}
		});
	});

	var imageUploadControl = function($el) {
		var
		$image = $el.find('.adace-image'),
		$addLink = $el.find('.adace-add-image'),
		$deleteLink = $el.find('.adace-delete-image'),
		$imageId = $el.find('.adace-image-id');

		if ( $imageId.val().length > 0 ) {
			$addLink.hide();
			$deleteLink.show();
		} else {
			$addLink.show();
			$deleteLink.hide();
		}

		$addLink.on('click', function(e) {
			e.preventDefault();
			openMediaLibrary(function(imageObj) {
				var
				thumb = '';
				if( imageObj.sizes.thumbnail !== undefined ){
					thumb = imageObj.sizes.thumbnail;
				} else {
					thumb = imageObj.sizes.full;
				}

				$image.html('<img src="' + thumb.url + '" width="' + thumb.width + '" height="' + thumb.height + '" />');
				$imageId.val(imageObj.id);

				$addLink.hide();
				$deleteLink.show();
			});
		});

		$deleteLink.on('click', function(e) {
			e.preventDefault();

			if ( ! confirm( 'Are you sure?' ) ) {
				return;
			}

			$image.empty();
			$imageId.val('');

			$addLink.show();
			$deleteLink.hide();
		});
	};

	var openMediaLibrary = function(callback) {
		var
		frame = wp.media({
			'title': 'Select an image',
			'multiple': false,
			'library': {
				'type': 'image'
			},
			'button': {
				'text': 'Add Image'
			}
		});

		frame.on('select',function() {
			var objSelected = frame.state().get('selection').first().toJSON();
			callback(objSelected);
		});

		frame.open();
	};

	var hideElements = function() {
		$('#adace_override_hide_elements').on('change', function() {
			var
			option = $(this).val(),
			$dependent = $('#adace-hide-elements-wrapper');

			if ('none' === option) {
				$dependent.hide();
			} else {
				$dependent.show();
			}
		});


		$('.ad_id .adace-ad-select').each(function () {
			var $adSelect = $(this);
			var $adRow = $adSelect.parents('tr');
			var $adGroup  = $adRow.next('tr.row-ad_group');
			var $adRepeat = $adGroup.next('tr.row-no_repeat');
			var isAdRepeatEnabled = $adRepeat.find('input:disabled').length === 0;

			$adSelect.on('change', function () {
				var selectedAd = parseInt($(this).val(), 10);

				// Disabled.
				if (isNaN(selectedAd)) {
					$adRow.addClass('adace-collection-ad-not-selected');

					$adGroup.hide();
					$adRepeat.hide();
				}

				// Random.
				if (-1 === selectedAd) {
					$adGroup.show();

					if (isAdRepeatEnabled) {
						$adRepeat.show();
					} else {
						$adRepeat.hide();
					}
				}

				// Ad.
				if (selectedAd > 0) {
					$adRow.removeClass('adace-collection-ad-not-selected');

					$adGroup.hide();
					$adRepeat.hide();
				}
			});

			$adSelect.trigger('change');
		});

		$('#adace_disable_all_slots').on( 'click', function() {
			var $parent = $(this).closest('tbody');
			var $row 	= $(this).closest('tr');
			if ( $(this).is(':checked') ){
				$('tr', $parent).not($row).hide();
			} else {
				$('tr', $parent).not($row).show();
			}
		});

	};

	var clearSponsorLogoOnPublish = function() {
		var $SponsorLogoWrap = $('.form-field.bimber-sponsor-logo-wrap'),
			$image = $SponsorLogoWrap.find('.bimber-image'),
			$addLink = $SponsorLogoWrap.find('.bimber-add-image'),
			$deleteLink = $SponsorLogoWrap.find('.bimber-delete-image'),
			$imageId = $SponsorLogoWrap.find('.bimber-image-id');

		if ($SponsorLogoWrap.lenght == 0) { return; }
		$('#addtag #submit').click(function () {
		    if ($('#addtag .form-invalid').length == 0) {
				$image.empty();
				$imageId.val('');

				$addLink.show();
				$deleteLink.hide();
		    }
		});
	};

	var manageSlotsToggles = function() {
		var SlotsToggles = $('#adace_slots-form .postbox');

		if (SlotsToggles.length === 0) {
			return;
		}

		SlotsToggles.each(function(){
			var
			ThisToggle = $(this),
			ClickTargets = ThisToggle.find('.handlediv, .hndle');
			ClickTargets.click(function(e){
				e.preventDefault();
				ThisToggle.toggleClass('closed');
			});
		});

		// Open on load.
		var queryArgs = getURLQueryArgs();

		if (typeof queryArgs.open_slot !== 'undefined') {
			var $slot = SlotsToggles.filter('#' + queryArgs.open_slot);

			if ($slot.length > 0) {
				$slot[0].scrollIntoView({
					behavior: 'smooth',
					block: 	  'center',
					inline:   'nearest'
				});

				$slot.find('.hndle').trigger('click');
			}
		}
	};

	var getURLQueryArgs = function () {
		var args = {};
		var queryStr = window.location.search.substring(1);
		var vars = queryStr.split('&');

		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split('=');
			args[pair[0]] = decodeURIComponent(pair[1]);
		}

		return args;
	};

	var initAdMetabox = function() {
		var $adType 		= $('#adace_ad_type');
		var $adsenseSection = $('#adace_ad_meta_box_adsense');
		var $customSection 	= $('#adace_ad_meta_box_custom');
		var $customTab		= $('.adace-nav-tab-custom');
		var $adsenseTab		= $('.adace-nav-tab-adsense');

		var $adSenseType 	= $('#adace_adsense_type');
		var $adSenseFormat 	= $('.adace_adsense_format');

		var $adSize 		= $('.adace_adsense_size');
		var $adSenseWidth 	= $('#adace_adsense_width');
		var $adSenseHeight 	= $('#adace_adsense_height');
		var $adSensePaste 	= $('#adace_adsense_paste');
		var $adSenseSlot 	= $('#adace_adsense_slot');
		var $adSensePub 	= $('#adace_adsense_pub');

		var $desktopCustom		= $('#adace_adsense_use_size_desktop');
		var $desktopSection		= $('.adace_adsense_size_desktop');
		var $landscapeCustom	= $('#adace_adsense_use_size_landscape');
		var $landscapeSection	= $('.adace_adsense_size_landscape');
		var $portraitCustom		= $('#adace_adsense_use_size_portrait');
		var $portraitSection	= $('.adace_adsense_size_portrait');
		var $phoneCustom 		= $('#adace_adsense_use_size_phone');
		var $phoneSection 		= $('.adace_adsense_size_phone');

		var setSectionVisibility = function() {
			if ( $adType.val() === 'adsense' ) {
				$customSection.hide();
				$adsenseSection.show();
			} else {
				$customSection.show();
				$adsenseSection.hide();
			}
		};
		var setSizesVisibility = function() {
			if ( $adSenseType.val() === 'fixed' ) {
				$adSize.show();
				$adSenseFormat.hide();
			} else {
				$adSize.hide();
				$adSenseFormat.show();
			}
			if ( $desktopCustom.is(':checked')) {
				$desktopSection.show();
			} else {
				$desktopSection.hide();
			}
			if ( $landscapeCustom.is(':checked')) {
				$landscapeSection.show();
			} else {
				$landscapeSection.hide();
			}
			if ( $portraitCustom.is(':checked')) {
				$portraitSection.show();
			} else {
				$portraitSection.hide();
			}
			if ( $phoneCustom.is(':checked')) {
				$phoneSection .show();
			} else {
				$phoneSection .hide();
			}
		};

		setSectionVisibility();
		setSizesVisibility();
		$adType.bind( 'change', setSectionVisibility);
		$adSenseType.bind( 'change', setSizesVisibility);
		$desktopCustom.bind( 'change', setSizesVisibility);
		$landscapeCustom.bind( 'change', setSizesVisibility);
		$portraitCustom.bind( 'change', setSizesVisibility);
		$phoneCustom.bind( 'change', setSizesVisibility);

		$customTab.bind('click', function() {
			$adsenseTab.removeClass('nav-tab-active');
			$customTab.addClass('nav-tab-active');
			$adType.val('custom');
			setSectionVisibility();
		});

		$adsenseTab.bind('click', function() {
			$customTab.removeClass('nav-tab-active');
			$adsenseTab.addClass('nav-tab-active');
			$adType.val('adsense');
			setSectionVisibility();
		});

		$adSensePaste.on( 'keyup', function() {
			if ($(this).val() === ''){
				return;
			}
			var $html 	= $($.parseHTML($(this).val()));
			var $ins 	= $html.filter('ins');
			$adSensePub.val($ins.attr('data-ad-client'));
			$adSenseSlot.val($ins.attr('data-ad-slot'));
			if ( parseInt($ins.css('width'), 10) > 0 ){
				$adSenseWidth.val(parseInt($ins.css('width'), 10));
				$adSenseHeight.val(parseInt($ins.css('height'), 10));
				$adSenseType.val('fixed');
			} else {
				$adSenseWidth.val(0);
				$adSenseHeight.val(0);
				$adSenseType.val('responsive');
			}
			setSizesVisibility();
			$(this).val('');
		});

	};

	var initAdLink = function () {
		var $context = $('#adace_ad_meta_box_custom');

		if ($context.length === 0) {
			return;
		}

		var $linkType = $('#adace_ad_link_type', $context);
		var $link 	  = $('#adace_ad_link', $context);

		var onLinkTypeChange = function () {
			// Hide all on load.
			$context.find('.adace-link-to-notice').hide();

			var type = $linkType.val();

			'' === type ? $link.parents('tr').show() : $link.parents('tr').hide();

			// Find type notice.
			var $notice = $context.find('[data-ref-ad-link-type=' + type + ']');

			// Show notice.
			if ($notice.length > 0) {
				$notice.show();
			}
		};

		$linkType.on('change', onLinkTypeChange);

		onLinkTypeChange();
	};

	var initMultipleSelect = function() {
		$('.adace-multiple-with-none').change( function() {
			if ($('option:first', this).is(':selected')) {
				$('option:not(:first)', this).prop('selected', false);
			}
		});
	};

	var shopThePostMetabox = function() {
		if (typeof adace_stp === 'undefined') {
			return;
		}

		$('.adace-metabox-tab.woocommerce').each(function () {
			var $metaboxTab = $(this);

			var $input = $metaboxTab.find('.adace_related_products');
			var selectLabel = 'Create Collection';
			var changeLabel = 'Edit Collection';
			var label = $input.val() ? changeLabel : selectLabel;
			var $preview = $metaboxTab.find('.adace-related-products-preview');
			var $button = $('<button type="button" class="button" data-editor="ad_ace_related_products" title="Shop the post"><span class="wp-media-buttons-icon"></span><span class="ad-ace-label">' + label + '</span></button>');

			$button.insertAfter($input);
			$button.wrap('<p></p>');
			$preview.insertAfter($input);
			$input.hide();

			// Open modal.
			$button.on('click', function() {
				var editorId = $(this).data('editor');

				var modal = adace_stp.getModal(editorId);

				var selectedIds = false;

				if ($input.val().length > 0) {
					selectedIds = $input.val().split(',');
				}

				if (selectedIds) {
					modal.open('wc-edit', 'update', null, selectedIds);
				} else {
					modal.open('wc-create', 'create', null, []);
				}

				var $modal = modal.getHtml();

				$modal.on('collectionUpdated', function(e, collection) {
					var ids = [];

					// Clear.
					$input.val('');
					$preview.empty();

					var items = collection.getItems();

					for (var i = 0; i < items.length; i++) {
						var item = items[i];

						ids.push(item.getId());

						var thumb = item.getThumb();

						$preview.append('<img width="120" height="120" src="' + thumb + '" />');
					}

					$input.val(ids.join(','));

					if (items.length === 0) {
						$button.find('span.ad-ace-label').text(selectLabel);
					} else {
						$button.find('span.ad-ace-label').text(changeLabel);
					}
				});
			});

			$preview.on('click', 'img', function() {
				$button.trigger('click');
			});

			$preview.on('hover', 'img', function() {
				$(this).css('cursor', 'pointer');
			});
		});
	};

	var shoppableImages = function() {
		if (typeof adace_stp === 'undefined') {
			return;
		}

		var loadPopup = function($wrapper) {
		 	var $pin     = $wrapper.data('Pin');
			var $input   = $wrapper.find('.adace-pin-product-id');
			var $label   = $wrapper.find('.adace-pin-product-label');
			var $preview = $wrapper.find('.adace-pin-product-preview');
			var $button  = $('<button type="button" class="adace-select-product button button-secondary button-small" data-editor="ad_ace_shoppable_images" value="Select Product"><span class="wp-media-buttons-icon"></span><span class="ad-ace-label">Select Product</span></button>');
			var $remove  = $('<button type="button" class="adace-remove-product button button-secondary button-small" value="Remove Product"><span class="wp-media-buttons-icon"></span><span class="ad-ace-label">Remove Product</span></button>');

			$label.hide();
			$remove.insertAfter($input);
			$preview.insertAfter($input);
			$button.insertAfter($input);
			$input.hide();

			if ($.trim($input.val()).length === 0) {
				$remove.hide();
				$button.show();
			} else {
				$button.hide();
				$remove.show();
				$preview.append('<img width="120" height="120" src="' + $pin.woo_thumb + '" />');
			}

			// Open modal.
			$button.on('click', function() {
				var editorId = $(this).data('editor');

				var modal = adace_stp.getModal(editorId);

				modal.open('wc-create', 'single-selection', null, []);

				var $modal = modal.getHtml();

				var update = function(e, collection) {
					var ids = [];

					// Clear.
					$input.val('');
					$preview.empty();

					var items = collection.getItems();

					for (var i = 0; i < items.length; i++) {
						var item = items[i];

						ids.push(item.getId());
						$pin.woo_title = item.getTitle();
						$pin.woo_price = item.getPrice();
						$pin.woo_permalink = item.getUrl();
						$pin.woo_thumb = item.getThumb();
						if( item.getTitle() ){
							$pin.selector.find('.imagemap-pin-name').text( item.getTitle() );
						} else {
							$pin.selector.find('.imagemap-pin-name').text('');
						}
						if( item.getPrice() ){
							$pin.selector.find('.imagemap-pin-price').html( item.getPrice() );
						} else {
							$pin.selector.find('.imagemap-pin-price').text('');
						}
						if( item.getUrl() ){
							$pin.selector.find('.imagemap-pin-url').html('<a href="' + item.getUrl() + '" class="button button-primary" target="_blank">Buy from here</a>');
						} else {
							$pin.selector.find('.imagemap-pin-url').text('');
						}
						if( item.getThumb() ){
							$pin.selector.find('.imagemap-pin-thumb').html( '<img width="120" height="120" src="' + item.getThumb() + '" />');
						} else {
							$pin.selector.find('.imagemap-pin-thumb').text('');
						}
						if( item.getThumb() ){
							$preview.append('<img width="120" height="120" src="' + item.getThumb() + '" />');
						}
					}

					if (ids.length > 0) {
						$input.val(ids[0]);
					}

					if (items.length === 0) {
						$button.show();
						$remove.hide();
					} else {
						$button.hide();
						$remove.show();
					}

					$modal.off('collectionUpdated', update);
				};

				$modal.on('collectionUpdated', update);
			});

			$remove.on('click', function() {
				$input.val('');
				$preview.empty();

				$button.show();
				$remove.hide();

				$pin.selector.find('.imagemap-pin-name').text( '' );
				$pin.selector.find('.imagemap-pin-price').text( '' );
				$pin.selector.find('.imagemap-pin-url').text( '' );
			});

			$preview.on('click', 'img', function() {
				$button.trigger('click');
			});

			$preview.on('hover', 'img', function() {
				$(this).css('cursor', 'pointer');
			});
		};

		$('body').on('adacePinOpened', function(e) {
			var $metabox = $(e.target);

			if ($metabox.find('.adace-select-product').length === 0) {
				loadPopup($metabox);
			}
		});
	};

	var dataPickers = function() {
		jQuery('.adace-datapicker').each(function(){
			jQuery(this).datepicker();
		});
	};

	var metaBoxTabs = function() {
		jQuery('.postbox').each(function(){
			var
			ThisMetabox           = jQuery(this),
			ThisMetaboxHeader     = ThisMetabox.find('.adace-metabox-header'),
			ThisMetaboxCurrentTab = ThisMetaboxHeader.find('input:checked').val(),
			ThisMetaboxTabs       = ThisMetabox.find('.adace-metabox-tab');

			ThisMetaboxHeader.on('click', function(e){
				ThisMetaboxCurrentTab = ThisMetaboxHeader.find('input:checked').val();
				// Swap Tabs.
				ThisMetaboxTabs.removeClass('current');
				ThisMetaboxTabs.filter('.' + ThisMetaboxCurrentTab).addClass('current');
			});
		});
	};

	var widgetTabs = function() {

		jQuery(document).on('widget-updated widget-added', function(e, ThisWidget){
			widgetTab(ThisWidget);
		});
		jQuery('.widget').each(function(){
			widgetTab( jQuery(this) );
		});
		function widgetTab(ThisWidget){
			var
			ThisWidgetHeader     = ThisWidget.find('.adace-widget-header'),
			ThisWidgetCurrentTab = ThisWidgetHeader.find('input:checked').val(),
			ThisWidgetTabs       = ThisWidget.find('.adace-widget-tab');

			ThisWidgetHeader.on('click', function(e){
				ThisWidgetCurrentTab = ThisWidgetHeader.find('input:checked').val();
				// Swap Tabs.
				ThisWidgetTabs.removeClass('current');
				ThisWidgetTabs.filter('.' + ThisWidgetCurrentTab).addClass('current');
			});
		}
	};

	var widgetSelect = function() {

		jQuery(document).on('widget-updated widget-added', function(e, widget){
			widgetSelects(widget);
		});
		jQuery('.widget').each(function(){
			widgetSelects( jQuery(this) );
		});
		function widgetSelects(widget){
			var $selectAds = $('.adace-widget-select-ad', widget);
			var $selectGroups = $('.adace-widget-select-group', widget);
			if ($selectAds.val() === '-1'){
				$selectGroups.show();
			} else {
				$selectGroups.hide();
			}
			$selectAds.on('change', function(){
				if ($selectAds.val() === '-1'){
					$selectGroups.show();
				} else {
					$selectGroups.hide();
				}
			});
		}
	};

	var productAutocomplete = function () {

		if ( ! AdaceAdminVars.plugins.is_woocommerce ) {
			return;
		}

		$('.adace-wc-product-autocomplete').each(function () {
			var $idInput = $(this);

			var $textInput = $('<input type="text" value="' + $idInput.attr('data-product-name') + '" placeholder="'+ AdaceAdminVars.i18n.start_typing +'" />');
			var $clearLink = $('<a href="#" class="adace-remove-product-selection button button-secondary">'+ AdaceAdminVars.i18n.clear_selection +'</a>');
			var $results   = $('<div class="adace-product-list"></div>');
			$results.append($textInput);
			$results.append($clearLink);
			$results.append('<span class="adace-spinner dashicons-before dashicons-image-filter"></span>');

			if ( $idInput.val() ) {
				$clearLink.show();
				$textInput.attr('readonly', 'readonly');
			} else {
				$clearLink.hide();
				$textInput.removeAttr('readonly');
			}

			$clearLink.on('click', function (e) {
				e.preventDefault();
				$clearLink.hide();
				$textInput.removeAttr('readonly');
				$textInput.val('');
				$idInput.val('');
			});

			$('body').on('click', function () {
				$results.find('ul').remove();
			});

			$textInput.on('keyup', function () {
				if ( $(this).is('[readonly]') ) {
					return;
				}

				var text = $(this).val();

				if (text.length >= 3) {
					$results.addClass('adace-loading');

					$.ajax({
						type: 		'GET',
						url:		ajaxurl,
						dataType:	'json',
						data: {
							action:  'adace_get_wc_products',
							text:	 text
						}
					}).done(function (res) {
						$results.removeClass('adace-loading');

						if (res.status === 'success') {
							$results.find('ul').remove();
							var $ul = $('<ul></ul>');

							var products = res.products;

							for (var product_id in products) {
								var $li = $('<li value="'+ product_id +'">'+ products[product_id] +'</li>');

								(function (productId, productName) {
									$li.on('click', function (e) {
										e.stopImmediatePropagation();
										$idInput.val(productId);
										$textInput.val(productName);
										$clearLink.show();
										$textInput.attr('readonly', 'readonly');
										$results.find('ul').remove();
									});
								})(product_id, products[product_id])

								$ul.append($li);
							}

							$ul.appendTo($results);
						} else {
							$results.find('ul').remove();
						}
					});
				}
			});

			$results.insertBefore($idInput);
			$idInput.hide();
		});
	};

})(jQuery);
