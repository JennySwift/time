var Vue = require('vue');
var $ = require('jquery');
require('sugar');
var moment = require('moment');
require('sweetalert2');

module.exports = {

    /**
     *
     * @param data
     * @param status
     * @param response
     */
    handleResponseError: function (data, status, response) {
        store.hideLoading();
        $.event.trigger('response-error', [data, status, response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     * @param object
     */
    clone: function (object) {
        return JSON.parse(JSON.stringify(object));
    },

    /**
     * storeProperty is the store property to set once the items are loaded.
     * loadedProperty is the store property to set once the items are loaded, to indicate that the items are loaded.
     * todo: allow for sending data: add {params:data} as second argument
     */
    get: function (options) {
        store.showLoading();
        Vue.http.get(options.url).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            if (options.storeProperty) {
                if (options.updatingArray) {
                    //Update the array the item is in
                    store.update(response.data, options.storeProperty);
                }
                else {
                    store.set(response.data, options.storeProperty);
                }
            }

            if (options.loadedProperty) {
                store.set(true, options.loadedProperty);
            }

            store.hideLoading();
        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     * options:
     * array: store array to add to
     */
    post: function (options) {
        store.showLoading();
        Vue.http.post(options.url, options.data).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            store.hideLoading();

            if (options.message) {
                $.event.trigger('provide-feedback', [options.message, 'success']);
            }

            if (options.array) {
                store.add(response.data, options.array);
            }

            if (options.clearFields) {
                options.clearFields();
            }

            if (options.redirectTo) {
                router.go(options.redirectTo);
            }
        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     *
     */
    put: function (options) {
        store.showLoading();
        Vue.http.put(options.url, options.data).then(function (response) {
            if (options.callback) {
                options.callback(response.data);
            }

            store.hideLoading();

            if (options.message) {
                $.event.trigger('provide-feedback', [options.message, 'success']);
            }

            if (options.property) {
                store.update(response.data, options.property);
            }

            if (options.redirectTo) {
                router.go(options.redirectTo);
            }

        }, function (response) {
            helpers.handleResponseError(response.data, response.status);
        });
    },

    /**
     *
     */
    delete: function (options) {
        swal({
            title: 'Are you sure?',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-default',
            buttonsStyling: false,
            reverseButtons: true,
            showCloseButton: true
        }).then(function() {
            store.showLoading();
            Vue.http.delete(options.url).then(function (response) {
                if (options.callback) {
                    options.callback(response);
                }

                store.hideLoading();

                if (options.message) {
                    $.event.trigger('provide-feedback', [options.message, 'success']);
                }

                if (options.array) {
                    store.delete(options.itemToDelete, options.array);
                }

                if (options.redirectTo) {
                    router.go(options.redirectTo);
                }
            }, function (response) {
                helpers.handleResponseError(response.data, response.status);
            });
        }, function(dismiss) {

        });
    },

    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findById: function (array, id) {
        var index = this.findIndexById(array, id);
        return array[index];
    },

    /**
     *
     * @param array
     * @param id
     * @returns {*}
     */
    findIndexById: function (array, id) {
        // return _.indexOf(array, _.findWhere(array, {id: id}));
        //So it still work if id is a string
        return _.indexOf(array, _.find(array, function (item) {
            return item.id == id;
        }));
    },

    /**
     *
     * @param array
     * @param id
     */
    deleteById: function (array, id) {
        var index = helpers.findIndexById(array, id);
        array = _.without(array, array[index]);

        return array;
    },

    /**
     *
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
    },

    formatDateToSql: function (date) {
        return Date.create(date).format('{yyyy}-{MM}-{dd}');
    },

    formatDateToLong: function (date) {
        return Date.create(date).format('{Weekday} {dd} {Month} {yyyy}');
    },

    formatTime: function (time) {
        return Date.create(time).format('{HH}:{mm}:{ss}');
    },

    formatToDateTime: function (time) {
        return Date.create(time).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
    },

    momentFormatToDateTime: function (time) {
        return moment(time).format('YYYY-MM-DD HH:mm:ss');
    },

    /**
     *
     * @param seconds
     * @returns {string}
     */
    formatDurationFromSeconds: function (seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor(seconds / 60) % 60;
        seconds = seconds % 60;

        return this.addZeros(hours) + ':' + this.addZeros(minutes) + ':' + this.addZeros(seconds);
    },

    /**
     *
     * @param number
     * @returns {*}
     */
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    roundNumber: function (number, howManyDecimals) {
        if (!howManyDecimals) {
            return Math.round(number);
        }

        var multiplyAndDivideBy = Math.pow(10, howManyDecimals);
        return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
    }
};