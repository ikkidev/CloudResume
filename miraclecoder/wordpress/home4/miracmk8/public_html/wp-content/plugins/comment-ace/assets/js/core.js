'use strict';

/* global commentace */
/* global console */

(function () {

    'use strict';

    /**
     * Check whether the user is logged in
     */

    commentace.isUserLoggedIn = function () {
        return commentace.user_logged_in;
    };

    /**
     * Extract ID from comment HTML tag
     */
    commentace.extractCommentId = function (comment) {
        return parseInt(comment.getAttribute('id').replace(/^[^\d]+/, ''), 10);
    };

    /**
     * Extract ID from comment HTML tag
     */
    commentace.extractCommentDepth = function (comment) {
        if (!comment) {
            return 0;
        }

        return comment.getAttribute('class').match(/depth-\d+/)[0].replace(/[^\d]+/, '');
    };

    commentace.decodeHTMLEntities = function (text) {
        var elem = document.createElement('textarea');
        elem.innerHTML = text;

        return elem.value;
    };

    /**
     * Format number according to WordPress locale
     */
    commentace.numberFormatI18N = function (number) {
        var decimals = commentace.number_format.decimals;
        var decPoint = commentace.number_format.dec_point;
        var thousandsSep = commentace.number_format.thousands_sep;

        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number;
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        var sep = typeof thousandsSep === 'undefined' ? ',' : thousandsSep;
        var dec = typeof decPoint === 'undefined' ? '.' : decPoint;
        var s = '';

        var toFixedFix = function toFixedFix(n, prec) {
            if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
            } else {
                var arr = ('' + n).split('e');
                var sig = '';
                if (+arr[1] + prec > 0) {
                    sig = '+';
                }

                return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
            }
        };

        s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }

        return s.join(dec);
    };

    /**
     * Log
     */
    commentace.log = function () {
        if (!commentace.in_debug_mode) {
            return;
        }

        if (typeof console !== 'undefined') {
            console.group('CommentAce');

            for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
                args[_key] = arguments[_key];
            }

            args.forEach(function (msg) {
                return console.log(msg);
            });
            console.groupEnd('CommentAce');
        }
    };

    /**
     * Error log
     */
    commentace.error = function () {
        if (!commentace.in_debug_mode) {
            return;
        }

        if (typeof console !== 'undefined') {
            console.group('CommentAce');

            for (var _len2 = arguments.length, args = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
                args[_key2] = arguments[_key2];
            }

            args.forEach(function (msg) {
                return console.error(msg);
            });
            console.groupEnd('CommentAce');
        }
    };

    /**
     * Extend Element class with parent() method
     */
    if (!('parent' in Element.prototype)) {
        Element.prototype.parent = function (selector) {
            var elem = this;
            var haveSelector = selector !== undefined;

            while ((elem = elem.parentElement) !== null) {
                if (elem.nodeType !== Node.ELEMENT_NODE) {
                    continue;
                }

                if (!haveSelector || elem.matches(selector)) {
                    return elem;
                }
            }

            return null;
        };
    }

    /**
     * Extend Element class with next() method
     */
    if (!('next' in Element.prototype)) {
        Element.prototype.next = function (selector) {
            var elem = this;
            var haveSelector = selector !== undefined;

            while ((elem = elem.nextSibling) !== null) {
                if (elem.nodeType !== Node.ELEMENT_NODE) {
                    continue;
                }

                if (!haveSelector || elem.matches(selector)) {
                    return elem;
                }
            }

            return null;
        };
    }

    /**
     * Extend Element class with isInViewport() method
     */
    if (!('isInViewport' in Element.prototype)) {
        Element.prototype.isInViewport = function () {
            var bottomOffset = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 0;

            var elem = this;
            var distance = elem.getBoundingClientRect();
            return distance.top >= 0 && distance.left >= 0 && distance.bottom - bottomOffset <= (window.innerHeight || document.documentElement.clientHeight) && distance.right <= (window.innerWidth || document.documentElement.clientWidth);
        };
    }

    /**
     * Extend document with createElementFromString() method
     */
    if (!('createElementFromString' in document)) {
        document.createElementFromString = function (string) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(string, 'text/html');

            return doc.body.childNodes[0];
        };
    }

    commentace.debounce = function (func) {
        var wait = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 20;
        var immediate = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;

        var timeout;
        return function () {
            var context = this,
                args = arguments;
            var later = function later() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    // Shortcut.
    window.ca = commentace;
})();