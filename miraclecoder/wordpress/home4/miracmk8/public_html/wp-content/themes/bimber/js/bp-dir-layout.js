/* global BP_Nouveau */

(function ($) {
    'use strict';

    $('.g1-bp-dir-layout').each(function() {
        var $container = $(this);
        var component = $container.data('component');

        $container.find('.g1-button').on('click', function (e) {
            $container.find('.g1-button-current').removeClass('g1-button-current');


            e.preventDefault();

            if (typeof BP_Nouveau === 'undefined') {
                return;
            }

            if (typeof BP_Nouveau.customizer_settings) {
                BP_Nouveau.customizer_settings = {};
            }

            // Apply new setup.
            var setting = 'bp_nouveau_appearance_' + component + '_layout';
            BP_Nouveau.customizer_settings[setting] = $(this).data('cols');
            $(this).addClass('g1-button-current');

            // Force BP to reload layout.
            $('#' + component + '-order-by').trigger('change');
        });

    });

})(jQuery);
