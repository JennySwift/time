module.exports = {
    template: '#activities-page-template',
    data: function () {
        return {
            shared: store.state,
            newActivity: {},
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
        */
        insertActivity: function () {
            var data = {
                name: this.newActivity.name,
                color: this.newActivity.color
            };

            helpers.post('/api/activities', data, 'Activity created', function (response) {
                store.add(response.data, 'activities');
            }.bind(this));
        },

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
