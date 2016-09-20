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
