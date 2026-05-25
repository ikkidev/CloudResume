/**************************
 *
 * Archive filters
 *
 **************************/
(function ($) {

    'use strict';

    g1.archiveFilters = function () {
        $('#g1-archive-filter-select').on('change', function() {
            var $this = $(this);
            $('option:selected', $this).each(function() {
                window.location.href = $(this).attr('data-g1-archive-filter-url');
            });
        });
    };

    $(document).ready(function () {
        g1.archiveFilters();
    });

})(jQuery);
