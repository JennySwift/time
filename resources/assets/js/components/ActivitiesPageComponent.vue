<template>
    <div id="activities">

        <activity-popup
            :activities.sync="activities"
        >
        </activity-popup>

        <h3>Activities</h3>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Colour</th>
                <th>Total Duration</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="activity in activities" v-on:click="showActivityPopup(activity)" class="activity">
                    <td class="activity-column"><span v-bind:style="{'background': activity.color}" class="label label-default">{{ activity.name }}</span></td>
                    <td>{{ activity.color }}</td>
                    <td>{{ activity.totalMinutes | formatDuration }}</td>
                </tr>
            </tbody>



        </table>

    </div>
</template>

<script>
    module.exports = {
        template: '#activities-page-template',
        data: function () {
            return {
                shared: store.state
            };
        },
        computed: {
            activities: function () {
                return this.shared.activities;
            }
        },
        components: {},
        filters: {
            formatDuration: function (minutes) {
                return filters.formatDuration(minutes);
            }
        },
        methods: {

            /**
             *
             * @param activity
             */
            showActivityPopup: function (activity) {
                $.event.trigger('show-activity-popup', [activity]);
            }
        },
        props: [
            //data to be received from parent
        ],
        ready: function () {

        }
    };

</script>