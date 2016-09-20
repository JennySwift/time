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
    '/activities': {
        component: require('./components/ActivitiesPageComponent.vue')
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