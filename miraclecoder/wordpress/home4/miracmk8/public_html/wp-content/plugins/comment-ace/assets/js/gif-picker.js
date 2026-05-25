'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * GIF Picker class
     */
    var GIFPicker = function () {
        function GIFPicker(props) {
            _classCallCheck(this, GIFPicker);

            ca.log('[Init GIF Picker]', props);

            this.props = props;

            this.init();
            this.bindEvents();
        }

        _createClass(GIFPicker, [{
            key: 'init',
            value: function init() {
                this.gifs = [];
                this.searchValue = '';
                this.searchField = this.props.container.querySelector('.cace-gifpicker-search-field');
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                var container = this.props.container;

                // Toggle the picker.

                container.querySelector('.cace-drop-toggle').addEventListener('click', function () {
                    if (container.classList.contains('cace-drop-expanded')) {
                        _this.close();
                    } else {
                        _this.open();
                    }
                });

                // Search.
                if (this.searchField) {
                    this.searchField.addEventListener('keyup', function (e) {
                        _this.searchValue = e.target.value;
                        _this.search();
                    });
                }
            }
        }, {
            key: 'render',
            value: function render() {
                var _this2 = this;

                var container = this.props.container;


                var list = container.querySelector('.cace-gif-items');

                list.innerHTML = '';

                this.gifs.forEach(function (gif) {
                    var item = document.createElement('li');
                    item.classList.add('cace-gif-item');

                    var img = document.createElement('img');
                    img.classList.add('cace-gif');
                    img.src = gif.images.fixed_width.url;
                    img.onclick = function () {
                        _this2.select(gif);
                    };

                    item.append(img);

                    list.append(item);
                });
            }
        }, {
            key: 'open',
            value: function open() {
                var container = this.props.container;

                // Show the picker.

                container.classList.add('cace-drop-expanded');

                // Focus on the search input.
                if (this.searchField) {
                    this.searchField.focus();
                }

                // Perform search.
                this.search();
            }
        }, {
            key: 'close',
            value: function close() {
                var container = this.props.container;


                container.classList.remove('cace-drop-expanded');
            }
        }, {
            key: 'select',
            value: function select(gif) {
                var container = this.props.container;


                container.dispatchEvent(new CustomEvent('gifSelected', { detail: gif }));

                this.close();
            }
        }, {
            key: 'search',
            value: function search() {
                var _this3 = this;

                var apiEndpoints = this.props.apiEndpoints;


                if (!apiEndpoints.search_url) {
                    return;
                }

                if (this.searchValue.length < 1) {
                    this.loadTrending();
                    return;
                }

                var url = apiEndpoints.search_url + '&q=' + this.searchValue.replace(' ', '+');

                this.gifs = [];

                fetch(url, {
                    method: 'get'
                }).then(function (response) {
                    return response.json();
                }).then(function (response) {
                    _this3.gifs = response.data;
                    _this3.render();
                });
            }
        }, {
            key: 'loadTrending',
            value: function loadTrending() {
                var _this4 = this;

                var apiEndpoints = this.props.apiEndpoints;


                if (!apiEndpoints.trending_url) {
                    return;
                }

                fetch(apiEndpoints.trending_url, {
                    method: 'get'
                }).then(function (response) {
                    return response.json();
                }).then(function (response) {
                    _this4.gifs = response.data;
                    _this4.render();
                });
            }
        }]);

        return GIFPicker;
    }();

    ca.GIFPicker = GIFPicker;
})();