var moment = require('moment');

module.exports = {



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