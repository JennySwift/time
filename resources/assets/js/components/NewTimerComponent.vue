<template>
    <div>

        <div v-show="!timerInProgress" id="new-timer">
            <div class="form-group">
                <label for="activity">Activity</label>

                <select
                    v-model="newTimer.activity.id"
                    id="new-timer-activity"
                    class="form-control"
                >
                    <option
                        v-for="activity in activities"
                        v-bind:value="activity.id"
                    >
                        {{ activity.name }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <button v-on:click="startTimer()" class="btn btn-success">Start</button>
            </div>
        </div>

        <div v-show="timerInProgress" id="timer-in-progress">
            <div v-if="timerInProgress.activity">@{{ timerInProgress.activity.data.name }}</div>
            <div v-show="showTimerInProgress" id="timer-clock">@{{ time | formatDurationFromSeconds }}</div>
            <button v-show="showTimerInProgress" v-on:click="stopTimer()" class="btn btn-danger">Stop</button>
            <button v-on:click="showTimerInProgress = !showTimerInProgress" class="btn btn-default">Toggle visibility</button>
        </div>
    </div>
</template>

<script>
    var TimersRepository = require('../repositories/TimersRepository');
    var moment = require('moment');

    module.exports = {
        template: '#new-timer-template',
        data: function () {
            return {
                newTimer: {
                    activity: {}
                },
                showTimerInProgress: true,
                timerInProgress: false,
                shared: store.state,
                time: ''
            };
        },
        computed: {
            activities: function () {
                return this.shared.activities;
            },
            timers: function () {
                return this.shared.timers;
            }
        },
        components: {},
        filters: {
            formatDurationFromSeconds: function (seconds) {
                return helpers.formatDurationFromSeconds(seconds);
            }
        },
        methods: {

            /**
            *
            */
            startTimer: function () {
                var data = TimersRepository.setData(this.newTimer);
                //So the previous timer's time isn't displayed at the start
                this.time = 0;

                helpers.post({
                    url: '/api/timers',
                    data: data,
                    message: 'Timer started',
                    callback: function (response) {
                        this.timerInProgress = response;
                        this.setTimerInProgress();
                    }.bind(this)
                });
            },

            /**
            *
            */
            stopTimer: function () {
                clearInterval(this.secondsInterval);

                var data = {
                    finish: TimersRepository.calculateFinishTime(this.timerInProgress)
                };

                helpers.put({
                    url: '/api/timers/' + this.timerInProgress.id,
                    data: data,
                    message: 'Timer finished',
                    callback: function (response) {
                        this.timerInProgress = false;
                        store.addTimer(response);
                    }.bind(this)
                });
            },

            /**
             *
             */
            setDefaultActivity: function () {
                this.newTimer.activity = this.activities[0];
            },

            /**
            *
            */
            checkForTimerInProgress: function () {
                helpers.get({
                    url: '/api/timers/checkForTimerInProgress',
                    callback: function (response) {
                        if (response.activity) {
                            this.timerInProgress = response;
                            this.setTimerInProgress();
                        }
                    }.bind(this)
                });
            },

            /**
             *
             */
            setTimerInProgress: function () {
                var seconds;
                var that = this;

                this.secondsInterval = setInterval(function () {
                    seconds = moment().diff(moment(that.timerInProgress.start, 'YYYY-MM-DD HH:mm:ss'), 'seconds');
                    that.time = seconds;
                }, 1000);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('activities-loaded', function (event) {
                    setTimeout(function () {
                        that.setDefaultActivity();
                    }, 500);
                });
            }

        },
        props: [

        ],
        ready: function () {
            this.listen();
            this.checkForTimerInProgress();
        }
    };

</script>