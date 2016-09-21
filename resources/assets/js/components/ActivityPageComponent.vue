<template>
    <div>

        <div class="input-group-container">
            <input-group
                label="Name:"
                :model.sync="activity.name"
                :enter="updateActivity"
                id="activity-name"
            >
            </input-group>

            <input-group
                label="Color:"
                :model.sync="activity.color"
                :enter="updateActivity"
                id="activity-color"
            >
            </input-group>
        </div>

        <buttons
            :save="updateActivity"
            :destroy="deleteActivity"
            :redirect-to="redirectTo"
        >
        </buttons>

    </div>
</template>

<script>
    module.exports = {
        template: '#activity-popup-template',
        data: function () {
            return {
                shared: store.state,
                redirectTo: '/activities'
            };
        },
        components: {},
        computed: {
            activity: function () {
              return helpers.clone(this.shared.activity);
            }
        },
        methods: {

            /**
            *
            */
            updateActivity: function () {
                var data = {
                    name: this.activity.name,
                    color: this.activity.color
                };

                helpers.put({
                    url: '/api/activities/' + this.activity.id,
                    data: data,
                    property: 'activities',
                    message: 'Activity updated',
                    redirectTo: this.redirectTo
                });
            },

            /**
            * Todo: the timers will be deleted, too.
            */
            deleteActivity: function () {
                if (confirm("Really? The timers for the activity will be deleted, too.")) {
                    helpers.delete({
                        url: '/api/activities/' + this.activity.id,
                        array: 'activities',
                        itemToDelete: this.activity,
                        message: 'Activity deleted',
                        redirectTo: this.redirectTo
                    });
                };

            }
        }
    };
</script>