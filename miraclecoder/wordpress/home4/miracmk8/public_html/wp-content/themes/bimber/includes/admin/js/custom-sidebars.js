/* global jQuery */
/* global document */
/* global confirm */
/* global ajaxurl */

(function ($) {
    'use strict';

    var config;

    $(document).ready(function() {
        if ($('body.widgets-php').length > 0) {
            config = bimber_sidebars_config;

            placeForm();
            bindEvents();
        }
    });

    function placeForm () {
        var $widgetsRight = $('#widgets-right');

        if ( $widgetsRight.find('.sidebars-column-2') ) {
            $widgetsRight = $widgetsRight.find('.sidebars-column-2');
        }

        $('#g1ui-sidegen').appendTo($widgetsRight);
    }

    function bindEvents () {
        $('.sidebar-g1-user').each(function () {
            var $sidebar = $(this);
            var $actions = $( '<p class="g1ui-sidegen-item-actions"></p>' );
            var $removeLink = $('<a class="button-link button-link-delete g1ui-sidegen-item-actions-remove" href="#">' + config.i18n.remove + '</a>');

            $removeLink.appendTo($actions);
            $actions.appendTo($sidebar);

            $removeLink.on('click', function (e) {
                e.preventDefault();

                var sidebarId = $(this).parent('p').prev('div').attr('id');

                if ( $sidebar.find('.widget').length > 0 ) {
                    alert(config.i18n.sidebar_not_empty);
                    return;
                }

                removeSidebar(sidebarId, $sidebar);
            });
        });
    }

    function removeSidebar (id, $sidebar) {
        if (!confirm(config.i18n.confirm_removal)) {
            return;
        }

        // remove previous error message
        $('.g1ui-sidegen-item-errors', $sidebar).remove();

        var $loader = $('<span class="g1ui-sidegen-item-actions-loader">' + config.i18n.removing + '</span>');
        var $errorMessage = $('<p class="g1ui-sidegen-item-errors">' + config.i18n.removal_failed + '</p>');

        var xhr = $.ajax({
            'type': 'POST',
            'url' : ajaxurl,
            'data': {
                'action'   :    'bimber_remove_sidebar',
                'security':     $('input[name=bimber-custom-sidebar-ajax-nonce]').val(),
                'ajax_data': {
                    sidebar_id: id
                }
            },
            'beforeSend': function () {
                $sidebar.find( '.g1ui-sidegen-actions' ).prepend($loader);
            }
        });

        xhr.done(function (res) {
            if (res === 'success') {
                $sidebar.slideUp(200);
            } else {
                $sidebar.append($errorMessage);
            }
        });

        xhr.fail(function () {
            $sidebar.append($errorMessage);
        });

        xhr.always(function () {
            $loader.remove();
        });
    }
})(jQuery);
