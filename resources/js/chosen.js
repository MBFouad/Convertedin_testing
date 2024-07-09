/* global $ */
import _forEach from 'lodash/forEach';
import _indexOf from 'lodash/indexOf';
import "../vendor/chosen/chosen.jquery.js";

window.Chosen = {

    defaultValue: 0,

    /**
     * @param {Object} event
     * @param {Object} params
     */
    change(event, params) {

        let target = $(event.target);
        let options = target.find('option');

        // selected some value
        if (params.hasOwnProperty('selected')) {

            let selectedValue = params.selected;

            switch (parseInt(selectedValue)) {

                // The Default value selected
                case this.defaultValue:
                    // redefine options
                    // only default value is selected
                    target.find('option').remove();
                    _forEach(options, (item, key) => {
                        let isSelected = parseInt(item.value) === this.defaultValue;
                        target.append(new Option(item.text, item.value, false, isSelected));
                    });
                    target.trigger('chosen:updated');
                    break;

                // not default value selected
                default:
                    // get selected values
                    let selectedValues = [];
                    let selectedOptions = target.find('option:selected');
                    _forEach(selectedOptions, (item, key) => {
                        if (parseInt(item.value) !== this.defaultValue) {
                            selectedValues.push(item.value);
                        }
                    });

                    // redefine options
                    // default value is not selected
                    target.find('option').remove();
                    _forEach(options, (item, key) => {
                        let isSelected = _indexOf(selectedValues, item.value) !== -1;
                        target.append(new Option(item.text, item.value, false, isSelected));
                    });
                    target.trigger('chosen:updated');
                    break;
            }
        }

    },

    /**
     * @param {String} selector
     * @param {Object} options
     * @param {Number|undefined} defaultValue
     */
    init(selector, options, defaultValue = 0) {
        this.defaultValue = defaultValue ? defaultValue : 0;

        $(selector)
            .chosen(options ? options : {})
            .change((event, params) => {
                this.change(event, params);
            });
    },
};
