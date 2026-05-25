(function ($) {

    $(document).ready( function() {
        handleInputNumber();
        handleMultiCheckboxControl();
        handleSortableControl();
        handleTagSelectControl();
        handleVC();
        handleElementor();
        handleInputRange();
        handleTypography();

        $('.bimber-test').on('click', function(e){
            e.preventDefault();
            wp.customize.section( 'bimber_header_layout_section' ).focus();
        });
    });

    function handleInputRange() {
        $('.g1-range-undo-icon').on('click', function() {
            var $parent = $(this).closest('li');
            $('.g1-custom-range-control-slider', $parent).val($(this).attr('data-g1-default'));
            $('.g1-custom-range-control-slider', $parent).change();
        });
        $('.g1-custom-range-control-slider').on('change', function() {
            var $parent = $(this).closest('li');
            $('.g1-custom-range-control-field', $parent).val($(this).val());
        });
        $('.g1-custom-range-control-field').on('change', function() {
            var $parent = $(this).closest('li');
            $('.g1-custom-range-control-slider', $parent).val($(this).val());
            $('.g1-custom-range-control-slider', $parent).change();
        });
    }

    function handleInputNumber() {
        $('input[type=number]').each(function() {
            var $input = $(this);
            var min = parseInt($input.attr('min'), 10);

            if (!isNaN(min)) {
                $input.on('keyup', function() {
                    var val = $input.val();

                    // Invalid number.
                    if (val && ! parseInt(val, 10)) {
                        $input.val(min);
                    }

                    // Valid number but negative.
                    if (val && parseInt(val, 10) && parseInt(val, 10) < 0) {
                        $input.val(min);
                    }
                });
            }
        });
    }

    function handleMultiCheckboxControl () {

        $('.customize-control-multi-checkbox input[type="checkbox"]').on( 'change', function() {

                var $checked = $(this).parents('.customize-control').find('input[type="checkbox"]:checked');
                var $hidden = $(this).parents('.customize-control').find('input[type="hidden"]');

                var values = $checked.map(
                    function() {
                        return this.value;
                    }
                ).get().join(',');

                $hidden.val(values).trigger('change');
            }
        );
    }

    function handleSortableControl() {
        var $elem = $('ul.g1-customizer-sortable');

        var findInput = function ($li) {
            var settingLink = $li.attr('data-bimber-setting-link');
            var escapedLink = settingLink.replace('[', '\\[').replace(']', '\\]');

            return $('input[data-customize-setting-link="' + escapedLink + '"]');
        };

        // Hide original controls.
        $elem.find('> li').each(function () {
            var $input = findInput($(this));

            $input.parents('.customize-control').hide();
        });

        $elem.sortable({
            'items': '> li',
            update: function () {
                var currentOrder = 10;
                var $ul = $(this);

                $ul.find('> li').each(function () {
                    var $input = findInput($(this));

                    if (currentOrder !== parseInt($input.val(), 10)) {
                        $input.val(currentOrder);
                        $input.trigger('change');
                    }

                    currentOrder += 5;
                });
            }
        });
    }

    function handleTagSelectControl() {
        var tagBox = window.tagBox;

        if (!tagBox) {
            return;
        }

        var origQuickClick = tagBox.quickClicks;

        /// Hook into the original function.
        tagBox.quickClicks = function(el) {
            origQuickClick(el);

            // Notify the Customizer about a change.
            $('.the-tags', el).trigger('change');
        };

        tagBox.init();
    }

    function handleVC() {
        $('#_customize-input-bimber_home_vc_page_id').on('change', function () {
            var val = $(this).val();

            if (val) {
                $('#customize-control-bimber_home_vc_edit_link .bimber-vc-page-id').each(function() {
                    var $link = $(this);

                    var href = $link.attr('href');
                    href = href.replace(/post=(\d+)?/, 'post=' + val);
                    href = href.replace(/post_id=(\d+)?/, 'post_id=' + val);

                    $link.attr('href', href);
                });
            }
        });
    }

    function handleElementor() {
        $('#_customize-input-bimber_home_elementor_page_id').on('change', function () {
            var val = $(this).val();

            if (val) {
                $('#customize-control-bimber_home_elementor_edit_link .bimber-elementor-page-id').each(function() {
                    var $link = $(this);

                    var href = $link.attr('href');
                    href = href.replace(/post=(\d+)?/, 'post=' + val);
                    href = href.replace(/post_id=(\d+)?/, 'post_id=' + val);

                    $link.attr('href', href);
                });
            }
        });
    }

    function handleTypography() {

       var bindManageControls = function() {
            $('.g1-typo-selectors-button').on('click', function(e){
                e.preventDefault();
                $('.g1-typo-selectors-modal').toggleClass('g1-typo-selectors-modal-active');
                $(this).toggleClass('active');
            });
            $('.g1-typo-selectors-modal-button-add').on('click', function(e){
                e.preventDefault();
                $('.g1-typo-selectors-modal').removeClass('g1-typo-selectors-modal-active');
                $('.g1-typo-selectors-button').removeClass('active');
                $('.g1-typo-selectors-modal-list li').each(function() {
                    if ($('input', $(this)).is(':checked')){
                        var id = $(this).attr('g1-data-id');
                        var $parent = $('#' + id);
                        $parent.trigger('activate');
                    }
                });
                populateSelectors();
            });
            $('.g1-typo-selectors-modal-button-cancel').on('click', function(e){
                e.preventDefault();
                $('.g1-typo-selectors-modal').removeClass('g1-typo-selectors-modal-active');
                $('.g1-typo-selectors-button').removeClass('active');
                $('.g1-typo-selectors-modal-list li').each(function() {
                    if ($('input', $(this)).is(':checked')){
                        $('input', $(this)).prop('checked', false);
                    }
                });
            });
       };

        var updateValue = function( $parent ) {
            var value = {};
            if ($('.customize-control-title', $parent).attr('data-g1-selector-active') === 'on') {
                value.selector = $('.customize-control-title', $parent).attr('data-g1-selector');
                $('.g1-typo-setting', $parent).each(function(){
                    var $setting = $(this);
                    if ($('.g1-typo-setting-input', $setting).val() !== 'g1-none' && ! $('.g1-typo-setting-input', $setting).prop('disabled')) {
                        var settingSlug = $setting.attr('data-g1-sub-field-name');
                        var settingValue = $('.g1-typo-setting-input', $setting).val();
                        //if (settingSlug === 'letter-spacing'){
                        //    settingValue = settingValue/1000;
                        //}
                        value[settingSlug] = settingValue;
                    }
                });
            }
            setValue( $parent,value );
        };

        var setValue = function( $parent, value ) {
            value = JSON.stringify(value);
            $('.g1-typo-final-value', $parent).val(value);
            $('.g1-typo-final-value', $parent).change();
        };

        var bindEvents = function( $scope ) {
            if ($(this).hasClass('g1-typo-settings-bind')) {
                return;
            }
            //tabs.
            $('.g1-typo-tab', $scope).on('click', function() {
                var $parent = $(this).closest('.customize-control-typography');
                $('.g1-typo-tab', $parent).removeClass('selected');
                $(this).addClass('selected');
                var tab= $(this).attr('data-g1-tab');
                $('.g1-typo-setting', $parent).hide();
                $('.g1-typo-setting-tab-' + tab, $parent).show();
            });

            //undo.
            $('.g1-typo-undo-icon', $scope).on('click', function(e) {
                if(!$(this).hasClass('g1-typo-undo-icon-active')){
                    return;
                }
                e.preventDefault();
                var $parent = $(this).closest('li');
                $('.g1-typo-setting-input', $parent).val($(this).attr('data-g1-default'));
                $('.g1-typo-setting-input', $parent).change();
            });

            //update.
            $('input:not(.g1-typo-final-value), select', $scope).each(function() {
                $(this).on('input change', function(e) {
                    var $parent = $(this).closest('.customize-control-typography');
                    //activate undo only if "manually" triggered.
                    if( ! e.isTrigger || $(this).hasClass('g1-typo-setting-range')) {
                        $('.g1-typo-undo-icon', $(this).closest('.g1-typo-setting')).addClass('g1-typo-undo-icon-active');
                    }
                    updateValue($parent);
                });
            });

            $('.g1-typo-setting-input-font-family', $scope).on('input change', function() {
                var $parent = $(this).closest('.g1-typo-settings');
                var $select = $('.g1-typo-setting-font-style select', $parent);
                var keepValue = $select.val();
                populateFontStyles($select);
                if ( $('option[value="' + keepValue + '"]').length > 0 ) {
                    $select.val(keepValue);
                }
                $select.change();
            });

            //initialize font styles.
            $('.g1-typo-setting-font-style select', $scope).each(function() {
                populateFontStyles($(this));
                var $parent = $(this).closest('.g1-typo-setting');
                $(this).val($('.g1-typo-undo-icon', $parent).attr('data-g1-default'));
                $(this).change();
            });

            //init tabs.
            $('.g1-typo-setting-tab-tablet', $scope).hide();
            $('.g1-typo-setting-tab-mobile', $scope).hide();

            //init slideup.
            $('.customize-control-title', $scope).on('click', function(){
                var $parent = $(this).closest('li');
                $parent.toggleClass('customize-control-typography-unwrapped');
                $('.g1-typo-toggle', $parent).toggleClass('g1-typo-toggle-rotate');
            });

            //init rangeslider.
            var $sizeSlider = $scope.find('.g1-typo-setting-range');

            $sizeSlider.rangeslider({
                polyfill: false
            });

            $('.g1-typo-setting-range', $scope).on('input change', function() {
                var $parent = $(this).closest('.g1-typo-setting');
                $('.g1-typo-setting-range-value', $parent).val($(this).val());
            });
            $('.g1-typo-setting-range-value', $scope).on('input change', function() {
                if ($(this).val() > 0) {
                    var $parent = $(this).closest('.g1-typo-setting');
                    $('.g1-typo-setting-range', $parent).val($(this).val());
                    $('.g1-typo-setting-range', $parent).change();
                }
            });

            //remove button.
            $('.g1-typo-remove').on('click', function() {
                var $parent = $(this).closest('.customize-control-typography');
                $('.g1-typo-selectors-modal').removeClass('g1-typo-selectors-modal-active');
                $('.g1-typo-selectors-button').removeClass('active');
                $($parent).trigger('deactivate');
                populateSelectors();
            });
        };

        var populateFontStyles = function($select) {
            $('.g1-typo-undo-icon', $select.closest('.g1-typo-setting')).removeClass('g1-typo-undo-icon-active');
            var $parent = $select.closest('.g1-typo-settings');
            var $fontFamily = $('.g1-typo-setting-font-family', $parent);
            var $fontFamilySelected =  $('.g1-typo-setting-input-font-family :selected', $fontFamily)
            if ( $fontFamilySelected.val() !== 'g1-none' && $fontFamily.length > 0 ) {
                $select.prop('disabled', false);
                var choices = $fontFamilySelected.attr('data-g1-font-variants').split(',');
                var labels = $fontFamilySelected.attr('data-g1-font-variant-names').split(',');
                var out = '';
                out += '<option value="-1"></option>';
                $.each(choices, function (index, choice) {
                    out += '<option value="' + choice + '">' + labels[index] + '</option>';
                });
                $select.html(out);
            } else {
                $select.prop('disabled', true);
                $select.html('<option value="-1"></option>');
            }
            $select.change();
        };

        var populateSelectors = function() {
            var html = '';
            $('.customize-control-typography').each(function() {
                var $this = $(this);
                var name = $('.customize-control-title', $this).text();
                var desc = $('.customize-control-description', $this).text();
                var id = $this.attr('id');
                var theClass = '';
                if($this.hasClass('customize-control-typography-active')){
                    theClass = 'g1-typo-modal-item-activated';
                }
                html += '<li g1-data-id="' + id + '" class="' + theClass + '"><label><input type="checkbox">' + name + '</label>';
                if (desc.length > 0) {
                    html += '<span class="g1-typo-modal-item-desc">' + desc + '</span>';
                }
                html += '</li>';
            });
            $('.g1-typo-selectors-modal-list').html(html);
        };

        var initializeControls = function(){
            $('.customize-control-typography').each(function() {
                $(this).on('activate', function() {
                    var $this = $(this);
                    if (!$this.hasClass('g1-typo-settings-bind')) {
                        bindEvents($this);
                        $this.addClass('g1-typo-settings-bind');
                    }
                    $('.g1-typo-settings', $this).addClass('g1-typo-settings-active');
                    $('.g1-typo-tabs', $this).addClass('g1-typo-settings-active');
                    $('.customize-control-title', $this).attr('data-g1-selector-active','on');
                    $this.addClass('customize-control-typography-active');
                    updateValue($this);
                });
                $(this).on('deactivate', function() {
                    var $this = $(this);
                    $('.g1-typo-settings', $this).removeClass('g1-typo-settings-active');
                    $('.g1-typo-tabs', $this).removeClass('g1-typo-settings-active');
                    $('.customize-control-title', $this).attr('data-g1-selector-active','off');
                    $this.removeClass('customize-control-typography-active');
                    updateValue($this);
                });
                if ($('.customize-control-title', this).attr('data-g1-selector-active') === 'on') {
                    var $this = $(this);
                    bindEvents($this);
                    $this.addClass('customize-control-typography-active');
                }
                updateValue($(this));
            });
        };

        initializeControls();
        populateSelectors();
        bindManageControls();
    }

})(jQuery);

/**
 * Override default wpTagsSuggest() to fetch tag slugs, not names.
 */
( function( $ ) {
    if ( typeof window.tagsSuggestL10n === 'undefined' || typeof window.uiAutocompleteL10n === 'undefined' ) {
        return;
    }

    var tempID = 0;
    var separator = window.tagsSuggestL10n.tagDelimiter || ',';

    function split( val ) {
        return val.split( new RegExp( separator + '\\s*' ) );
    }

    function getLast( term ) {
        return split( term ).pop();
    }

    /**
     * Add UI Autocomplete to an input or textarea element with presets for use
     * with non-hierarchical taxonomies.
     *
     * Example: `$( element ).wpTagsSuggest( options )`.
     *
     * The taxonomy can be passed in a `data-wp-taxonomy` attribute on the element or
     * can be in `options.taxonomy`.
     *
     * @since 4.7.0
     *
     * @param {object} options Options that are passed to UI Autocomplete. Can be used to override the default settings.
     * @returns {object} jQuery instance.
     */
    $.fn.wpTagsSuggest = function( options ) {
        var cache;
        var last;
        var $element = $( this );

        options = options || {};

        var taxonomy = options.taxonomy || $element.attr( 'data-wp-taxonomy' ) || 'post_tag';

        delete( options.taxonomy );

        options = $.extend( {
            source: function( request, response ) {
                var term;

                if ( last === request.term ) {
                    response( cache );
                    return;
                }

                term = getLast( request.term );

                $.get( window.ajaxurl, {
                    action: 'bimber_tag_search',
                    tax: taxonomy,
                    q: term
                } ).always( function() {
                    $element.removeClass( 'ui-autocomplete-loading' ); // UI fails to remove this sometimes?
                } ).done( function( data ) {
                    var tagName;
                    var tags = [];

                    if ( data ) {
                        data = data.split( '\n' );

                        for ( tagName in data ) {
                            var id = ++tempID;

                            tags.push({
                                id: id,
                                name: data[tagName]
                            });
                        }

                        cache = tags;
                        response( tags );
                    } else {
                        response( tags );
                    }
                } );

                last = request.term;
            },
            focus: function( event, ui ) {
                $element.attr( 'aria-activedescendant', 'wp-tags-autocomplete-' + ui.item.id );

                // Don't empty the input field when using the arrow keys to
                // highlight items. See api.jqueryui.com/autocomplete/#event-focus
                event.preventDefault();
            },
            select: function( event, ui ) {
                var tags = split( $element.val() );
                // Remove the last user input.
                tags.pop();
                // Append the new tag and an empty element to get one more separator at the end.
                tags.push( ui.item.name, '' );

                $element.val( tags.join( separator + ' ' ) );

                if ( $.ui.keyCode.TAB === event.keyCode ) {
                    // Audible confirmation message when a tag has been selected.
                    window.wp.a11y.speak( window.tagsSuggestL10n.termSelected, 'assertive' );
                    event.preventDefault();
                } else if ( $.ui.keyCode.ENTER === event.keyCode ) {
                    // Do not close Quick Edit / Bulk Edit
                    event.preventDefault();
                    event.stopPropagation();
                }

                return false;
            },
            open: function() {
                $element.attr( 'aria-expanded', 'true' );
            },
            close: function() {
                $element.attr( 'aria-expanded', 'false' );
            },
            minLength: 2,
            position: {
                my: 'left top+2',
                at: 'left bottom',
                collision: 'none'
            },
            messages: {
                noResults: window.uiAutocompleteL10n.noResults,
                results: function( number ) {
                    if ( number > 1 ) {
                        return window.uiAutocompleteL10n.manyResults.replace( '%d', number );
                    }

                    return window.uiAutocompleteL10n.oneResult;
                }
            }
        }, options );

        $element.on( 'keydown', function() {
            $element.removeAttr( 'aria-activedescendant' );
        } )
            .autocomplete( options )
            .autocomplete( 'instance' )._renderItem = function( ul, item ) {
            return $( '<li role="option" id="wp-tags-autocomplete-' + item.id + '">' )
                .text( item.name )
                .appendTo( ul );
        };

        $element.attr( {
            'role': 'combobox',
            'aria-autocomplete': 'list',
            'aria-expanded': 'false',
            'aria-owns': $element.autocomplete( 'widget' ).attr( 'id' )
        } )
            .on( 'focus', function() {
                var inputValue = split( $element.val() ).pop();

                // Don't trigger a search if the field is empty.
                // Also, avoids screen readers announce `No search results`.
                if ( inputValue ) {
                    $element.autocomplete( 'search' );
                }
            } )
            // Returns a jQuery object containing the menu element.
            .autocomplete( 'widget' )
            .addClass( 'wp-tags-autocomplete' )
            .attr( 'role', 'listbox' )
            .removeAttr( 'tabindex' ) // Remove the `tabindex=0` attribute added by jQuery UI.

            // Looks like Safari and VoiceOver need an `aria-selected` attribute. See ticket #33301.
            // The `menufocus` and `menublur` events are the same events used to add and remove
            // the `ui-state-focus` CSS class on the menu items. See jQuery UI Menu Widget.
            .on( 'menufocus', function( event, ui ) {
                ui.item.attr( 'aria-selected', 'true' );
            })
            .on( 'menublur', function() {
                // The `menublur` event returns an object where the item is `null`
                // so we need to find the active item with other means.
                $( this ).find( '[aria-selected="true"]' ).removeAttr( 'aria-selected' );
            });

        return this;
    };

}( jQuery ) );
