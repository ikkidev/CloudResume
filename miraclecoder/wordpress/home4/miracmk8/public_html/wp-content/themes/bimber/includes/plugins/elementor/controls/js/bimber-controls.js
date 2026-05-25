/**
 * Terms
 */
var bimberTermsView = elementor.modules.controls.BaseData.extend({
    onReady: function () {

        var that = this;

        this.ui.select = jQuery('<select multiple></select>');

        var valueArr = [];

        // Populate selected options.
        var valueStr = this.ui.input.val();

        if (valueStr.length > 0) {
            valueArr = valueStr.split(',');

            for (var i in valueArr) {
                this.ui.select.append('<option selected="selected" value="'+ valueArr[i] +'">'+ valueArr[i] +'</option>');
            }
        }

        this.ui.select.insertAfter(this.ui.input);

        this.ui.input.hide();

        this.ui.select.select2({
            multiple: true,
            ajax: {
                url: window.ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        action:     'bimber_search_terms',
                        taxonomy:   that.ui.input.attr('data-bimber-taxonomy'),
                        term:       params.term
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.items,
                        more: false
                    };
                },
                cache: true
            },
            minimumInputLength: 3
        });

        this.ui.select.on('change.select2', function () {
            that.saveValue();
        });
    },

    saveValue: function () {
        var data = this.ui.select.select2('data');
        var dataArr = [];

        for(var i in data) {
            dataArr.push(data[i].id);
        }

        var dataStr = dataArr.join(',');

        this.setValue(dataStr);
    },

    onBeforeDestroy: function () {
        this.saveValue();

        this.ui.select.select2('destroy');
    }
});

elementor.addControlView('bimber_terms', bimberTermsView);

/**
 * Radio
 */
var bimberRadioView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
        var that = this;

        this.$el.on('change', 'input[type=radio]', function () {
            that.saveValue();
        });
    },

    saveValue: function () {
        var val = this.$el.find('input:checked').val();

        this.setValue(val);
    },

    onBeforeDestroy: function () {
        this.saveValue();
    }
});

elementor.addControlView('bimber_radio', bimberRadioView);