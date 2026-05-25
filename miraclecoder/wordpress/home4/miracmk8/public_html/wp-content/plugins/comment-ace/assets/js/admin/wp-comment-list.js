'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global alert */

window.ca_admin = window.ca_admin || {};

(function () {
    /**
     * WP Admin Comment List class
     */
    var WpAdminCommentList = function () {
        function WpAdminCommentList(comments) {
            _classCallCheck(this, WpAdminCommentList);

            this.comments = comments;

            this.init();
            this.bindEvents();
        }

        _createClass(WpAdminCommentList, [{
            key: 'init',
            value: function init() {}
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                this.comments.querySelectorAll('.cace-row-action.cace-reject-report').forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();

                        _this.rejectReport(e.target);
                    });
                });
            }
        }, {
            key: 'rejectReport',
            value: function rejectReport(elem) {
                var _this2 = this;

                var commentId = elem.dataset.commentId;

                var requestData = {
                    comment_id: commentId
                };

                var params = new window.URLSearchParams(requestData);
                var url = ajaxurl + '?action=commentace_reject_report';

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
                        // Remove comment from list.
                        var comment = _this2.comments.querySelector('#comment-' + commentId);

                        comment.addEventListener('transitionend', function () {
                            comment.innerHTML = '<td colspan="7">' + response.args.info + '</td>';
                            comment.style.opacity = 1;
                        });

                        comment.style.opacity = 0;
                        comment.style.transition = 'opacity .2s ease-out';
                    } else {
                        alert(response.message);
                    }
                });
            }
        }]);

        return WpAdminCommentList;
    }();

    ca_admin.WpAdminCommentList = WpAdminCommentList;
})();

// Init.
document.addEventListener('DOMContentLoaded', function () {
    var comments = document.querySelector('#the-comment-list');

    if (!comments) {
        return;
    }

    ca_admin.wpCommentList = new ca_admin.WpAdminCommentList(comments);
});