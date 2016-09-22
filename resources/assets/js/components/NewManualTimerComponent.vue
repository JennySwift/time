<template>
    <div class="input-group-container">
        
        <input-group
            label="Activity:"
            :model.sync="newManualTimer.activity"
            :enter="insertManualTimer"
            id="new-manual-timer-activity"
            :options="shared.activities"
            options-prop="name"
        >
        </input-group>

        <input-group
            label="Start:"
            :model.sync="newManualTimer.start"
            :enter="insertManualTimer"
            id="new-manual-timer-start"
        >
        </input-group>

        <input-group
            label="Finish:"
            :model.sync="newManualTimer.finish"
            :enter="insertManualTimer"
            id="new-manual-timer-finish"
        >
        </input-group>
    </div>

    <buttons
        :save="insertManualTimer"
        :redirect-to="redirectTo"
    >
    </buttons>
</template>

<script>
    var TimersRepository = require('../repositories/TimersRepository');
    var Vue = require('vue');
    var VueRouter = require('vue-router');
    Vue.use(VueRouter);

    module.exports = {
        template: '#new-manual-timer-template',
        data: function () {
            return {
                newManualTimer: {
                    activity: {},
                    start: '',
                    finish: ''
                },
                shared: store.state,
                redirectTo: '/timers'
            };
        },
        computed: {
            timers: function () {
                return this.shared.timers;
            },
            activities: function () {
                return this.shared.activities;
            }
        },
        components: {},
        methods: {

            /**
             * Instead of starting and stopping the timer,
             * enter the start and stop times manually
             */
            insertManualTimer: function () {
                var data = TimersRepository.setData(this.newManualTimer, store.state.date.sql);

                helpers.post({
                    url: '/api/timers',
                    data: data,
                    message: 'Timer created',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.addTimer(response, true);
                    }.bind(this)
                });
            },

            /**
             *
             */
            setDefaultActivity: function () {
                this.newManualTimer.activity = this.activities[0];
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
            this.setDefaultActivity();
        }
    };

</script>