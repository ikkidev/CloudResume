'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */
/* global alert */

(function () {
    /**
     * WordPress Comment Report class
     */
    var WpCommentReport = function () {
        function WpCommentReport(props) {
            _classCallCheck(this, WpCommentReport);

            this.comment = props.comment;
            this.props = props;

            this.init();
            this.bindEvents();
        }

        _createClass(WpCommentReport, [{
            key: 'init',
            value: function init() {}
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                var userLoggedIn = this.props.userLoggedIn;


                var reportLink = this.comment.querySelector('.cace-comment-report');

                if (!reportLink) {
                    return;
                }

                reportLink.addEventListener('click', function (e) {
                    e.preventDefault();

                    var elem = e.target;

                    if (userLoggedIn) {
                        _this.loadForm();
                    } else {
                        if (elem.classList.contains('cace-login-required')) {
                            alert(ca.i18n.login_required);
                        }
                    }
                });
            }
        }, {
            key: 'createForm',
            value: function createForm() {
                var _this2 = this;

                this.form = document.createElementFromString(this.props.reportTpl);
                this.text = this.form.querySelector('#cace-report-text');
                this.submitButton = this.form.querySelector('#cace-report-submit');

                this.submitButton.disabled = true;

                if (ca.WpCharacterCountdown) {
                    new ca.WpCharacterCountdown(this.text);
                }

                // Cancel form.
                this.form.querySelector('#cace-report-cancel').addEventListener('click', function (e) {
                    e.preventDefault();
                    _this2.removeForm();
                });

                // Submit form.
                this.form.querySelector('#cace-report-submit').addEventListener('click', function (e) {
                    e.preventDefault();
                    _this2.submitForm();
                });

                // Toggle Submit button.
                this.text.addEventListener('keyup', function (e) {
                    _this2.submitButton.disabled = e.target.value.length < 1;
                });
            }
        }, {
            key: 'loadForm',
            value: function loadForm() {
                if (this.form) {
                    return;
                }

                this.createForm();

                this.comment.querySelector('.comment-footer').after(this.form);
            }
        }, {
            key: 'removeForm',
            value: function removeForm() {
                if (!this.form) {
                    return;
                }

                this.form.remove();
                this.form = null;
            }
        }, {
            key: 'submitForm',
            value: function submitForm() {
                var _this3 = this;

                // Indicate processing.
                this.form.classList.add('cace-processing');
                this.submitButton.disabled = true;

                var reportText = this.form.querySelector('#cace-report-text').value;

                var requestData = {
                    comment_id: this.props.commentId,
                    comment_depth: ca.extractCommentDepth(this.comment),
                    report_text: reportText
                };

                var params = new window.URLSearchParams(requestData);
                var url = ca.ajax_url + '?action=commentace_report&security=' + ca.nonce;

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
                    if ('success' === response.status) {
                        var comment = document.createElementFromString(response.args.comment_html);

                        var list = _this3.comment.parent('.comment-list');

                        _this3.comment.querySelector('.comment-body').replaceWith(comment.querySelector('.comment-body'));

                        list.dispatchEvent(new CustomEvent('caceNewCommentAddedToList', { detail: _this3.comment }));
                    } else {
                        ca.error(response.message);
                    }
                });
            }
        }]);

        return WpCommentReport;
    }();

    ca.WpCommentReport = WpCommentReport;
})();