<template>
    <div class="input-group-container">
        <input-group
            label="Start:"
            :model.sync="timer.start"
            :enter="updateTimer"
            id="timer-start"
        >
        </input-group>

        <input-group
            label="Finish:"
            :model.sync="timer.finish"
            :enter="updateTimer"
            id="timer-finish"
        >
        </input-group>

        <input-group
            label="Activity:"
            :model.sync="timer.activity.data"
            :enter="updateTimer"
            id="timer-activity"
            :options="shared.activities"
            options-prop="name"
        >
        </input-group>
    </div>

    <buttons
        :save="updateTimer"
        :redirect-to="redirectTo"
    >
    </buttons>
</template>

<script>
    module.exports = {
        template: '#timer-popup-template',
        data: function () {
            return {
                shared: store.state,
                redirectTo: '/timers'
            };
        },
        computed: {
            timer: function () {
              return helpers.clone(this.shared.timer);
            }
        },
        components: {},
        methods: {

            /**
            *
            */
            updateTimer: function () {
                var data = {
                    start: this.timer.start,
                    finish: this.timer.finish,
                    activity_id: this.timer.activity.data.id
                };

                helpers.put({
                    url: '/api/timers/' + this.timer.id,
                    data: data,
                    property: 'timers',
                    message: 'Timer updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {

                    }.bind(this)
                });
            },

            /**
            *
            */
            deleteTimer: function () {
                helpers.delete({
                    url: '/api/timers/' + this.timer.id,
                    array: 'timers',
                    itemToDelete: this.timer,
                    message: 'Timer deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        store.getTotalMinutes();
                    }.bind(this)
                });
            }
        }
    };

</script>