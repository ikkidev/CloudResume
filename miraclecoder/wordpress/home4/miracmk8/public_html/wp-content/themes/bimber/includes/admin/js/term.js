/* global jQuery */
/* global document */
/* global confirm */

(function($) {

    'use strict';

    $(document).ready(function(){
        $('.bimber-color-picker').wpColorPicker();

        $('.bimber-image-upload').each(function() {
            imageUploadControl($(this));
        });

        hideElements();
    });

    var imageUploadControl = function($el) {
        var $image      = $el.find('.bimber-image');
        var $addLink    = $el.find('.bimber-add-image');
        var $deleteLink = $el.find('.bimber-delete-image');
        var $imageId    = $el.find('.bimber-image-id');

        if ( $imageId.val().length > 0 ) {
            $addLink.hide();
            $deleteLink.show();
        } else {
            $addLink.show();
            $deleteLink.hide();
        }

        $addLink.on('click', function(e) {
            e.preventDefault();

            openMediaLibrary(function(imageObj) {
                var thumb = imageObj.sizes.thumbnail;
                if (thumb){
                    $image.html('<img src="' + thumb.url + '" width="' + thumb.width + '" height="' + thumb.height + '" />');
                }

                $imageId.val(imageObj.id);

                $addLink.hide();
                $deleteLink.show();
            });
        });

        $deleteLink.on('click', function(e) {
            e.preventDefault();

            if ( ! confirm( 'Are you sure?' ) ) {
                return;
            }

            $image.empty();
            $imageId.val('');

            $addLink.show();
            $deleteLink.hide();
        });
    };

    var openMediaLibrary = function(callback) {
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

            callback(objSelected);
        });

        frame.open();
    };

    var hideElements = function() {
        $('#bimber_override_hide_elements').on('change', function() {
            var option     = $(this).val();
            var $dependent = $('#bimber-hide-elements-wrapper');

            if ('none' === option) {
                $dependent.hide();
            } else {
                $dependent.show();
            }
        });
        $('#bimber_header_override_hide_elements').on('change', function() {
            var option     = $(this).val();
            var $dependent = $('#bimber-hide-elements-archive-header-wrapper');

            if ('none' === option) {
                $dependent.hide();
            } else {
                $dependent.show();
            }
        });
    };

})(jQuery);