var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.router = new VueRouter({hashbang: false});

router.map({
    '/welcome': {
        component: require('./components/WelcomePageComponent.vue')
    },
    '/timers': {
        component: require('./components/TimersPageComponent.vue'),
        subRoutes: {
            '/new-manual': {
                component: require('./components/NewManualTimerComponent.vue')
            }
        }
    },
    '/timers/:id': {
        component: require('./components/TimerPageComponent.vue')
    },
    '/timers/#/add-manual': {
        component: require('./components/NewManualTimerComponent.vue')
    },
    '/activities': {
        component: require('./components/ActivitiesPageComponent.vue')
    },
    '/activities/:id': {
        component: require('./components/ActivityPageComponent.vue')
    },
    '/activities/#/add': {
        component: require('./components/NewActivityComponent.vue')
    },
    '/graphs': {
        component: require('./components/GraphsPageComponent.vue')
    }
});

router.redirect({
    '/': '/welcome'
});

var App = Vue.component('app', require('./components/AppComponent'));

router.start(App, 'body');