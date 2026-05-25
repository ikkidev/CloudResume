/* global adace_stp_modal */

var adace_stp = {};

/**
 * Modal
 */
(function(ctx, $) {

	var modals = {};

	ctx.modal = function(editorId) {
		var obj 			= {};
		var activeEditor 	= false;
		var frame 			= 'wc-insert';
		var frames			= {};
		var collection 		= false;
		var $modal;
		var $content;
		var $items;
		var currentState;
		var editId;

		var init = function() {
			activeEditor	= editorId;
			$modal 			= $('.adace-stp-modal-container');
			$content 		= $modal.find('.media-frame-content .frame-create');
			$items 			= $content.find('.attachments > li');
			collection 		= ctx.collection('wc', $modal);
			frames = {
				'wc-create': 	ctx.frame('wc-create', $modal),
				'wc-edit': 		ctx.frame('wc-edit', $modal)
			};

			bindEvents();

			return obj;
		};

		obj.open = function(frameId, state, id, itemsIds) {
			currentState = state || 'create';
			editId = id;

			collection.reset();
			collection.setState(currentState);

			collection.load(function() {
				updateCollection(itemsIds);
			});

			activeFrame(frameId);

			$('body').addClass('modal-open');
			$modal.show();
		};

		var updateCollection = function(itemsIds) {
			// Add items.
			if (typeof itemsIds !== 'undefined') {
				for (var i = 0; i < itemsIds.length; i++) {
					collection.add(itemsIds[i]);
				}
			}

			collection.updateSelection();
		};

		obj.close = function() {
			$('body').removeClass('modal-open');
			$modal.hide();
		};

		obj.getHtml = function() {
			return $modal;
		};

		var bindEvents = function() {
			// Close modal.
			$modal.on('click', '.media-modal-close', function(e) {
				e.preventDefault();

				obj.close();
			});

			// Switch to edition frame.
			$modal.on('editCollection', function() {
				activeFrame('wc-edit');
			});

			// Insert collection into post editor.
			$modal.on('insertCollection', function() {
				var ids = collection.getItemsIds();
				var id = new Date().getTime();

				var shortcode = getShortcode(id, ids);

				insertIntoEditor(shortcode);

				obj.close();

				$modal.trigger('collectionUpdated', [collection]);
			});

			// Update collection in post editor.
			$modal.on('updateCollection', function() {
				var ids = collection.getItemsIds();

				var shortcode = getShortcode(editId, ids);

				updateInEditor(editId, shortcode);

				obj.close();

				$modal.trigger('collectionUpdated', [collection]);
			});

			// Cancel edition.
			$modal.on('click', '.media-menu-item.adace-stp-cancel', function(e) {
				e.preventDefault();

				activeFrame('wc-create');
			});

			// Add to collection.
			$modal.on('click', '.media-menu-item.adace-stp-add', function(e) {
				e.preventDefault();

				activeFrame('wc-create');
			});

			// Close collection.
			$modal.on('click', '.media-menu-item.adace-stp-close', function(e) {
				e.preventDefault();

				obj.close();
			});
		};

		var activeFrame = function(id) {
			for (var frameId in frames) {
				if (id === frameId) {
					frames[frameId].activate(currentState);
				} else {
					frames[frameId].deactivate();
				}
			}
		};

		var insertIntoEditor = function(text) {
			if (activeEditor) {
				if (typeof tinyMCE === 'undefined' || !tinyMCE.get(activeEditor) || tinyMCE.get(activeEditor).isHidden()) {
					var $txtEditor = $('textarea#' + activeEditor);

					text += '\n\r';

					$txtEditor.val(text + $txtEditor.val());
				} else {
					text += '<br /><br />';

					tinyMCE.get(activeEditor).focus(true);
					tinyMCE.activeEditor.selection.collapse(false);
					tinyMCE.activeEditor.execCommand('mceInsertContent', false, text);
				}
			}
		};

		var updateInEditor = function(collectionId, shortcode) {
			if (activeEditor) {
				if (typeof tinyMCE !== 'undefined' && tinyMCE.get(activeEditor) && !tinyMCE.get(activeEditor).isHidden()) {
					tinyMCE.get(activeEditor).focus(true);
					tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.dom.select('div#bstp-shortcode-' + collectionId)[0]);
					tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode);
				}
			}
		};

		var getShortcode = function(id, ids) {
			return '['+ getShortcodeName() +' ids="'+ ids.join(',') +'" id="' + id + '"]';
		};

		var getShortcodeName = function() {
			return 'adace_shop_the_post';
		};

		return init();
	};

	ctx.getModal = function(editorId) {
		if (!modals[editorId]) {
			modals[editorId] = ctx.modal(editorId);
		}

		return modals[editorId];
	};

})(adace_stp, jQuery);

/**
 * Frame (modal view frame)
 */
(function(ctx, $) {

	ctx.frame = function(id, $modal) {
		var obj 		= {};
		var $elements;

		var init = function() {
			var selectors = [
				'.media-frame-menu .frame-' + id,
				'.media-frame-title .frame-' + id,
				'.media-frame-router .frame-' + id,
				'.media-frame-content .frame-' + id,
				'.media-frame-toolbar .frame-' + id
			];

			$elements = $modal.find(selectors.join(','));

			return obj;
		};

		obj.activate = function(state) {
			if (state) {
				$elements.find('.state').hide();
				$elements.find('.state-' + state).show();
			} else {
				$elements.find('.state').hide();
				$elements.find('.state-default').show();
			}

			$elements.show();
		};

		obj.deactivate = function() {
			$elements.hide();
		};

		return init();
	};

})(adace_stp, jQuery);

/**
 * Collection
 */
(function(ctx, config, $) {

	ctx.collection = function(id, $modal) {
		var obj 		= {};
		var state;
		var items		= [];	// We use array to prevent Chromium bug with numberic indexed objects (Chromium sorts them).
		var itemsCount 	= 0;
		var $spinner;
		var $categoryFilter;
		var $searchInput;
		var $collection;
		var $sidebar;
		var $items;
		var $editionItems;
		var $selection;
		var $itemsCount;
		var $clearSelection;
		var $selectedItems;
		var $createNew;
		var $insertButton;
		var $updateButton;
		var loaded 		= false;
		var filters		= {
			'text': 		'',
			'categoryId':	''
		};

		var init = function() {
			$spinner 		= $modal.find('.spinner');
			$categoryFilter = $modal.find('.media-frame-content .frame-create #wc-product-filters');
			$searchInput 	= $modal.find('.media-frame-content .frame-create .search-form .search');
			$collection 	= $modal.find('.media-frame-content .frame-create .attachments');
			$sidebar 		= $modal.find('.media-frame-content .frame-create .media-sidebar');
			$selection 		= $modal.find('.media-frame-toolbar .frame-create .media-selection');
			$itemsCount		= $selection.find('.selection-info .count > span');
			$clearSelection	= $selection.find('.selection-info .clear-selection');
			$selectedItems	= $selection.find('.selection-view .attachments');
			$createNew		= $modal.find('.media-frame-toolbar .frame-create .adace-stp-create-new');
			$editionItems 	= $modal.find('.media-frame-content .frame-edit .attachments');
			$insertButton	= $modal.find('.media-frame-toolbar .adace-stp-insert');
			$updateButton	= $modal.find('.media-frame-toolbar .frame-edit .adace-stp-update');

			$selection.hide();

			bindEvents();

			return obj;
		};

		obj.add = function(id) {
			var $item = $items.filter('[data-id=' + id + ']');

			var item = ctx.item(id, $item);

			item.select();

			items.push(item);
			itemsCount++;

			obj.updateSelection();

			showDetails(id);

			return item;
		};

		obj.remove = function(id) {
			id = parseInt(id, 10);

			for (var i = 0; i < items.length; i++) {
				if (id === items[i].getId()) {
					items[i].deselect();

					// Remove element from array at index i.
					items.splice(i, 1);
					itemsCount--;

					obj.updateSelection();
				}
			}
		};

		obj.reset = function() {
			var ids = obj.getItemsIds();

			for (var i = 0; i < ids.length; i++) {
				obj.remove(ids[i]);
			}
		};

		obj.setState = function(val) {
			state = val;
		};

		obj.isState = function(val) {
			return state && val === state;
		};

		var showDetails = function(id) {
			$items.removeClass('details');

			var $item = $items.filter('[data-id=' + id + ']');

			$item.addClass('details');

			obj.updateSelection();

			showItemInfoInSidebar($item);
			$sidebar.show();
		};

		obj.hideDetails = function() {
			$sidebar.hide();
		};

		var showItemInfoInSidebar = function($item) {
			var item = obj.getItemById($item.data('id'));

			if (item) {
				$sidebar.find('.details .title').text(item.getTitle());
				$sidebar.find('.thumbnail-image').html('<img src="' + item.getThumb() + '" />');
			}
		};

		obj.updateSelection = function() {
			if (itemsCount > 0) {
				$itemsCount.text(itemsCount);

				$selectedItems.empty();
				$editionItems.empty();

				for (var i = 0; i < items.length; i++) {
					var $item = items[i].getHtml();

					updateSelectionItems($item);
					updateEditionItems($item);
				}

				$selection.show();
				$editionItems.show();
				$createNew.removeAttr('disabled');
				$insertButton.removeAttr('disabled');
			} else {
				$selection.hide();
				$editionItems.hide();
				$createNew.attr('disabled', 'disabled');
				$insertButton.attr('disabled', 'disabled');
			}
		};

		obj.getItems = function() {
			return items;
		};

		obj.getItemsIds = function () {
			var ids = [];

			for (var i = 0; i <items.length; i++) {
				ids.push(items[i].getId());
			}

			return ids;
		};

		obj.getItemById = function(id) {
			for (var i = 0; i <items.length; i++) {
				if (id === items[i].getId()) {
					return items[i];
				}
			}

			return null;
		};

		obj.load = function(callback) {
			$spinner.addClass('is-active');

			if (!loaded) {
				var xhr = $.ajax({
					type: 		'GET',
					url:		config.ajax_url,
					dataType:	'html',
					data: {
						action:     'adace_stp_load_collection',
						security:	config.nonce,
						collection:	id
					}
				});

				xhr.done(function (html) {
					loaded = html;

					loadHtml(callback);
				});
			} else {
				loadHtml(callback);
			}
		};

		var loadHtml = function(callback) {
			$collection.html(loaded);

			$items = $collection.find('> li');

			bindItemsEvents();

			$spinner.removeClass('is-active');

			$searchInput.removeAttr('disabled');

			callback();
		};

		var updateSelectionItems = function($item) {
			var $selectionItem = $item.clone(true, true);

			$selectionItem.addClass('selection');
			$selectionItem.find('> button').remove();

			$selectedItems.append($selectionItem);
		};

		var updateEditionItems = function($item) {
			var $editionItem = $item.clone(true, true);

			$editionItem.removeClass('selected details');

			var $removeButton = $editionItem.find('button.check');
			$removeButton.removeClass('check');
			$removeButton.addClass('button-link attachment-close media-modal-icon');

			$editionItem.find('.thumbnail').after($removeButton);

			$editionItems.append($editionItem);

            $editionItems.sortable({
                'stop': function() {
                    updateItemsOrder();
                }
            });
		};

        var updateItemsOrder = function() {
            var newItems = [];

			// Build temporary quick access list (we will need to get item by id while sorting).
			var itemsById = {};

			for (var i = 0; i < items.length; i++) {
				var item = items[i];

				itemsById[item.getId()] = item;
			}

			// Update items order.
            $editionItems.children().each(function() {
                var itemId = $(this).data('id');

                newItems.push(itemsById[itemId]);
            });

            items = newItems;

			obj.updateSelection();
        };

		obj.filter = function() {
			$items.show().filter(function() {
				var title = $(this).find('.title').text().toLowerCase();
				var categoryIds = $(this).data('category-ids').toString().split(',');

				var dontMatch = false;

				// Filter by text.
				if (filters.text.length >= 2) {
					dontMatch = ( -1 === title.indexOf(filters.text) );
				}

				// Filter by category.
				if (filters.categoryId) {
					dontMatch = ( -1 === $.inArray(filters.categoryId, categoryIds) );
				}

				return dontMatch;
			}).hide();
		};

		var bindEvents = function() {
			// Clear selection.
			$clearSelection.on('click', function(e) {
				e.preventDefault();

				obj.reset();
			});

			// Create new collection.
			$createNew.on('click', function(e) {
				e.preventDefault();

				var state = $(this).data('state');

				$modal.trigger('editCollection', [id]);
			});

			// Insert collection.
			$insertButton.on('click', function(e) {
				e.preventDefault();

				$modal.trigger('insertCollection');
			});

			// Update collection.
			$updateButton.on('click', function(e) {
				e.preventDefault();

				$modal.trigger('updateCollection');
			});

			// Search.
			$searchInput.on('input', function() {
				filters.text = $.trim($(this).val()).toLowerCase();

				obj.filter();
			});

			// Filter by category.
			$categoryFilter.on('change', function() {
				filters.categoryId = $(this).val();

				obj.filter();
			});
		};

		var bindItemsEvents = function() {
			// Select/Deselect item.
			$items.on('click', function(e) {
				e.preventDefault();

				if (obj.isState('single-selection')) {
					obj.reset();
				}

				var $item  = $(this);
				var itemId = $item.data('id');

				// @todo - fix it.
				if ($item.parents('.frame-wc-create').length === 0) {
					return;
				}

				if ($item.is('.selected')) {
					showDetails(itemId);
				} else {
					obj.add(itemId);
				}
			});

			// Remove item.
			$items.on('click', 'button.check, button.attachment-close', function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();

				var $item = $(this).parents('li.attachment');

				if ($item.hasClass('details')) {
					obj.hideDetails();
				}

				obj.remove($item.data('id'));
			});
		};

		return init();
	};

})(adace_stp, adace_stp_modal, jQuery);

/**
 * Collection item
 */
(function(ctx, $) {

	ctx.item = function(itemId, $itemObj) {
		var obj = {};
		var id;
		var $item;

		var init = function() {
			id 		= itemId;
			$item 	= $itemObj;

			return obj;
		};

		obj.getId = function() {
			return parseInt(id, 10);
		};

		obj.select = function() {
			$item.addClass('selected');
		};

		obj.deselect = function() {
			$item.removeClass('details selected');
		};

		obj.getHtml = function() {
			return $itemObj;
		};

		obj.getTitle = function() {
			return $item.find('.title').text();
		};

		obj.getPrice = function() {
			return $item.data('price');
		};

		obj.getUrl = function() {
			return $item.data('url');
		};

		obj.getThumb = function() {
			return $item.find('.thumbnail img').attr('src');
		};

		return init();
	};

})(adace_stp, jQuery);

/**
 * Bind with WP UI.
 */
(function(ctx, $) {

	$(document).ready(function() {

		// Open modal.
		$('.adace-stp-modal-button').on('click', function() {
			var editorId = $(this).data('editor');

			var modal = ctx.getModal(editorId);

			modal.open('wc-create', 'create', null, []);
		});
	});

})(adace_stp, jQuery);
