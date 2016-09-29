var moment = require('moment');

module.exports = {


    getWeekNumber: function (date) {
        date = moment(date);
        var weekNumber = parseInt(date.format('W'));

        if (date.day() === 0) {
            //If I don't do this, it makes Sunday the last day of the week instead of the first day of a new week
            weekNumber++;
        }
        return weekNumber;
    },

    /**
     *
     * @param minutes
     * @returns {*}
     */
    formatDurationFromMinutes: function (minutes) {
        if (!minutes && minutes != 0) {
            return '-';
        }

        var hours = Math.floor(minutes / 60);
        minutes = minutes % 60;

        return {
            hours: helpers.addZeros(hours),
            minutes: helpers.addZeros(minutes)
        };

        // return helpers.addZeros(hours) + ':' + helpers.addZeros(minutes);
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

        return helpers.addZeros(hours) + ':' + helpers.addZeros(minutes) + ':' + helpers.addZeros(seconds);
    },

    /**
     *
     * @param dateTime
     * @param format
     * @returns {*}
     */
    formatDateTime: function (dateTime, format) {
        dateTime = moment(dateTime, 'YYYY-MM-DD HH:mm:ss');

        if (!format) {
            return dateTime.format('hh:mm:ssa DD/MM');
        }
        else if (format === 'seconds') {
            return dateTime.format('ss');
        }
        else if (format === 'hoursAndMinutes') {
            return dateTime.format('hh:mm');
        }
        else if (format === 'date') {
            return dateTime.format('DD/MM');
        }
        else if (format === 'a') {
            return dateTime.format('a');
        }
        else if (format === 'object') {
            return {
                seconds: dateTime.format('ss')
            };
        }
    },

    formatToDateTime: function (time) {
        return Date.create(time).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
    },

    momentFormatToDateTime: function (time) {
        return moment(time).format('YYYY-MM-DD HH:mm:ss');
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
};