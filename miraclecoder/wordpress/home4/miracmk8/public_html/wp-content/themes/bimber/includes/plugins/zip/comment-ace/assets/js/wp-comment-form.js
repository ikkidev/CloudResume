'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * WordPress Comments class
     */
    var WpCommentForm = function () {
        function WpCommentForm(props) {
            _classCallCheck(this, WpCommentForm);

            this.props = props;

            this.init();
        }

        _createClass(WpCommentForm, [{
            key: 'init',
            value: function init() {
                ca.log('[Init WP Comment Form]', this.props);

                this.wrapper = this.props.wrapper;
                this.form = this.wrapper.querySelector('#commentform');

                if (!this.form) {
                    return;
                }

                this.submitButton = this.form.querySelector('#submit');

                if ( ! this.submitButton ) {
                    this.submitButton = {};
                }

                this.commentField = this.form.querySelector('textarea#comment');
                this.oldPlaceholder = this.commentField.placeholder;
                this.joinPlaceholder = this.commentField.dataset.caceJoinDiscussion;
                this.startPlaceholder = this.commentField.dataset.caceStartDiscussion;

                // Character Countdown.
                if (ca.WpCharacterCountdown) {
                    this.characterCountdown = new ca.WpCharacterCountdown(this.commentField);
                }

                // Reply with GIF.
                if (ca.WpReplyWithGif) {
                    this.replyWithGif = new ca.WpReplyWithGif(this.form);
                }

                this.blur();
                this.bindEvents();
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                // Form focus.
                this.commentField.addEventListener('focus', function () {
                    return _this.focus();
                });

                // Form inactive.
                this.form.addEventListener('snaxFormInactive', function () {
                    return _this.blur();
                });

                // Form submit.
                this.form.addEventListener('submit', function (e) {
                    return _this.onSubmit(e);
                });

                // Cancel Reply.
                this.wrapper.querySelector('#cancel-comment-reply-link').addEventListener('click', function () {
                    return _this.blur();
                });
            }
        }, {
            key: 'blur',
            value: function blur() {
                this.commentField.placeholder = this.props.hasComments ? this.joinPlaceholder : this.startPlaceholder;
                this.submitButton.disabled = true;
                this.form.classList.add('comment-form-blur');

                if (this.replyWithGif) {
                    this.replyWithGif.removeGif();
                }
            }
        }, {
            key: 'focus',
            value: function focus() {
                this.commentField.placeholder = this.oldPlaceholder;
                this.submitButton.disabled = false;
                this.form.classList.remove('comment-form-blur');
            }
        }, {
            key: 'onSubmit',
            value: function onSubmit(e) {
                var _this2 = this;

                e.preventDefault();
                e.stopImmediatePropagation();

                // Clear form errors.
                this.form.classList.remove('cace-validation-error');
                var formError = this.form.querySelector('.cace-error-message');

                if (formError) {
                    formError.remove();
                }

                // Validate form.
                if (!this.validate()) {
                    return;
                }

                // Indicate processing.
                this.submitButton.disabled = true;
                this.form.classList.add('cace-processing');

                var parentDepth = ca.extractCommentDepth(this.form.parent('.comment'));

                // Collect form fields.
                var formData = new window.FormData(this.form);
                formData.append('parent_depth', parentDepth);

                var params = new window.URLSearchParams(formData);
                var url = ca.ajax_url + '?action=commentace_comment&security=' + ca.nonce;

                fetch(url, {
                    method: 'POST',
                    cache: 'no-cache',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: params.toString()
                }).then(function (response) {
                    return response.json();
                }).then(function (response) {
                    // Clear processing.
                    _this2.submitButton.disabled = false;
                    _this2.form.classList.remove('cace-processing');

                    if ('success' === response.status) {
                        // Top level.
                        var list = _this2.form.parent('#respond').next('.comment-list');

                        // Depth 1, 2, ...
                        if (!list) {
                            list = _this2.form.parent('#respond').next('.children');

                            // If no children, create a new list.
                            if (!list) {
                                list = document.createElement('ul');
                                list.classList.add('children');

                                _this2.form.parent('#respond').parentNode.append(list);
                            }

                            var listParent = list.parent('.comment');

                            listParent.classList.remove('cace-children-collapsed');
                            listParent.classList.add('cace-children-expanded');

                            // Cancel and close Reply form.
                            var cancelElement = document.getElementById('cancel-comment-reply-link');
                            cancelElement.dispatchEvent(new Event('click'));
                        }

                        if (list) {
                            var comment = document.createElementFromString(response.args.comment_html);

                            list.prepend(comment);

                            list.dispatchEvent(new CustomEvent('caceNewCommentAddedToList', { detail: comment }));
                        }

                        // Clear the comment text. Other fields may be used again.
                        _this2.commentField.value = '';

                        _this2.blur();
                    } else {
                        _this2.form.classList.add('cace-validation-error');

                        var errorMessage = document.createElement('div');
                        errorMessage.classList.add('cace-error-message');
                        errorMessage.textContent = ca.decodeHTMLEntities(response.message);

                        _this2.form.prepend(errorMessage);
                    }
                });
            }
        }, {
            key: 'validate',
            value: function validate() {
                var isValid = true;

                // Check the Comment field.
                this.commentField.classList.remove('cace-validation-error');

                if (0 === this.commentField.value.length) {
                    this.commentField.classList.add('cace-validation-error');
                    isValid = false;
                }

                // Check <input> fields.
                var inputs = this.form.querySelectorAll('input[required=required]');

                inputs.forEach(function (input) {
                    // Remove existing errors.
                    input.classList.remove('cace-validation-error');

                    var invalidText = (input.matches('[type=text]') || input.matches('[type=email]')) && 0 === input.value.length;
                    var invalidCheckbox = input.matches('[type=checkbox]') && !input.checked;

                    if (invalidText || invalidCheckbox) {
                        input.classList.add('cace-validation-error');
                        isValid = false;
                    }
                });

                return isValid;
            }
        }]);

        return WpCommentForm;
    }();

    ca.WpCommentForm = WpCommentForm;
})();