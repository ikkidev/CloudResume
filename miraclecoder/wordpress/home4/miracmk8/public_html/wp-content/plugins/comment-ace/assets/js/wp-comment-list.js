'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * WordPress Comments class
     */
    var WpCommentList = function () {
        function WpCommentList(props) {
            _classCallCheck(this, WpCommentList);

            this.props = props;

            this.init();
        }

        _createClass(WpCommentList, [{
            key: 'init',
            value: function init() {
                ca.log('[Init WP Comment List]', this.props);

                this.initList();
                this.initLoadMore();
                this.initTools();
            }
        }, {
            key: 'initList',
            value: function initList() {
                var _this = this;

                this.list = this.props.comments.querySelector('.comment-list');
                this.comments = [];

                // Init comments.
                this.list.querySelectorAll('.comment').forEach(function (comment) {
                    _this.add(comment);
                });

                this.bindListEvents();
            }
        }, {
            key: 'bindListEvents',
            value: function bindListEvents() {
                var _this2 = this;

                // New comment added to the DOM.
                this.list.addEventListener('caceNewCommentAddedToList', function (e) {
                    _this2.add(e.detail);
                });
            }
        }, {
            key: 'initLoadMore',
            value: function initLoadMore() {
                this.loadMore = this.props.comments.querySelector('.cace-load-more');
                this.loading = false;
                this.allLoaded = false;
                this.loadOnScroll = false;

                if (this.loadMore) {
                    var loadMoreType = this.props.loadMoreType;

                    // Init label.

                    this.loadMore.innerText = 'infinite_scroll' === loadMoreType ? this.loadMore.dataset.loadingLabel : this.loadMore.dataset.loadLabel;

                    this.bindLoadMoreEvents();
                }
            }
        }, {
            key: 'bindLoadMoreEvents',
            value: function bindLoadMoreEvents() {
                var _this3 = this;

                var loadMoreType = this.props.loadMoreType;

                // Scroll.

                if ('infinite_scroll' === loadMoreType || 'infinite_scroll_on_demand' === loadMoreType) {
                    // Prevent adding it twice, e.g. when Load More is reloaded and we have to bind events to the new one.
                    if (!this.scrollEventAdded) {
                        window.addEventListener('scroll', ca.debounce(function () {
                            _this3.checkIfLoadMore();
                        }));

                        this.scrollEventAdded = true;
                    }

                    // Allow loading and check current position.
                    if ('infinite_scroll' === loadMoreType) {
                        this.loadOnScroll = true;

                        // Check on init.
                        this.checkIfLoadMore();
                    }
                }

                // Click.
                if ('infinite_scroll_on_demand' === loadMoreType || 'load_more' === loadMoreType) {
                    this.loadMore.addEventListener('click', function (e) {
                        return _this3.onClickHandler(e);
                    });
                }
            }
        }, {
            key: 'onClickHandler',
            value: function onClickHandler(e) {
                var _this4 = this;

                e.preventDefault();

                this.loadMoreComments(this.loadMore.href, function () {
                    var loadMoreType = _this4.props.loadMoreType;


                    if ('infinite_scroll_on_demand' === loadMoreType) {
                        // Change label.
                        _this4.loadMore.innerText = _this4.loadMore.dataset.loadingLabel;

                        // Prevent further clicks.
                        _this4.loadMore.removeEventListener('click', _this4.onClickHandler);

                        // Allow loading.
                        _this4.loadOnScroll = true;

                        // Check if after loading new posts, the Load More is in the viewport and new comments should be loaded.
                        _this4.checkIfLoadMore();
                    }
                });
            }
        }, {
            key: 'initTools',
            value: function initTools() {
                this.tools = this.props.comments.querySelector('.cace-comments-tools');

                if (!this.tools) {
                    return;
                }

                this.collapseReplies = this.tools.querySelector('.cace-toggle-replies-collapse');
                this.expandReplies = this.tools.querySelector('.cace-toggle-replies-expand');
                this.changeOrder = this.tools.querySelector('#cace-comment-order');

                this.bindToolsEvents();
            }
        }, {
            key: 'bindToolsEvents',
            value: function bindToolsEvents() {
                var _this5 = this;

                // Collapse all replies.
                this.collapseReplies.addEventListener('click', function (e) {
                    e.preventDefault();

                    _this5.list.querySelectorAll('.comment.depth-1.parent').forEach(function (elem) {
                        elem.classList.remove('cace-children-expanded');
                        elem.classList.add('cace-children-collapsed');
                    });

                    _this5.disableCollapseReplies();
                    _this5.enableExpandReplies();
                });

                // Expand all replies.
                this.expandReplies.addEventListener('click', function (e) {
                    e.preventDefault();

                    _this5.list.querySelectorAll('.comment.depth-1.parent').forEach(function (elem) {
                        elem.classList.remove('cace-children-collapsed');
                        elem.classList.add('cace-children-expanded');
                    });

                    _this5.disableExpandReplies();
                    _this5.enableCollapseReplies();
                });

                // Change order.
                this.changeOrder.addEventListener('change', function (e) {
                    var elem = e.target;

                    var url = elem.getAttribute('data-tpl-url').replace('%ORDER%', elem.value);

                    _this5.reloadComments(url);
                });
            }
        }, {
            key: 'add',
            value: function add(comment) {
                var _this6 = this;

                var commentProps = {
                    comment: comment,
                    guestCanVote: this.props.guestCanVote,
                    collapseReplies: this.props.collapseReplies,
                    viewRepliesTpl: this.props.viewRepliesTpl,
                    reportTpl: this.props.reportTpl
                };

                this.comments.push(new ca.WpComment(commentProps));

                comment.addEventListener('caceRepliesExpanded', function () {
                    _this6.enableCollapseReplies();
                });
            }
        }, {
            key: 'checkIfLoadMore',
            value: function checkIfLoadMore() {
                if (this.loading || this.allLoaded || !this.loadOnScroll) {
                    return;
                }

                if (this.loadMore.isInViewport(500)) {
                    this.loadMoreComments(this.loadMore.href);
                }
            }
        }, {
            key: 'loadMoreComments',
            value: function loadMoreComments(url, callback) {
                var _this7 = this;

                var comments = this.props.comments;


                var commentCount = this.list.querySelectorAll('.comment.depth-1').length;

                var urlObj = new URL(url);
                urlObj.searchParams.set('cace-offset', commentCount);
                url = urlObj.toString();

                ca.log('Loading new comments...', url);
                this.loading = true;
                comments.classList.remove('cace-loaded-comments');
                comments.classList.add('cace-loading-comments');

                fetch(url).then(function (response) {
                    return response.text();
                }).then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');

                    // Remove elements that may contain comments.
                    doc.querySelectorAll('.snax-post-container').forEach(function (elem) {
                        return elem.remove();
                    });

                    // Update comment list.
                    doc.querySelector('.comment-list').childNodes.forEach(function (node) {
                        // Only Element node.
                        if (node.nodeType !== 1) {
                            return;
                        }

                        _this7.list.append(node);

                        // Comment.
                        if (node.classList.contains('comment')) {
                            _this7.add(node);
                        }
                    });

                    // Update Load More link.
                    var newLoadMore = doc.querySelector('.cace-load-more');

                    if (newLoadMore) {
                        _this7.loadMore.href = newLoadMore.href;
                    } else {
                        _this7.loadMore.remove();
                        _this7.loadMore = null;
                        _this7.allLoaded = true;
                    }

                    _this7.loading = false;
                    comments.classList.remove('cace-loading-comments');
                    comments.classList.add('cace-loaded-comments');

                    _this7.checkIfLoadMore();

                    if (newLoadMore && callback) {
                        callback();
                    }
                });
            }
        }, {
            key: 'reloadComments',
            value: function reloadComments(url) {
                var _this8 = this;

                var comments = this.props.comments;


                ca.log('Reloading comments...', url);
                this.loading = true;
                comments.classList.remove('cace-sorted-comments');
                comments.classList.add('cace-sorting-comments');

                fetch(url).then(function (response) {
                    return response.text();
                }).then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');

                    // Remove elements that may contain comments.
                    doc.querySelectorAll('.snax-post-container').forEach(function (elem) {
                        return elem.remove();
                    });

                    // Remove old Load More link.
                    if (_this8.loadMore) {
                        _this8.loadMore.parent('.comment-list-pagination').remove();
                    }

                    var newLoadMore = doc.querySelector('.comment-list-pagination');

                    // Insert new link before replacing the list.
                    if (newLoadMore) {
                        _this8.list.after(newLoadMore);
                    }

                    _this8.list.replaceWith(doc.querySelector('.comment-list'));

                    _this8.loading = false;

                    _this8.initList();
                    _this8.initLoadMore();

                    comments.classList.remove('cace-sorting-comments');
                    comments.classList.add('cace-sorted-comments');

                    _this8.checkIfLoadMore();
                });
            }
        }, {
            key: 'enableExpandReplies',
            value: function enableExpandReplies() {
                this.expandReplies.disabled = false;
            }
        }, {
            key: 'disableExpandReplies',
            value: function disableExpandReplies() {
                this.expandReplies.disabled = true;
            }
        }, {
            key: 'enableCollapseReplies',
            value: function enableCollapseReplies() {
                this.collapseReplies.disabled = false;
            }
        }, {
            key: 'disableCollapseReplies',
            value: function disableCollapseReplies() {
                this.collapseReplies.disabled = true;
            }
        }]);

        return WpCommentList;
    }();

    ca.WpCommentList = WpCommentList;
})();