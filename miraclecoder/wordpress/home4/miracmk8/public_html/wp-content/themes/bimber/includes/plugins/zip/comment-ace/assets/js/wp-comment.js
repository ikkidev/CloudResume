'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * WordPress Comment class
     */
    var WpComment = function () {
        function WpComment(props) {
            _classCallCheck(this, WpComment);

            this.comment = props.comment;
            this.props = props;

            this.init();
            this.bindEvents();
        }

        _createClass(WpComment, [{
            key: 'init',
            value: function init() {
                this.initContentVideo();
                this.initViewReplies();
                this.initVotes();
                this.initReporting();
                this.initCopyLink();

                document.body.dispatchEvent(new CustomEvent('caceWpCommentInitialized', { detail: this.comment }));
            }
        }, {
            key: 'initContentVideo',
            value: function initContentVideo() {
                // Load video.
                var videoInComment = this.comment.querySelector('video');

                if (videoInComment) {
                    videoInComment.load();
                }
            }
        }, {
            key: 'initViewReplies',
            value: function initViewReplies() {
                if (!this.comment.classList.contains('depth-1') || !this.comment.classList.contains('parent')) {
                    return;
                }

                // Clean up state.
                this.viewReplies = this.comment.querySelector('.cace-view-replies');

                if (this.viewReplies) {
                    this.viewReplies.remove();
                    this.viewReplies = null;
                }

                var hash = window.location.hash;

                // If URL contains hash (#comment-123), and the target comment is one of the replies, don't collapse.
                if (hash && this.comment.querySelector(hash)) {
                    return;
                }

                var replies = this.comment.querySelectorAll('.comment').length;
                var button = this.props.viewRepliesTpl.replace('%d', replies);

                this.viewReplies = document.createElementFromString(button);

                this.comment.querySelector('ul.children').before(this.viewReplies);

                // Init state.
                if (this.props.collapseReplies) {
                    this.comment.classList.add('cace-children-collapsed');
                    this.comment.classList.remove('cace-children-expanded');
                } else {
                    this.comment.classList.add('cace-children-expanded');
                    this.comment.classList.remove('cace-children-collapsed');
                }
            }
        }, {
            key: 'initVotes',
            value: function initVotes() {
                if (!ca.WpCommentVotes) {
                    return;
                }

                var commentId = this.getId();

                var props = {
                    comment: this.comment,
                    commentId: commentId,
                    guestCanVote: this.props.guestCanVote
                };

                this.votes = new ca.WpCommentVotes(props);

                if (!ca.isUserLoggedIn()) {
                    var guestVote = ca.WpCommentGuestVoteStorage.get(commentId);

                    if (guestVote) {
                        this.votes.vote(guestVote, false);
                    }
                }
            }
        }, {
            key: 'initReporting',
            value: function initReporting() {
                if (!ca.WpCommentReport) {
                    return;
                }

                var commentId = this.getId();

                var props = {
                    comment: this.comment,
                    commentId: commentId,
                    reportTpl: this.props.reportTpl,
                    userLoggedIn: ca.isUserLoggedIn()
                };

                this.report = new ca.WpCommentReport(props);
            }
        }, {
            key: 'initCopyLink',
            value: function initCopyLink() {
                if (!ca.WpCommentCopyLink) {
                    return;
                }

                var commentId = this.getId();

                var props = {
                    comment: this.comment,
                    commentId: commentId
                };

                this.copyLink = new ca.WpCommentCopyLink(props);
            }
        }, {
            key: 'getId',
            value: function getId() {
                return ca.extractCommentId(this.comment);
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                // Toggle comment replies.
                if (this.viewReplies) {
                    this.viewReplies.addEventListener('click', function (e) {
                        e.preventDefault();

                        var button = e.target;

                        var parent = button.parent('.comment.depth-1.parent');
                        parent.classList.remove('cace-children-collapsed');
                        parent.classList.add('cace-children-expanded');

                        _this.comment.dispatchEvent(new Event('caceRepliesExpanded'));
                    });
                }
            }
        }]);

        return WpComment;
    }();

    ca.WpComment = WpComment;
})();