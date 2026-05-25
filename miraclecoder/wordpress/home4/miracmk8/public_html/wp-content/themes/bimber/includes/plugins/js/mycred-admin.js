/* global document */
/* global jQuery */
/* global bimber_loco_translate_config */

(function($) {
    'use strict';

    $(document).ready(function() {
        var init = function(scope){
            if (!scope) {
                scope = $('.widget-liquid-right');
            }
            var template = '<div style="margin: 12px 0;"><input type="checkbox" value="1" class="bimber-mycred-admin-widget-list-checkbox">Use Bimber\'s built in layout.</div>'

            var textareas = $('.myCRED-widget-field textarea', scope);
            textareas.each(function( index ){
                var id = $(textareas[index]).attr('id');
                if (id.indexOf('mycred_widget_list') > -1 && id.indexOf('-text') > 0){
                    var textarea = $(textareas[index]);
                    textarea.before(template);
                    if (textarea.val() === ''){
                        $('input',textarea.prev('div')).prop('checked', true);
                        textarea.hide();
                    }
                }
            });
            bindEvents();
        };

        var bindEvents = function() {
            $('.bimber-mycred-admin-widget-list-checkbox').click(function(){
                var textarea = $(this).closest('div').next('textarea');
                if ($(this).is(':checked')) {
                    textarea.val('');
                    textarea.hide();
                }else {
                    textarea.val('#%position% %user_profile_link% %cred_f%');
                    textarea.show();
                }
            });
        };

        $(document).on('widget-updated', function(event, widget){
            init(widget);
        });
        $(document).on('widget-added', function(event, widget){
            init(widget);
        });
        init();
    });


})(jQuery);
