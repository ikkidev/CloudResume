/* global jQuery */
/* global document */
/* global confirm */
/* global ajaxurl */

/**
 *
 * Navigation
 *
 */
(function($) {

    'use strict';

    $(document).ready(function(){
        if ($('body.appearance_page_theme-options').length > 0) {
            new $.G1ThemeOptionsNav();
        }
    });

    $.G1ThemeOptionsNav = function () {
        this._init();
    };

    $.G1ThemeOptionsNav.prototype = {
        '_init': function () {
            this._bindEvents();
            this._loadPluginPage();
            this._showSubmitButton();
            this._setCurrentTab();
        },
        '_setCurrentTab': function () {
            // GET variable has higher priority.
            if (window.location.href.match('group=.*')) {
                return;
            }

            var tabId = this._readCookie('g1_theme_options_group');

            var $tabToActivate = $('#nav-tab-' + tabId);

            if ($tabToActivate.length > 0) {
                $tabToActivate.trigger('click');
            }
        },
        '_loadPluginPage': function () {
            var $pluginSelectedOnStartup = $('a.nav-tab-active.g1-plugin');

            if ($pluginSelectedOnStartup.length > 0) {
                // emulate user selection
                $pluginSelectedOnStartup.trigger('click');
            }
        },
        '_bindEvents': function () {
            this._onMainMenuClick();
            this._onSubMenuClick();
        },
        '_onMainMenuClick': function () {
            var _this = this;

            $('.nav-tab-wrapper > a').on('click', function (e) {
                e.preventDefault();

                var $navicon = $(this);
                var isCurrent = $navicon.is('.nav-tab-active');
                var isExternalPlugin = $navicon.is('.g1-plugin');
                var isLoaded = $navicon.is('.g1-plugin-loaded');

                // skip
                if (( isCurrent && !isExternalPlugin ) || ( isCurrent && isExternalPlugin && isLoaded )) {
                    return;
                }

                // highlight current group
                $('.nav-tab-active').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');

                _this._hideSubmenus();
                _this._showSubmitButton();

                // get group id from link anchor param
                var anchor = $(this).attr('href');
                var group = anchor.match(/&group=(.*)/);
                var groupId = '';

                // theme's internal settings
                if (group) {
                    groupId = group[1];

                    var $sectionsMenu = $('#g1ui-nav-tab-wrapper-' + groupId);

                    if ($sectionsMenu.length > 0) {
                        $sectionsMenu.show();
                        $sectionsMenu.find('> a:first').
                            removeClass('nav-tab-active').
                            trigger('click');
                    } else {
                        _this._deleteCookie('g1_theme_options_section');

                        // remove section selection
                        //$('.nav-tab-active').removeClass('nav-tab-active');

                        _this._showContentForSection(groupId);
                    }
                    // if group not defined, load plugin page via iframe
                } else {
                    var page = anchor.match(/\?page=(.*)/);

                    if (page) {
                        groupId = page[1];

                        // right now plugins have no sections
                        // so we need to clear current selection
                        _this._deleteCookie('g1_theme_options_section');

                        // load
                        if (!isLoaded) {
                            _this._hideAllSections();
                            _this._createSection(groupId, anchor);
                            $navicon.addClass('g1-plugin-loaded');
                        }

                        _this._showContentForSection(groupId);
                    }
                }

                _this._createCookie('g1_theme_options_group', groupId);
            });
        },
        '_onSubMenuClick': function () {
            var _this = this;

            $('.nav-tab-wrapper > a').on('click', function (e) {
                // skip if tab is selected
                if ($(this).is('.nav-tab-active')) {
                    e.preventDefault();
                    return;
                }

                // get section id from link anchor param
                var anchor = $(this).attr('href');
                var section = anchor.match(/&group=(.*)&section=(.*)/);

                if (section) {
                    e.preventDefault();

                    var groupId = section[1];
                    var sectionId = section[2];

                    // highlight current section
                    $('.nav-tab-active').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active');

                    _this._createCookie('g1_theme_options_section', sectionId);

                    _this._showContentForSection(groupId, sectionId);
                }
            });
        },
        '_createSection': function (groupId, anchor) {
            var $section = $('<div id="g1ui-settings-section-' + groupId + '" class="g1ui-settings-section">');
            var $info = $('<p class="g1ui-settings-section-msg">Loading...</p>');

            $('.g1ui-settings-section:last').after($section);

            $section.append($info);

            var $iframe = $('<iframe class="g1-plugin-page" src="' + anchor + '">');

            $iframe.hide();
            $section.append($iframe);

            $iframe.load(function () {
                var $iframeContent = $iframe.contents();

                $info.remove();
                $iframe.show();

                // hide elements inside iframe, besides plugin form
                //$iframeContent.find('#adminmenu, #adminmenuback, #wpadminbar, #wpfooter, .nav-tab-wrapper').remove();

                $iframeContent.find('#wpcontent').css({
                    'margin-left': 0,
                    'padding-left': 0
                });

                $iframeContent.find('.wp-toolbar').css({
                    'padding-top': 0
                });

                $iframeContent.find('.wrap').css({
                    'margin-top': 0
                });

                $iframeContent.find('input[type=submit]').hide();

                $iframeContent.find('#wpbody-content').css('padding-bottom', '20px');

                // adjust iframe height
                var $pluginContent = $iframeContent.find('#wpwrap');

                $iframe.css('height', $pluginContent.css('height'));
            });
        },
        '_showSubmitButton': function () {
            var $selectedNavItem = $('.nav-tab-wrapper > a.nav-tab-active');
            var $themeOptionsForm = $('#theme-options-form');

            if ($themeOptionsForm.length === 0) {
                return;
            }

            var $submitButton = $themeOptionsForm.find('.g1ui-settings-toolbar .button-primary');

            if ($selectedNavItem.is('.g1-form')) {
                $submitButton.show();
            } else {
                $submitButton.hide();
            }
        },
        '_hideAllSections': function () {
            $('.g1ui-settings-section').hide();
        },
        '_hideSubmenus': function () {
            //$('.nav-tab-wrapper').hide();
        },
        '_showContentForSection': function (groupId, sectionId) {
            this._hideAllSections();

            var selector = '#g1ui-settings-section-' + groupId;

            if (sectionId) {
                selector += '-' + sectionId;
            }

            $(selector).show();
        },
        '_createCookie': function (name, value, days) {
            var expires;

            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            else {
                expires = '';
            }

            document.cookie = name.concat('=', value, expires, '; path=/');
        },
        '_readCookie': function (name) {
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

        },
        '_deleteCookie': function (name) {
            this._createCookie(name, '', -1);
        }
    };
})(jQuery);

/**
 *
 * Registration
 *
 */
(function($) {

    'use strict';

    $(document).ready(function() {

        var $wrapper = $('#bimber-theme-registration');

        if ($wrapper.length === 0) {
            return;
        }

        var $purchaseCodeInput  = $wrapper.find('#bimber-purchase-code');
        var $tokenInput         = $wrapper.find('#bimber-token');
        var $termsInput         = $wrapper.find('#bimber-accept-license-terms');
        var $registerButton     = $wrapper.find('#bimber-register-theme');
        var $deregisterButton   = $wrapper.find('#bimber-deregister-theme');
        var $registerWithTokenButton = $wrapper.find('#bimber-register-with-token');
        var $altRegistration    = $wrapper.find('#bimber-alt-registration');

        $termsInput.on('change', function(e) {
            if ($(this).is(':checked')) {
                $registerButton.removeAttr('disabled');
            } else {
                $registerButton.attr('disabled', 'disabled');
            }
        });

        $tokenInput.on('keyup', function(e) {
            if ($(this).val().length > 0) {
                $registerWithTokenButton.removeAttr('disabled');
            } else {
                $registerWithTokenButton.attr('disabled', 'disabled');
            }
        });

        $registerButton.on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            $button.attr('disabled', true);
            $altRegistration.hide();
            var $spinner = $button.next('.bimber-actions .spinner');
            $spinner.addClass('is-active');

            var xhr = $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'dataType': 'json',
                'data': {
                    'action':           'bimber_register_purchase_code',
                    'purchase_code':    $purchaseCodeInput.val()
                }
            });

            xhr.done(function(res) {
                $button.attr('disabled', false);
                $spinner.removeClass('is-active');

                var $error = $wrapper.find('#bimber-registration-error');
                $error.empty();

                if (res.status === 'success') {
                    window.location.reload();
                } else {
                    $error.html('<div class="error"><p>' + res.message + '</p></div>');

                    // Show token registration form.
                    $altRegistration.show();

                    // Update purchase code in token generator URL.
                    var $url = $altRegistration.find('a#bimber-token-generator-url');
                    var url = $url.attr('href').replace('PURCHASE_CODE', $purchaseCodeInput.val());
                    $url.attr('href', url);
                }
            });
        });

        $deregisterButton.on('click', function(e) {
            e.preventDefault();
            var nonce = $(this).attr('data-bimber-nonce');

            var xhr = $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'dataType': 'json',
                'data': {
                    'action':           'bimber_deregister_purchase_code',
                    'security':         nonce,
                }
            });

            xhr.done(function(res) {
                if (res.status === 'success') {
                    window.location.reload();
                }
            });
        });

        $registerWithTokenButton.on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            $button.attr('disabled', true);
            var $spinner = $button.next('.bimber-actions .spinner');
            $spinner.addClass('is-active');

            var xhr = $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'dataType': 'json',
                'data': {
                    'action':           'bimber_register_token',
                    'token':            $tokenInput.val(),
                    'purchase_code':    $purchaseCodeInput.val()
                }
            });

            xhr.done(function(res) {
                $button.attr('disabled', false);
                $spinner.removeClass('is-active');

                var $error = $wrapper.find('#bimber-token-registration-error');
                $error.empty();

                if (res.status === 'success') {
                    window.location.reload();
                } else {
                    $error.html('<div class="error"><p>' + res.message + '</p></div>');
                }
            });
        });

    });

})(jQuery);

/**
 *
 * GDPR
 *
 */
(function($) {

    'use strict';

    $(document).ready(function() {
        if ($('#bimber-enable-gdpr').is(':checked')) {
            $('#g1ui-settings-section-gdpr').addClass('g1ui-settings-section-gdpr-enabled');
        } else {
            $('#g1ui-settings-section-gdpr').removeClass('g1ui-settings-section-gdpr-enabled');
        }
        $('.g1-install-wp-gdpr-compliance').on('click', function(e) {
            e.preventDefault();

            var $link               = $(this);
            var $wrapper            = $link.parents('p');
            var pluginActionUrl     = $link.attr('href');
            var $notActivatedState  = $wrapper.find('.wp-gdpr-compliance-not-activated');
            var $installingState    = $wrapper.find('.wp-gdpr-compliance-installing');
            var $activatedState     = $wrapper.find('.wp-gdpr-compliance-activated');
            var $failedState        = $wrapper.find('.wp-gdpr-compliance-installation-failed');

            $notActivatedState.hide();
            $installingState.show();

            var xhr = $.ajax({
                'type': 'GET',
                'url': pluginActionUrl
            });

            xhr.done(function() {
                $installingState.hide();
                $activatedState.show();
            });

            xhr.fail(function() {
                $installingState.hide();
                $failedState.show();
            });
        });
        $('#bimber-enable-gdpr').on('click', function() {
            var enabled = $(this).is(':checked');
            $('#g1ui-settings-section-gdpr tr:nth-child(3) input').prop('checked', enabled);
            if (enabled) {
                $('#g1ui-settings-section-gdpr').addClass('g1ui-settings-section-gdpr-enabled');
            } else {
                $('#g1ui-settings-section-gdpr').removeClass('g1ui-settings-section-gdpr-enabled');
            }
        });
    });

})(jQuery);

/**
 *
 * my CRED import
 *
 */
(function($) {

    'use strict';

    $(document).ready(function() {
        $('.bimber-import-mycred-button-reset').on('click', function(e) {
            e.preventDefault();

            var $link               = $(this);
            var nonce = $('#bimber_mycred_import_nonce_reset').val();

            var xhr = $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'dataType': 'json',
                'data': {
                    'action':       'bimber_mycred_import_reset',
                    'security': nonce,
                }
            });

            xhr.done(function() {
                $link.replaceWith('<span>Done!</span>');
            });

            xhr.fail(function() {
                $link.replaceWith('<span>Something went wrong</span>');
            });
        });

        $('.bimber-import-mycred-button-import').on('click', function(e) {
            e.preventDefault();

            var $link               = $(this);
            var $result             = $('.bimber-import-mycred-result');
            var data = {};
            $('input[name^="import_mycred_settings"]').each(function() {
                data[$(this).val()] = $(this).is(':checked');
            });
            var nonce = $('#bimber_mycred_import_nonce').val();

            $result.html('<br><span>Working...</span>');

            var xhr = $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'dataType': 'json',
                'data': {
                    'action':       'bimber_mycred_import',
                    'security': nonce,
                    'options': data
                }
            });

            xhr.done(function() {
                $result.html('<br><span>Done!</span>');
            });

            xhr.fail(function() {
                $link.replaceWith('<span>Something went wrong</span>');
            });
        });
    });

})(jQuery);

/**
 *
 * Shares
 *
 */
(function ($) {

    'use strict';

    var networksSorting = function() {

        if (typeof $.fn.sortable !== 'function' ) {
            return;
        }

        $('.bimber-share-networks.sortable').sortable({
            cursor: "move", // Cursor during dragging.
            update: function(e, ui) {
                var $networks = $(ui.item).parents('.bimber-share-networks');
                var $order    = $networks.parent().find('.bimber-share-networks-order');

                var order = [];

                $networks.find('.bimber-share-network').each(function() {
                    order.push($(this).val());
                });

                // Update networks order.
                $order.val(order.join(','));
            }
        });
    };

    $(document).ready(function () {

        // Allow networks sorting.
        networksSorting();

    });

})(jQuery);
