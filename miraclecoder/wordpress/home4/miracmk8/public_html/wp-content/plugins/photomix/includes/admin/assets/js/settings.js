/* global document */
/* global jQuery */

(function ($) {

    'use strict';

    var adjustWidthHeight = function() {
        var $format = $('#photomix_format');
        var $width  = $('#photomix_max_width');
        var $height = $('#photomix_max_height');

        $format.on('change', function() {
            var width = parseInt($width.val(), 10);

            if (!width) {
                return;
            }

            var scale = getFormatScale('width');
            var height = Math.round(width * scale);

            $height.val(height);
        });

        $width.on('keyup', function() {
            var width = parseInt($(this).val(), 10);

            if (!width) {
                $height.val('');
                return;
            }

            var scale = getFormatScale('width');
            var height = Math.round(width * scale);

            $height.val(height);
        });

        $height.on('keyup', function() {
            var height = parseInt($(this).val(), 10);

            if (!height) {
                $width.val('');
                return;
            }

            var scale = getFormatScale('height');
            var width = Math.round(height * scale);

            $width.val(width);
        });
    };

    var getFormatScale = function(type) {
        type = type || 'width';

        var format  = $('#photomix_format').val();
        var scale   = 1;

        switch(format) {
            case '16_9':
                scale = type === 'width' ? 9 / 16 : 16 / 9;
                break;

            case '4_3':
                scale = type === 'width' ? 3 / 4 : 4 / 3;
                break;
        }

        return scale;
    };

    var toggleGutterColor = function() {
        var isGutterOn = ('standard' === $('#photomix_gutter').val());
        var $gutterColor = $('#photomix_gutter_color').parents('tr');

        if (isGutterOn) {
            $gutterColor.show();
        } else {
            $gutterColor.hide();
        }
    };

    $(document).ready(function() {

        // Color picker.
        $('.photomix-color-picker').wpColorPicker({
            'mode' : 'hsl'
        });

        adjustWidthHeight();

        // Gutter.
        $('#photomix_gutter').on('change', function() {
            toggleGutterColor();
        });

        toggleGutterColor();
    });

})(jQuery);
