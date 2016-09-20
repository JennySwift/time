<div id="timers">
    <table class="table table-bordered">

        <thead>
        <tr>
            <th>Activity</th>
            <th>Duration</th>
            <th>Duration today</th>
            <th>Start</th>
            <th>End</th>
        </tr>
        </thead>

        <tbody>
        <tr
            v-for="timer in timers
                | filterBy activitiesFilter in 'activity.data.name'
                | orderBy 'start' -1"
            v-on:click="showTimerPopup(timer)"
            class="timer pointer"
        >
            <td class="activity">
                <span v-bind:style="{'background': timer.activity.data.color}" class="label">@{{ timer.activity.data.name }}</span>
            </td>

            <td class="duration">
                <span>@{{ timer.hours | doubleDigits }}:@{{ timer.minutes | doubleDigits }}</span>
            </td>

            <td>
                <span>@{{ timer.durationInMinutesForDay | formatDuration }}</span>
            </td>

            <td>
                <span>@{{ timer.start | formatDateTime 'hoursAndMinutes' }}</span>
                <span class="seconds">:@{{ timer.start | formatDateTime 'seconds' }}</span>
            </td>

            <td>
                <span>@{{ timer.finish | formatDateTime 'hoursAndMinutes' }}</span>
                <span class="seconds">:@{{ timer.finish | formatDateTime 'seconds' }}</span>
            </td>

        </tr>
        </tbody>

    </table>
</div>