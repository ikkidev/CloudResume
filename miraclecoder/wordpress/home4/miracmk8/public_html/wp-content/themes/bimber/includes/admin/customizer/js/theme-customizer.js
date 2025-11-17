/* global window */
/* global document */
/* global jQuery */
/* global wp */
/* global g1 */
/* global bimber_themecustomizer */
/* global bimber_headerbuilder */

// TODO modularize this into Font Control and Header Builder

/*************
 *
 * Live preview callbacks
 *
 *************/
(function ($) {
    'use strict';

    var config = bimber_themecustomizer;

    // CSS variables.
    var variablesSkinMode = {};
    var variablesRoot = {};

    var reload = function() {
        var styles = '';

        styles += ':root { ';
        for(var i in variablesRoot) {
            styles += variablesRoot[i];
        }
        styles += '}';

        styles += ':root.g1-skinmode { ';
        for(var i in variablesSkinMode) {
            styles += variablesSkinMode[i];
        }
        styles += '}';

        $('style#bimber-customizer-preview-css').remove();
        $('head').append('<style type="text/css" id="bimber-customizer-preview-css">' + styles + '</style>');
    };

    var applySkinModeVariable = function(id, variable) {
        variablesSkinMode[id] = variable;

        reload();
    };

    var applyRootVariable = function(id, variable) {
        variablesRoot[id] = variable;

        reload();
    };




    // Boxed / Stretched mode.
    wp.customize('bimber_theme[global_layout]', function (value) {
        value.bind( function( newval ) {
            if ( newval === 'boxed' ) {
                $('body').addClass('g1-layout-boxed');
            } else {
                $('body').removeClass('g1-layout-boxed');
            }
        } );
    });

    // Boxed / Stretched background color.
    wp.customize('bimber_theme[global_background_color]', function (value) {
        value.bind( function( newval ) {
            applyRootVariable('global_background_color', '--g1-layout-bg-color: ' + newval + ';');
        } );
    });

    // Boxed / Stretched skinmode background color.
    wp.customize('bimber_theme[global_skinmode_background_color]', function (value) {
        value.bind( function( newval ) {
            applySkinModeVariable('global_background_color', '--g1-layout-bg-color: ' + newval + ';');
        } );
    });


    // Breadcrumbs Ellipsis.
    wp.customize('bimber_theme[breadcrumbs_ellipsis]', function (value) {
        value.bind( function( newval ) {
            $('.g1-breadcrumbs').each(function(){
                var $this = $(this);

                $this.removeClass('g1-breadcrumbs-with-ellipsis');

                if ( true === newval ) {
                    $this.addClass('g1-breadcrumbs-with-ellipsis');
                }
            });
        } );
    });

    // Posts > Single > Sidebar Location.
    wp.customize('bimber_theme[post_sidebar_location]', function (value) {
        value.bind( function( newval ) {
            $('body.single-post').each(function(){
                var $this = $(this);
                $this.removeClass('g1-sidebar-normal g1-sidebar-invert');
                $this.addClass( 'left' === newval ? 'g1-sidebar-invert' : 'g1-sidebar-normal' );
            });
        } );
    });


    var skinModeElements = {
        // Row A.
        'header_builder_a_skinmode_text_color':         '--g1-hba-itxt-color:%NEW_VALUE%;',
        'header_builder_a_skinmode_accent_color':       '--g1-hba-atxt-color:%NEW_VALUE%;',
        'header_builder_a_skinmode_background_color':   '--g1-hba-bg-color:%NEW_VALUE%;',
        'header_builder_a_skinmode_border_color':       '--g1-hba-border-color:%NEW_VALUE%;',

        // Row B.
        'header_builder_b_skinmode_text_color':         '--g1-hbb-itxt-color:%NEW_VALUE%;',
        'header_builder_b_skinmode_accent_color':       '--g1-hbb-atxt-color:%NEW_VALUE%;',
        'header_builder_b_skinmode_background_color':   '--g1-hbb-bg-color:%NEW_VALUE%;',
        'header_builder_b_skinmode_border_color':       '--g1-hbb-border-color:%NEW_VALUE%;',

        // Row C.
        'header_builder_c_skinmode_text_color':         '--g1-hbc-itxt-color:%NEW_VALUE%;',
        'header_builder_c_skinmode_accent_color':       '--g1-hbc-atxt-color:%NEW_VALUE%;',
        'header_builder_c_skinmode_background_color':   '--g1-hbc-bg-color:%NEW_VALUE%;',
        'header_builder_c_skinmode_border_color':       '--g1-hbc-border-color:%NEW_VALUE%;',

        // Submenus.
        'header_submenu_skinmode_text_color':         '--g1-submenu-rtxt-color:%NEW_VALUE%;',
        'header_submenu_skinmode_accent_color':       '--g1-submenu-atxt-color:%NEW_VALUE%;',
        'header_submenu_skinmode_background_color':   '--g1-submenu-bg-color:%NEW_VALUE%;',

        // Off-canvas.
        'header_builder_canvas_skinmode_text_color':        '--g1-canvas-itxt-color:%NEW_VALUE%;',
        'header_builder_canvas_skinmode_accent_color':      '--g1-canvas-atxt-color:%NEW_VALUE%;',
        'header_builder_canvas_skinmode_background_color':  '--g1-canvas-bg-color:%NEW_VALUE%;',

        // Footer.
        'footer_skinmode_itxt_color':       '--g1-footer-itxt-color:%NEW_VALUE%;',
        'footer_skinmode_rtxt_color':       '--g1-footer-rtxt-color:%NEW_VALUE%;',
        'footer_skinmode_mtxt_color':       '--g1-footer-mtxt-color:%NEW_VALUE%;',
        'footer_skinmode_atxt_color':       '--g1-footer-atxt-color:%NEW_VALUE%;',
        'footer_skinmode_bg_color':         '--g1-footer-bg-color:%NEW_VALUE%;'
    };

    $.each(skinModeElements, function (setting, selector) {
        wp.customize('bimber_theme[' + setting + ']', function (value) {
            value.bind(function (newval) {
                applySkinModeVariable(setting, selector.replace('%NEW_VALUE%', newval));
            });
        });
    });

    // End of new code.




    var getStyle = function (option, mediaQuery, disableAttributes) {
        option = $.parseJSON(option);
        var style = option.selector + '{';
        //handle generic attributes.
        $.each(option, function (index, value) {
            if (index in config.attributes && 'template' in config.attributes[index] && config.attributes[index]['media-query'] === mediaQuery && value !== '') {
                var template = config.attributes[index].template;
                template = template.replace('%val%', value);
                style += template;
            }
        });
        //handle font-family and add if needed.
        if ('font-family' in option && typeof option['font-family'] === 'string'){
            var font        = option['font-family'];
            var fontConfig  = config.fonts[font];
            var link        = '<link rel="stylesheet" id="bimber-google-fonts-customized-css" href="' + fontConfig.css_link + '" type="text/css" media="all">';
            var $container  = $('#bending-cat-google-fonts-live');
            style += 'font-family:' + fontConfig.css_value + ';';
            if ($container.html().indexOf(link) < 0){
                $container.append(link);
            }
        }
        //handle font-style.
        if ('font-style' in option && typeof option['font-style'] === 'string'){
            var fontStyle = option['font-style'];
            fontStyle = fontStyle.replace('regular', '400');
            if(fontStyle.indexOf('italic') > -1){
                fontStyle = fontStyle.replace('italic','');
                style += 'font-style:italic;';
            }
            if(fontStyle.length > 0){
                style += 'font-weight:' + fontStyle + ';';
            }
        }
        style += '}';

        if (mediaQuery === 'desktop') {
            style = '@media only screen and (min-width: 1025px){' + style + '}';
        }
        if (mediaQuery === 'tablet') {
            style = '@media only screen and (min-width: 768px) and (max-width: 1023px){' + style + '}';
        }
        if (mediaQuery === 'mobile') {
            style = '@media only screen and (max-width: 767px){' + style + '}';
        }
        return style;
    };

    var getButtonsFontSizes = function(option) {
        option = $.parseJSON(option);
        var style = '';
        if ('font-size' in option && option['font-size'] > 0) {
            style += getCalculatedButtonsFontSizes(option['font-size']);
        }
        if ('font-size-tablet' in option && option['font-size-tablet'] > 0) {
            style += '@media only screen and (min-width: 768px) and (max-width: 1023px){' + getCalculatedButtonsFontSizes(option['font-size-tablet']) + '}';
        }
        if ('font-size-mobile' in option && option['font-size-mobile'] > 0) {
            style += '@media only screen and (max-width: 767px){' + getCalculatedButtonsFontSizes(option['font-size-mobile']) + '}';
        }
        return style;
    };

    var getCalculatedButtonsFontSizes = function(sizeM) {
        sizeM = parseInt(sizeM, 10);
        var sizeXS = sizeM - 4;
        var sizeS = sizeM - 2;
        var sizeL = sizeM + 2;
        var sizeXL = sizeM + 4;
        var output = '.g1-button-xs{\
                font-size:' + sizeXS + 'px;\
            }\
            .g1-button-s{\
                font-size:' + sizeS + 'px;\
            }\
            .g1-button-m{\
                font-size:' + sizeM + 'px;\
            }\
            .g1-button-l{\
                font-size:' + sizeL + 'px;\
            }\
            .g1-button-xl{\
                font-size:' + sizeXL + 'px;\
        }';
        return output;
    };

/*
    wp.customize('bimber_theme[page_width]', function (value) {
        value.bind(function (newval) {
            var template = '<style type="text/css" media="screen">@media only screen and (min-width: 801px){.g1-row-inner{max-width:' + newval + 'px}}</style>';
            $('#g1-bending-cat-page-width').html(template);
        });
    });
*/
    $.each(config.selectors, function (index, setting) {
        wp.customize('bimber_theme[' + setting + ']', function (value) {
            value.bind(function (newval) {
                var disableAttributes = [];
                if (setting === 'typo_button'){
                    disableAttributes.push('font-size');
                    disableAttributes.push('font-size-tablet');
                    disableAttributes.push('font-size-mobile');
                }
                if ($('#g1-bending-cat-' + setting).length < 1) {
                    $('#bending-cat-customizer').append('<div id="g1-bending-cat-' + setting + '"></div>');
                }
                var template = '<style type="text/css" media="screen">' + getStyle(newval, 'all',disableAttributes) + getStyle(newval, 'desktop',disableAttributes) + getStyle(newval, 'tablet',disableAttributes) + getStyle(newval, 'mobile',disableAttributes);
                if (setting === 'typo_button'){
                    template  += getButtonsFontSizes(newval);
                }
                template  += '</style>';
                $('#g1-bending-cat-' + setting).html(template);
            });
        });
    });

    var generateHeaderPreview = function(values, headerName) {
        var output = '';
        var layout = values[headerName];
        var stickyStarted = false;
        var stickyClosed = false;
        $.each(layout, function(rowIndex,row) {
            if('on' === row.sticky && ! stickyStarted){
                stickyStarted = true;
                output+= '<div class="g1-sticky-top-wrapper g1-hb-row-' + rowIndex + '">';
            }
            if('on' !== row.sticky && stickyStarted && ! stickyClosed){
                stickyClosed = true;
                output+= '</div>';
            }
            var rowLetter = row.letter;
            var rowClass = 'g1-hb-row g1-hb-row-' + headerName + ' g1-hb-row-' + rowLetter + ' g1-hb-' + row.style +  ' g1-hb-row-' + rowIndex;
            if ('on' === row.sticky){
                rowClass+= ' g1-hb-sticky-on';
            } else{
                rowClass+= ' g1-hb-sticky-off';
            }
            if ('on' === row.shadow){
                rowClass+= ' g1-hb-shadow-on';
            } else{
                rowClass+= ' g1-hb-shadow-off';
            }
            output+= '<div class="g1-row g1-row-layout-page '+ rowClass + '"><div class="g1-row-inner"><div class="g1-column g1-dropable">';

            $.each(row.cols, function(colIndex, col){
                var colClass = 'g1-bin-' + colIndex;
                var alignClass = 'g1-bin ' + 'g1-bin-align-' + col.align;
                if ('on' === col.grow){
                    colClass+= ' g1-bin-grow-on';
                } else{
                    colClass+= ' g1-bin-grow-off';
                }
                output+= '<div class="' + colClass + '">';
                output+= '<div class="' + alignClass + '">';
                col.elements.forEach(function(element) {
                    var elementHTML = $('#g1-hb-preview-elements .g1-hb-preview-element-' + element)[0].innerHTML;
                    output+= elementHTML;
                });
                output+= '</div>';
                output+= '</div>';
            });

            output+= '</div></div><div class="g1-row-background"></div></div>';
        });
        if (stickyStarted && ! stickyClosed){
            stickyClosed = true;
            output+= '</div>';
        }
        $('#g1-hb-preview-elements').after(output);
    };

    var generateCanvasPreview = function(values) {
        $('.g1-canvas-content').html('');
        var layout = values['canvas'];
        var output = '<a class="g1-canvas-toggle" href="#"></a>';
        $.each(layout[1]['cols'][1]['elements'], function(elementIndex, element) {
            var elementHTML = $('#g1-hb-preview-elements-canvas .g1-hb-preview-canvas-element-' + element)[0].innerHTML;
            output+= elementHTML;
        });
        $('.g1-canvas-content').html(output);
        g1.canvas();
    };

    wp.customize('bimber_theme[header_builder]', function (value) {
        value.bind(function (newval) {
            if (typeof newval === 'undefined' || newval === 'workaround'){
                return;
            }
            $('.g1-hb-row').remove();
            $('.g1-sticky-top-wrapper').remove();
            generateHeaderPreview(newval, 'normal');
            generateHeaderPreview(newval, 'mobile');
            generateCanvasPreview(newval);
        });
    });

    var setupRowRefresh = function(rowLetter) {
        var rowSettings = [
            'header_builder_' + rowLetter +'_text_color',
            'header_builder_' + rowLetter +'_accent_color',
            'header_builder_' + rowLetter +'_background_color',
            'header_builder_' + rowLetter +'_gradient_color',
            'header_builder_' + rowLetter +'_border_color',
            'header_builder_' + rowLetter +'_button_background',
            'header_builder_' + rowLetter +'_button_text',
            'header_builder_' + rowLetter +'_skinmode_background_color',
            'header_builder_' + rowLetter +'_skinmode_gradient_color',

        ];
        $.each(rowSettings, function (index, setting) {
            wp.customize('bimber_theme[' + setting + ']', function (value) {
                value.bind(function (newval) {
                    refreshRowCSS(rowLetter);
                });
            });
        });
    }
    setupRowRefresh('a');
    setupRowRefresh('b');
    setupRowRefresh('c');

    var refreshRowCSS = function(rowLetter) {
        var values    = wp.customize.get();

        var rootVars = {
            'itxt-color':           'text_color',
            'atxt-color':           'accent_color',
            'bg-color':             'background_color',
            'gradient-color':       'gradient_color',
            'border-color':         'border_color',
            '2-itxt-color':         'button_text',
            '2-bg-color':           'button_background'
        };

        for(var key in rootVars) {
            rootVars[key] = values['bimber_theme[header_builder_' + rowLetter + '_' + rootVars[key] + ']'];
        }
        // Fallback for the gradient color.
        if ( 0 == rootVars['gradient-color'].length ) {
            rootVars['gradient-color'] = rootVars['bg-color'];
        }
        rootVars['2-border-color'] = rootVars['2-bg-color'];
        console.log(rootVars);

        var rootStyles = '';
        for(var key in rootVars) {
            rootStyles += '--g1-hb' + rowLetter + '-' + key + ':' + rootVars[key] + ';';
        }
        rootStyles = ':root {' + rootStyles + '}';



        var skinmodeVars = {
            'itxt-color':           'skinmode_text_color',
            'atxt-color':           'skinmode_accent_color',
            'bg-color':             'skinmode_background_color',
            'gradient-color':       'skinmode_gradient_color',
            'border-color':         'skinmode_border_color',
        };

        for(var key in skinmodeVars) {
            skinmodeVars[key] = values['bimber_theme[header_builder_' + rowLetter + '_' + skinmodeVars[key] + ']'];
        }
        // Fallback for the gradient color.
        if ( 0 == skinmodeVars['gradient-color'].length ) {
            skinmodeVars['gradient-color'] = skinmodeVars['bg-color'];
        }

        var skinmodeStyles = '';
        for(var key in skinmodeVars) {
            skinmodeStyles += '--g1-hb' + rowLetter + '-' + key + ':' + skinmodeVars[key] + ';';
        }
        skinmodeStyles = '.g1-skinmode {' + skinmodeStyles + '}';

        $('#g1-csspreview-hb' + rowLetter).remove();
        $('head').append($('<style id="g1-csspreview-hb' + rowLetter + '">' + rootStyles + skinmodeStyles + '</style>'));
    };

    var submenuSettings = [
        'header_submenu_background_color',
        'header_submenu_text_color',
        'header_submenu_accent_color',
    ];
    $.each(submenuSettings, function (index, setting) {
        wp.customize('bimber_theme[' + setting + ']', function (value) {
            value.bind(function (newval) {
                refreshSubmenuCSS();
            });
        });
    });

    var refreshSubmenuCSS = function() {
        var values    = wp.customize.get();

        var rootVars = {
            'rtxt-color':           'text_color',
            'atxt-color':           'accent_color',
            'bg-color':             'background_color',
        };

        for(var key in rootVars) {
            rootVars[key] = values['bimber_theme[header_submenu_' + rootVars[key] + ']'];
        }

        var rootStyles = '';
        for(var key in rootVars) {
            rootStyles += '--g1-submenu-' + key + ':' + rootVars[key] + ';';
        }
        rootStyles = ':root {' + rootStyles + '}';

        var skinmodeVars = {
            'itxt-color':           'skinmode_text_color',
            'atxt-color':           'skinmode_accent_color',
            'bg-color':             'skinmode_background_color',
        };

        for(var key in skinmodeVars) {
            skinmodeVars[key] = values['bimber_theme[header_submenu_' + skinmodeVars[key] + ']'];
        }

        var skinmodeStyles = '';
        for(var key in skinmodeVars) {
            skinmodeStyles += '--g1-submenu-' + key + ':' + skinmodeVars[key] + ';';
        }
        skinmodeStyles = '.g1-skinmode {' + skinmodeStyles + '}';

        $('#g1-csspreview-submenu').remove();
        $('head').append($('<style id="g1-csspreview-submenu">' + rootStyles + skinmodeStyles + '</style>'));
    };

    var marginSettings = [
        'header_mobile_logo_margin_top',
        'header_mobile_logo_margin_bottom',
        'header_logo_margin_top',
        'header_logo_margin_bottom',
        'header_quicknav_margin_top',
        'header_quicknav_margin_bottom',
        'header_primary_nav_margin_top',
        'header_primary_nav_margin_bottom',
    ];
    $.each(marginSettings, function (index, setting) {
        wp.customize('bimber_theme[' + setting + ']', function (value) {
            value.bind(function (newval) {
                refreshMarginCSS();
            });
        });
    });

    var refreshMarginCSS = function() {
        var customizerValues = wp.customize.get();

        var logoTop     		= customizerValues['bimber_theme[header_logo_margin_top]'];
        var logoBottom 	   		= customizerValues['bimber_theme[header_logo_margin_bottom]'];
        var mobileLogoTop     	= customizerValues['bimber_theme[header_mobile_logo_margin_top]'];
        var mobileLogoBottom 	= customizerValues['bimber_theme[header_mobile_logo_margin_bottom]'];
        var quicknavTop       	= customizerValues['bimber_theme[header_quicknav_margin_top]'];
        var quicknavBottom     	= customizerValues['bimber_theme[header_quicknav_margin_bottom]'];
        var primarynavTop       = customizerValues['bimber_theme[header_primary_nav_margin_top]'];
        var primarynavBottom    = customizerValues['bimber_theme[header_primary_nav_margin_bottom]'];

        var newCSS = '';

        if (logoTop === 0) {
            newCSS += ' .g1-hb-row-normal .g1-id { margin-top: 0; }';
       }

       if (logoBottom === 0) {
            newCSS += ' .g1-hb-row-normal  .g1-id { margin-bottom: 0; }';
        }

        if (mobileLogoTop === 0) {
            newCSS += ' .g1-hb-row-mobile .g1-id { margin-top: 0; }';
       }

       if (mobileLogoBottom === 0) {
            newCSS += '.g1-hb-row-mobile .g1-id { margin-bottom: 0; }';
        }

        newCSS += ' .g1-hb-row-mobile  .g1-id {\
            margin-top: '+ mobileLogoTop +'px;\
            margin-bottom: '+ mobileLogoBottom +'px;\
        }';

        newCSS += ' .g1-hb-row-normal  .g1-primary-nav {\
            margin-top: '+ primarynavTop +'px;\
            margin-bottom: '+ primarynavBottom +'px;\
        }';

        newCSS += ' @media only screen and ( min-width: 801px ) {\
                .g1-hb-row-normal  .g1-id {\
                    margin-top: '+ logoTop +'px;\
                    margin-bottom: '+ logoBottom +'px;\
                }\
                .g1-hb-row  .g1-quick-nav {\
                    margin-top: '+ quicknavTop +'px;\
                    margin-bottom: '+ quicknavBottom +'px;\
                }\
            }'

        $('head #bimber-customize-margins-css').remove();
        $('head').append('<style type="text/css" id="bimber-customize-margins-css">' + newCSS+ '</style>');
    };


    var elementsSettings = {
        'header_builder_element_label_mobile_menu' : '.g1-hamburger-label',
        'header_builder_element_size_create_button' : '.snax-button-create',
        'snax_header_create_button_label' : '.snax-button-create',
        'header_builder_element_size_search' : '.g1-hb-row .g1-hb-search-form',
        'header_builder_element_size_search_dropdown' : '.g1-drop-the-search',
        'header_builder_element_size_mobile_menu' : '.g1-hamburger',
        'header_builder_element_size_social_icons_full' : '.g1-socials-hb-list, .g1-socials-items-tpl-grid',
        'header_builder_element_size_social_icons_dropdown'  : '.g1-drop-the-socials',
        'header_builder_element_size_user_menu' : '.g1-drop-the-user',
        'header_builder_element_size_cart' : '.g1-drop-the-cart',
        'header_builder_element_size_newsletter' : '.g1-drop-the-newsletter',
        'header_builder_element_size_skin_dropdown' : '.g1-drop-the-skin',
        'header_builder_element_size_nsfw_dropdown' : '.g1-drop-the-nsfw'
    };
    $.each(elementsSettings, function (index, selector) {
        wp.customize('bimber_theme[' + index + ']', function (value) {
            value.bind(function (newval) {
                switch (index) {
                    case 'snax_header_create_button_label':
                        $(selector).text(newval);
                        break;

                    default:
                        $(selector).removeClass('g1-hamburger-label-hidden g1-hamburger-s g1-hamburger-m g1-socials-s g1-form-s g1-drop-l g1-drop-s g1-drop-m g1-button-m g1-button-s');
                        if (newval !== 'standard') {
                            $(selector).addClass(newval);
                        }
                }
            });
        });
    });

    elementsSettings = {
        'header_builder_element_type_search_dropdown' : '.g1-drop-the-search',
        'header_builder_element_type_social_icons_dropdown'  : '.g1-drop-the-socials',
        'header_builder_element_type_user_menu' : '.g1-drop-the-user',
        'header_builder_element_type_cart' : '.g1-drop-the-cart',
        'header_builder_element_type_newsletter' : '.g1-drop-the-newsletter',
        'header_builder_element_type_skin_dropdown' : '.g1-drop-the-skin'
    };
    $.each(elementsSettings, function (index, selector) {
        wp.customize('bimber_theme[' + index + ']', function (value) {
            value.bind(function (newval) {
                $(selector).removeClass('g1-drop-icon g1-drop-text');
                if (newval !== 'standard') {
                    $(selector).addClass(newval);
                }
            });
        });
    });


    var canvasSettings = [
    'header_builder_canvas_text_color',
	'header_builder_canvas_accent_color',
	'header_builder_canvas_background_color',
	'header_builder_canvas_gradient_color',
	'header_builder_canvas_background_image',
	'header_builder_canvas_background_repeat',
	'header_builder_canvas_background_size',
    'header_builder_canvas_background_opacity',
    'header_builder_canvas_button_background',
    'header_builder_canvas_button_text',
    'header_builder_canvas_background_position',
    ];
    $.each(canvasSettings, function (index, setting) {
        wp.customize('bimber_theme[' + setting + ']', function (value) {
            value.bind(function (newval) {
                refreshCanvasCSS();
            });
        });
    });
    var refreshCanvasCSS = function() {
        var values    = wp.customize.get();

        var rootVars = {
            'itxt-color':           'text_color',
            'atxt-color':           'accent_color',
            'bg-color':             'background_color',
            'bg-image':             'background_image',
            'bg-repeat':            'background_repeat',
            'bg-size':              'background_size',
            'bg-position':          'background_position',
            'bg-opacity':           'background_opacity',
            'gradient-color':       'gradient_color',
            '2-itxt-color':         'button_text',
            '2-bg-color':           'button_background'
        };

        for(var key in rootVars) {
            rootVars[key] = values['bimber_theme[header_builder_canvas_' + rootVars[key] + ']'];
        }
        // Normalize background-image.
        rootVars['bg-image'] = 'url(' + rootVars['bg-image'] + ')';
        // Remove lazyloaded image.
        $('.g1-canvas-background').removeAttr('style');

        // Fallback for the gradient color.
        if ( 0 == rootVars['gradient-color'].length ) {
            rootVars['gradient-color'] = rootVars['bg-color'];
        }
        // Normalize opacity.
        rootVars['bg-opacity'] = 0.01 * rootVars['bg-opacity'];



        var rootStyles = '';
        for(var key in rootVars) {
            rootStyles += '--g1-canvas-' + key + ':' + rootVars[key] + ';';
        }
        rootStyles = ':root {' + rootStyles + '}';

        var skinmodeVars = {
            'itxt-color':           'skinmode_text_color',
            'atxt-color':           'skinmode_accent_color',
            'bg-color':             'skinmode_background_color',
        };

        for(var key in skinmodeVars) {
            skinmodeVars[key] = values['bimber_theme[header_builder_canvas_' + skinmodeVars[key] + ']'];
        }

        var skinmodeStyles = '';
        for(var key in skinmodeVars) {
            skinmodeStyles += '--g1-canvas-' + key + ':' + skinmodeVars[key] + ';';
        }
        skinmodeStyles = '.g1-skinmode {' + skinmodeStyles + '}';

        $('#g1-csspreview-canvas').remove();
        $('head').append($('<style id="g1-csspreview-canvas">' + rootStyles + skinmodeStyles + '</style>'));
    };


    var footerSettings = [
	'footer_cs_1_background_color',
	'footer_cs_1_gradient_color',
	'footer_cs_1_background_image',
	'footer_cs_1_background_repeat',
	'footer_cs_1_background_size',
    'footer_cs_1_background_opacity',
    'footer_cs_1_background_position'
    ];
    $.each(footerSettings, function (index, setting) {
        wp.customize('bimber_theme[' + setting + ']', function (value) {
            value.bind(function (newval) {
                refreshFooterCSS();
            });
        });
    });
    var refreshFooterCSS = function() {
        var customizerValues = wp.customize.get();
        var bgColor = customizerValues['bimber_theme[footer_cs_1_background_color]'];
        var gradientColor = customizerValues['bimber_theme[footer_cs_1_gradient_color]'];
        var bgImage = customizerValues['bimber_theme[footer_cs_1_background_image]'];
        var bgRepeat = customizerValues['bimber_theme[footer_cs_1_background_repeat]'];
        var bgSize = customizerValues['bimber_theme[footer_cs_1_background_size]'];
        var bgOpacity = customizerValues['bimber_theme[footer_cs_1_background_opacity]'];
        var bgPosition = customizerValues['bimber_theme[footer_cs_1_background_position]'];

        var newCSS = '/*customizer_preview_footer*/';

        // Prefooter.

        newCSS +=  '.g1-prefooter > .g1-row-background,\
                    .g1-prefooter .g1-current-background {\
	                 background-color: ' + bgColor + ';';
        newCSS += '}';

        if (gradientColor) {
            newCSS += '.g1-prefooter .g1-row-background {\
                            background-image: -webkit-linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                            background-image:    -moz-linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                            background-image:      -o-linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                            background-image:         linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                        }';
        } else {
            newCSS += '.g1-prefooter .g1-row-background { background-image: none; }';
        }


        newCSS += '.g1-prefooter > .g1-row-background > .g1-row-background-media {';

        if (bgImage) {
            newCSS += 'background-image: 	url(' + bgImage + ');\
                            background-size: 	' + bgSize + ';\
                            background-repeat: 	' + bgRepeat + ';\
                            background-position: ' + bgPosition + ';\
                            opacity: ' + bgOpacity * 0.01 + ';';



        } else {
            newCSS += 'background-image: none;';
        }

        newCSS += '}';

        // Footer.

        newCSS +=  '.g1-footer .g1-row-background {\
            background-color: ' + bgColor + ';';
        if (gradientColor) {
            newCSS += ' background-image: -webkit-linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                            background-image:    -moz-linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                            background-image:      -o-linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');\
                            background-image:         linear-gradient(to right, ' + bgColor + ', ' + gradientColor + ');';
        } else {
            newCSS += ' background-image: none;';
        }

        newCSS += '}';

        newCSS += '/*customizer_preview_footer_end*/';

        var $style = $('#g1-dynamic-styles');
        var regEx = new RegExp('\/\\*customizer_preview_footer.*customizer_preview_footer_end\\*\/', 's');
        $style.html($style.html().replace(regEx,newCSS));
    };

    $(document).ready(function() {
        wp.customize.preview.bind( 'bimber-try-opening-canvas', function() {
            g1.canvasInstance.open();
        } );
        wp.customize.preview.bind( 'bimber-try-closing-canvas', function() {
            g1.canvasInstance.close();
        } );
    });
})(jQuery);
