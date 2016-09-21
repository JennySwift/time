
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

var Vue = require('vue');
global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
global.store = require('./repositories/Store');
var VueResource = require('vue-resource');
Vue.use(VueResource);
require('./config.js');
global.helpers = require('./repositories/Helpers');
global.filters = require('./repositories/FiltersRepository');
Date.setLocale('en-AU');

//Shared components
Vue.component('navbar', require('./components/shared/NavbarComponent.vue'));
Vue.component('feedback', require('@jennyswift/feedback'));
Vue.component('loading', require('./components/shared/LoadingComponent.vue'));
Vue.component('popup', require('./components/shared/PopupComponent.vue'));
Vue.component('autocomplete', require('@jennyswift/vue-autocomplete'));

Vue.component('date-navigation', require('./components/shared/DateNavigationComponent.vue'));
Vue.component('buttons', require('./components/shared/ButtonsComponent.vue'));
Vue.component('input-group', require('./components/shared/InputGroupComponent.vue'));
Vue.component('checkbox-group', require('./components/shared/CheckboxGroupComponent.vue'));

//Components
Vue.component('new-timer', require('./components/NewTimerComponent.vue'));

//Transitions
Vue.transition('fade', require('./transitions/FadeTransition'));

require('./routes');





