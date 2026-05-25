'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */
/* global FB */

(function () {
    /**
     * Facebook Comments class
     *
     * Facebook Comments plugin:
     * https://developers.facebook.com/docs/plugins/comments/
     */
    var FbComments = function () {
        function FbComments(comments, config) {
            _classCallCheck(this, FbComments);

            ca.log('[Init FB comments]', comments, config);
            this.comments = comments;
            this.config = config;

            this.load();
        }

        _createClass(FbComments, [{
            key: 'load',
            value: function load() {
                var _this = this;

                var fbComments = this.comments.querySelector('.fb-comments');

                // Skip if root container not found.
                if (!fbComments) {
                    ca.error('Facebook root container .fb-comments not found!');
                    return;
                }

                // Skip if already rendered.
                if ('rendered' === fbComments.getAttribute('fb-xfbml-state')) {
                    ca.log('Skip. Facebook comments already rendered.');
                    return;
                }

                // Check if SDK loaded.
                if (typeof window.FB === 'undefined') {
                    ca.log('Facebook JS SDK not loaded');
                    ca.log('Loading Facebook SDK...');

                    this.comments.classList.remove('cace-fb-loaded-comments');
                    this.comments.classList.add('cace-fb-loading-comments');

                    window.fbAsyncInit = function () {
                        ca.log('Facebook JS SDK loaded');

                        _this.initSDK();
                    };

                    var sdkURL = this.config.sdk_url;

                    ca.log('Load SDK: ' + sdkURL);

                    var sdkScript = document.createElement('script');
                    sdkScript.id = 'cace-facebook-jssdk';
                    sdkScript.src = sdkURL;
                    sdkScript.async = 'async';
                    sdkScript.defer = 'defer';

                    document.body.prepend(sdkScript);
                }
            }
        }, {
            key: 'initSDK',
            value: function initSDK() {
                var _this2 = this;

                var appID = this.config.app_id;

                ca.log('Facebook App ID: ' + appID);

                if (!appID) {
                    ca.log('Quit, Facebook App ID not set!');
                    return;
                }

                FB.Event.subscribe('xfbml.render', function () {
                    _this2.comments.classList.add('cace-fb-loaded-comments');
                    _this2.comments.classList.remove('cace-fb-loading-comments');
                });

                FB.init({
                    appId: appID,
                    autoLogAppEvents: false,
                    xfbml: true,
                    version: 'v8.0'
                });

                ca.log('FB SDK initialized');
            }
        }]);

        return FbComments;
    }();

    ca.FbComments = FbComments;
})();