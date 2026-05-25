/* global document */
/* global jQuery */

var photomix = {};

(function ($, ctx) {

    'use strict';

    ctx.openMediaLibrary = function(callbacks) {
        var frame = wp.media({
            'title':    'Select an image',
            'multiple': false,
            'library':  {
                'type': 'image'
            },
            'button': {
                'text': 'Insert'
            }
        });

        frame.on('select',function() {
            var objSelected = frame.state().get('selection').first().toJSON();

            callbacks.onSelect(objSelected);
        });

        frame.open();
    };

})(jQuery, photomix);
