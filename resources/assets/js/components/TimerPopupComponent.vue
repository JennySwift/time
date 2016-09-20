<template>
    <div>
        <popup
            :show-popup.sync="showPopup"
            id="timer-popup"
            :redirect-to="redirectTo"
            :update="updateTimer"
            :destroy="deleteTimer"
        >
            <div slot="content">
                <div class="form-group">
                    <label for="selected-timer-start">Start</label>
                    <input
                        v-model="selectedTimer.start"
                        type="text"
                        id="selected-timer-start"
                        name="selected-timer-start"
                        placeholder="start"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-timer-finish">Finish</label>
                    <input
                        v-model="selectedTimer.finish"
                        type="text"
                        id="selected-timer-finish"
                        name="selected-timer-finish"
                        placeholder="finish"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="selected-timer-activity">Activity</label>
                    <!--Using id and not object so the activity doesn't change until the user clicks the 'save' button-->
                    <select
                        v-model="selectedTimer.activity.data.id"
                        id="selected-timer-activity"
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
            </div>
        </popup>

    </div>
</template>

<script>
    module.exports = {
        template: '#timer-popup-template',
        data: function () {
            return {
                showPopup: false,
                selectedTimer: {
                    id: '',
                    start: '',
                    finish: '',
                    activity: {
                        data: {}
                    }
                }
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateTimer: function () {
                $.event.trigger('show-loading');

                var data = {
                    start: this.selectedTimer.start,
                    finish: this.selectedTimer.finish,
                    activity_id: this.selectedTimer.activity.data.id
                };

                helpers.put('/api/timers/' + this.selectedTimer.id, data, 'Timer updated', function (response) {
                    store.update(response.data, 'timers');
                    this.showPopup = false;
                }.bind(this));
            },

            /**
             *
             */
            deleteTimer: function () {
                helpers.delete('/api/timers/' + this.selectedTimer.id, 'Timer deleted', function (response) {
                    store.delete(this.selectedTimer, 'timers');
                    $.event.trigger('timer-deleted', [this.selectedTimer]);
                    this.showPopup = false;
                }.bind(this));
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-timer-popup', function (event, timer) {
                    that.selectedTimer = helpers.clone(timer);
                    that.showPopup = true;
                });
            }
        },
        props: [
            'activities',
            'timers'
        ],
        ready: function () {
            this.listen();
        }
    };

</script>