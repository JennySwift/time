var moment = require('moment');

module.exports = {

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
    },

    /**
     *
     * @param minutes
     * @returns {*}
     */
    formatDuration: function (minutes) {
        if (!minutes && minutes != 0) {
            return '-';
        }

        var hours = Math.floor(minutes / 60);
        if (hours < 10) {
            hours = '0' + hours;
        }

        minutes = minutes % 60;
        if (minutes < 10) {
            minutes = '0' + minutes;
        }

        return hours + ':' + minutes;
    },

    /**
     *
     * @param dateTime
     * @param format
     * @returns {*}
     */
    formatDateTime: function (dateTime, format) {
        if (!format) {
            return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm:ssa DD/MM');
        }
        else if (format === 'seconds') {
            return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss a DD/MM');
        }
        else if (format === 'hoursAndMinutes') {
            return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm');
        }
        else if (format === 'object') {
            return {
                seconds: moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss')
            };
        }
    }
};