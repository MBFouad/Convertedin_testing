import _map from 'lodash/map';
import _filter from 'lodash/filter';
import _uniq from 'lodash/uniq';
import _forEach from 'lodash/forEach';
import _clone from 'lodash/clone';
import _indexOf from 'lodash/indexOf';

/**
 * @param {Array} data 0 => distributions, 1 => deviceTypes
 * @param {Array} distributions {id: name}
 * @param {Array} deviceTypes {id: name}
 * @return {{handle(String, String): void}}
 * @constructor
 */
window.CustomersFilter = function (data, distributions, deviceTypes) {
    const DEFAULT_VALUE = 0;

    let filters = {
        "distributions": {
            el: null,
            current: [DEFAULT_VALUE],
            processed: false,
            index: 0,
            values: distributions
        },
        "deviceTypes": {
            el: null,
            current: [DEFAULT_VALUE],
            processed: false,
            index: 1,
            values: deviceTypes
        }
    };

    let filterOrder = [];

    let filterData = function (data, name) {
        let filter = filters[name];

        return _filter(data, function (item) {
            if (!_isIn(filter.current, DEFAULT_VALUE)) {
                return _isIn(filter.current, item[filter.index]);
            }

            return true;
        });
    };

    let filterOptions = function () {
        _forEach(filters, function (filter) {
            filter.processed = false;
        });

        let optionHandler = function (el, options, values, comparator) {
            el.find("option").remove();
            el.append(new Option('All', DEFAULT_VALUE, false, _isIn(comparator, DEFAULT_VALUE)));
            _forEach(options, function (value) {
                el.append(new Option(values[value], value, false, _isIn(comparator, value)));
            });
            el.trigger("chosen:updated");
        };

        let currentData = data;

        _forEach(filterOrder, function (name) {
            let filter = filters[name];
            optionHandler(filter.el, _uniq(_map(currentData, item => item[filter.index])), filter.values, filter.current);
            filter.processed = true;

            currentData = filterData(_clone(currentData), name);
        });

        _forEach(filters, function (filter) {
            if (!filter.processed) {
                optionHandler(filter.el, _uniq(_map(currentData, item => item[filter.index])), filter.values, filter.current);
            }
        });
    };

    let triggerSelection = function (name, value) {
        filters[name].current = value;

        let index = filterOrder.indexOf(name);

        if (_isIn(filters[name].current, DEFAULT_VALUE) && index !== -1) {
            filterOrder.splice(index, 1);
        }

        if (!_isIn(filters[name].current, DEFAULT_VALUE) && index === -1) {
            filterOrder.push(name);
        }

        filterOptions();
    };

    return {
        /**
         * @param {String} distributionsId
         * @param {String} deviceTypesId
         */
        init(distributionsId, deviceTypesId) {
            let distributionsElement = $("#" + distributionsId);
            let deviceTypesElement = $("#" + deviceTypesId);

            filters.distributions.el = distributionsElement;
            filters.deviceTypes.el = deviceTypesElement;

            distributionsElement.on("change", function (event) {
                triggerSelection("distributions", $(event.target).val());
            });

            deviceTypesElement.on("change", function (event) {
                triggerSelection("deviceTypes", $(event.target).val());
            });

            return this;
        },

        setDefaults(currentDistributions, currentDeviceTypes) {
            if (!_isIn(currentDistributions, DEFAULT_VALUE)) {
                triggerSelection("distributions", currentDistributions);
            }

            if (!_isIn(currentDeviceTypes, DEFAULT_VALUE)) {
                triggerSelection("deviceTypes", currentDeviceTypes);
            }

            return this;
        }
    };

    function _isIn(array, value) {
        return _indexOf(array, value) !== -1;
    }
};
