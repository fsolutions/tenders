/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// var $  = require( 'jquery' );
// var dt = require( 'datatables.net' )( window, $ );

import store from './store.js'

const moment = require('moment')
require('moment/locale/ru')

Vue.use(require('vue-moment'), {
    moment
})

Vue.prototype.moment = moment

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('pagination', require('laravel-vue-pagination'));
// Vue.component('line-chart', require('./components/Chart.vue').default);
Vue.component('home-component', require('./components/HomeComponent.vue').default);
Vue.component('orders', require('./components/OrderComponent.vue').default);
Vue.component('one-order', require('./components/OneOrderComponent.vue').default);
Vue.component('one-client-order', require('./components/ClientOrderNaryadActComponent.vue').default);
Vue.component('order-create', require('./components/OrderCreate.vue').default);
Vue.component('footer-block', require('./components/Footer.vue').default);


Vue.filter('formattedNamedDate', function (date) {
    let formatDate = moment(date, 'DD.MM.YYYY').format('MMMM YYYY')

    if (formatDate == 'Invalid date') return "Не указана"
    return formatDate
})
Vue.filter('formattedDate', function (date) {
    let formatDate = moment(date).format('DD.MM.YYYY')

    if (formatDate == 'Invalid date') return "Не указана"
    return formatDate
})
Vue.filter('formattedDateTime', function (date) {
    let formatDate = moment(date).format('DD.MM.YYYY HH:mm')

    if (formatDate == 'Invalid date') return "Не указана"
    return formatDate
})

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value
    }
    let formatter = new Intl.NumberFormat('ru-RU', {
        minimumFractionDigits: 0
    })
    return formatter.format(value)
})
Vue.filter('toShortCurrency', function (value) {
    if (typeof value !== "number") {
        return value
    }
    let formatter = new Intl.NumberFormat('ru-RU', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    })
    return formatter.format(value)
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    store,
    el: '#app',
});
