<template>
    <div id="activities">

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
                <tr v-for="activity in shared.activities" v-link="{path: '/activities/' + activity.id}" v-on:click="selectActivity(activity)" class="activity pointer">
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
            selectActivity: function (activity) {
                store.set(activity, 'activity')
            }
        }
    };

</script>