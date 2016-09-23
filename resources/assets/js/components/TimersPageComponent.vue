<template>
    <div id="timers-page">

        <!--Activities filter-->
        <div class="input-group-container">
            <input-group
                label="Filter Activities:"
                :model.sync="activitiesFilter"
                id="activities-filter"
            >
            </input-group>
        </div>

        <!--Activites with durations for the day-->
        <div id="activities-with-durations-for-day">
            <span
                v-for="activity in shared.activitiesWithDurationsForTheDay | filterBy activitiesFilter in 'name'"
                v-bind:style="{'background': activity.color}"
                class="label label-default"
            >
                {{ activity.name }} {{ formatDurationFromMinutes(activity.totalMinutesForDay).hours }}:{{ formatDurationFromMinutes(activity.totalMinutesForDay).minutes }}
            </span>
        </div>

        <date-navigation
            :date.sync="date"
        >
        </date-navigation>

        <new-timer></new-timer>

        <div id="activities-and-timers-container">
            <!--Timers-->
            <div id="timers">
                <h2>Timers</h2>
                <table class="table table-bordered" v-if="shared.timers.length > 0">

                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Duration</th>
                            <th>Duration today</th>
                            <th>Start</th>
                            <th>End</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="timer in shared.timers
                                | filterBy activitiesFilter in 'activity.data.name'
                                | orderBy 'start' -1"
                            is="timer"
                            :timer="timer"
                            v-link="{path: '/timers/' + timer.id}"
                            v-on:click="selectTimer(timer)"
                            class="timer pointer"
                        >
                        </tr>
                    </tbody>

                </table>
                <p v-else>No timers here</p>
            </div>

            <!--Activites with durations for the week-->
            <div id="activities-with-durations-for-week">
                <h2>Activities</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Duration for week</th>
                            <th>Avg/day for week</th>
                            <th>Duration for all time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="activity in shared.activitiesWithDurationsForTheWeek
                                | filterBy activitiesFilter in 'name'"
                        >
                            <td class="activity">
                                <span
                                    v-bind:style="{'background': activity.color}"
                                    class="label label-default"
                                >
                                    {{ activity.name }}
                                </span>
                            </td>
                            <td>{{ activity.week.hours | doubleDigits }}:{{ activity.week.minutes | doubleDigits }}</td>
                            <td>{{ activity.week.dailyAverage.hours | doubleDigits }}:{{ activity.week.dailyAverage.minutes | doubleDigits }}</td>
                            <td>
                                <div v-if="activity.duration.totalMinutes">
                                    {{ activity.duration.hours | doubleDigits }}:{{ activity.duration.minutes | doubleDigits }}
                                </div>
                                <div v-else>-</div>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</template>

<script>
    var TimersRepository = require('../repositories/TimersRepository');

    module.exports = {
        template: '#timers-page-template',
        data: function () {
            return {
                date: store.state.date,
                timersFilter: false,
                activitiesFilter: '',
                shared: store.state
            };
        },
        filters: {
            doubleDigits: function (number) {
                return helpers.addZeros(number);
            },
            formatDateTime: function (dateTime, format) {
                return helpers.formatDateTime(dateTime, format);
            },
//            formatDurationFromMinutes: function (minutes) {
//                return helpers.formatDurationFromMinutes(minutes);
//            }
        },
        components: {
            'timer': require('./TimerComponent.vue')
        },
        methods: {
            /**
             *
             * @param minutes
             * @returns {*|{hours, minutes}}
             */
            formatDurationFromMinutes: function (minutes) {
                return helpers.formatDurationFromMinutes(minutes);
            },

            /**
             *
             */
            selectTimer: function (timer) {
                store.set(timer, 'timer');
            },

            /**
             *
             * @param timer
             * @returns {boolean}
             */
            filterTimers: function (timer) {
                if (this.timersFilter) {
                    return timer.activity.data.name.indexOf(this.timersFilter) !== -1;
                }
                return true;

            },

            /**
             *
             * @param minutes
             * @returns {number}
             */
            formatMinutes: function (minutes) {
                return minutes * 10;
            }
        }
    };
</script>

