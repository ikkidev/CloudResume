'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * Comments controller class
     */
    var Comments = function () {
        function Comments(comments) {
            _classCallCheck(this, Comments);

            this.comments = comments;

            this.init();
            this.bindEvents();
        }

        _createClass(Comments, [{
            key: 'init',
            value: function init() {
                ca.log('[Init comments]', this.comments);

                this.tabs = this.comments.querySelectorAll('.cace-tab-item');
                this.types = this.comments.querySelectorAll('.cace-comment-type');
                this.activeTypes = {};

                var currentType = this.comments.querySelector('.cace-comment-type-current').dataset.commentType;

                this.activateType(currentType);
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                // Handle tabs.
                this.tabs.forEach(function (tab) {
                    return tab.addEventListener('click', function (e) {
                        e.preventDefault();
                        _this.selectTab(e.currentTarget);
                    });
                });
            }
        }, {
            key: 'getActiveType',
            value: function getActiveType(type) {
                if (type in this.activeTypes) {
                    return this.activeTypes[type];
                }

                return null;
            }
        }, {
            key: 'activateType',
            value: function activateType(type) {
                if (type in this.activeTypes) {
                    return;
                }

                var typeComments = this.comments.querySelector('.cace-comment-type-' + type);

                if (typeComments) {
                    var typeClass = type.charAt(0).toUpperCase() + type.slice(1) + 'Comments';
                    var configVar = 'commentace_' + type;

                    if (ca[typeClass] && window[configVar]) {
                        this.activeTypes[type] = new ca[typeClass](typeComments, window[configVar]);
                    }
                }
            }
        }, {
            key: 'selectTab',
            value: function selectTab(tab) {
                var type = tab.dataset.commentType;

                // Remove current selection.
                this.comments.querySelector('.cace-tab-item-current').classList.remove('cace-tab-item-current', 'g1-tab-item-current');
                this.comments.querySelector('.cace-comment-type-current').classList.remove('cace-comment-type-current');

                // Select target tab and its content.
                tab.classList.add('cace-tab-item-current', 'g1-tab-item-current');
                this.comments.querySelector('.cace-comment-type-' + type).classList.add('cace-comment-type-current');

                // Activate.
                this.activateType(type);
            }
        }]);

        return Comments;
    }();

    ca.Comments = Comments;
})();

// Init.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cace-comments').forEach(function (comments) {
        new ca.Comments(comments);
    });
});