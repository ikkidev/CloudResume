'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * WordPress Comment Copy Link class
     */
    var WpCommentCopyLink = function () {
        function WpCommentCopyLink(props) {
            _classCallCheck(this, WpCommentCopyLink);

            this.comment = props.comment;
            this.props = props;

            this.init();
            this.bindEvents();
        }

        _createClass(WpCommentCopyLink, [{
            key: 'init',
            value: function init() {}
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                // Copy to clipboard.
                this.comment.addEventListener('click', function (e) {
                    if (!e.target.classList.contains('cace-comment-link')) {
                        return;
                    }

                    e.preventDefault();

                    var link = e.target;
                    var text = link.href;

                    _this.copyToClipboard(text, link);
                });
            }
        }, {
            key: 'copyToClipboard',
            value: function copyToClipboard(text, link) {
                if (!navigator.clipboard) {
                    this.fallbackCopyToClipboard(text, link);
                    return;
                }

                navigator.clipboard.writeText(text).then(function () {
                    document.querySelectorAll('.cace-comment-link-copied').forEach(function (elem) {
                        elem.classList.remove('cace-comment-link-copied');
                        elem.classList.add('cace-comment-link-not-copied');
                    });

                    link.textContent = ca.i18n.copied_to_clipboard;
                    link.classList.remove('cace-comment-link-not-copied');
                    link.classList.add('cace-comment-link-copied');

                    ca.log('Clipboard API: Text was successfully copied', text, link);
                }, function (err) {
                    ca.error('Clipboard API: Could not copy text: ', err);
                });
            }
        }, {
            key: 'fallbackCopyToClipboard',
            value: function fallbackCopyToClipboard(text, link) {
                var textArea = document.createElement('textarea');
                textArea.value = text;

                // Avoid scrolling to bottom
                textArea.style.top = '0';
                textArea.style.left = '0';
                textArea.style.position = 'fixed';

                document.body.appendChild(textArea);

                // Focus and select.
                textArea.focus();
                textArea.select();

                try {
                    var successful = document.execCommand('copy');
                    if (successful) {
                        document.querySelectorAll('.cace-comment-link-copied').forEach(function (elem) {
                            elem.classList.remove('cace-comment-link-copied');
                            elem.classList.add('cace-comment-link-not-copied');
                        });

                        link.textContent = ca.i18n.copied_to_clipboard;
                        link.classList.remove('cace-comment-link-not-copied');
                        link.classList.add('cace-comment-link-copied');
                    }

                    ca.log('execCommand: Copy commend executed', text, link);
                } catch (err) {
                    ca.error('execCommand: Oops, unable to copy', err);
                }

                document.body.removeChild(textArea);
            }
        }]);

        return WpCommentCopyLink;
    }();

    ca.WpCommentCopyLink = WpCommentCopyLink;
})();