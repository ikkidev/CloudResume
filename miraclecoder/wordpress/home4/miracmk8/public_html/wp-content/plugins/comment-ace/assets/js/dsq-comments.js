'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * Disqus Comments class
     *
     * Disqus Universal Code:
     * https://help.disqus.com/en/articles/1717258-web-integration
     *
     * Reload counter with:
     * DISQUSWIDGETS.getCount({reset: true});
     */
    var DsqComments = function () {
        function DsqComments(comments, config) {
            _classCallCheck(this, DsqComments);

            ca.log('[Init DSQ comments]', comments, config);
            this.comments = comments;
            this.config = config;

            this.load();
        }

        _createClass(DsqComments, [{
            key: 'load',
            value: function load() {
                var dsqComments = this.comments.querySelector('#disqus_thread');

                // Skip if root container not found.
                if (!dsqComments) {
                    ca.error('Disqus root container #disqus_thread not found!');
                    return;
                }

                // Skip if already rendered.
                if (dsqComments.querySelector('iframe')) {
                    ca.log('Skip. Disqus comments already rendered.');
                    return;
                }

                // Check if SDK loaded.
                if (typeof window.DISQUS === 'undefined') {
                    ca.log('Disqus JS SDK not loaded');
                    ca.log('Loading Disqus SDK...');

                    ca.log('Load Disqus SDK from URL: ' + this.config.sdk_url);
                    ca.log('Load Disqus for page URL: ' + this.config.page_url);

                    var _this = this;

                    window.disqus_config = function () {
                        this.page.url = _this.config.page_url;
                        this.page.identifier = _this.config.page_url;
                        this.language = _this.config.language;
                    };

                    var sdkScript = document.createElement('script');
                    sdkScript.id = 'cace-disqus-jssdk';
                    sdkScript.src = this.config.sdk_url;
                    sdkScript.setAttribute('data-timestamp', new Date());

                    document.head.append(sdkScript);
                }

                // Check if Disqus Count script loaded.
                /*
                if (typeof window.DISQUSWIDGETS === 'undefined') {
                    ca.log('Disqus Count JS not loaded');
                    ca.log('Loading Disqus Count JS...');
                     ca.log('Load Disqus Count JS from URL: ' + this.config.count_js_url);
                     const countSpan = document.createElement('span');
                    countSpan.classList.add('hidden', 'disqus-comment-count');
                    countSpan.setAttribute('data-disqus-identifier', new Date().getTime());
                     const countScript = document.createElement('script');
                    countScript.id  = 'dsq-count-scr';
                    countScript.src = this.config.count_js_url;
                    countScript.async = 'async';
                     // https://ygen.ca/blog/article/showing-accurate-disqus-comment-counts
                    dsqComments.before(countSpan);
                    dsqComments.before(countScript);
                }
                */
            }
        }]);

        return DsqComments;
    }();

    ca.DsqComments = DsqComments;
})();