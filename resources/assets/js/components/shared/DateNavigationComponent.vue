<template>
    <div id="date-navigation-container">

        <h1 class="center">{{ shared.date.long }}</h1>

        <div id="date-navigation" class="margin-bottom">

            <div class="my-btn-group">
                <button
                    type="button"
                    id="last-week"
                    class="my-btn fa fa-angle-double-left"
                    v-on:click="goToDate(-7)"
                >
                </button>

                <button
                    type="button"
                    id="prev"
                    class="my-btn fa fa-angle-left"
                    v-on:click="goToDate(-1)"
                >
                </button>
            </div>

            <input
                v-on:keyup.13="changeDate()"
                type="text"
                placeholder="date"
                id="date"
                class="my-input"
            >

            <button
                v-on:click="goToToday()"
                type="button"
                id="today"
                class="my-btn"
            >
                <span class="hidden-xs">today</span>
                <span class="fa fa-star visible-xs"></span>
            </button>

            <div class="my-btn-group">
                <button
                    type="button"
                    id="next"
                    class="my-btn fa fa-angle-right"
                    v-on:click="goToDate(1)"
                >
                </button>

                <button
                    type="button"
                    id="next-week"
                    class="my-btn fa fa-angle-double-right"
                    v-on:click="goToDate(7)"
                >
                </button>
            </div>

        </div>

    </div>

</template>

<script>
    var DatesRepository = require('../../repositories/DatesRepository');
    var $ = require('jquery');

    var moment = require('moment');

    module.exports = {
        template: '#date-navigation-template',
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        watch: {
            'shared.date.typed': function (newValue, oldValue) {
                $("#date").val(this.shared.date.typed);
                store.getTimers();
                store.getTotalMinutesForActivitiesForTheDay();

                //Only get the total minutes for the week if it's a different week
                if (helpers.getWeekNumber(helpers.formatDateToSql(oldValue)) !== helpers.getWeekNumber(helpers.formatDateToSql(newValue))) {
                    store.getTotalMinutesForActivitiesForTheWeek();
                }
            }
        },
        methods: {
            /**
             *
             * @param number
             */
            goToDate: function (number) {
                DatesRepository.goToDate(number);
            },

            /**
             *
             */
            goToToday: function () {
                DatesRepository.today();
            },

            /**
             *
             * @param date
             * @returns {boolean}
             */
            changeDate: function (date) {
                var date = date || $("#date").val();
                DatesRepository.changeDate(date);
            }
        },
        ready: function () {

        }

    };

</script>

