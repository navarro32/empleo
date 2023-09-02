/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import CKEditor from '@ckeditor/ckeditor5-vue';

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
// Vue.component('app', require('./components/App.vue'));
// Vue.component('empresas', require('./components/empresas/home.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// USO DE VUETIFY

import Vuetify from 'vuetify';
import Vuelidate from 'vuelidate';
import VueRouter from 'vue-router'
Vue.use(VueRouter)
Vue.use(Vuetify);
Vue.use(Vuelidate);
Vue.use(CKEditor);
import menutoolbar from './components/empresas/menu.vue'

// vue router

// importamos los componentes

// import App from './views/App'
// Menu de usuario
import Usuario from './components/empresas/views/editarUsuario.vue'
import ChangePass from './components/empresas/views/cambiarPass.vue'
import error404 from './components/views/404.vue'
import Home from './components/empresas/views/Home'
// menu de empresa

import Estadisticas from './components/empresas/views/estadisticas.vue'
import nuevaOferta from './components/empresas/views/newOfert.vue'
import ofertas from './components/empresas/views/ofertas.vue';
// import Venta from './views/Venta'

import Vuex from 'vuex'

Vue.use(Vuex)

window.EventBus = new Vue;

const router = new VueRouter({
    // mode: 'history',
    routes: [{
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/editarPerfil',
            name: 'usuario',
            component: Usuario,
        },
        {
            path: '/cambiarClave',
            name: 'pass',
            component: ChangePass
        },
        {
            path: '/Estadisticas',
            name: 'estadisticas',
            component: Estadisticas,
        },
        {
            path: '/Ofertas',
            name: 'ofertas',
            component: ofertas,
        },
        {
            path: '/nuevaOferta',
            name: 'nuevaOferta',
            component: nuevaOferta,
        },
        {
            path: '*',
            component: error404
        }
    ],
});

const store = new Vuex.Store({
    state: {
        empresa: null,
        dialog: false,
    },
    getters: {

    },
    mutations: {
        setEmpresa(state, payload) {
            state.empresa = payload.id
        },
        setDialog(state, payload) {
            state.dialog = payload
        }
    },
    actions: {
        setDialog(state, payload) {
            state.commit('setDialog', payload)
        }
    }
});
const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router,
    store,
    components: {
        menutoolbar,
    }
});