var expect = require('chai').expect;
var assert = require('chai').assert;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/Store');
global.helpers = require('../resources/assets/js/repositories/Helpers');
Date.setLocale('en-AU');
global.$ = require('jquery');

describe('date methods', function () {
    it.only('can get the week number', function () {
        //Thursday
        var result = helpers.getWeekNumber('2016-09-29');
        assert.equal(result, 39);

        //Saturday
        var result = helpers.getWeekNumber('2016-10-01');
        assert.equal(result, 39);

        //Sunday
        var result = helpers.getWeekNumber('2016-10-02');
        assert.equal(result, 40);

        //Monday
        var result = helpers.getWeekNumber('2016-10-03');
        assert.equal(result, 40);
    });
});


describe('date navigation component', function () {
    var vm;

    beforeEach(function () {
        vm = new Vue(require('../resources/assets/js/components/shared/DateNavigationComponent.vue'));
    });

    it("is today's date initially", function () {
        expect(vm.shared.date).to.eql({
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('today'),
            sql: helpers.formatDateToSql('today')
        });
    });

    it("can go to the previous day", function () {
        vm.goToDate(-1);

        expect(vm.shared.date).to.eql({
            typed: Date.create('yesterday').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('yesterday'),
            sql: helpers.formatDateToSql('yesterday')
        });
    });

    it("can go to back to today", function () {
        //Check the date is still on yesterday's date
        expect(vm.shared.date).to.eql({
            typed: Date.create('yesterday').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('yesterday'),
            sql: helpers.formatDateToSql('yesterday')
        });

        vm.goToToday();

        expect(vm.shared.date).to.eql({
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: helpers.formatDateToLong('today'),
            sql: helpers.formatDateToSql('today')
        });
    });

    it("can go to a specific date", function () {
        vm.changeDate('1/4/16');

        expect(vm.shared.date).to.eql({
            typed: '01/04/2016',
            long: 'Friday 01 April 2016',
            sql: '2016-04-01'
        });
    });
});