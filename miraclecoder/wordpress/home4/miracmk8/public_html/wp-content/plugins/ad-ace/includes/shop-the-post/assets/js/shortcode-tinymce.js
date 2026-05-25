(function(config, $) {

	tinymce.PluginManager.add('adace_shop_the_post', function(editor) {

		editor.on('mouseup', function(e) {
			var $target 	= $(e.target);
			var $shortcode 	= $target.is('.bstp-shortcode') ? $target : $target.parents('.bstp-shortcode');

			if (0 === $shortcode.length) {
				return;
			}

			if ($target.is('.bstp-remove')) {
				if (confirm(config.l10n.remove_shortcode)) {
					editor.dom.remove($shortcode.get(0));
				}
			} else {
				var id  = $shortcode.data('bstp-id');
				var ids = $shortcode.data('bstp-ids').split(',');

				var modal = adace_stp.getModal(editor.id);

				modal.open('wc-edit', 'update', id, ids);
			}
		});

		/*
		 * Convert shortcode to preview while loading editor.
		 */
		editor.on('BeforeSetContent', function(e) {
			e.content = e.content.replace(/(<p>)?\s*<span class="bstp-placeholder" data-mce-contenteditable="false">&nbsp;<\/span>\s*(<\/p>)?/gi,'');
			e.content = e.content.replace(/^(\s*<p>)(\s*\[adace_shop_the_post)/, '$1<span class="bstp-placeholder" contentEditable="false">&nbsp;</span>$2');
			e.content = shortcodeToPreview(e.content);
		});

		/*
		 * Convert preview to shortcode before saving.
		 */
		editor.on('PostProcess', function(e) {
			if (e.get) {
				e.content = previewToShortcode(e.content);
			}
		});

		var shortcodeToPreview = function(content) {
				return content.replace(/\[adace_shop_the_post ([^\]]*)\]/g, function(shortcode) {
						return getShortcodePreview(shortcode);
				});
		};

		var getShortcodePreview = function (shortcode) {
			// Get shortcode ids.
			var foundId  = shortcode.match(/id="?'?([\d,]+)/i);
			var foundIds = shortcode.match(/ids="?'?([\d,]+)/i);

			if(!foundIds || !foundId) {
				return shortcode;
			}

			var id 					= foundId[1];
			var ids 				= foundIds[1];
			var encodedShortcode	= window.encodeURIComponent(shortcode);

			var xhr = $.ajax({
				type: 		'POST',
				url:		config.ajax_url,
				dataType:	'html',
				data: {
					action:     'adace_stp_shortcode_preview',
					security:	config.nonce,
					ids: 		ids
				}
			});

			xhr.done(function (preview) {
				var content = $(editor.iframeElement).contents().find('#tinymce').html();

				content = content.replace(new RegExp('<span id="bstp-preview-placeholder-'+ ids +'">[^<]+<\/span>'), preview);

				editor.setContent(content);
			});

			var placeholder = '';

			placeholder += '<div id="bstp-shortcode-'+ id +'" class="bstp-shortcode" contentEditable="false" data-bstp-shortcode="' + encodedShortcode + '" data-bstp-id="' + id + '" data-bstp-ids="' + ids + '" data-mce-resize="false" data-mce-placeholder="1" style="display: block; cursor: pointer; margin: 5px; padding: 10px; border: 1px solid #999;">';
				placeholder += '<span id="bstp-preview-placeholder-'+ ids +'">' + config.l10n.loading_preview + '</span>';
			placeholder += '</div>';

			return placeholder;
		};

		var previewToShortcode = function(content) {
			var getAttr = function(str, name) {
				name = new RegExp(name + '=\"([^\"]+)\"').exec(str);

				return name ? window.decodeURIComponent(name[1]) : '';
			};

			content = content.replace(/<p><span class="bstp-(?=(.*?span>))\1\s*<\/p>/g, '');
			content = content.replace(/<span class="bstp-.*?span>/g, '');

			return content.replace(/(?:<p(?: [^>]+)?>)*(<div [^>]+>[\s\S]*?<\/div>)(?:<\/p>)*/g, function(match, div) {
				var data = getAttr(div, 'data-bstp-shortcode');

				if (data) {
					return '<p>' + data + '</p>';
				}

				return match;
			});
		};

	});

})(adace_stp_modal, jQuery);
