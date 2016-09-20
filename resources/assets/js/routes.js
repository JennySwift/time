var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.router = new VueRouter({hashbang: false});

router.map({
    '/timers': {
        component: require('./components/TimersPageComponent'),
        subRoutes: {
            //'/': {
            //    component: TimersPage
            //},
            '/new-manual': {
                component: require('./components/NewManualTimerComponent')
            }
        }
    },
    '/activities': {
        component: require('./components/ActivitiesPageComponent')
    },
    '/graphs': {
        component: require('./components/GraphsPageComponent')
    }
});

router.redirect({
    '/': '/timers'
});

var App = Vue.component('app', require('./components/AppComponent'));

router.start(App, 'body');