/*************************
 *
 * Custom Share Buttons
 * (open in a new window)
 *
 *************************/

(function ($) {

    'use strict';

    g1.customShareButtons = function () {
        openCustomSharesInNewWindow();
    };

    function openCustomSharesInNewWindow () {
        $('.mashicon-pinterest, .mashicon-google').click( function(e) {
            var winWidth = 750;
            var winHeight = 550;
            var winTop = (screen.height / 2) - (winHeight / 2);
            var winLeft = (screen.width / 2) - (winWidth / 2);
            var url = $(this).attr('href');

            // Since Mashshare v3.2.8.
            if ('#' === url) {
                url = $(this).attr('data-mashsb-url');
            }

            window.open(
                url,
                'sharer',
                'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight
            );

            e.preventDefault();
        });
    }

    $('body').on('g1NewContentLoaded', function(){
        if (typeof lashare_fb == "undefined" && typeof mashsb !== 'undefined') {
            $('.mashicon-facebook').click(function (mashfb) {
                var winWidth = 520;
                var winHeight = 550;
                var winTop = (screen.height / 2) - (winHeight / 2);
                var winLeft = (screen.width / 2) - (winWidth / 2);
                var url = $(this).attr('href');
                window.open(url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
                mashfb.preventDefault(mashfb);
                return false;
            });
        }
        if (typeof mashsb !== 'undefined') {
            $('.mashicon-twitter').click(function (e) {
                var winWidth = 520;
                var winHeight = 350;
                var winTop = (screen.height / 2) - (winHeight / 2);
                var winLeft = (screen.width / 2) - (winWidth / 2);
                var url = $(this).attr('href');
                // deprecated and removed because TW popup opens twice
                if (mashsb.twitter_popup === '1') {
                    window.open(url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
                }
                e.preventDefault();
                return false;
            });
        }
    });


})(jQuery);

/***************************
 *
 * Customize Share Buttons
 * (open in a new window)
 *
 ***************************/

(function ($) {

    'use strict';

    g1.customizeShareButtons = function () {
        overrideOnOffSwitch();
        subscribeViaMailbox();
    };

    function overrideOnOffSwitch () {
        // disable current events
        var $onoffswitch    = $('.onoffswitch');
        var $onoffswitch2   = $('.onoffswitch2');

        $onoffswitch.off('click');
        $onoffswitch2.off('click');

        $onoffswitch.on('click', function() {
            var $container = $(this).parents('.mashsb-container');

            $('.onoffswitch', $container).hide();
            $('.secondary-shares', $container).show();
            $('.onoffswitch2', $container).show();
        });

        $onoffswitch2.on('click', function() {
            var $container = $(this).parents('.mashsb-container');

            $('.onoffswitch', $container).show();
            $('.secondary-shares', $container).hide();
        });
    }

    function subscribeViaMailbox () {
        // Skip if subscription is done via content box.
        if (typeof mashsb !== 'undefined' && mashsb.subscribe === 'content') {
            return;
        }

        // Skip if subsciption is done via custom url.
        if (typeof mashsb !== 'undefined' && mashsb.subscribe_url !== '') {
            return;
        }

        // Open default mail client to subscribe.
        $('a.mashicon-subscribe').each(function () {
            var $link = $(this);

            if ($link.attr('href') === '#') {
                // remove all assigned events
                $link.off('click');

                var postTitle = $('head > title').text();
                var postUrl = location.href;

                var subject = g1.config.i18n.newsletter.subscribe_mail_subject_tpl.replace('%subject%', postTitle);
                var body = postTitle + '%0A%0A' + postUrl;

                // template
                var mailTo = 'mailto:?subject={subject}&body={body}';

                // build final link
                mailTo = mailTo.replace('{subject}', subject);
                mailTo = mailTo.replace('{body}', body);

                $link.attr('href', mailTo);
            }
        });
    }

})(jQuery);

/*************
 *
 * Share Bar
 *
 *************/

(function ($) {

    'use strict';

    g1.shareBarTopOffsetSelectors = [
        '#wpadminbar'
    ];

    g1.shareBar = function () {
        var $shareBar = g1.activateShareBar();

        $('body').on('g1PageHeightChanged', function () {
            if ($shareBar !== false) {
                g1.updateShareBarPosition($shareBar);
            }
        });

        enquire.register('screen and ( min-width: 801px )', {
            match : function() {
                if ($shareBar !== false) {
                    g1.updateShareBarPosition($shareBar);
                }
            },
            unmatch: function() {
                if ($shareBar !== false) {
                    g1.updateShareBarPosition($shareBar);
                }
            }
        });
    };

    g1.activateShareBar = function () {
        var $shareBar = $('.g1-sharebar');
        var $shareButtons = $('.mashsb-main:first');

        // exit if any of required elements not exists
        if ($shareBar.length === 0 || $shareButtons.length === 0) {
            return false;
        }

        var $shareBarInner = $shareBar.find('.g1-sharebar-inner');

        if (!$shareBar.is('.g1-sharebar-loaded')) {
            var $clonedShareButtons = $shareButtons.clone(true);
            //$clonedShareButtons.removeClass('mashsb-main');

            // If shares are animated, we need to set total count in sharebar before animation ends
            if (typeof mashsb !== 'undefined' && mashsb.animate_shares === '1' && $clonedShareButtons.find('.mashsbcount').length) {
                $clonedShareButtons.find('.mashsbcount').text(mashsb.shares);
            }

            $shareBarInner.append($clonedShareButtons);

            $shareBar.addClass('g1-sharebar-loaded');

            g1.updateShareBarPosition($shareBar);
        }

        new Waypoint({
            element: $('body'),
            handler: function (direction) {
                if (direction === 'down') {
                    $shareBar.addClass('g1-sharebar-on');
                    $shareBar.removeClass('g1-sharebar-off');
                } else {
                    $shareBar.removeClass('g1-sharebar-on');
                    $shareBar.addClass('g1-sharebar-off');
                }
            },
            offset: function() {
                // trigger waypoint when body is scrolled down by 100px
                return -100;
            }
        });

        return $shareBar;
    };

    g1.updateShareBarPosition = function ($shareBar) {
        var shareBarWidth = parseInt($shareBar.outerWidth(), 10);
        var cssMediaQueryBreakpoint = 800;

        // Below breakpoint value, sticky is placed on bottom so top has to be reset.
        if (shareBarWidth <= cssMediaQueryBreakpoint) {
            $shareBar.css('top', '');
        } else {
            var top = 0;

            for (var i = 0; i < g1.shareBarTopOffsetSelectors.length; i++) {
                var $element = $(g1.shareBarTopOffsetSelectors[i]);

                if ($element.length > 0 && $element.is(':visible')) {
                    top += parseInt($element.outerHeight(), 10);
                }
            }

            $shareBar.css('top', top + 'px');
        }
    };

})(jQuery);

/**********************
 *
 * Bimber Load Next Post
 *
 **********************/

(function ($) {

    'use strict';

    g1.loadNextPostConfig = {
        'offset': '500%'
    };

    var selectors = {
        'button' :       '.bimber-load-next-post',
        'urlWaypoint':   '.bimber-url-waypoint',
        'elementButton': '.g1-auto-load-button'
    };

    g1.loadNextPost = function () {

        var mainUrl = window.location.href;
        var autoLoadLimit = g1.config.auto_load_limit;
        var loadedPosts = 0;

        var loadNextEvent = function(e){

            e.preventDefault();

            if (autoLoadLimit > 0 && loadedPosts >= autoLoadLimit){
                $(this).remove();
                return;
            }

            var $button = $(this);
            var template = 'classic';
            if($('#secondary').length === 0){
                template = 'row';
            }
            $button.css('position','relative');
            $button.addClass('g1-collection-more-loading');
            var postUrl = $('a', this).attr('href');
            var gaPostUrl = $('a', this).attr('data-bimber-analytics-href');
            var url = postUrl + '?bimber_auto_load_next_post_template=' + template;

            $('a', this).remove();

            // load page
            var xhr = $.get(url);
            loadedPosts += 1;

            // on success
            xhr.done(function (data) {
                var $html = $($.parseHTML(data, document, true));
                var $content = $html.find('#content');
                var title = $($content.find('.entry-title')[0]).text();
                $content.find('#secondary').remove();

                // If there are insta embeds BEFORE the load, we will force to refresh them AFTER the load
                var $insta = $('script[src="//platform.instagram.com/en_US/embeds.js"]');

                // make sure that mejs is loaded
                var mejsLoaded = typeof window.wp !== 'undefined' && typeof window.wp.mediaelement !== 'undefined';

                if (!mejsLoaded) {
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
                    $button.after(mejsCode);
                }
                $button.before('<div class="g1-divider"></div>');

                $('body').trigger( 'g1BeforeNewContentReady', [ $content ] );

                // @todo
                var $scope = $($content.html()).insertAfter($button);

                if ( $insta.length > 0) {
                    window.instgrm.Embeds.process();
                }

                $button.remove();

                $('body').trigger( 'g1NewContentLoaded', [ $scope ] );

                // MEJS loaded, so run init.
                if (typeof window.wp !== 'undefined' && typeof window.wp.mediaelement !== 'undefined') {
                    window.wp.mediaelement.initialize();
                }

                // Google Analytics.
                if ( typeof ga !== 'undefined' && typeof ga.getAll !== 'undefined') {
                    ga('create', ga.getAll()[0].get('trackingId'), 'auto');
                    ga('set', { location: gaPostUrl, title: title });
                    ga('send', 'pageview');
                }

                // WPP Ajax.
                var $nonce = $html.find('#bimber-wpp-nonce');
                if ($nonce.length>0){
                    var nonce  = $nonce.attr('data-bimber-wpp-nonce');
                    var postId = $nonce.attr('data-bimber-wpp-id');
                    g1.updatePostViews(nonce,postId);
                }
                bindEvents();
            });

            xhr.always(function () {
                $button.removeClass('g1-collection-more-loading');
            });
        };

        var bindEvents = function() {
            $(selectors.button).click(loadNextEvent);

            $(selectors.elementButton).click(function(){
                window.history.replaceState( {} , '', mainUrl );
            });

            $(selectors.button).waypoint(function(direction) {
                if('down' === direction) {
                    $(selectors.button).trigger('click');
                }
            }, {
                offset: g1.loadNextPostConfig.offset
            });

            $(selectors.urlWaypoint).waypoint(function(direction) {
                var $waypoint = $(this.element);
                if('up' === direction) {
                    var $waypointUp = $waypoint.parent('article').prev('.bimber-url-waypoint');
                    if ($waypointUp.length > 0){
                        $waypoint = $waypointUp;
                    }
                }

                var url = $waypoint.attr('data-bimber-post-url');
                var title = $waypoint.attr('data-bimber-post-title');
                var currentUrl = window.location.href;
                if ( url !== currentUrl ){
                    var $article = $waypoint.next('article');

                    // Mashshare.
                    var $mashShare = $('.mashsb-container', $article);
                    var $shareBar = $('.g1-sharebar .g1-sharebar-inner');
                    if ( $mashShare.length >0 && $.trim($shareBar.html())) {
                        $shareBar.html($mashShare[0].outerHTML);
                    }

                    // ESSB.
                    var $essbShare = $('.essb_topbar_inner', $article);
                    var $essbBar = $('.essb_topbar_inner');
                    if ( $essbShare.length >0 && $.trim($essbBar.html())) {
                        $essbBar.html($essbShare[0].outerHTML);
                    }

                    g1.customizeShareButtons();
                    window.history.replaceState( {} , '', url );
                    document.title = title;
                }
            });
        };

        bindEvents();
    };

})(jQuery);

/*************
 *
 * Comments
 *
 ************/

(function ($) {

    'use strict';

    var selectors = {
        'wrapper':      '.g1-comments',
        'tabs':         '.g1-tab-items > li',
        'tab':          '.g1-tab-item',
        'currentTab':   '.g1-tab-item-current',
        'commentType':  '.g1-comment-type'
    };

    var classes = {
        'currentTab':   'g1-tab-item-current',
        'currentType':  'g1-tab-pane-current',
        'type':         'g1-tab-pane',
        'loading':      'g1-loading',
        'loaded':       'g1-loaded'
    };

    var $wrapper;

    g1.comments = function () {
        $wrapper = $(selectors.wrapper);

        if ($wrapper.length === 0) {
            return;
        }

        // Skip if CommentsAce in use.
        if ($wrapper.find('.cace-comments').length > 0) {
            return;
        }

        g1.facebookComments();
        g1.disqusComments();

        initTabs();
    };

    var initTabs = function() {
        var $tabs = $wrapper.find(selectors.tabs);
        var currentType = $tabs.filter(selectors.currentTab).attr('data-bimber-type');

        // Can't find current tab.
        if (!currentType) {
            var types = g1.config.comment_types;

            if (types && types.length > 0) {
                currentType = types[0];
            } else {
                currentType = 'wp';
            }
        }

        $wrapper.find(selectors.commentType).each(function() {
            var $type = $(this);

            $type.addClass(classes.type);
        });

        if ( 'dsq' === currentType ) {
            setTimeout(function() {
                selectTab(currentType);
            }, 1000);
        } else {
            selectTab(currentType);
        }

        $tabs.on('click', function() {
            var type = $(this).attr('data-bimber-type');

            selectTab(type);
        });
    };

    var selectTab = function(type) {
        var $tab = $wrapper.find(selectors.tab + '-' + type);
        var $type = $wrapper.find(selectors.commentType + '-' + type);

        //if ($type.hasClass(classes.currentType)) {
        //    return;
        //}

        if ('fb' === type) {
            if (!$type.hasClass(classes.loaded)) {
                $type.addClass(classes.loading);
            }
        }

        // Select new type.
        $wrapper.find(selectors.commentType).removeClass(classes.currentType);
        $type.addClass(classes.currentType);

        $type.trigger('loadComments');

        // Select new tab.
        $wrapper.find(selectors.tabs).removeClass(classes.currentTab);
        $tab.addClass(classes.currentTab);
    };

})(jQuery);

/*******************
 *
 * Facebook Comments
 * (plugin integration)
 *
 ******************/

(function ($) {

    'use strict';

    var selectors = {
        'wrapper':  '.g1-comment-type-fb',
        'counter':  '.g1-comment-count',
        'list':     '.g1-comment-list',
        'tab':      '.g1-comments .g1-tab-item-fb'
    };

    var classes = {
        'loading':  'g1-loading',
        'loaded':   'g1-loaded'
    };

    var $wrapper;
    var loaded = false;

    g1.facebookComments = function () {
        $wrapper = $(selectors.wrapper);

        if (!$wrapper.is('.g1-on-demand')) {
            loaded = true;
        }

        if ($wrapper.length > 0) {
            init();

            return $wrapper;
        } else {
            return false;
        }
    };

    var init = function() {
        // Init when FB is ready.
        var origFbAsyncInit = window.fbAsyncInit;

        window.fbAsyncInit = function() {
            if (typeof FB === 'undefined') {
                return;
            }

            // Update on post load.
            FB.Event.subscribe('xfbml.render', function() {
                $wrapper.removeClass(classes.loading);
                $wrapper.addClass(classes.loaded);

                var $counter = $wrapper.find(selectors.counter);

                var url = $counter.find('.fb_comments_count').attr('data-bimber-graph-api-url');
                FB.api(
                    '/' + url,
                    'GET',
                    {'fields':'engagement'},
                    function(response) {
                        if(response.engagement) {
                            var count = response.engagement.comment_plugin_count;
                            $('.fb_comments_count').html(count);
                        }
                    }
                );
                var realCount = parseInt($counter.find('.fb_comments_count').text(), 10);
                var postCount = parseInt($counter.attr('data-bimber-fb-comment-count'), 10);

                if (realCount !== postCount) {
                    save(realCount);
                }
            });

            // New comment added.
            FB.Event.subscribe('comment.create', function() {
                changeCommentsNumber(1);
            });

            // Comment removed.
            FB.Event.subscribe('comment.remove', function() {
                changeCommentsNumber(-1);
            });

            if (typeof origFbAsyncInit === 'function') {
                origFbAsyncInit();
            }
        };

        // Listen on "Load comments" event.
        $wrapper.on('loadComments', function() {
            if (loaded) {
                return;
            }

            $wrapper.addClass(classes.loading);

            loadComments(function(html) {
                g1.resetFacebookSDK();

                $wrapper.find(selectors.list).html(html);
                $wrapper.removeClass(classes.loading);
            });
        });
    };

    var changeCommentsNumber = function(diff) {
        var $counter = $wrapper.find(selectors.counter);
        var postCount = parseInt($counter.attr('data-bimber-fb-comment-count'), 10);

        postCount += diff;

        // Update Facebook comment count.
        $wrapper.find('.fb_comments_count').text(postCount);
        $counter.attr('data-bimber-fb-comment-count', postCount);

        // Update total post comment count.
        var $postCommentCount = $wrapper.parents('#content').find('.entry-comments-link strong');

        var postCommentCount = parseInt($postCommentCount.text(), 10);
        $postCommentCount.text(postCommentCount + diff);

        // Update tab counter.s
        var $fbCount = $(selectors.tab).find('a > span');

        if ($fbCount.length > 0) {
            var fbCount = parseInt($fbCount.text(), 10);

            $fbCount.text(fbCount + diff);
        }

        save(postCount);
    };

    var save = function(newCount) {
        var postId = $wrapper.find(selectors.counter).attr('data-bimber-post-id');
        var nonce  = $wrapper.find(selectors.counter).attr('data-bimber-nonce');

        $.ajax({
            'type': 'POST',
            'url': g1.config.ajax_url,
            'dataType': 'json',
            'data': {
                'action':   'bimber_update_fb_comment_count',
                'post_id':  postId,
                'security': nonce,
                'count':    newCount
            }
        });
    };

    var loadComments = function(callback) {
        var postId = $wrapper.find(selectors.counter).attr('data-bimber-post-id');

        var xhr = $.ajax({
            'type': 'GET',
            'url': g1.config.ajax_url,
            'data': {
                'action':   'bimber_load_fbcommentbox',
                'post_id':  postId
            }
        });

        xhr.done(function(res) {
            callback(res);

            loaded = true;
        });
    };

})(jQuery);


/**********************
 *
 * Disqus Comments
 * (plugin integration)
 *
 *********************/

(function ($) {

    'use strict';

    var selectors = {
        'wrapper':  '.g1-comment-type-dsq',
        'counter':  '.g1-comment-count',
        'list':     '.g1-comment-list',
        'tab':      '.g1-comments .g1-tab-item-dsq'
    };

    var classes = {
        'loading':  'g1-loading'
    };

    var $wrapper;
    var loaded = false;

    g1.disqusComments = function () {
        $wrapper = $(selectors.wrapper);

        if ($wrapper.length > 0) {
            init();

            return $wrapper;
        } else {
            return false;
        }
    };

    var init = function() {
        var origDsqConfig = window.disqus_config;

        window.disqus_config = function() {
            if (typeof origDsqConfig === 'function') {
                origDsqConfig();

                $wrapper.removeClass(classes.loading);
                loaded = true;
            }

            // DISQUSWIDGETS.getCount({reset: true});

            // Init.
            var $counter = $wrapper.find(selectors.counter);

            var realCount = parseInt($counter.find('.disqus-comment-count').text(), 10);
            var postCount = parseInt($counter.attr('data-bimber-dsq-comment-count'), 10);

            if (realCount !== postCount) {
                save(realCount);
            }

            this.callbacks.onNewComment = [function() {
                changeCommentsNumber(1);
            }];
        };

        // Listen on "Load comments" event.
        $wrapper.on('loadComments', function() {
            if (loaded) {
                return;
            }

            $wrapper.addClass(classes.loading);

            loadComments();
        });
    };

    var changeCommentsNumber = function(diff) {
        var $counter = $wrapper.find(selectors.counter);
        var postCount = parseInt($counter.attr('data-bimber-dsq-comment-count'), 10);

        postCount += diff;

        // Update Facebook comment count.
        $wrapper.find('.disqus-comment-count').text(postCount);
        $counter.attr('data-bimber-dsq-comment-count', postCount);

        // Update total post comment count.
        var $postCommentCount = $wrapper.parents('#content').find('.entry-comments-link strong');

        var postCommentCount = parseInt($postCommentCount.text(), 10);
        $postCommentCount.text(postCommentCount + diff);

        // Update tab counter.s
        var $dsqCount = $(selectors.tab).find('a > span');

        if ($dsqCount.length > 0) {
            var dsqCount = parseInt($dsqCount.text(), 10);

            $dsqCount.text(dsqCount + diff);
        }

        save(postCount);
    };

    var save = function(newCount) {
        var postId = $wrapper.find(selectors.counter).attr('data-bimber-post-id');
        var nonce  = $wrapper.find(selectors.counter).attr('data-bimber-nonce');

        $.ajax({
            'type': 'POST',
            'url': g1.config.ajax_url,
            'dataType': 'json',
            'data': {
                'action':   'bimber_dsq_update_comment_count',
                'post_id':  postId,
                'security': nonce,
                'count':    newCount
            }
        });
    };

    var loadComments = function() {
        var dsq = document.createElement('script');
        dsq.type = 'text/javascript';
        dsq.async = true;
        dsq.src = 'https://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    };

})(jQuery);


/**************************
 *
 * document ready functions
 * (keep this at the end for
 * better compatibillity
 * with optimizing plugins)
 *
 *************************/

(function ($) {

    'use strict';

    $(document).ready(function () {
        g1.customShareButtons();
        g1.customizeShareButtons();

        if (g1.config.sharebar === 'on') {
            g1.shareBar();
        }

        g1.comments();
        g1.loadNextPost();
    });

})(jQuery);