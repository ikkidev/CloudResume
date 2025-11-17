/* global window */
/* global document */
/* global jQuery */
/* global g1 */
/* global bimber_front_config */
/* global SuperGif */
/* global Waypoint */
/* global enquire */
/* global mashsb */
/* global auto_load_next_post_params */
/* global FB */
/* global mejs */
/* global ga */
/* global embedly */
/* global console */

/*************
 *
 * Init env
 *
 *************/

(function($) {
    'use strict';

    var config = bimber_front_config;

    // namespace
    var g1 = {
        'config': config
    };

    g1.getWindowWidth = function () {
        if (typeof window.innerWidth !== 'undefined') {
            return window.innerWidth;
        }

        return $(window).width();
    };

    g1.isDesktopDevice = function () {
        return g1.getWindowWidth() > g1.getDesktopBreakpoint();
    };

    g1.getDesktopBreakpoint = function () {
        var desktopBreakPoint = $('#g1-breakpoint-desktop').css('min-width');

        if ( ! desktopBreakPoint ) {
            return 9999;
        }

        desktopBreakPoint = parseInt( desktopBreakPoint, 10 );

        // not set explicitly via css
        if (desktopBreakPoint === 0) {
            return 9999;
        }

        return desktopBreakPoint;
    };

    g1.isTouchDevice = function () {
        return ('ontouchstart' in window) || navigator.msMaxTouchPoints;
    };

    g1.isStickySupported = function () {
        var prefixes = ['', '-webkit-', '-moz-', '-ms-'];
        var block = document.createElement('div');
        var supported = false;
        var i;

        // Test for native support.
        for (i = prefixes.length - 1; i >= 0; i--) {
            try {
                block.style.position = prefixes[i] + 'sticky';
            }
            catch(e) {}
            if (block.style.position !== '') {
                supported = true;
            }
        }

        return supported;
    };

    g1.isRTL = function () {
        return $('body').is('.rtl');
    };

    g1.log = function(data) {
        if ((g1.config.debug_mode || window.bimberDebugMode) && typeof console !== 'undefined') {
            if ($.isArray(data)) {
                for (var i in data) {
                    console.log(data[i]);
                }
            } else {
                console.log(data);
            }
        }
    };

    g1.createCookie = function (name,value,time) {
        var expires;

        if (time) {
            var date = new Date();
            var ms = time;

            if (typeof time === 'object') {
                ms = time.value;

                switch (time.type) {
                    case 'days':
                        ms = ms * 24 * 60 * 60 * 1000;
                        break;
                }
            }

            date.setTime(date.getTime() + ms);
            expires = '; expires=' + date.toGMTString();
        }
        else {
            expires = '';
        }

        document.cookie = name + '=' + value + expires + '; path=/';
    };

	g1.readCookie = function (name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');

        for(var i = 0; i < ca.length; i += 1) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1,c.length);
            }

            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length,c.length);
            }
        }

        return null;
    };

	g1.removeCookie = function (name) {
        g1.createCookie(name, '', -1);
    };

    // expose to the world
    window.g1 = g1;
})(jQuery);

/*************
 *
 * UI Helpers
 *
 ************/

(function ($) {

    'use strict';

    g1.uiHelpers = function () {
        if (g1.isTouchDevice()) {
            $('body').removeClass( 'g1-hoverable' );
        }

        // -----------------
        // Mailchimp widget.
        // -----------------

        var mc4wpClasses = [
            'g1-box',
            'g1-box-tpl-frame',
            'g1-newsletter'
        ];

        var mc4wpBackgroundClasses = [
            'g1-box-background'
        ];

        if ('original-2018' === g1.config.stack || 'food' === g1.config.stack ) {
            mc4wpBackgroundClasses.push('g1-current-background');
        }

        if ( 'miami' === g1.config.stack || 'music' === g1.config.stack ) {
            mc4wpClasses.push('g1-dark');
        }

        $('.widget_mc4wp_form_widget').
            addClass(mc4wpClasses.join(' ')).
            wrapInner('<div class="g1-box-inner"></div>').
            prepend('<div class="g1-box-icon"></div>').
            append('<div class="' + mc4wpBackgroundClasses.join(' ') + '"></div>');



        // -----------
        // bbPress.
        // -----------

        var bbPressBackgroundClasses = [
            'g1-box-background'
        ];

        if ('original-2018' === g1.config.stack || 'food' === g1.config.stack ) {
            bbPressBackgroundClasses.push('g1-current-background');
        }

        $('.bbp_widget_login').
            addClass('g1-box g1-box-tpl-frame').
            wrapInner('<div class="g1-box-inner"></div>').
            append('<div class="' + bbPressBackgroundClasses.join(' ') + '"></div>');


        // ----------
        // Search UI.
        // ----------

        $('.g1-drop-the-search').on('click', '.g1-drop-toggle', function (e) {
            e.preventDefault();

           $('.g1-drop-the-search input.search-field').focus();
        });

        $('.search-submit').on('click', function(e){
            var $form = $(this).closest('form');
            var $input = $('input.search-field', $form);
            if ( ! $input.val()){
               e.preventDefault();
            }
        });

        $('#buddypress .load-more').click(function() {
            var i = 0;
            var intervalID = setInterval( function() {
                $('body').trigger( 'g1PageHeightChanged' );
                i++;
                if (i === 5){
                    window.clearInterval(intervalID);
                }
            },1000);
        });
    };

})(jQuery);

/****************
 *
 * Facebook SDK
 *
 ****************/

(function ($) {

    'use strict';

    g1.resetFacebookSDK = function () {
        $('script#facebook-jssdk').remove();
        $('#fb-root').remove();
        if (window.FB) {
            delete window.FB;
        }
    };

    $('body').on( 'g1BeforeNewContentReady', function ( e, $newContent ) {
        if ($newContent.find('.fb-video').length > 0) {
            g1.resetFacebookSDK();
        }
    } );

})(jQuery);

/****************
 *
 * Instagram SDK
 *
 ****************/

(function ($) {

    'use strict';

    $('body').on( 'g1NewContentLoaded', function ( e, $newContent ) {
        if ($newContent.find('.instagram-media').length > 0) {
            if (typeof instgrm !== 'undefined' && typeof instgrm.Embeds !== 'undefined') {
                instgrm.Embeds.process();
            }
        }
    } );

})(jQuery);

/********************
 *
 * Load More Button
 *
 ********************/

(function ($) {

    'use strict';

    // prevent triggering the action more than once at the time
    var loading = false;
    var startingUrl = window.location.href;
    var setTargetBlank = g1.config.setTargetBlank;
    var useWaypoints = g1.config.useWaypoints;

    g1.loadMoreButton = function () {
        $('.g1-load-more').on('click', function (e) {
            if (loading) {
                return;
            }

            loading = true;

            e.preventDefault();

            var $button = $(this);
            var $collectionMore = $button.parents('.g1-collection-more');
            var url = $button.attr('data-g1-next-page-url');
            var $endMessage = $('.g1-pagination-end');

            $collectionMore.addClass('g1-collection-more-loading');

            // load page
            var xhr = $.get(url);

            // on success
            xhr.done(function (data) {
                var collectionSelector = '#primary > .g1-collection .g1-collection-items';

                // find elements in response
                var $resCollectionItems = $(data).find(collectionSelector).find('.g1-collection-item');
                var $resButton = $(data).find('.g1-load-more');

                // find collection on page
                var $collection = $(collectionSelector);

                // add extra classes to new loaded items
                $resCollectionItems.addClass('g1-collection-item-added');

                // If there are insta embeds BEFORE the load, we will force to refresh them AFTER the load
                var $insta = $('script[src="//platform.instagram.com/en_US/embeds.js"]');

                // make sure that mejs is loaded
                if (window.wp && typeof window.wp.mediaelement === 'undefined') {
                    var matches = data.match(/<script(.|\n)*?<\/script>/g);
                    var mejsCode = '';
                    matches.forEach(function( match ) {
                        if ( match.indexOf('mejs') > 0 || match.indexOf('mediaelement') > 0 ){
                            match = match.replace('<script','<script async');
                            mejsCode+=match;
                        }
                    });
                    matches = data.match(/<link(.|\n)*?\/>/g);
                    matches.forEach(function( match ) {
                        if ( match.indexOf('mejs') > 0 || match.indexOf('mediaelement') > 0 ){
                            mejsCode+=match;
                        }
                    });
                    $collection.after(mejsCode);
                }
                if (setTargetBlank) {
                    $('a',$resCollectionItems).attr('target','_blank');
                }

                var $collection_waypoint = '<span class="bimber-collection-waypoint" data-bimber-archive-url="' + url + '"></span>';
                $collection.append($collection_waypoint);
                // add new elements to collection
                $collection.append($resCollectionItems);

                // Google Analytics.
                if ( typeof ga !== 'undefined' && typeof ga.getAll !== 'undefined') {
                    ga('create', ga.getAll()[0].get('trackingId'), 'auto');
                    ga('set', { location: url });
                    ga('send', 'pageview');
                }

                if ( $insta.length > 0) {
                    window.instgrm.Embeds.process();
                }

                if (window.wp && typeof window.wp.mediaelement !== 'undefined') {
                    window.wp.mediaelement.initialize();
                }

                // load all dependent functions
                $('body').trigger( 'g1PageHeightChanged' );
                $('body').trigger( 'g1NewContentLoaded', [ $resCollectionItems ] );

                // update more button
                if ($resButton.length > 0) {
                    $button.attr('data-g1-next-page-url', $resButton.attr('data-g1-next-page-url'));
                } else {
                    $collectionMore.remove();
                }

                //bind auto play video events
                if (typeof g1.autoPlayVideo === 'function') {
                    g1.autoPlayVideo();
                }

                if ( useWaypoints ){
                    $('.bimber-collection-waypoint').waypoint(function(direction) {
                        var $waypoint = $(this.element);
                        if('up' === direction) {
                            var $waypointUp = $waypoint.prevAll('.bimber-collection-waypoint');
                            if ($waypointUp.length > 0){
                                $waypoint = $($waypointUp[0]);
                            } else {
                                window.history.replaceState( {} , '', startingUrl );
                                return;
                            }
                        }
                        var waypointUrl = $waypoint.attr('data-bimber-archive-url');
                        var currentUrl = window.location.href;
                        if ( waypointUrl !== currentUrl ){
                            window.history.replaceState( {} , '', waypointUrl );
                        }
                    }, {
                        offset: '-5%'
                    });
                }
            });

            xhr.fail(function () {
                $button.addClass('g1-info-error');
                $button.remove();
                $endMessage.show();
            });

            xhr.always(function () {
                $collectionMore.removeClass('g1-collection-more-loading');
                loading = false;
            });
        });
    };

})(jQuery);


/*******************
 *
 * Infinite scroll
 *
 ******************/

(function ($) {

    'use strict';

    g1.infiniteScrollConfig = {
        'offset': '150%'
    };

    var triggeredByClick = false;

    g1.infiniteScroll = function () {
        $('.g1-collection-more.infinite-scroll').each(function () {
            var $this = $(this);

            if ($this.is('.on-demand') && !triggeredByClick) {
                return false;
            }

            $this.waypoint(function(direction) {
                if('down' === direction) {
                    $this.find('.g1-load-more').trigger('click');
                }
            }, {
                // trigger when the "Load More" container is 10% under the browser window
                offset: g1.infiniteScrollConfig.offset
            });
        });
    };

    // wait for new content and apply infinite scroll events to it
    $('body').on( 'g1NewContentLoaded', function () {
        triggeredByClick = true;
        g1.infiniteScroll();
    } );

})(jQuery);



/*******************
 *
 * Date > Time ago
 *
 ******************/

(function ($) {

    'use strict';

    g1.dateToTimeago = function () {
        if (!$.fn.timeago) {
            return;
        }

        var $body = $('body');

        $('time.entry-date, span.entry-date > time, .comment-metadata time, time.snax-item-date').timeago();

        $body.on( 'g1NewContentLoaded', function (e, $newContent) {
            if ($newContent) {
                $newContent.find('time.entry-date, .comment-metadata time, time.snax-item-date').timeago();
            }
        } );

        // CommentAce integration.
        $body.on( 'caceWpCommentInitialized', function (e) {
            $(e.detail).find('time.entry-date, .comment-metadata time, time.snax-item-date').timeago();
        } );
    };

})(jQuery);


/**********
 *
 * Canvas
 *
 *********/

(function($) {
    'use strict';

    var selectors = {
        'toggle':   '.g1-hamburger'
    };

    g1.globalCanvasSelectors = selectors;

    var canvas;

    g1.canvas = function() {
        canvas = Canvas();

        // Allow global access.
        g1.canvasInstance = canvas;

        $(selectors.toggle).on('click', function (e) {
            e.preventDefault();

            //$('html, body').animate({ // @maybe
            //    scrollTop: 0 // @maybe
            //}, 10); // @maybe

            canvas.toggle();
        });
    };

    function Canvas () {
        var that = {};
        var listeners = {
            'open': [],
            'close': []
        };

        var currentContent = '';

        var currentScroll = 0; // @maybe

        var _clientY;

        var init = function () {
            var $overlay = $( '.g1-canvas-overlay');

            // toogle canvas events
            $overlay.on('click', that.toggle);

            $('.g1-canvas').on('toggle-canvas', function () {
                that.toggle();
            });

            $('.g1-canvas .g1-canvas-toggle').on('click', that.toggle);


            if ( $('html.g1-off-outside').length ) {
                enquire.register('screen and ( min-width: 700px )', {
                    match : function() {
                        that.close();
                    }
                });
            }

            if ( $('html.g1-off-inside').length ) {
                enquire.register('screen and ( max-width: 1024px )', {
                    match : function() {
                        that.close();
                    }
                });

                enquire.register('screen and ( min-width: 1025px )', {
                    match : function() {
                        that.lazyload();
                    }
                });
            }

            return that;
        };

        that.getContent = function() {
            return $('.g1-canvas-global .g1-canvas-content');
        };

        that.captureClientY = function (event) {
            // only respond to a single touch
            //if (event.targetTouches.length === 1) {
                _clientY = event.targetTouches[0].clientY;
            //}
        };

        that.disableCanvasScroll = function( e ) {
            // only respond to a single touch
            //if (e.targetTouches.length !== 1) {
            //   return;
            //}

            var _element = $('.g1-canvas');

            var clientY = e.targetTouches[0].clientY - _clientY;

            // The element at the top of its scroll,
            // and the user scrolls down
            if (_element.scrollTop === 0 && clientY > 0) {

                alert('top scroll');

                e.preventDefault();
                e.stopPropagation();
                return false;
            }

            // The element at the bottom of its scroll,
            // and the user scrolls up
            // https://developer.mozilla.org/en-US/docs/Web/API/Element/scrollHeight#Problems_and_solutions
            if ((_element.scrollHeight - _element.scrollTop <= _element.clientHeight) && clientY < 0) {
                alert('bottom scroll');

                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        };

        that.disableBodyScroll = function( e ) {
            //e.preventDefault();
            //e.stopPropagation();
            //return false;
        };

        that.lazyload = function() {
            $('.g1-canvas-background[data-bg]:not(.lazyloaded)').addClass('lazyload');
        };

        that.open = function (content) {
            //$('.g1-canvas').on('touchstart', that.captureClientY);
            //$('.g1-canvas').on('touchmove', that.disableCanvasScroll);

            that.lazyload();

            window.requestAnimationFrame(function () {
                var breakpoint = $(document).width();
                var cssClass = breakpoint >= 1025 ? 'g1-off-global-desktop' : 'g1-off-global';
                $('html').addClass(cssClass);

                currentContent = content;
                var $canvas = $('.g1-canvas-global');

                if (content) {
                    if (typeof content === 'string') {
                        $canvas.find('.g1-canvas-content').html(content);
                    } else {
                        $canvas.find('.g1-canvas-content').empty().append(content);
                    }

                    // notify about adding new content to DOM so other elements can be reloaded
                    $canvas.find('.g1-canvas-content').trigger('g1-new-content');

                }

                that.notify('open');
            });
        };

        that.close = function () {
            //$('.g1-canvas').off('touchmove', that.disableCanvasScroll);
            //$('.g1-canvas').off('touchstart', that.captureClientY);

            window.requestAnimationFrame(function () {
                $('html').removeClass('g1-off-global g1-off-global-desktop');

                that.notify('close');
            });
        };

        that.toggle = function (e) {
            if (e) {
                e.preventDefault();
            }

            // is opened?
            if ( $('html').is('.g1-off-global, .g1-off-global-desktop') ) {
                that.close();
            } else {
                that.open(null);
            }
        };

        that.notify = function (eventType) {
            var callbacks = listeners[eventType];

            for (var i = 0; i < callbacks.length; i++) {
                callbacks[i](that.getContent());
            }
        };

        that.on = function (eventType, listener, priority) {
            listeners[eventType][priority] = listener;
        };

        return init();
    }

})(jQuery);


/********************
 *
 * Sticky sidebar
 *
 ********************/

(function($) {
    'use strict';

    var $waypointElem = false;

    var selectors = {
        'stickyWidgetWrapper': '.g1-sticky-widget-wrapper',
        'stickyWidget':        '.g1-sticky-widget',
        'widget':              '.widget',
        'content':             '#primary .entry-content'
    };

    var sidebarSelectors = [
        '#secondary',
        '#tertiary'
    ];

    g1.stickyTopOffsetSelectors = [
        '#wpadminbar',
        '.g1-iframe-bar',
        '.g1-sharebar-loaded',
        '.g1-sticky-top-wrapper'
    ];

    g1.resetStickyElements = function() {
        $(selectors.stickyWidgetWrapper).css('height', '');
        $(selectors.stickyWidget).css('position', 'block');
    };

    g1.stickySidebar = function() {
        if (!g1.isDesktopDevice()) {
            g1.resetStickyElements();
            return;
        }

        var $widgets = $(selectors.stickyWidget);

        if ($widgets.length === 0) {
            return;
        }

        // Calc top offset for sticky elements.
        var topOffset = 0;

        $(g1.stickyTopOffsetSelectors).each(function() {
            var $element = $(this);

            if ($element.length > 0 && $element.is(':visible')) {
                topOffset += parseInt($element.outerHeight(), 10);
            }
        });

        // Adjust widgets top offset to keep them always fully visible.
        $widgets.each(function() {
            var $widget = $(this);
            var top = parseInt($widget.css('top'), 10);

            if (topOffset > 0) {
                top += topOffset;

                $widget.css('top', top + 'px');
            }
        });

        // Apply "position: sticky" pollyfill (IE9+).
        if (typeof Stickyfill !== 'undefined') {
            Stickyfill.add($widgets);
        }

        /**
         * Increase the last widget height to cover entire sidebar.
         *
         * @param {Boolean} isVariableContent  - Determines which element is used for widget height calculation.
         */
        var adjustLastWidgetHeight = function(isVariableContent) {
            $(sidebarSelectors).each(function() {
                var $sidebar = $(this);
                var $widgets = $sidebar.children(selectors.widget + ',' + selectors.stickyWidgetWrapper);
                var $lastWidget = $widgets.last();

                if ($lastWidget.is(selectors.stickyWidgetWrapper)) {
                    // Reset height (if previously set) to calculate it from scratch.
                    $lastWidget.css('height', '');

                    var sidebarHeight;

                    // Lazy loaded dynamic content elements (like embeds) affects its height.
                    // At this point, we don't know what will be the final height.
                    // So when we calculate the first time the last widget height, we do this based on current content height,
                    // and then when we reach the end of the content, we recalculate (assuming that all elements
                    // have already thier final size) the widget height based on current sidebar height (flexbox gives us a certainty
                    // that both #primary and #secondary/#tetriary elements have the same height).
                    if (isVariableContent) {
                        sidebarHeight = parseInt($(selectors.content).outerHeight(), 10);
                    } else {
                        sidebarHeight = parseInt($sidebar.outerHeight(), 10);
                    }

                    var widgetsHeight = 0;

                    $widgets.each(function() {
                        widgetsHeight += parseInt($(this).outerHeight(true), 10);
                    });

                    // Increase last widget height if exists a space to cover.
                    if (widgetsHeight < sidebarHeight) {
                        var diffHeight = sidebarHeight - widgetsHeight;

                        var lastWidgetHeight = parseInt($lastWidget.css('height'), 10);

                        lastWidgetHeight += diffHeight;

                        $lastWidget.css('height', lastWidgetHeight + 'px');
                    }

                    $waypointElem = $lastWidget;
                }
            });
        };

        var $body = $('body');

        // For a single post, use entry content height to calculate the last widget height at first.
        var isVariableContent = $body.is('.single');

        adjustLastWidgetHeight(isVariableContent);

        // Listen to page height changes.
        // ------------------------------

        // New content is added (e.g. via auto-load next post) or dynamic element is fully loaded (e.g. Facebook widget).
        $body.on( 'g1NewContentLoaded g1PageHeightChanged', function(e) {
            adjustLastWidgetHeight();
        } );

        // Entry content is entirely in the viewport (all inside elements should have final size), adjust widget height.
        if (false !== $waypointElem) {
            $waypointElem.waypoint(function(direction) {
                if ('down' === direction) {
                    adjustLastWidgetHeight();
                }
            }, {
                // When the bottom of the element hits the bottom of the viewport.
                offset: 'bottom-in-view'
            });
        }
    };

})(jQuery);


/*****************
 *
 * Sticky Elements
 *
 ****************/

(function ($) {

    'use strict';

    var selectors = [
        '#wpadminbar',
        '.g1-iframe-bar',
        '.g1-sharebar-loaded',
        '.g1-sticky-top-wrapper'
    ];

    // Accessible globally.
    g1.stickyElementsTopOffsetSelectors = selectors;

    g1.stickyPosition = function ($context) {
        $context = $context || $('body');

        // Sticky top.
        var $stickyTop = $('.g1-sticky-top-wrapper');

        // If exists and not loaded already.
        if ($stickyTop.length > 0 && !$stickyTop.is('.g1-loaded')) {
            var disableStickyHeader = false;
            var isDesktop = g1.getWindowWidth() > 800;



            // Disable if sharebar enabled.
            var sharebarLoaded = $('.g1-sharebar-loaded').length > 0 || $('.essb_topbar').length > 0;

            if (sharebarLoaded && isDesktop) {
                disableStickyHeader = true;
            }

            if (disableStickyHeader) {
                // Prevent native sticky support, like on FF.
                $stickyTop.removeClass('g1-sticky-top-wrapper');
            } else {
                // Apply pollyfill only if not supported.
                if (!g1.isStickySupported()) {
                    Stickyfill.add($stickyTop);
                }

                $stickyTop.addClass('g1-loaded');
            }
        }

        // Calculate topOffset after disabling the sticky header!
        var topOffset = 0;
        for (var i = 0; i < selectors.length; i++) {
            var $elem = $(selectors[i]);

            if ($elem.length > 0 && $elem.is(':visible')) {
                topOffset += $elem.outerHeight();
            }
        }


        // Sticky Item Actions (shares, votes etc).
        $context.find('.g1-wrapper-with-stickies > .entry-actions').each(function() {
            var $this = $(this);

            $this.css('top', topOffset);

            Stickyfill.add($this);
        });

        // Sticky snax form sidebar.
        Stickyfill.add($('.snax-form-frontend .snax-form-side'));


        // Sticky Item Actions (shares, votes etc).
        $context.find('.entry-tpl-index-stickies > .entry-actions, .entry-tpl-feat-stickies .entry-actions').each(function() {
            var $this = $(this);

            $this.css('top', topOffset + 10);

            Stickyfill.add($this);
        });
    };

    $('body').on( 'g1NewContentLoaded', function ( e, $newContent ) {
        if ($newContent) {
            g1.stickyPosition($newContent);
        }
    } );

})(jQuery);


/**********************
 *
 * Droppable Elements
 *
 **********************/

(function ($) {

    'use strict';

    var selectors = {
        'drop' :        '.g1-drop',
        'dropExpanded': '.g1-drop-expanded',
        'dropToggle':   '.g1-drop-toggle'
    };

    var classes = {
        'dropExpanded': 'g1-drop-expanded'
    };

    g1.droppableElements = function () {
        // Hide drop on focus out.
        $('body').on('click touchstart', function (e) {
            var $activeDrop = $(e.target).parents('.g1-drop-expanded');

            // Collapse all except active.
            $(selectors.dropExpanded).not($activeDrop).removeClass(classes.dropExpanded);
        });

        // Handle drop state (expanded | collapsed).

        // For touch devices we need to toggle CSS class to trigger state change.
        if ( g1.isTouchDevice() ) {
            $('body').on( 'click', selectors.drop, function(e) {
                var $drop = $(this);

                // If there is no drop-content inside, skip.
                if ($drop.find('.g1-drop-content').length === 0) {
                    return;
                }

                // Drop is expanded, collapse it on toggle click.
                if ($drop.is(selectors.dropExpanded)) {
                    var $clickedElement = $(e.target);

                    var toggleClicked = $clickedElement.parents(selectors.dropToggle).length > 0;

                    if (toggleClicked) {
                        $drop.removeClass(classes.dropExpanded);
                        e.preventDefault();
                    }
                // Drop is collapsed, expand it.
                } else {
                    $drop.addClass(classes.dropExpanded);
                    e.preventDefault();
                }
            } );
        // Devices without touch events, state is handled via CSS :hover
        } else {
            // Prevent click on toggle.
            $(selectors.dropToggle).on( 'click', function() {});
        }
    };

})(jQuery);

/********************************
 *
 * Mailchimp for WP Integration
 *
 *******************************/

(function ($) {

    'use strict';

    $('body').on('submit', '.mc4wp-form', function (e) {
        var submissionType = 'ajax';

        // Allow filtering the type.
        if (typeof bimberMc4wpFormSubmissionType === 'function') {
            submissionType = bimberMc4wpFormSubmissionType(submissionType);
        }

        if (submissionType !== 'ajax') {
            return;
        }

        e.preventDefault();

        var $form = $(this);

        $.ajax({
            'type': 'POST',
            'url':  window.location.href,
            'data': $form.serialize()
         }).done(function (res) {
            var $response = $(res).find('#' + $form.attr('id') + ' .mc4wp-response');

            $response.prependTo($form);
        });
    });

})(jQuery);

/*******************
 *
 * Snax Integration
 *
 ******************/

(function ($) {

    'use strict';

    g1.snax = function () {
        var $body = $('body');

        $body.on('snaxFullFormLoaded', function(e, $post) {
            $post.find('.g1-button-m.g1-button-solid').removeClass('g1-button-m  g1-button-solid').addClass('g1-button-s  g1-button-simple');
            $post.find('.g1-beta.g1-beta-1st').removeClass('g1-beta g1-beta-1st').addClass('g1-gamma g1-gamma-1st');
        });
    };

    // Integration between Snax and CommentAce.
    window.caceWpCommentListPropsFilter = function (props, comments) {
        if ($(comments).parents('.snax-item').length > 0) {
            props.loadMoreType = 'load_more';
        }

        return props;
    }

})(jQuery);

/*******************
 *
 * Media Ace Integration
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaAce = function () {

        $('body').on('g1NewContentLoaded', function() {
            $('body').trigger('maceLoadYoutube');
        });

        $(document).on('lazybeforeunveil', function(e) {
            var $target = $(e.target);
            var targetSrc = $target.attr('data-src');

            if (targetSrc && targetSrc.endsWith('.gif')) {
                // Wait till image fully loaded.
                $target.on('load', function() {
                    $target.addClass('g1-enable-gif-player');

                    // Wait a while before applying player.
                    setTimeout(function() {
                        if (typeof g1.gifPlayer === 'function') {
                            g1.gifPlayer($target.parent());
                        }
                    }, 100);
                });
            }
        });

        $(document).on('bimberGifPlayerLoaded', function(e, $canvasWrapper) {
            if ($canvasWrapper.hasClass('lazyloading')) {
                $canvasWrapper.removeClass('lazyloading');
                $canvasWrapper.addClass('lazyloaded');
            }
        });
    };

})(jQuery);


/*******
 *
 * Menu
 *
 *******/
(function (context, $, i18n) {

    'use strict';

    $(document).ready(function () {
        PrimaryMenu();
    });

    function PrimaryMenu () {
        var that = {};

        that.init = function () {
            that.registerEventsHandlers();

            // add toggle to menu
            $('.menu-item-has-children > a, .menu-item-g1-mega > a').append( '<span class="g1-link-toggle"></span>' );

            return that;
        };

        that.registerEventsHandlers = function () {
            that.handleMenuItemClick();
            that.handleMenuItemFocusOut();
        };

        that.handleMenuItemFocusOut = function () {
            $('body').on('click', function (e) {
                if ($(e.target).parents('.mtm-drop-expanded').length === 0 ) {

                    // Only horizontal menus.
                    if ($(e.target).parents('.g1-menu-h').length ) {
                        that.collapseAllOpenedSubmenus();
                    }
                }
            });
        };

        that.handleMenuItemClick = function () {
            $('.g1-primary-nav, .g1-secondary-nav').on('click', '.menu-item > a', function (e) {
                var $menu = $(this).parents('.g1-primary-nav');

                if ($menu.length === 0) {
                    $menu = $(this).parents('.g1-secondary-nav');
                }

                var isSimpleList = $menu.is('#g1-canvas-primary-nav') || $menu.is('#g1-canvas-secondary-nav');

                if (g1.isTouchDevice() || isSimpleList) {
                    that.handleMenuTouchEvent($(this), e);
                }
            });
        };

        that.handleMenuTouchEvent = function ($link, event) {
            var $li = $link.parent('li');

            that.collapseAllOpenedSubmenus($li);

            if ($li.hasClass('menu-item-has-children')) {
                event.preventDefault();

                var $helper = $li.find('ul.sub-menu:first > li.g1-menu-item-helper');

                if ($helper.length === 0) {
                    var href = $link.attr('href');

                    if ( href && href.length ) {
                        var anchor = i18n.go_to + ' <span class="mtm-item-helper-title">'+ $link.html() +'</span>';

                        $helper = $('<li class="menu-item g1-menu-item-helper"><a class="mtm-link" href="'+ href +'"><span class="mtm-link-text"><span class="mtm-link-title">' + anchor +'</span></span></a></li>');

                        $li.find('ul.sub-menu:first').prepend($helper);
                    }
                }

                if (!$li.is('.mtm-drop-expanded')) {
                    $li.find('.mtm-drop-expanded .g1-menu-item-helper').remove();
                    $li.addClass('mtm-drop-expanded');
                } else {
                    $li.find('.mtm-drop-expanded').removeClass('mtm-drop-expanded');
                    $li.removeClass('mtm-drop-expanded');
                }
            }
        };

        that.collapseAllOpenedSubmenus = function ($currentItem) {
            if ($currentItem) {
                var $currentMenu = $currentItem.parents('nav');
                var $topLevelLi = $currentItem.parents('li.menu-item');

                // We are at top level of menu
                if($topLevelLi.length === 0) {
                    // Collapse all, except current.
                    // This will collapse current item subtree items but not item itself. Item will be collapse by the "handleMenuTouchEvent" handler.
                    $currentMenu.find('.mtm-drop-expanded').not($currentItem).removeClass('mtm-drop-expanded');
                } else {
                    // Collapse all opened submenus in current menu, except current subtree.
                    $topLevelLi.siblings('li').find('.mtm-drop-expanded').removeClass('mtm-drop-expanded');
                }

                // Collapse all opened submenus in all other menus.
                $('nav').not($currentMenu).find('.mtm-drop-expanded').removeClass('mtm-drop-expanded');
            } else {
                // collapse all opened, site wide, submenus
                $('.mtm-drop-expanded').removeClass('mtm-drop-expanded');
            }
        };

        return that.init();
    }

})(window, jQuery, g1.config.i18n.menu);

// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating
// requestAnimationFrame polyfill by Erik Möller. fixes from Paul Irish and Tino Zijdel
// MIT license

(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame) {
        window.requestAnimationFrame = function(callback) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
    }

    if (!window.cancelAnimationFrame) {
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
    }
}());



/****************
 *
 * Popup/Slideup
 *
 ****************/

(function ($) {
    'use strict';
    g1.popup = function () {
		var
		HTMLBase          = $('html'),
		Popup             = $('.g1-popup-newsletter'),
		PopupCookie       = g1.readCookie('g1_popup_disabled'),
		PopupCloser       = $('.g1-popup-base, .g1-popup-closer');
		// If we dont have popup exit.
		if( PopupCookie ){ HTMLBase.addClass('exit-intent-disabled'); }
		if( Popup.length <= 0 ){ return; }
		$(document).on('mouseleave', function(e){
			if( e.clientY < 10 && ! HTMLBase.hasClass('exit-intent-disabled') && ! HTMLBase.hasClass('g1-slideup-visible') ){
                HTMLBase.addClass('g1-popup-ready');
                setTimeout(function(){
                    HTMLBase.addClass('g1-popup-visible').addClass('exit-intent-disabled');
                }, 50);
			}
		});
		PopupCloser.on('click', function(e){
			e.preventDefault();
			HTMLBase.removeClass('g1-popup-visible');
	        g1.createCookie('g1_popup_disabled', 1, 24 * 60 * 60 * 1000);
		});
    }



    g1.slideup = function () {
		var
		HTMLBase           = $('html'),
		Slideup            = $('.g1-slideup-newsletter'),
		SlideupCloser      = $('.g1-slideup-newsletter-closer'),
		SlideupCookie      = g1.readCookie('g1_slideup_disabled'),
		ScrollPositon      = $(document).scrollTop(),
		ScrollTarget       = $('.single-post article .entry-content'),
		ScrollTargetOffset = ScrollTarget.offset(),
		ScrollTargetHeight = ScrollTarget.height(),
		ShowOn             = 50;
		// If we dont have popup exit.
		SlideupCloser.on('click', function(e){
			e.preventDefault();
			HTMLBase.removeClass('g1-slideup-visible').addClass('slideup-intent-disabled');
	        g1.createCookie('g1_slideup_disabled', 1, 24 * 60 * 60 * 1000);
		});
		if( SlideupCookie ){ HTMLBase.addClass('slideup-intent-disabled'); }
		if( Slideup.length <= 0 ){ return; }
		if( ScrollTarget.length <= 0 ){ return; }
		$(window).on('scroll', function(){
			ScrollPositon      = $(document).scrollTop();
			ScrollTargetOffset = ScrollTarget.offset();
			ScrollTargetHeight = ScrollTarget.height();
			if( ( (ScrollPositon - ScrollTargetOffset.top) / (ScrollTargetHeight) ).toFixed(6) * 100 >= ShowOn && ! HTMLBase.hasClass('slideup-intent-disabled') && ! HTMLBase.hasClass('g1-popup-visible') ){
				HTMLBase.addClass('g1-slideup-visible');
			}
		});
    }
})(jQuery);

/****************
 *
 * GDPR
 *
 ****************/

(function ($) {
    'use strict';
    $(document).ready(function () {
        $('.wp-social-login-provider-list').on('click', function() {
                if ($(this).hasClass('wp-social-login-provider-list-active')){
                    return;
                }
                $('.snax-wpsl-gdpr-consent').addClass('snax-wpsl-gdpr-consent-blink');
                setTimeout(function(){
                    $('.snax-wpsl-gdpr-consent').removeClass('snax-wpsl-gdpr-consent-blink');
                },
                2000);
        });
        $('.snax-wpsl-gdpr-consent input').on('click', function() {
            var enabled = $(this).is(':checked');
            if (enabled) {
                $('.wp-social-login-provider-list').addClass('wp-social-login-provider-list-active');
            } else {
                $('.wp-social-login-provider-list').removeClass('wp-social-login-provider-list-active');
            }
        });
    });
})(jQuery);

/**************************
 *
 * WordPress Popular Posts
 *
 **************************/

(function ($) {

    'use strict';

    g1.updatePostViews = function(nonce,postId) {
        $.ajax({
            'type': 'POST',
            'url': g1.config.ajax_url,
            'data': {
                'action':   'update_views_ajax',
                'wpp_id':   postId,
                'token':    nonce
            }
        });
    };

    // ------------
    // Update views
    // ------------

    $('.bimber-count-view').on('click', function() {
        var postId;
        var $body = $('body');

        if ( $body.is('.single-format-link') ) {
            var res = $body.attr('class').match(/postid-(\d+)/);

            if (res) {
                postId = res[1];
            }
        } else {
            var $article = $(this).parents('article.format-link');
            if ( $article.length > 0 ) {
                postId = $article.attr('class').match(/post\-(\d+)/)[1];
            }
        }

        if (postId) {
            $.ajax({
                'type': 'POST',
                'url': g1.config.ajax_url,
                'data': {
                    //'action':   'bimber_update_post_views',
                    //'post_id':   postId
                    'action':   'update_views_ajax',
                    'wpp_id':   postId,
                    'token':    g1.config.wpp.token
                }
            });
        }
    });

})(jQuery);

/*******************
 *
 * Flickity
 *
 ******************/


(function ($) {

    'use strict';

    g1.flickity = function ($context) {
        $context = $context || $('body');

        var
            FlickitySpots = $context.find('.adace-shop-the-post-wrap.carousel-wrap .woocommerce .products, .g1-products-widget-carousel .product_list_widget');

        FlickitySpots.each(function () {
            if ($(this).hasClass('.flickity-enabled')) {
                return;
            }

            var
                ThisFlickityItems = $(this).children(),
                ThisFlickityItemsWidth = ThisFlickityItems.outerWidth() * ThisFlickityItems.length,
                ThisFlickityArgs = {
                    cellAlign: 'left',
                    wrapAround: true,
                    prevNextButtons: true,
                    pageDots: false,
                    groupCells: true,
                    rightToLeft: g1.isRTL(),
                    imagesLoaded: true
                };
            if (ThisFlickityItemsWidth <= $(this).outerWidth()) {
                ThisFlickityArgs.cellAlign = 'center';
                ThisFlickityArgs.wrapAround = false;
            } else {
                var ThisRequiredNumber = Math.round($(this).outerWidth() / ThisFlickityItems.outerWidth()) * ThisFlickityItems.length;

                while (ThisFlickityItems.length < ThisRequiredNumber) {
                    $(this).append(ThisFlickityItems.clone(true));
                    ThisFlickityItems = $(this).children();
                }
            }
            $(this).flickity(ThisFlickityArgs);
        });
    };

    $('body').on('g1NewContentLoaded', function (e, $newContent) {
        g1.flickity($newContent);
    });
})(jQuery);


// Sticky off-canvas top offset adjustments.
(function ($) {
    'use strict';

    $(document).ready(function () {
        var selectors = [
            '#wpadminbar',
            '.g1-iframe-bar',
            '.g1-sticky-top-wrapper'
        ];

        var applyOffset = function() {
            // Calculate the total height of all sticky elements.
            var topOffset = 0;
            for (var i = 0; i < selectors.length; i++) {
                var $elem = $(selectors[i]);

                if ($elem.length > 0 && $elem.is(':visible')) {
                    topOffset += $elem.outerHeight();
                }
            }


            var cssRule = 'html.g1-off-inside.g1-off-global-desktop .g1-canvas {top:' + topOffset + 'px;'

            $('#g1-canvas-js-css').remove();
            $('head').append( '<style id="g1-canvas-js-css">' + cssRule + '</style>' );
            $('html.g1-off-inside .g1-canvas').removeClass('g1-canvas-no-js').addClass('g1-canvas-js');
        };

        $('body').on( 'g1PageHeightChanged', function(e) {
            applyOffset();
        } );

        $('html.g1-off-inside .g1-canvas.g1-canvas-no-js').each(function() {
            applyOffset();
        });
    } );
})(jQuery);


/**************************
 *
 * document ready functions (keep this at the end for better compatibillity with optimizing plugins)
 *
 *************************/

(function ($) {

    'use strict';

    $(document).ready(function () {
        g1.uiHelpers();

        if (g1.config.timeago === 'on') {
            g1.dateToTimeago();
        }

        g1.loadMoreButton();
        g1.infiniteScroll();
        g1.stickyPosition();
        g1.droppableElements();
        g1.canvas();
        g1.snax();
        g1.mediaAce();
        g1.flickity();
        g1.popup();
        g1.slideup();
        g1.stickySidebar();
    });

})(jQuery);
