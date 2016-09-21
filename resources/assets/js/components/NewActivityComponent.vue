<template>
    <div id="new-activity">

        <div class="input-group-container">
            <input-group
                label="Name:"
                :model.sync="newActivity.name"
                :enter="insertActivity"
                id="new-activity-name"
            >
            </input-group>

            <input-group
                label="Color:"
                :model.sync="newActivity.color"
                :enter="insertActivity"
                id="new-activity-color"
            >
            </input-group>
        </div>

        <buttons
            :save="insertActivity"
            :redirect-to="redirectTo"
        >
        </buttons>

    </div>
</template>

<script>
    module.exports = {

        data: function () {
            return {
                newActivity: {},
                redirectTo: '/activities'
            }
        },

        methods: {
            /**
             *
             */
            insertActivity: function () {
                var data = {
                    name: this.newActivity.name,
                    color: this.newActivity.color
                };

                helpers.post({
                    url: '/api/activities',
                    data: data,
                    array: 'activities',
                    message: 'Activity created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            clearFields: function () {
                this.newActivity.name = '';
                this.newActivity.color = '';
            }
        }


    };
</script>