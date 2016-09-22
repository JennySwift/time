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
            :options="activities"
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
                $.event.trigger('show-loading');

                var data = {
                    start: this.timer.start,
                    finish: this.timer.finish,
                    activity_id: this.timer.activity.data.id
                };

                helpers.put('/api/timers/' + this.timer.id, data, 'Timer updated', function (response) {
                    store.update(response.data, 'timers');
                }.bind(this));
            },

            /**
             *
             */
            deleteTimer: function () {
                helpers.delete('/api/timers/' + this.timer.id, 'Timer deleted', function (response) {
                    store.delete(this.timer, 'timers');
                    store.getTotalMinutes();
                }.bind(this));
            }
        }
    };

</script>