/******************
 *
 * Init GIF Player
 *
 *****************/

(function ($) {

    'use strict';
    var isEnabled   = g1.config.use_gif_player;

    g1.gifPlayer = function ($scope) {
        if ( ! isEnabled ) {
            return;
        }
        if (! $scope ) {
            $scope = $('body');
        }

        // SuperGif library depends on the overrideMimeType method of the XMLHttpRequest object
        // if browser doesn't support this method, we can't use that library
        if ( typeof XMLHttpRequest.prototype.overrideMimeType === 'undefined' ) {
            return;
        }

        g1.gifPlayerIncludeSelectors =[
            '.entry-content img.aligncenter[src$=".gif"]',
            '.entry-content .aligncenter img[src$=".gif"]',
            'img.g1-enable-gif-player',
            '.entry-featured-media-main img[src$=".gif"]',
            '.entry-tpl-stream .entry-featured-media img[src$=".gif"]',
            '.entry-tpl-grid-l .entry-featured-media img[src$=".gif"]'
        ];

        g1.gifPlayerExcludeSelectors = [
            '.ajax-loader',             // for Contact Form 7
            '.g1-disable-gif-player',
            '.wp-block-image.g1-disable-gif-player img[src$=".gif"]'
        ];

        $( g1.gifPlayerIncludeSelectors.join(','), $scope ).not( g1.gifPlayerExcludeSelectors.join(',') ).each(function () {
            var $img = $(this);
            var imgClasses = $img.attr('class');
            var imgSrc = $img.attr('src');

            // Check only absolute paths. Relative paths, by nature, are from the same domain.
            // Seems like the GIFs from outside of the site's domain work too.
            // if (-1 !== imgSrc.indexOf('http')) {
            //     // Only locally stored gifs, unless user decided otherwise.
            //     if (imgSrc.indexOf(location.hostname) === -1 && !$img.is('.g1-enable-gif-player')) {
            //         return;
            //     }
            // }


            var gifObj = new SuperGif({
                gif: this,
                auto_play: 0
            });

            var $gitIndicator = $('<span class="g1-indicator-gif g1-loading">');

            gifObj.load(function() {
                var frames = gifObj.get_length();

                var $canvasWrapper = $(gifObj.get_canvas()).parent();

                // Only for animated gifs.
                if (frames > 1) {
                    gifObj.isPlaying = false;

                    // Store references to original methods.
                    var playRef = gifObj.play;
                    var pauseRef = gifObj.pause;

                    var playGif = function() {
                        playRef();
                        gifObj.isPlaying = true;
                        $gitIndicator.addClass('g1-indicator-gif-playing');
                    };

                    var pauseGif = function() {
                        pauseRef();
                        gifObj.isPlaying = false;
                        $gitIndicator.removeClass('g1-indicator-gif-playing');
                    };

                    // Override and extend the API.
                    gifObj.play = playGif;
                    gifObj.pause = pauseGif;

                    // Play/stop the GIF.
                    $canvasWrapper.on('click', function(e) {
                        // Prevent redirecting to single post.
                        e.preventDefault();

                        if (gifObj.isPlaying) {
                            pauseGif();
                        } else {
                            playGif();
                        }
                    });

                    $gitIndicator.toggleClass('g1-loading g1-loaded');

                    $(document).trigger('bimberGifPlayerLoaded', [$canvasWrapper]);
                } else {
                    // It's just a gif type image, not animation to play.
                    $gitIndicator.remove();
                }
            });

            // canvas parent can be fetched after gifObj.load() call
            var $canvasWrapper = $(gifObj.get_canvas()).parent();

            $canvasWrapper.
                addClass(imgClasses + ' g1-enable-share-links').
                attr('data-img-src', imgSrc).
                append($gitIndicator).
                data('gifPlayer', gifObj);
        });
    };

    // Listeners.
    $('body').on('g1NewContentLoaded', function(e, $newContent) {
        g1.gifPlayer($newContent);
    });

})(jQuery);

/************************
 *
 * Media Players Factory
 *
 ***********************/

(function ($) {

    'use strict';

    var selectors = {
        'iframe':       'iframe',
        'mp4':          '.mejs-video',
        'gif':          '.jsgif',
        'html5Video':   '.snax-native-video',
        'embedly':      '.embedly-card iframe',
        'maceYT':       '.mace-youtube'
    };

    g1.mediaPlayers = {};

    g1.getMediaPlayer = function(element) {
        var player = $(element).data('g1MediaPlayer');

        if (player) {
            g1.log('Returning a player (' + player.getType() + ') assigned to the element.');
            return player;
        }

        // IFRAME.
        var $iframe  = $(selectors.iframe, element);

        if ($iframe.length > 0) {
            var iframesrc = false;

            if ($iframe.attr('data-src')) {
                iframesrc = $iframe.attr('data-src');
            } else {
                iframesrc= $iframe.attr('src');
            }

            if (iframesrc) {
                // YouTube?
                if (iframesrc.indexOf('youtu') > 0) {
                    player = g1.mediaPlayers.youtube($iframe);
                    $(element).data('g1MediaPlayer', player);

                    return player;
                }

                // Vimeo?
                if (iframesrc.indexOf('vimeo') > 0) {
                    player = g1.mediaPlayers.vimeo($iframe);
                    $(element).data('g1MediaPlayer', player);

                    return player;
                }

                // Dailymotion?
                if (iframesrc.indexOf('dailymotion') > 0) {
                    player = g1.mediaPlayers.dailymotion($iframe);
                    $(element).data('g1MediaPlayer', player);

                    return player;
                }

                // Gfycat?
                if (iframesrc.indexOf('gfycat') > 0) {
                    player = g1.mediaPlayers.gfycat($iframe);
                    $(element).data('g1MediaPlayer', player);

                    return player;
                }


            }


            return false;
        }

        // MP4.
        var $mp4 = $(selectors.mp4, element);

        if ($mp4.length > 0) {
            var playerId = $mp4.attr('id');

            if (playerId && mejs && typeof mejs.players !== 'undefined') {
                if (typeof mejs.players[playerId] !== 'undefined') {
                    var mejsPlayer = mejs.players[playerId];
                    player = g1.mediaPlayers.mp4(mejsPlayer);

                    $(element).data('g1MediaPlayer', player);

                    return player;
                }
            }

            return false;
        }

        // GIF.
        var $gif = $(selectors.gif, element);

        if ($gif.length > 0) {
            var gifPlayer = $gif.data('gifPlayer');

            if (gifPlayer) {
                player = g1.mediaPlayers.gif(gifPlayer);

                $(element).data('g1MediaPlayer', player);

                return player;
            }

            return false;
        }

        // MediaAce Lazy Loaded video.
        var $maceYT = $(selectors.maceYT, element);

        // HTML5 native videos.
        var $html5  = $(selectors.html5Video, element);

        if ( $html5.length > 0 ) {
            player = g1.mediaPlayers.html5video($html5[0]);

            $(element).data('g1MediaPlayer', player);

            return player;
        }

        // Embedly.
        if (typeof embedly !== 'undefined') {
            var $embedly = $(selectors.embedly, element);

            if ($embedly.length > 0 ) {
                // The following iterates over all the instances of the player.
                embedly('player', function(embedlyPlayer){
                    if ($embedly[0] === $(embedlyPlayer.frame.elem)[0]) {
                        player = g1.mediaPlayers.embedly(embedlyPlayer);

                        $(element).data('g1MediaPlayer', player);

                        return player;
                    } else {
                        player.pause();
                    }
                });

                return false;
            } else {
                embedly('player', function(player){
                    player.pause();
                });
            }
        }

        // MediaAce YouTube lazy loader.
        if ($maceYT.length > 0) {
            // Start to load YouTube player.
            $maceYT.find('.mace-play-button').trigger('click');

            // YouTube player (iframe) loaded.
            $maceYT.on('maceIframeLoaded', function(e, $iframe) {
                // Get YT player to initialize YT properly.
                player = g1.getMediaPlayer($maceYT);

                // Assign the player to the element.
                $(element).data('g1MediaPlayer', player);
            });

            // We don't want to return an instance of the MaceYT player here, as the MaceYT is just a wrapper for YT player.
            // So we return false here and wait for YT iframe. When it's loaded, we assigned the YT player to the element.
            return false;
        }
    };

})(jQuery);

/*******************
 *
 * YouTube Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.youtube = function ($iframe) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('YouTube player object initialized');

            $iframe.on('load', function() {
                // Mute on load.
                $iframe[0].contentWindow.postMessage(JSON.stringify({
                    'event': 'command',
                    'func': 'mute',
                    'args': ''}),
                '*');
            });

            let iframesrc = '';
            let separator = '?';

            if ($iframe.attr('data-src')) {
                iframesrc = $iframe.attr('data-src');
            } else {
                iframesrc = $iframe.attr('src');
            }

            if (iframesrc.indexOf('?') > 0){
                separator = '&';
            }

            // Trigger the "load" event, with new params.
            $iframe.attr('src', iframesrc + separator + 'autoplay=1&enablejsapi=1&loop=1');

            return obj;
        }

        obj.getType = function() {
            return 'YouTube';
        };

        obj.play = function () {
            $iframe[0].contentWindow.postMessage(JSON.stringify({
                'event': 'command',
                'func': 'playVideo',
                'args': ''}),
            '*');

            isPlaying = true;
        };

        obj.pause = function () {
            $iframe[0].contentWindow.postMessage(JSON.stringify({
                'event': 'command',
                'func': 'pauseVideo',
                'args': ''}),
            '*');

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * Vimoe Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.vimeo = function ($iframe) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('Vimeo player object initialized');

            $iframe.on('load', function() {
                $iframe[0].contentWindow.postMessage(JSON.stringify({
                    method: 'setVolume',
                    value:  0
                }), '*');
            });

            let iframesrc = '';
            let separator = '?';

            if ($iframe.attr('data-src')) {
                iframesrc = $iframe.attr('data-src');
            } else {
                iframesrc = $iframe.attr('src');
            }

            if (iframesrc.indexOf('?') > 0){
                separator = '&';
            }

            $iframe.attr('src', iframesrc + separator + 'autoplay=1&autopause=0');

            return obj;
        }

        obj.getType = function() {
            return 'Vimeo';
        };

        obj.play = function () {
            $iframe[0].contentWindow.postMessage(JSON.stringify({
                method: 'play'
            }), '*');

            isPlaying = true;
        };

        obj.pause = function () {
            $iframe[0].contentWindow.postMessage(JSON.stringify({
                method: 'pause'
            }), '*');

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * DailyMotion Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.dailymotion = function ($iframe) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('DailyMotion player object initialized');

            // Mute on load.
            let iframesrc = '';
            let separator = '?';

            if ($iframe.attr('data-src')) {
                iframesrc = $iframe.attr('data-src');
            } else {
                iframesrc = $iframe.attr('src');
            }

            if (iframesrc.indexOf('?') > 0){
                separator = '&';
            }

            $iframe.attr('src', iframesrc + separator + 'autoplay=1&api=postMessage&mute=1');

            return obj;
        }

        obj.getType = function() {
            return 'DailyMotion';
        };

        obj.play = function () {
            $iframe[0].contentWindow.postMessage('play', '*');

            isPlaying = true;
        };

        obj.pause = function () {
            $iframe[0].contentWindow.postMessage('pause', '*');

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * Gfycat Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.gfycat = function ($iframe) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('Gfycat player object initialized');
            return obj;
        }

        obj.getType = function() {
            return 'Gfycat';
        };

        obj.play = function () {
            $iframe[0].contentWindow.postMessage('play', '*');

            isPlaying = true;
        };

        obj.pause = function () {
            $iframe[0].contentWindow.postMessage('pause', '*');

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * MP4 Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.mp4 = function (mejsPlayer) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('MP4 player initialized');

            // Start muted.
            mejsPlayer.setMuted(true);

            // Play in loop.
            mejsPlayer.media.addEventListener('ended', function() {
                mejsPlayer.play();
            }, false);

            return obj;
        }

        obj.getType = function() {
            return 'MP4';
        };

        obj.play = function() {
            mejsPlayer.play();

            isPlaying = true;
        };

        obj.pause = function() {
            mejsPlayer.pause();

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * GIF Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.gif = function (gifPlayer) {
        let obj = {};

        function init() {
            g1.log('GIF player initialized');

            return obj;
        }

        obj.getType = function() {
            return 'GIF';
        };

        obj.play = function() {
            gifPlayer.play();
        };

        obj.pause = function() {
            gifPlayer.pause();
        };

        obj.isPlaying = function() {
            return gifPlayer.isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * HTML Video Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.html5video = function (video) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('HTML5 Video player initialized');

            return obj;
        }

        obj.getType = function() {
            return 'HTML5 Video';
        };

        obj.play = function() {
            video.play();

            isPlaying = true;
        };

        obj.pause = function() {
            video.pause();

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);

/*******************
 *
 * Embedly Player
 *
 ******************/

(function ($) {

    'use strict';

    g1.mediaPlayers.embedly = function (embedlyPlayer) {
        let obj = {};
        let isPlaying = false;

        function init() {
            g1.log('Embedly player initialized');

            embedlyPlayer.mute();

            return obj;
        }

        obj.getType = function() {
            return 'Embedly';
        };

        obj.play = function() {
            embedlyPlayer.play();

            isPlaying = true;
        };

        obj.pause = function() {
            embedlyPlayer.pause();

            isPlaying = false;
        };

        obj.isPlaying = function() {
            return isPlaying;
        };

        return init();
    };

})(jQuery);


/**********************
/**********************
 *
 * Auto Play Controller
 *
 **********************
 **********************/

(function ($) {

    'use strict';

    var selectors = {
        'postMedia': '.archive-body-stream .entry-tpl-stream .entry-featured-media:not(.entry-media-nsfw-embed)'
    };

    // Due to varied autoplay browsers' policies, it's almost impossible to guarantee autoplying on mobiles, so we turn it off.
    g1.isAutoPlayEnabled   = g1.config.auto_play_videos && ! g1.isTouchDevice();

    var players = {};   // Initialized players.

    g1.autoPlayVideo = function () {
        if ( ! g1.isAutoPlayEnabled ) {
            return;
        }

        var pauseAllVideos = function() {
            g1.log('Pausing all videos');

            for(var i in players) {
                players[i].pause();
            }
        };

        var play = function(element) {
            var postId = $(element).parents('article').attr('id');

            g1.log('Trying to play media...');

            var player = g1.getMediaPlayer(element);

            if (!player) {
                g1.log('Media player not defined for the element');
                return;
            }

            // Before playing this video we want to make sure that others video are paused too.
            pauseAllVideos();

            player.play();

            g1.log(player.getType() + ' played');

            // Store reference.
            if (!players[postId]) {
                players[postId] = player;
            }
        };

        var pause = function (element) {
            g1.log('Trying to pause media...');

            var player = g1.getMediaPlayer(element);

            if (!player) {
                g1.log('Media player not defined for the element');
                return;
            }

            player.pause();

            g1.log(player.getType() + ' paused');
        };

        var bindEvents = function() {

            // Delay waypoint. User scroll activate events.
            var scrollEvents = 0;
            var allowPlaying = false;

            // Wait for user scroll. Not scroll event while page loading.
            $(document).scroll(function() {
                scrollEvents++;

                if (scrollEvents > 5) {
                    allowPlaying = true;
                }
            });

            // ENTER, while up to down scrolling.
            $(selectors.postMedia).waypoint(function(direction) {
                if ('down' === direction) {
                    if (allowPlaying) {
                        g1.log([ '>>> ENTER post (direction: DOWN)', this.element ] );

                        play(this.element);
                    }

                }
            }, {
                // When the bottom of the element hits the bottom of the viewport.
                offset: 'bottom-in-view'
            });

            // ENTER, while down to up scrolling.
            $(selectors.postMedia).waypoint(function(direction) {
                if ('up' === direction) {
                    if (allowPlaying) {
                        g1.log([ '>>> ENTER post (direction: UP)', this.element ] );

                        play(this.element);
                    }
                }
            }, {
                // When the top of the element hits the top of the viewport.
                offset: '0'
            });

            // EXIT, while up to down scrolling.
            $(selectors.postMedia).waypoint(function(direction) {
                if ('down' === direction) {
                    g1.log([ '>>> EXIT post (direction: DOWN)', this.element ] );

                    pause(this.element);
                }
            }, {
                offset: function() {
                    // Fires when top of the element is (HALF OF ELEMENT HEIGHT)px from the top of the window.
                    return -Math.round(this.element.clientHeight / 2);
                }
            });

            // EXIT, while down to up scrolling.
            $(selectors.postMedia).waypoint(function(direction) {
                if ('up' === direction) {
                    g1.log([ '>>> EXIT post (direction: UP)', this.element ] );

                    pause(this.element);
                }
            }, {
                offset: function() {
                    var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

                    // Fires when top of the element is (HALF OF ELEMENT HEIGHT)px from the bottom of the window.
                    return viewportHeight - Math.round(this.element.clientHeight / 2);
                }
            });

            // Play on demand.
            $(selectors.postMedia).on('bimber:play', function() {
                g1.log([ '>>> PLAY ', $(this).get(0) ] );

                play($(this).get(0));
            });

            // Pause on demand.
            $(selectors.postMedia).on('bimber:pause', function() {
                g1.log([ '>>> PAUSE ', $(this).get(0) ] );

                pause($(this).get(0));
            });
        };

        bindEvents();
    };

})(jQuery);

/**************************
 *
 * document ready functions (keep this at the end for better compatibillity with optimizing plugins)
 *
 *************************/

(function ($) {

    'use strict';

    $(document).ready(function () {
        // Init GIF player.
        g1.gifPlayer();

        // Init videos auto load on scroll.
        g1.autoPlayVideo();
    });

})(jQuery);