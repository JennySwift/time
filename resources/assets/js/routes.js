var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.router = new VueRouter({hashbang: false});

router.map({
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
    '/': '/timers'
});

var App = Vue.component('app', require('./components/AppComponent'));

router.start(App, 'body');