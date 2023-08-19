/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('avatar', require('./components/web/avatar.vue').default);
Vue.component('buscador', require('./components/web/buscador.vue').default);
Vue.component('menu-user', require('./components/web/menu.vue').default);
// Vue.component('InfiniteLoading', require('vue-infinite-loading').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vuetify from 'vuetify';
import swal from 'sweetalert';
import { store } from './store/public.js'
// var VueCookie = require('vue-cookie');
Vue.use(Vuetify);

// Tell Vue to use the plugin
// Vue.use(VueCookie);

const app = new Vue({
    el: '#app',
    data() {
      return {
        collapseOnScroll: true,
        drawer:false,
        activeUser:true
      }
    },
    store,
    vuetify: new Vuetify(),
});
// When the user scrolls the page, execute myFunction
// window.onscroll = function() {myFunction()};

// Get the header
// var header = document.getElementById("menu");

// Get the offset position of the navbar
// var sticky = header.offsetTop;

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
// function myFunction() {
//   if (window.pageYOffset > sticky) {
//     header.classList.add("animate__bounceInDown");
//     header.classList.add("sticky");
//   } else {
//     header.classList.remove("animate__bounceInDown");
//     header.classList.remove("sticky");
//   }
// }