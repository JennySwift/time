module.exports = {
    ready: function () {
        store.getActivities();
        store.getTimers();
    }
};