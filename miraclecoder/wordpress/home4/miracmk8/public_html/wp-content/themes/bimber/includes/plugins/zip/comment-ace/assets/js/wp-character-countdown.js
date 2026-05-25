'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * Character Countdown class
     */
    var WpCharacterCountdown = function () {
        function WpCharacterCountdown(commentField) {
            _classCallCheck(this, WpCharacterCountdown);

            this.commentField = commentField;

            this.init();
            this.bindEvents();
        }

        _createClass(WpCharacterCountdown, [{
            key: 'init',
            value: function init() {
                ca.log('[Init WP Charcter Countdown]', this.commentField);

                this.charactersLimit = this.commentField.getAttribute('maxLength');

                this.renderCountdown();
            }
        }, {
            key: 'renderCountdown',
            value: function renderCountdown() {
                this.countdown = document.createElement('span');
                this.countdown.classList.add('cace-character-countdown');
                this.countdown.textContent = this.charactersLimit;
                this.commentField.after(this.countdown);
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                // Limit number of characters.
                this.commentField.addEventListener('keyup', function () {
                    return _this.applyCharactersLimit();
                });
            }
        }, {
            key: 'applyCharactersLimit',
            value: function applyCharactersLimit() {
                var length = this.commentField.value.length;
                length = this.charactersLimit - length;
                this.countdown.textContent = length;
            }
        }]);

        return WpCharacterCountdown;
    }();

    ca.WpCharacterCountdown = WpCharacterCountdown;
})();