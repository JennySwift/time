<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer"
    >

        <div id="new-manual-timer-popup" class="popup-inner">

            <div class="content">
                <div class="form-group">
                    <label for="new-manual-timer-activity">Activity</label>

                    <select
                        v-model="newManualTimer.activity"
                        id="new-manual-timer-activity"
                        class="form-control"
                    >
                        <option
                            v-for="activity in activities"
                            v-bind:value="activity"
                        >
                            {{ activity.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="new-manual-timer-start">Start</label>
                    <input
                        v-model="newManualTimer.start"
                        v-on:keyup.13="insertManualTimer()"
                        type="text"
                        id="new-manual-timer-start"
                        name="new-manual-timer-start"
                        placeholder="start"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="new-manual-timer-finish">Finish</label>
                    <input
                        v-model="newManualTimer.finish"
                        v-on:keyup.13="insertManualTimer()"
                        type="text"
                        id="new-manual-timer-finish"
                        name="new-manual-timer-finish"
                        placeholder="finish"
                        class="form-control"
                    >
                </div>

            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" v-link="{path: '/timers'}" class="btn btn-default">Cancel</button>
                <button v-on:click="insertManualTimer()" class="btn btn-success">Insert manual timer</button>
            </div>

        </div>
    </div>
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
                    activity: {}
                },
                showPopup: true,
                shared: store.state
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

                helpers.post('/api/timers', data, 'Timer created', function (response) {
                    store.addTimer(response.data, true);
                    $.event.trigger('manual-timer-created');
                    router.go('/timers');
                }.bind(this));
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
            closePopup: function ($event) {
                helpers.closePopup($event, this);
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

                $(document).on('show-new-manual-timer-popup', function (event) {
                    if (that.$route.path.indexOf('/timers') !== -1) {
                        //We're on the timers page so we can show the popup
                        that.showPopup = true;
                    }
                    else {
                        //Wait for the timers page to load before showing the popup
                        setTimeout(function () {
                            that.showPopup = true;
                        }, 5000);
                    }
                });
            }

        },
        props: [

        ],
        ready: function () {
            this.listen();
            this.setDefaultActivity();
        }
    };

</script>