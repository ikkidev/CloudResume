/* global window */
/* global document */
/* global jQuery */
/* global wp */
/* global JSON */
/* global bimber_customizer_builder */

(function(api, $) {

    'use strict';

    var config = bimber_customizer_builder;
    var values = config.values;
    var ajax_url = config.ajax_url;

    var updateOption = function(setting, value) {
        var setting_obj = api.instance(setting);
        if(setting_obj) {
            // we need to set the value to something temporary, because customize API can't properly take two arrays in a row.
            // wp-includes\js\customize-base.js:217 isEqual function is to blame most likely.
            setting_obj.set('workaround');
            setting_obj.set(value);
        }
    };

    var updatePresetInput = function() {
        var preset = JSON.stringify(values);
        var $container = $('.g1-hb-preset');
        $container.val(preset);
    };

    var focusPanel = function(panel_slug){
        var panel = api.panel(panel_slug);
        if(panel) {
            panel.focus();
        }
    };

    var focusSection = function(section_slug){
        var section = api.section(section_slug);
        if(section) {
            section.focus();
        }
    };

    var focusControl = function(control_slug){
        var control = api.control(control_slug);
        if(control) {
            control.focus();
        }
    };

    var blinkControl = function(control_selector){
        $(control_selector).addClass('control-flash');
        setTimeout(function(){
            $(control_selector).removeClass('control-flash');
        },
        2000);
    };

    var updateValues = function() {
        $('.g1-hb-tabs-content-tab').each(function() {
            var tabSlug = $(this).attr('data-bimber-tab');
            $('.g1-hb-layout-row-wrapper', $(this)).each(function(indexRow) {
                var rowStyle = $(this).attr('data-bimber-style');
                var rowSticky = $(this).attr('data-bimber-sticky') === 'on' ? 'on' : 'off';
                var rowShadow = $(this).attr('data-bimber-shadow') === 'on' ? 'on' : 'off';
                var rowLetter = $(this).attr('data-bimber-letter');
                values[tabSlug][indexRow+1].style = rowStyle;
                values[tabSlug][indexRow+1].sticky = rowSticky;
                values[tabSlug][indexRow+1].shadow = rowShadow;
                values[tabSlug][indexRow+1].letter = rowLetter;
                $('.g1-hb-layout-col', $(this)).each(function(indexCol) {
                    var colAlign = $(this).attr('data-bimber-col-align');
                    var colGrow = $(this).attr('data-bimber-col-grow') === 'on' ? 'on' : 'off';
                    values[tabSlug][indexRow+1].cols[indexCol+1].align = colAlign;
                    values[tabSlug][indexRow+1].cols[indexCol+1].grow = colGrow;
                    values[tabSlug][indexRow+1].cols[indexCol+1].elements= [];
                    $('.g1-hb-layout-row-content .g1-hb-element', $(this)).each(function() {
                        values[tabSlug][indexRow+1].cols[indexCol+1].elements.push( $(this).attr('data-bimber-element') );
                    });
                });
            });
        });
        updatePresetInput();
        updateOption('bimber_theme[header_builder]', values);
    };

    var bindElements = function() {
        $( '.g1-hb-elements, .g1-hb-layout-row-content' ).sortable( {
            items: '.g1-hb-element',
            connectWith: '.g1-hb-element-container',
            placeholder: 'g1-hb-highlight',
            forcePlaceholderSize: true,
            update: function(){
                updateValues();
            }
        } ).disableSelection();
        $( '.g1-hb-layout' ).sortable( {
            items: '.g1-hb-layout-row-wrapper',
            placeholder: 'g1-hb-highlight-row',
            forcePlaceholderSize: true,
            handle: '.g1-hb-layout-row-handle',
            update: function(){
                updateValues();
            }
        } ).disableSelection();
        $( '.g1-hb-element').on('click', function(){
            if ($(this).attr('data-bimber-control')){
                focusControl($(this).attr('data-bimber-control'));
            }
            if ($(this).attr('data-bimber-section')){
                focusSection($(this).attr('data-bimber-section'));
            }
            if ($(this).attr('data-bimber-panel')){
                focusPanel($(this).attr('data-bimber-panel'));
            }
            if ($(this).attr('data-bimber-highlight')){
                blinkControl($(this).attr('data-bimber-highlight'));
            }
        });
    };

    var bindSettings = function() {
        var closeButton = false;
        $('.g1-hb-layout-button-settings').on('click', function(e) {
            if (closeButton) {
                closeButton = false;
                return;
            }
            if (e.target.localName !== 'label' && e.target.localName !== 'input'){
                e.preventDefault();
            }
            e.stopPropagation();
            $('.g1-hb-layout-settings-box').removeClass('g1-hb-layout-settings-box-visible');
            $('.g1-hb-layout-settings-box', $(this)).addClass('g1-hb-layout-settings-box-visible');
        });
        $('.g1-hb:not(.g1-hb-layout-button-settings)').on('click', function() {
            $('.g1-hb-layout-settings-box').removeClass('g1-hb-layout-settings-box-visible');
        });
        $('.g1-hb-settings-box-close-button').on('click', function() {
            $('.g1-hb-layout-settings-box').removeClass('g1-hb-layout-settings-box-visible');
            closeButton = true;
        });
        $('.g1-hb-layout-style-select').on('change', function() {
            $(this).closest('.g1-hb-layout-row-wrapper').attr('data-bimber-style',$(this).val());
        });
        $('.g1-hb-layout-row-sticky-select').on('change', function() {
            $(this).closest('.g1-hb-layout-row-wrapper').attr('data-bimber-sticky',$(this).val());
        });
        $('.g1-hb-layout-row-shadow-select').on('change', function() {
            $(this).closest('.g1-hb-layout-row-wrapper').attr('data-bimber-shadow',$(this).val());
        });
        $('.g1-hb-layout-col-grow').on('click', function() {
            var value = $('input[type=radio]:checked', this).val();
            $(this).closest('.g1-hb-layout-col').attr('data-bimber-col-grow',value);
            $(this).closest('.g1-hb-layout-col').removeClass('g1-hb-grow-off g1-hb-grow-on');
            $(this).closest('.g1-hb-layout-col').addClass('g1-hb-grow-' + value);
            $('.g1-hb-layout-settings-box').removeClass('g1-hb-layout-settings-box-visible');
        });
        $('.g1-hb-layout-col-align').on('click', function() {
            var value = $('input[type=radio]:checked', this).val();
            $(this).closest('.g1-hb-layout-col').attr('data-bimber-col-align',value);
            $(this).closest('.g1-hb-layout-col').removeClass('g1-hb-align-left g1-hb-align-center g1-hb-align-right');
            $(this).closest('.g1-hb-layout-col').addClass('g1-hb-align-' + value);
            $('.g1-hb-layout-settings-box').removeClass('g1-hb-layout-settings-box-visible');
        });
        $('.g1-hb select').trigger('change');
        $('.g1-hb-layout-col-grow, .g1-hb-layout-col-align').trigger('click');
        updateValues();
    };

    var bindTabs = function() {
        $('.g1-hb-tab-button').on('click', function() {
            var tabName = $(this).attr('data-bimber-tab');
            $('.g1-hb-top-bar .g1-hb-button').removeClass('g1-hb-tab-active');
            $('.g1-hb-tabs').removeClass('g1-hb-tabs-active');
            $('.g1-hb-tabs-switcher-' + tabName).addClass('g1-hb-tab-active');
            $('.g1-hb-tabs-' + tabName).addClass('g1-hb-tabs-active');
            if ('canvas' !== tabName) {
                $('.g1-hb').removeClass('g1-hb-vertical');
                api.previewer.send( 'bimber-try-closing-canvas', [] );
            }
        });
        $('.g1-hb-subtab-button').on('click', function() {
            var tabName = $(this).attr('data-bimber-tab');
            $('.g1-hb-tabs-active .g1-hb-tabs-switcher .g1-hb-button').removeClass('g1-hb-tab-active');
            $('.g1-hb-tabs-active .g1-hb-tabs-content .g1-hb-tabs-content-tab').removeClass('g1-hb-tabs-content-active');
            $('.g1-hb-tabs-active .g1-hb-tabs-switcher .g1-hb-tabs-switcher-' + tabName).addClass('g1-hb-tab-active');
            $('.g1-hb-tabs-active .g1-hb-tabs-content .g1-hb-tabs-content-' + tabName).addClass('g1-hb-tabs-content-active');
            if ('canvas' !== tabName) {
                $('.g1-hb').removeClass('g1-hb-vertical');
                api.previewer.send( 'bimber-try-closing-canvas', [] );
            }
        });
    };

    var bindUpdates =  function() {
        $('.g1-hb select').on('change',function(){
            updateValues();
        });
        $('.g1-hb-layout-col-grow, .g1-hb-layout-col-align').on('click',function(){
            updateValues();
        });
    };

    var bindCustomizer = function() {
        $('.g1-hb-open').on('click', function(){
            $('.g1-hb').show();
        });
        $('#accordion-section-bimber_header_layout_section').on('click', function(){
            $('.g1-hb').show();
        });
        $('.g1-hb-tabs-switcher-close').on('click', function(){
            $('.g1-hb').hide();
        });
        $('.g1-hb-tabs-switcher-mobile').on('click', function(){
            $('.preview-mobile').trigger('click');
        });
        $('.g1-hb-tabs-switcher-desktop').on('click', function(){
            $('.preview-desktop').trigger('click');
        });
        $('.g1-hb-tabs-switcher-canvas').on('click', function(){
            $('.g1-hb').addClass('g1-hb-vertical');
            api.previewer.send( 'bimber-try-opening-canvas', [] );
            focusSection('bimber_header_builder_colors_section_canvas');
        });
    };

    var applyPreset = function(composition){
        $('.g1-hb').css('opacity','0.5');
        $.ajax({
            'type': 'POST',
            'url': ajax_url,
            'dataType': 'json',
            'data': {
                'action':       'bimber_hb_load_preset',
                'composition':  composition
            }
        }).always(function (res) {
            if (res === null) {
                $('.g1-hb').css('opacity','1');
            }
            var html = String(res.html);
            $.each(res.settings,function(index, value) {
                updateOption('bimber_theme[' + index + ']', value);
            });
            $('.g1-hb').replaceWith($(html));
            bindElements();
            bindSettings();
            bindTabs();
            bindUpdates();
            bindCustomizer();
            updatePresetInput();
            $('.g1-hb').show();
        });
    };

    var bindPresets = function() {
        $('#customize-control-bimber_header_composition .g1ui-img-radio-item').on('click', function(){
            applyPreset($('input', this).val());
        });
    };

    $(document).ready(function() {
        bindElements();
        bindSettings();
        bindTabs();
        bindUpdates();
        bindCustomizer();

        bindPresets();

        updatePresetInput();
    });

})(wp.customize, jQuery);
