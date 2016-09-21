var Vue = require('vue');
var VueResource = require('vue-resource');
Vue.use(VueResource);
var helpers = require('./Helpers');
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
var TimersRepository = require('./TimersRepository');

module.exports = {

    state: {
        me: {gravatar: ''},
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('today'),
            sql: helpers.formatDateToSql('today')
        },
        loading: false,
        activities: [],
        activitiesLoaded: false,
        activity: {},
        activityLoaded: false,
        timers: [],
        timersLoaded: false
    },

    /**
     *
     */
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },

    /**
     *
     * @param date
     */
    setDate: function (date) {
        this.state.date.typed = Date.create(date).format('{dd}/{MM}/{yyyy}');
        this.state.date.long = helpers.formatDateToLong(date);
        this.state.date.sql = helpers.formatDateToSql(date);
    },

    /**
    *
    */
    getTimers: function () {
        helpers.get({
            url: TimersRepository.calculateUrl(false, this.state.date.sql),
            storeProperty: 'timers',
            loadedProperty: 'timersLoaded'
        });
    },

    /**
    *
    */
    getActivities: function () {
        helpers.get({
            url: '/api/activities',
            storeProperty: 'activities',
            loadedProperty: 'activitiesLoaded',
            callback: function (response) {
                $.event.trigger('activities-loaded');
            }
        });
    },

    /**
     *
     * @param timer
     * @param timerIsManual
     */
    addTimer: function (timer, timerIsManual) {
        if (store.state.date.sql === helpers.formatDateToSql() || timerIsManual) {
            //Only add the timer if the date is on today or the timer is a manual entry
            store.state.timers.push(timer);
        }
    },

    /**
     * Add an item to an array
     * @param item
     * @param path
     */
    add: function (item, path) {
        object.get(this.state, path).push(item);
    },

    /**
     * Update an item that is in an array
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        object.get(this.state, path).$set(index, item);
    },

    /**
     * Set a property (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Delete an item from an array
     * To delete a nested property of store.state, for example a class in store.state.classes.data:
     * store.delete(itemToDelete, 'student.classes.data');
     * @param itemToDelete
     * @param path
     */
    delete: function (itemToDelete, path) {
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
};