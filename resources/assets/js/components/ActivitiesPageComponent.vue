<template>
    <div id="activities">

        <h1 class="top-heading">Activities</h1>
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
                    <td>{{ activity.duration.hours | doubleDigits }}:{{ activity.duration.minutes | doubleDigits }}</td>
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
            doubleDigits: function (number) {
                return helpers.addZeros(number);
            },
//            formatDurationFromMinutes: function (minutes) {
//                return helpers.formatDurationFromMinutes(minutes);
//            }
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