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
                {{ activity.name }} {{ activity.totalMinutesForDay | formatDuration }}
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
                        v-link="{path: '/timers/' + timer.id}"
                        v-on:click="selectTimer(timer)"
                        class="timer pointer"
                    >
                        <td class="activity">
                            <span v-bind:style="{'background': timer.activity.data.color}" class="label">{{ timer.activity.data.name }}</span>
                        </td>

                        <td class="duration">
                            <span>{{ timer.hours | doubleDigits }}:{{ timer.minutes | doubleDigits }}</span>
                        </td>

                        <td>
                            <span>{{ timer.durationInMinutesForDay | formatDuration }}</span>
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
                            <td>{{ activity.totalMinutesForWeek | formatDuration }}</td>
                            <td>{{ activity.averageMinutesPerDayForWeek | formatDuration }}</td>
                            <td>
                                <div v-if="activity.totalMinutesForAllTime">
                                    {{ activity.totalMinutesForAllTime | formatDuration }}
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
            formatDateTime: function (dateTime, format) {
                return filters.formatDateTime(dateTime, format);
            },
            doubleDigits: function (number) {
                if (number < 10) {
                    return '0' + number;
                }

                return number;
            },
            formatDuration: function (minutes) {
                return filters.formatDuration(minutes);
            }
        },
        components: {},
        methods: {

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

