<template>
    <div id="activity-popup">

        <popup
            :show-popup.sync="showPopup"
            id="activity-popup"
            :redirect-to="redirectTo"
            :update="updateActivity"
            :destroy="deleteActivity"
        >
            <div slot="content">
                <form>
                    <div>
                        <label for="selected-activity-name">Name</label>
                        <input v-model="selectedActivity.name" type="text" id="selected-activity-name" name="selected-activity-name" placeholder="name" class="form-control"/>
                    </div>

                    <div>
                        <label for="selected-activity-color">Color</label>
                        <input v-model="selectedActivity.color" type="text" id="selected-activity-color" name="selected-activity-color" placeholder="color" class="form-control"/>
                    </div>

                </form>
            </div>
        </popup>

    </div>
</template>

<script>
    module.exports = {
        template: '#activity-popup-template',
        data: function () {
            return {
                showPopup: false,
                selectedActivity: {}
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateActivity: function () {
                $.event.trigger('show-loading');

                var data = {
                    name: this.selectedActivity.name,
                    color: this.selectedActivity.color
                };

                helpers.put('/api/activities/' + this.selectedActivity.id, data, 'Activity updated', function (response) {
                    store.update(response.data, 'activities');
                    this.showPopup = false;
                }.bind(this));
            },

            /**
             * Todo: the timers will be deleted, too.
             */
            deleteActivity: function () {
                if (confirm("Really? The timers for the activity will be deleted, too.")) {
                    helpers.delete('/api/activities/' + this.selectedActivity.id, 'Activity deleted', function (response) {
                        store.delete(this.selectedActivity, 'activities');
                        this.showPopup = false;
                    }.bind(this));
                }
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-activity-popup', function (event, activity) {
                    that.selectedActivity = helpers.clone(activity);
                    that.showPopup = true;
                });
            }
        },
        props: [

        ],
        ready: function () {
            this.listen();
        }
    };
</script>