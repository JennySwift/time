<template>
    <div>

        <div v-show="!timerInProgress" id="new-timer">

            <input-group
                label="Activity:"
                :model.sync="newTimer.activity"
                :enter="startTimer"
                id="new-timer-activity"
                :options="shared.activities"
                options-prop="name"
            >
            </input-group>

            <div class="form-group">
                <button v-on:click="startTimer()" class="btn btn-success">Start</button>
            </div>
        </div>

        <div v-if="timerInProgress && timerInProgress.activity && shared.showTimerInProgress" id="timer-in-progress">
            <h2 id="timer-clock">{{ timerInProgress.activity.data.name }}: <span>{{ time | formatDurationFromSeconds }}</span></h2>
            <button v-on:click="stopTimer()" class="btn btn-danger btn-sm">Stop</button>
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
                timerInProgress: false,
                shared: store.state,
                time: ''
            };
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
                this.newTimer.activity = this.shared.activities[0];
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
        ready: function () {
            this.listen();
            this.checkForTimerInProgress();
        }
    };

</script>