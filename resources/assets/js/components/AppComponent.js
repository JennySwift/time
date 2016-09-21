module.exports = {
    ready: function () {
        store.getActivities();
        store.getTimers();
        store.getTotalMinutesForActivitiesForTheDay();
        store.getTotalMinutesForActivitiesForTheWeek();
    }
};