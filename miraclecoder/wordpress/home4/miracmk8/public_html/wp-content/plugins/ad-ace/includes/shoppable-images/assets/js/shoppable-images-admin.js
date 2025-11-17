/**
 * Imagemap Backend JavaScript
 * @package Adace
 * @global window, document, jQuery, jQueryUI, adace_shoppable_images
*/

/**
 * Initate Functions
 */
( function( $ ) {

	// Set for strict.
    'use strict';
	// Declare imagemap home.
	var
	adace_imagemap = {
		'ajax' : adace_shoppable_images.ajax_vars,
		'functions' : {},
		'selectors' : {},
		'variables' : {},
	};
	// Game is on.
	window.adace_imagemap = adace_imagemap;
	// Functions that start on init.
    $(document).ready(function(){
		adace_imagemap.functions.edit_page.init();
	});
})(jQuery);

/**
 * Script Selectors & Variables
 */
( function( $ ) {
	// Set for strict.
    'use strict';

    var i18n = adace_shoppable_images.i18n;

	// Define functions
	adace_imagemap.functions.define = {};
	// Define selectors.
	adace_imagemap.functions.define.selectors = function(){
		// Add elements for editor. For now defined here later, pull from php.
		adace_imagemap.selectors.editor                = {};
		adace_imagemap.selectors.editor.Board          = $();
		adace_imagemap.selectors.editor.Pins           = $();
		adace_imagemap.selectors.editor.MetaboxesPlace = $();
		adace_imagemap.selectors.editor.Metaboxes      = $();
		adace_imagemap.selectors.editor.AddPinBtn      = $();
	};
	// Define Variables that are used in different functions.
	adace_imagemap.functions.define.variables = function(){
		// Just to know if we are working on something.
		adace_imagemap.variables.Working             = false;
		// Editor variables.
		adace_imagemap.variables.editor              = {};
		adace_imagemap.variables.editor.Pins         = [];
		adace_imagemap.variables.editor.Metaboxes    = [];
		adace_imagemap.variables.editor.DefaultPin   = { 'pos_x' : 0, 'pos_y' : 0, 'content' : '', 'type' : 'custom_product', 'name' : 'Shiny Thing', 'price' : '', 'url' : '', 'woocommerce_id': '' };
		adace_imagemap.variables.editor.html         = {};
		adace_imagemap.variables.editor.html.Pin     =
			'<div class="imagemap-pin">' +
			'<a href="#" class="imagemap-pin-icon"></a>' +
			'<div class="imagemap-pin-body">' +
				'<p class="imagemap-pin-thumb"></p>' +
				'<p class="imagemap-pin-name"></p>' +
				'<p class="imagemap-pin-price"></p>' +
				'<p class="imagemap-pin-url"></p>' +
			'</div>' +
			'</div>';
		adace_imagemap.variables.editor.html.Metabox =
			'<div class="postbox closed">' +
				'<button type="button" class="handlediv button-link">' +
					'<span class="toggle-indicator" aria-hidden="true"></span>' +
				'</button>' +
				'<h2 class="hndle"><span>'+ i18n.pin_nr +'</span></h2>' +
				'<div class="inside">' +
					'<fieldset class="adace-box-header">' +
						'<span class="header-label">'+ i18n.product_type +'</span>:' +
						'<label><input type="radio" class="adace-pin-type custom_product" value="custom_product" /><span>'+ i18n.product_type_custom +'</span></label>' +
						'<label><input type="radio" class="adace-pin-type woocommerce" value="woocommerce" /><span>'+ i18n.product_type_wc +'</span></label>' +
					'</fieldset>' +
					'<div class="adace-box-tab custom_product ">' +
						'<p>' +
							'<label><strong>'+ i18n.product_name +'</strong></label>' +
							'<br>' +
							'<input type="text" rows="3" class="widefat adace-pin-name" />' +
						'</p>' +
						'<p>' +
							'<label><strong>'+ i18n.product_url +'</strong></label>' +
							'<br>' +
							'<input type="text" rows="3" class="widefat adace-pin-url" />' +
						'</p>' +
						'<p>' +
							'<label><strong>'+ i18n.product_price +'</strong></label>' +
							'<br>' +
							'<input type="text" rows="3" class="short-text adace-pin-price" />' +
						'</p>' +
					'</div>' +
					'<div class="adace-box-tab woocommerce">' +
						'<div class="adace-pin-products-picker">' +
							'<label class="adace-pin-product-label"><strong>'+ i18n.product_id +'</strong></label>' +
							'<input type="text" rows="3" class="short-text adace-pin-product-id" />' +
							'<p class="adace-pin-product-preview"></p>' +
						'</div>' +
					'</div>' +
					'<p class="adace-box-footer submitbox">' +
						'<a href="#" class="adace-delete-pin submitdelete">'+ i18n.delete_pin +'</a>' +
					'</p>' +
				'</div>' +
			'</div>';
	}
})(jQuery);

/**
 * Scripts for edit page
 */
( function( $ ) {
	// Set for strict.
    'use strict';

    var i18n = adace_shoppable_images.i18n;

	// Edit page functions home.
	adace_imagemap.functions.edit_page = {};
	// Initiate slider.
	adace_imagemap.functions.edit_page.init = function(){
		var
		Img               = $('.wp_attachment_holder .wp_attachment_image img'),
		Editor            = $('<div id="adace-image-pins-editor"></div>'),
	 	Board             = $('<div id="adace-image-pins-board"></div>'),
		Surface           = $('<div class="board-surface"></div>'),
		Side              = $('<div style="display:none;" id="adace-image-pins-side"><div class="editor-controls"></div><div class="editor-metaboxes"></div></div>'),
		EditBtnPlace      = $('.wp_attachment_holder .wp_attachment_image input[type=button]'),
		EditBtn           = $('<input type="button" id="adace-edit-pins" class="button" value="'+ i18n.edit_pins +'">'),
		SaveBtn           = $('<input type="button" id="adace-save-pins" class="button button-primary" value="'+ i18n.save_pins +'">'),
		PublishBtn        = $('#publish'),
		AddPinBtn         = $('<input type="button" id="adace-add-pin" class="button button-primary" value="'+ i18n.add_pin +'">'),
		ShortcodeShortcut = $('<span class="shortcode description">[adace_shoppable_image attachment="' + adace_imagemap.ajax.AttachmentID + '"]</span>');
		// Wrap img with editor.
		Editor            = Img.wrap(Editor).parent();
		// Wrap img with board.
		Board             = Img.wrap(Board).parent();
		// Wrap img with surface.
		Img.wrap(Surface);
		// Append side panel.
		Editor.append(Side);
		// Insert edit btn.
		EditBtn.insertAfter(EditBtnPlace);
		// Insert editor controls.
		Side.find('.editor-controls').append(SaveBtn).append(AddPinBtn).append(ShortcodeShortcut);
		//Lets handle click.
		EditBtn.on('click', function(e){
			// Prevent defaults events.
			e.preventDefault();
			EditBtn.toggle();
			Side.toggle();
			Editor.toggleClass('active');
			// Init editor.
			adace_imagemap.functions.pin_editor.init(Board, Side.find('.editor-metaboxes'), AddPinBtn);
		});
		SaveBtn.on('click', function(e){
			// Prevent defaults events.
			e.preventDefault();
			EditBtn.toggle();
			Side.toggle();
			Editor.toggleClass('active');
			// Save editor.
			adace_imagemap.functions.pin_editor.save_attachment_pins();
		});
		PublishBtn.on('click', function(e){
			EditBtn.toggle();
			Side.toggle();
			Editor.toggleClass('active');
			// Save editor.
			adace_imagemap.functions.pin_editor.save_attachment_pins();
		});
	}
})(jQuery);

/**
 * Scripts for editor
 */
( function( $ ) {
	// Set for strict.
    'use strict';
	// Lets make special place for editor.
	adace_imagemap.functions.pin_editor = {};
	// Edit page functions home.
	adace_imagemap.functions.pin_editor.init = function(Board, MetaboxesPlace, AddPinBtn){
			// Reset all neccesary selectors and variables.
			adace_imagemap.functions.define.selectors();
			adace_imagemap.functions.define.variables();
			adace_imagemap.selectors.editor.Board          = Board;
			adace_imagemap.selectors.editor.MetaboxesPlace = MetaboxesPlace;
			adace_imagemap.selectors.editor.AddPinBtn      = AddPinBtn;
			// Call functions.
			adace_imagemap.functions.pin_editor.get_attachment_pins();
			adace_imagemap.functions.pin_editor.add_pin_on_board();
			adace_imagemap.functions.pin_editor.add_pin_on_btn();
	}
	// Close editor.
	adace_imagemap.functions.pin_editor.kill = function(){
		var
		Variables = adace_imagemap.variables,
		Pins      = adace_imagemap.selectors.editor.Pins,
		Metaboxes = adace_imagemap.selectors.editor.Metaboxes,
		Board     = adace_imagemap.selectors.editor.Board;
		// Check if we are not doing something important already.
		if( true === Variables.Working){
			return;
		} else {
			Variables.Working = true;
		}
		// Remove Pins
		Pins.remove();
		// Remove Metaboxes
		Metaboxes.remove();
		// Unwrap in just a moment to make sure that pins are gone.
		setTimeout(function(){
			Variables.Working = false;
		},100);
	}
	// Get pins from attachment meta.
	adace_imagemap.functions.pin_editor.get_attachment_pins = function(){
		var
		Variables = adace_imagemap.variables,
		Ajax      = adace_imagemap.ajax;
		// Check if we are not doing something important already.
		if( true === Variables.Working){
			return;
		} else {
			Variables.Working = true;
		}
		// Lets ask server nicely about attachment pins.
		jQuery.post(
			Ajax.Endpoint,
			{
				action         : Ajax.GetPinsAction,
				'Nonce'        : Ajax.Nonce,
				'AttachmentID' : Ajax.AttachmentID,
			},
			function(answer){
				// Parse answer.
				answer = $.parseJSON(answer);
				console.log(answer);
				// We are done with important things.
				Variables.Working = false;
				// Act on answer.
				switch(answer.status) {
				    case 'failure':
						// Log message.
						console.log(answer.message);
			        break;
				    case 'success':
						// Add Pins to variable.
						if( false !== answer.pins ){
							Variables.editor.Pins = answer.pins;
							// Render Pins.
							adace_imagemap.functions.pin_editor.render_pins();
						}
			        break;
				}
			}
		);
	}
	// Save pins to attachment meta.
	adace_imagemap.functions.pin_editor.save_attachment_pins = function(){
		var
		Variables = adace_imagemap.variables,
		Ajax      = adace_imagemap.ajax,
		Pins      = [];
		// Check if we are not doing something important already.
		if( true === Variables.Working){
			return;
		} else {
			Variables.Working = true;
		}
		// Sort them again before saving.
		adace_imagemap.functions.pin_editor.sort_pins();
		// Make clean array for AJAX, to make sure that we push proper data.
		$.each(Variables.editor.Pins, function(PinIndex, PinData){
			Pins.push({
				'pos_x'          : PinData.pos_x,
				'pos_y'          : PinData.pos_y,
				'type'           : PinData.type,
				'name'           : PinData.name,
				'price'          : PinData.price,
				'url'            : PinData.url,
				'woocommerce_id' : PinData.metabox.find('.adace-pin-product-id').val(),
			});
		});
		// Lets ask server nicely about saving attachment pins.
		jQuery.post(
			Ajax.Endpoint,
			{
				action         : Ajax.SavePinsAction,
				'Nonce'        : Ajax.Nonce,
				'AttachmentID' : Ajax.AttachmentID,
				'Pins'         : Pins,
			},
			function(answer){
				// Parse answer.
				answer = $.parseJSON(answer);
				console.log(answer);
				// We are done with important things.
				Variables.Working = false;
				// Kill this editor.
				adace_imagemap.functions.pin_editor.kill();
			}
		);
	}
	// Add pin on click.
	adace_imagemap.functions.pin_editor.add_pin_on_board = function(){
		var
		Variables  = adace_imagemap.variables,
		Surface = adace_imagemap.selectors.editor.Board.find('.board-surface');

		Surface.on('click', function(e){
			e.preventDefault();
			var
			ClickX        = e.offsetX,
			ClickY        = e.offsetY,
			SurfaceOffset = Surface.offset(),
			SurfaceWidth  = Surface.width(),
			SurfaceHeight = Surface.height(),
			PinSize       = 20,
			PinX          = ( (ClickX - PinSize) / (SurfaceWidth) * 100 ).toFixed(2),
			PinY          = ( (ClickY - PinSize) / (SurfaceHeight) * 100 ).toFixed(2),
			NewPin        = $.extend({}, adace_imagemap.variables.editor.DefaultPin);
			// Set position for new Pin.
			NewPin.pos_x = PinX;
			NewPin.pos_y = PinY;
			// Make it.
			adace_imagemap.functions.pin_editor.create_pin(NewPin);
		});
	}
	// Add new pin btn.
	adace_imagemap.functions.pin_editor.add_pin_on_btn = function(){
		var
		AddPinBtn = adace_imagemap.selectors.editor.AddPinBtn,
		NewPin    = $.extend({}, adace_imagemap.variables.editor.DefaultPin);
		// Handle clicks on this amazing btn.
		AddPinBtn.on('click', function(e){
			e.preventDefault();
			adace_imagemap.functions.pin_editor.create_pin(NewPin);
		});
	}
	// Add new pin btn.
	adace_imagemap.functions.pin_editor.create_pin = function(NewPin){
		var
		Variables      = adace_imagemap.variables,
		Board          = adace_imagemap.selectors.editor.Board,
		MetaboxesPlace = adace_imagemap.selectors.editor.MetaboxesPlace;
		// Check if we are not doing something important already.
		if( true === Variables.Working){
			return;
		} else {
			Variables.Working = true;
		}
		// Add it to variables.
		Variables.editor.Pins.push(NewPin);
		// Render control.
		adace_imagemap.functions.pin_editor.render_pin(NewPin);
		adace_imagemap.functions.pin_editor.render_metabox(NewPin);
		// Update selector.
		adace_imagemap.selectors.editor.Pins = Board.find('.imagemap-pin');
		adace_imagemap.selectors.editor.Metaboxes = MetaboxesPlace.find('.postbox');
		// Add little delay so they dont spasm this poor function.
		setTimeout(function(){
			Variables.Working = false;
			// Make it active.
			console.log(NewPin);
			NewPin.selector.find('.imagemap-pin-icon').trigger('click');
		}, 500);
	}
	// Add delete pin.
	adace_imagemap.functions.pin_editor.delete_pin = function(Pin){
		var
		Pins  = adace_imagemap.variables.editor.Pins,
		Board = adace_imagemap.selectors.editor.Board,
		MetaboxesPlace = adace_imagemap.selectors.editor.MetaboxesPlace,
		PinIndex = Pins.findIndex(function(CurrentPin){
			return CurrentPin === Pin;
		});
		// Remove it from DOM.
		Pin.selector.remove();
		Pin.metabox.remove();
		// Give it a moment to make sure we can proceed.
		setTimeout(function(){
			// Remove it from Pins.
			Pins.splice(PinIndex, 1);
			// Update selector
			adace_imagemap.selectors.editor.Pins = Board.find('.imagemap-pin');
			adace_imagemap.selectors.editor.Metaboxes = MetaboxesPlace.find('.postbox');
			// Sort Again
			adace_imagemap.functions.pin_editor.sort_pins();
		}, 100);
	}
	// Render attachment pins.
	adace_imagemap.functions.pin_editor.render_pins = function(){
		var
		Pins           = adace_imagemap.variables.editor.Pins,
		Board          = adace_imagemap.selectors.editor.Board,
		MetaboxesPlace = adace_imagemap.selectors.editor.MetaboxesPlace;
		// Render pins and attach controls, in reverse.
		$.each(Pins.reverse(), function(PinIndex, PinData){
			adace_imagemap.functions.pin_editor.render_pin(PinData);
			adace_imagemap.functions.pin_editor.render_metabox(PinData);
		});
		adace_imagemap.selectors.editor.Pins      = Board.find('.imagemap-pin');
		adace_imagemap.selectors.editor.Metaboxes = MetaboxesPlace.find('.postbox');
	}
	// Render attachment pin.
	adace_imagemap.functions.pin_editor.render_pin = function(Pin){
		// Pin as adace_imagemap.variables.editor.Pins.Pin
		var
		Board          = adace_imagemap.selectors.editor.Board,
		PinHTML        = adace_imagemap.variables.editor.html.Pin;
		// Create element for this pin.
		Pin.selector   = $(PinHTML);
		// Add proper style.
		Pin.selector.css({'left' : Pin.pos_x + '%', 'top': Pin.pos_y + '%' });
		// Attach variable to this selector so we know who we dance with.
		Pin.selector.data('Pin', Pin);
		// Fill with dreams.
		adace_imagemap.functions.pin_editor.render_pin_inside(Pin);
		// Add to board.
		Board.prepend(Pin.selector);
		// Make it clickable, draggable etc.
		adace_imagemap.functions.pin_editor.handle_pin(Pin);
		// Sort pins after adding this new one, with some delay to make sure its rendered.
		setTimeout(function(){
			adace_imagemap.functions.pin_editor.sort_pins();
		}, 100);
	}
	// Render Pin Insides HTML
	adace_imagemap.functions.pin_editor.render_pin_inside = function(Pin){
		if( 'custom_product' === Pin.type ){
			if( Pin.name ) {
				Pin.selector.find('.imagemap-pin-name').html(Pin.name);
			} else {
				Pin.selector.find('.imagemap-pin-name').text('');
			}
			if( Pin.price ) {
				Pin.selector.find('.imagemap-pin-price').text(Pin.price);
			} else {
				Pin.selector.find('.imagemap-pin-price').text('');
			}
			if( Pin.url ) {
				Pin.selector.find('.imagemap-pin-url').html('<a href="' + Pin.url + '" class="button button-primary" target="_blank">Buy from here</a>');
			} else {
				Pin.selector.find('.imagemap-pin-url').text('');
			}
		}
		if( 'woocommerce' === Pin.type ){
			if( Pin.woo_title ) {
				Pin.selector.find('.imagemap-pin-name').html( Pin.woo_title );
			} else {
				Pin.selector.find('.imagemap-pin-name').text('');
			}
			if( Pin.woo_price ) {
				Pin.selector.find('.imagemap-pin-price').html( Pin.woo_price );
			} else {
				Pin.selector.find('.imagemap-pin-price').text('');
			}
			if( Pin.woo_permalink ) {
				Pin.selector.find('.imagemap-pin-url').html('<a href="' + Pin.woo_permalink + '" class="button button-primary" target="_blank">Buy from here</a>');
			} else {
				Pin.selector.find('.imagemap-pin-url').text('');
			}
			if( Pin.woo_thumb ) {
				Pin.selector.find('.imagemap-pin-thumb').html( '<img width="120" height="120" src="' + Pin.woo_thumb + '" />');
			} else {
				Pin.selector.find('.imagemap-pin-thumb').text('');
			}
		}
	}
	// Handle pin behavior.
	adace_imagemap.functions.pin_editor.handle_pin = function(Pin){
		// Pin as adace_imagemap.variables.editor.Pins.Pin
		var
		Board = adace_imagemap.selectors.editor.Board;
		// Make sure that we dont attach triggers twice.
		if( Pin.selector.hasClass('trigger-happy') ){
			return;
		} else {
			Pin.selector.addClass('trigger-happy');
		}
		// Add Dragable to it, after append, need to be done this way.
		Pin.selector.draggable({
			containment: "parent",
			drag: function(){
				// Get new pin position, pin size and wrapper size. Get it here to make sure its accurate even after screen change.
            	var
				PinPosNew   = Pin.selector.position(),
				BoardWidth  = Board.outerWidth(),
				BoardHeight = Board.outerHeight();
				Pin.pos_x   = (PinPosNew.left/(BoardWidth) * 100).toFixed(2);
				Pin.pos_y   = (PinPosNew.top/(BoardHeight) * 100).toFixed(2);
				Pin.selector.css({'left' : Pin.pos_x + '%', 'top': Pin.pos_y + '%' });
				adace_imagemap.functions.pin_editor.sort_pins();
        	}
		});
		// Handle pin pokes.
		Pin.selector.find('.imagemap-pin-icon').on('click', function(e){
			e.preventDefault();
			// Remove active from all other pins.
			if(Pin.selector.hasClass('active')){
				// Remove active class and kill metabox.
				Pin.selector.removeClass('active');
				Pin.metabox.addClass('closed');
			} else {
				// Remove active from all other pins.
				adace_imagemap.selectors.editor.Pins.removeClass('active');
				Pin.selector.addClass('active');
				adace_imagemap.selectors.editor.Metaboxes.addClass('closed');
				Pin.metabox.removeClass('closed');
			}
		});
	}
	//  Render metabox.
	adace_imagemap.functions.pin_editor.render_metabox = function(Pin){
		var
		MetaboxesPlace = adace_imagemap.selectors.editor.MetaboxesPlace,
		MetaboxHTML    = adace_imagemap.variables.editor.html.Metabox;
		// Create metabox for this pin.
		Pin.metabox    = $(MetaboxHTML),
		Pin.id = adace_imagemap.functions.pin_editor.uniqId();
		// Fill.
		Pin.metabox.find('.adace-pin-type').attr('name', 'name_' + Pin.id);
		if( AdaceAdminVars.plugins.is_woocommerce == false ) {
			Pin.metabox.find('.adace-pin-type.woocommerce').attr('disabled', true);
		}
		Pin.metabox.find('.adace-pin-type.' + Pin.type).attr('checked', true);
		Pin.metabox.find('.adace-box-tab.' + Pin.type).addClass('current');
		Pin.metabox.find('.adace-pin-name').val(Pin.name);
		Pin.metabox.find('.adace-pin-price').val(Pin.price);
		Pin.metabox.find('.adace-pin-url').val(Pin.url);
		Pin.metabox.find('.adace-pin-product-id').val(Pin.woocommerce_id);
		// Add to side.
		MetaboxesPlace.append(Pin.metabox);
		// Make it clickable, draggable etc.
		adace_imagemap.functions.pin_editor.handle_metabox(Pin);
		// Sort pins after adding this new one, with some delay to make sure its rendered.
		setTimeout(function(){
			adace_imagemap.functions.pin_editor.sort_pins();
		}, 100);
		Pin.metabox.data( 'Pin', Pin );
		Pin.metabox.trigger('adacePinOpened');
	}
	//  Handle metabox.
	adace_imagemap.functions.pin_editor.handle_metabox = function(Pin){
		// Make sure that we dont attach triggers twice.
		if( Pin.metabox.hasClass('trigger-happy') ){
			return;
		} else {
			Pin.metabox.addClass('trigger-happy');
		}
		// Open/Close
		Pin.metabox.find('.handlediv, .hndle').on('click', function(e){
			e.preventDefault();
			// Remove active from all other pins.
			if(Pin.metabox.hasClass('closed')){
				// Remove active from all other pins.
				adace_imagemap.selectors.editor.Pins.removeClass('active');
				Pin.selector.addClass('active');
				adace_imagemap.selectors.editor.Metaboxes.addClass('closed');
				Pin.metabox.removeClass('closed');
			} else {
				// Remove active class and kill metabox.
				Pin.selector.removeClass('active');
				Pin.metabox.addClass('closed');
			}
		});
		// Handle Tabs.
		Pin.metabox.find('.adace-box-header').on('click', function(e){
			// Update data.
			Pin.type = Pin.metabox.find('.adace-box-header input:checked').val();
			// Swap Tabs.
			Pin.metabox.find('.adace-box-tab').removeClass('current');
			Pin.metabox.find('.adace-box-tab.' + Pin.type).addClass('current');
			adace_imagemap.functions.pin_editor.render_pin_inside(Pin);
		});
		// Update on name change.
		Pin.metabox.find('.adace-pin-name').on('keydown keyup paste copy focus unfocus', function(e){
			Pin.name = $(this).val();
			adace_imagemap.functions.pin_editor.render_pin_inside(Pin);
		});
		// Update on price change.
		Pin.metabox.find('.adace-pin-price').on('keydown keyup paste copy focus unfocus', function(e){
			Pin.price = $(this).val();
			adace_imagemap.functions.pin_editor.render_pin_inside(Pin);
		});
		// Update on price url.
		Pin.metabox.find('.adace-pin-url').on('keydown keyup paste copy focus unfocus', function(e){
			Pin.url = $(this).val();
			adace_imagemap.functions.pin_editor.render_pin_inside(Pin);
		});
		// Handle delete.
		Pin.metabox.find('.adace-delete-pin').on('click', function(e){
			e.preventDefault();
			if( confirm("Are you sure you want to do it?") ){
				adace_imagemap.functions.pin_editor.delete_pin(Pin);
			}
		});
	}
	// Sort pins on board.
	adace_imagemap.functions.pin_editor.sort_pins = function(){
		var
		Pins = adace_imagemap.variables.editor.Pins,
		Board = adace_imagemap.selectors.editor.Board,
		MetaboxesPlace = adace_imagemap.selectors.editor.MetaboxesPlace;
		// Sort pins.
		Pins.sort(function (a, b) {
			return + a.pos_y - + b.pos_y;
		});
		// Apply it to pins on board.
		$.each(Pins, function(PinIndex, PinData){
			Board.append(PinData.selector);
		});
		// Apply it to pins moxes.
		$.each(Pins, function(PinIndex, PinData){
			MetaboxesPlace.append(PinData.metabox);
		});
	}

	// Generate random id.
	adace_imagemap.functions.pin_editor.uniqId = function(){
	  return Math.round(new Date().getTime() + (Math.random() * 100));
	}

})(jQuery);
