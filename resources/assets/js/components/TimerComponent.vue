<template>
    <tr>
        <td class="activity">
            <span v-bind:style="{'background': timer.activity.data.color}" class="label">{{ timer.activity.data.name }}</span>
        </td>

        <td class="duration">
            <span>{{ timer.duration.hours | doubleDigits }}:{{ timer.duration.minutes | doubleDigits }}</span>
        </td>

        <td>
            <span>{{ timer.durationForDay.hours | doubleDigits }}</span><span>:{{ timer.durationForDay.minutes | doubleDigits }}</span>
        </td>

        <td>
            <span>{{ timer.start | formatDateTime 'hoursAndMinutes' }}</span><span v-if="shared.showSeconds" class="seconds">:{{ timer.start | formatDateTime 'seconds' }}</span>
            <span >{{ timer.start | formatDateTime 'a' }}</span>
        </td>

        <td>
            <span>{{ timer.finish | formatDateTime 'hoursAndMinutes' }}</span><span v-if="shared.showSeconds" class="seconds">:{{ timer.finish | formatDateTime 'seconds' }}</span>
            <span >{{ timer.finish | formatDateTime 'a' }}</span>
        </td>

    </tr>
</template>

<script>
    module.exports = {
        data: function () {
            return {
                shared: store.state
            }
        },
        filters: {
//            formatDurationFromMinutes: function (minutes) {
//                return helpers.formatDurationFromMinutes(minutes);
//            },
            formatDateTime: function (dateTime, format) {
                return helpers.formatDateTime(dateTime, format);
            },
            doubleDigits: function (number) {
                return helpers.addZeros(number);
            },
        },
        methods: {
            /**
             *
             * @param minutes
             * @returns {*|{hours, minutes}}
             */
//            formatDurationFromMinutes: function (minutes) {
//                return helpers.formatDurationFromMinutes(minutes);
//            }
        },
        props: [
            'timer'
        ]
    };
</script>