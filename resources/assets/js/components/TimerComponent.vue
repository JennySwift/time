<template>
    <tr>
        <td class="activity">
            <span v-bind:style="{'background': timer.activity.data.color}" class="label">{{ timer.activity.data.name }}</span>
        </td>

        <td class="duration">
            <span>{{ timer.hours | doubleDigits }}:{{ timer.minutes | doubleDigits }}</span>
        </td>

        <td>
            <span>{{ formatDurationFromMinutes(timer.durationInMinutesForDay).hours }}</span><span>:{{ formatDurationFromMinutes(timer.durationInMinutesForDay).minutes }}</span>
        </td>

        <td>
            <span>{{ timer.start | formatDateTime 'hoursAndMinutes' }}</span>
            <span class="seconds">:{{ timer.start | formatDateTime 'seconds' }}</span>
        </td>

        <td>
            <span>{{ timer.finish | formatDateTime 'hoursAndMinutes' }}</span>
            <span class="seconds">:{{ timer.finish | formatDateTime 'seconds' }}</span>
        </td>

    </tr>
</template>

<script>
    module.exports = {
        filters: {
//            formatDurationFromMinutes: function (minutes) {
//                return helpers.formatDurationFromMinutes(minutes);
//            },
            formatDateTime: function (dateTime, format) {
                return helpers.formatDateTime(dateTime, format);
            },
            doubleDigits: function (number) {
                if (number < 10) {
                    return '0' + number;
                }

                return number;
            },
        },
        methods: {
            /**
             *
             * @param minutes
             * @returns {*|{hours, minutes}}
             */
            formatDurationFromMinutes: function (minutes) {
                return helpers.formatDurationFromMinutes(minutes);
            }
        },
        props: [
            'timer'
        ]
    };
</script>