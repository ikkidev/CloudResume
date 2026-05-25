/**************************
 *
 * Taxonomy filters
 *
 **************************/
(function ($) {

    'use strict';

    $(document).ready(function () {

        $('.g1-widget-taxonomy-filter').on('click', '.g1-filter-items-more', function(e) {
            $(e.delegateTarget).find('.g1-filter-item-hidden').removeClass('g1-filter-item-hidden');
            $(e.target).remove();
        });

    });

})(jQuery);
