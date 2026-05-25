'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * WordPress Comments class
     */
    var WpComments = function () {
        function WpComments(comments, config) {
            _classCallCheck(this, WpComments);

            this.comments = comments;
            this.config = config;

            this.init();
        }

        _createClass(WpComments, [{
            key: 'init',
            value: function init() {
                ca.log('[Init WP comments]', this.comments, this.config);

                // Comment Form.
                var formProps = {
                    wrapper: this.comments.querySelector('.comment-respond'),
                    hasComments: this.comments.querySelectorAll('.comment-list > .comment').length > 0
                };

                this.commentForm = new ca.WpCommentForm(formProps);

                // Comment List.
                var listProps = {
                    comments: this.comments,
                    guestCanVote: this.config.guest_can_vote,
                    collapseReplies: this.config.collapse_replies,
                    loadMoreType: this.config.load_more_type,
                    viewRepliesTpl: this.comments.querySelector('#cace-view-replies-tpl').textContent,
                    reportTpl: this.comments.querySelector('#cace-report-tpl').textContent
                };

                // Allow props filtering by other plugins.
                if (typeof window.caceWpCommentListPropsFilter === 'function') {
                    listProps = window.caceWpCommentListPropsFilter(listProps, this.comments);
                }

                this.commentList = new ca.WpCommentList(listProps);
            }
        }, {
            key: 'getCommentForm',
            value: function getCommentForm() {
                return this.commentForm;
            }
        }, {
            key: 'getCommentList',
            value: function getCommentList() {
                return this.commentList;
            }
        }]);

        return WpComments;
    }();

    ca.WpComments = WpComments;
})();