'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * Reply with GIF class
     */
    var WpReplyWithGif = function () {
        function WpReplyWithGif(form) {
            _classCallCheck(this, WpReplyWithGif);

            this.form = form;

            this.init();
            this.bindEvents();
        }

        _createClass(WpReplyWithGif, [{
            key: 'init',
            value: function init() {
                ca.log('[Init WP Reply With GIF]');

                this.pickerContainer = this.form.parent('.g1-comment-type').querySelector('.cace-drop-the-gifpicker');
                this.commentField = this.form.querySelector('textarea#comment');
                this.commentFieldWrapper = this.form.querySelector('.comment-form-comment');

                this.gifPicker = new ca.GIFPicker({
                    container: this.pickerContainer,
                    apiEndpoints: window.commentace_gif_picker
                });

                this.initToolbar();
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                // GIF selected.
                this.pickerContainer.addEventListener('gifSelected', function (e) {
                    return _this.onGifSelection(e);
                });
            }
        }, {
            key: 'initToolbar',
            value: function initToolbar() {
                var toolbar = document.createElement('div');
                toolbar.classList.add('cace-comment-toolbar');
                this.commentField.after(toolbar);
                toolbar.append(this.pickerContainer);
            }
        }, {
            key: 'removeGif',
            value: function removeGif() {
                if (this.gif) {
                    this.gif.remove();
                }
            }
        }, {
            key: 'onGifSelection',
            value: function onGifSelection(e) {
                var _this2 = this;

                var gifObj = e.detail;

                var video = document.createElementFromString('<video preload="none" autoplay playsinline muted loop>\n                    <source src="' + gifObj.images.original.mp4 + '" type="video/mp4"></source>\n                </video>');

                var button = document.createElement('button');
                button.classList.add('cace-button-reset', 'cace-gif-remove');
                button.type = 'button';
                button.title = ca.i18n.remove;
                button.textContent = ca.i18n.remove;

                this.gif = document.createElement('figure');
                this.gif.classList.add('cace-gif');
                this.gif.append(video);

                // Afmaszter it's in DOM, we have to load it.
                video.load();

                this.gif.append(button);

                button.addEventListener('click', function () {
                    _this2.removeGif();
                    _this2.commentField.value = '';
                    _this2.commentField.focus();
                });

                this.commentField.value = gifObj.embed_url;
                this.commentField.focus();

                this.commentFieldWrapper.before(this.gif);
            }
        }]);

        return WpReplyWithGif;
    }();

    ca.WpReplyWithGif = WpReplyWithGif;
})();