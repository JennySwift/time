<div id="activities-with-durations-for-week">
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Activity</th>
                <th>Duration for week</th>
                <th>Avg/day for week</th>
                <th>Duration for all time</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="activity in activitiesWithDurationsForTheWeek
                    | filterBy activitiesFilter in 'name'"
            >
                <td class="activity">
                    <span
                        v-bind:style="{'background': activity.color}"
                        class="label label-default"
                    >
                        @{{ activity.name }}
                    </span>
                </td>
                <td>@{{ activity.totalMinutesForWeek | formatDuration }}</td>
                <td>@{{ activity.averageMinutesPerDayForWeek | formatDuration }}</td>
                <td>
                    <div v-if="activity.totalMinutesForAllTime">
                        @{{ activity.totalMinutesForAllTime | formatDuration }}
                    </div>
                    <div v-else>-</div>
                </td>
            </tr>
        </tbody>

    </table>
</div>