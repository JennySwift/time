var expect = require('chai').expect;
var assert = require('chai').assert;
var Vue = require('vue');
global.store = require('../resources/assets/js/repositories/Store');

describe('filters', function () {
    it('can round a number to one decimal place', function () {
        var number = helpers.roundNumber(10.246, 1);
        expect(number).to.equal(10.2);
    });

    it('can round a number to two decimal places', function () {
        var number = helpers.roundNumber(10.246, 2);
        expect(number).to.equal(10.25);
    });

    it('can round a number to three decimal places', function () {
        var number = helpers.roundNumber(10.2469, 3);
        expect(number).to.equal(10.247);
    });

    it('can format the seconds', function () {
        var result = helpers.formatDurationFromSeconds(30);
        expect(result).to.equal('00:00:30');

        result = helpers.formatDurationFromSeconds(60);
        expect(result).to.equal('00:01:00');

        result = helpers.formatDurationFromSeconds(65);
        expect(result).to.equal('00:01:05');

        result = helpers.formatDurationFromSeconds(3600);
        expect(result).to.equal('01:00:00');

        result = helpers.formatDurationFromSeconds(3665);
        expect(result).to.equal('01:01:05');
    });

    it('can format the minutes', function () {
        var result = helpers.formatDurationFromMinutes(60);
        assert.equal('01:00', result);

        result = helpers.formatDurationFromMinutes(61);
        assert.equal('01:01', result);

        result = helpers.formatDurationFromMinutes(2254);
        assert.equal('37:34', result);
    });

    it('can format a time', function () {
        var result = helpers.formatTime('4:30pm');
        expect(result).to.equal('16:30:00');
    });
});