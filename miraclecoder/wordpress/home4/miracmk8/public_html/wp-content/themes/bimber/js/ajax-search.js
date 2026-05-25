/*****************
 *
 * Ajax search
 *
 *****************/

(function ($) {

    'use strict';

    var selectors = {
        'wrapper':  'div[role=search]',
        'form':     'form.g1-searchform-ajax',
        'input':    'form.g1-searchform-ajax input.search-field',
        'results':  '.g1-searches-ajax',
        'seeAll':   '.bimber-see-all-results'
    };

    g1.ajaxSearch = function () {
        if (!$.fn.autocomplete) {
            return;
        }

        var minLength = 2;

        $( selectors.input).each(function() {
            var $input   = $(this);
            var $form    = $input.parents(selectors.form);
            var $wrapper = $input.parents(selectors.wrapper);



            $input.autocomplete({
                'appendTo':   $form,
                'delay':      500,
                'minLength':  minLength,
                'source': function(request) {
                    var xhr = $.ajax({
                        'type': 'GET',
                        'url': g1.config.ajax_url,
                        'dataType': 'json',
                        'data': {
                            'action':       'bimber_search',
                            'bimber_term':  request.term
                        }
                    });

                    xhr.done(function (res) {
                        if (res.status === 'success') {
                            $wrapper.find(selectors.results).html(res.html);
                        }
                    });
                }
            });

            // Empty results.
            $input.on('keyup search', function() {
                if ($(this).val().length < minLength) {
                    $wrapper.find(selectors.results).html('');
                }
            });

            // See all results.
            $wrapper.on('click', selectors.seeAll, function(e) {
                e.preventDefault();

                $form.submit();
            });
        });
    };

    // Fire it up.
    $(document).ready(function () {
        g1.ajaxSearch();
    });
})(jQuery);
