/* global $ */

import _map from 'lodash/map';
import _filter from 'lodash/filter';
import _uniq from 'lodash/uniq';
import _forEach from 'lodash/forEach';
import _clone from 'lodash/clone';

/**
 * @param {Array} data 0 => acManufacturer, 1 => acModel, 2 => busManufacturer, 3 => busModel
 * @return {{handle(String, String, String, String): void}}
 * @constructor
 */
window.BusDataFilter = function (data) {
    const DEFAULT_VALUE = '';

    let filters = {
        'acManufacturer': {
            el: null,
            current: DEFAULT_VALUE,
            processed: false,
            index: 0
        },
        'acModel': {
            el: null,
            current: DEFAULT_VALUE,
            processed: false,
            index: 1
        },
        'busManufacturer': {
            el: null,
            current: DEFAULT_VALUE,
            processed: false,
            index: 2
        },
        'busModel': {
            el: null,
            current: DEFAULT_VALUE,
            processed: false,
            index: 3
        }
    };

    let filterOrder = [];

    let filterData = function (data, name) {
        return _filter(data, function (item) {
            if (filters[name].current !== DEFAULT_VALUE) {
                return filters[name].current === item[filters[name].index];
            }

            return true;
        });
    };

    let filterOptions = function () {
        _forEach(filters, function (item) {
            item.processed = false;
        });

        let optionHandler = function (el, options, comparator) {
            el.find('option').remove();
            el.append(new Option('-- All --', DEFAULT_VALUE, false, false));
            _forEach(options, function (value, key) {
                el.append(new Option(value, value, false, comparator === value));
            });
        };

        let currentData = data;

        _forEach(filterOrder, function (name) {
            let filter = filters[name];
            optionHandler(filter.el, _uniq(_map(currentData, item => item[filter.index])), filter.current);
            filter.processed = true;

            currentData = filterData(_clone(currentData), name);
        });

        _forEach(filters, function (filter, key) {
            if (!filter.processed) {
                optionHandler(filter.el, _uniq(_map(currentData, item => item[filter.index])), filter.current);
            }
        })
    };

    let triggerSelection = function (name, value) {
        filters[name].current = value;

        let index = filterOrder.indexOf(name);

        if (filters[name].current === DEFAULT_VALUE && index !== -1) {
            filterOrder.splice(index, 1);
        }

        if (filters[name].current !== DEFAULT_VALUE && index === -1) {
            filterOrder.push(name);
        }

        filterOptions();
    };

    return {
        /**
         * @param {String} acManufacturerId
         * @param {String} acModelId
         * @param {String} busManufacturerId
         * @param {String} busModelId
         */
        init(acManufacturerId, acModelId, busManufacturerId, busModelId) {
            let acManufacturerElement = $('#' + acManufacturerId);
            let acModelElement = $('#' + acModelId);
            let busManufacturerElement = $('#' + busManufacturerId);
            let busModelElement = $('#' + busModelId);

            filters.acManufacturer.el = acManufacturerElement;
            filters.acModel.el = acModelElement;
            filters.busManufacturer.el = busManufacturerElement;
            filters.busModel.el = busModelElement;

            filterOptions();

            acManufacturerElement.on('change', function (event) {
                triggerSelection('acManufacturer', $(event.target).val());
            });

            acModelElement.on('change', function (event) {
                triggerSelection('acModel', $(event.target).val());
            });

            busManufacturerElement.on('change', function (event) {
                triggerSelection('busManufacturer', $(event.target).val());
            });
            busModelElement.on('change', function (event) {
                triggerSelection('busModel', $(event.target).val());
            });

            return this;
        },

        setDefaults(currentAcManufacturer, currentAcModel, currentBusManufacturer, currentBusModel) {
            if (currentAcManufacturer) {
                triggerSelection('acManufacturer', currentAcManufacturer);
            }
            if (currentAcModel) {
                triggerSelection('acModel', currentAcModel);
            }
            if (currentBusManufacturer) {
                triggerSelection('busManufacturer', currentBusManufacturer);
            }
            if (currentBusModel) {
                triggerSelection('busModel', currentBusModel);
            }

            return this;
        }
    };
};
