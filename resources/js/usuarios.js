/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import CKEditor from '@ckeditor/ckeditor5-vue';


// USO DE VUETIFY

import Vuetify from 'vuetify';
import Vuelidate from 'vuelidate';
import VueRouter from 'vue-router';
import Vuesax from 'vuesax';

import 'vuesax/dist/vuesax.css';
Vue.use(Vuesax, {
    // options here
})


Vue.use(VueRouter)
Vue.use(Vuetify);
Vue.use(Vuelidate);
Vue.use(CKEditor);
import menutoolbar from './components/usuarios/menu.vue'

// Menu de usuario
import Usuario from './components/usuarios/views/editarUsuario.vue'
import ChangePass from './components/usuarios/views/cambiarPass.vue'
import ChangeAvatar from './components/usuarios/views/cambiarAvatar.vue'
import error404 from './components/views/404.vue'
import Home from './components/usuarios/views/Home'
// menu de empresa

import Estadisticas from './components/usuarios/views/estadisticas.vue'

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
            path: '/cambiarAvatar',
            name: 'avatarchange',
            component: ChangeAvatar,
        },

        {
            path: '*',
            component: error404
        }
    ],
});

const store = new Vuex.Store({
    state: {
        avatar: '',
        editable: true,
        experinew: false,
        studynew: false,
        deleted: {
            state: false,
            mensaje: '',
            type : ''
        },
        dataUser: {
            perfil:{
                movilidad:false,
                profesion: '',
                experiencia: '',
                aspiracion: '',
                descripcion: ''
            }
        }
    },    
    mutations: {
        setAvatar(state, payload) {
            state.avatar = payload
        },
        setdataUser(state, payload) {
            state.dataUser = payload
        },
        setEditable(state, payload) {
            state.editable = payload
        },
        setAddexperi(state) {
            state.experinew = !state.experinew
        },
        setAddstudy(state) {
            state.studynew = !state.studynew
        },
        setDeleted(state, payload) {
            state.deleted = payload
        },
        setPerfil(state, payload){
            state.dataUser.perfil = payload
        },

    },
    actions: {
        setAvatar(state, payload) {
            state.commit('setAvatar', payload)
        },
        setEditable(state, payload) {
            state.commit('setEditable', payload)
        },
        showMessage(state, data) {
            const noti = app.$vs.notification({
                progress: "auto",
                square: true,
                color: data.color,
                position: data.position,
                title: data.title,
                duration: data.duration,
                text: data.text
            });
        },        
        getDatos(state) {
            axios
                .get("/getUser")
                .then(response => {
                    this.state.dataUser = response.data;
                    if (response.data.datos_basicos == null) {
                        this.state.dataUser.datos_basicos = {
                            telefono: "",
                            celular: "",
                            documento: "",
                            fecha_nacimiento: "",
                            genero: "",
                            estado_civil: "",
                            departamento: "",
                            ciudad: "",
                            direccion: "",
                            ruta_avatar: ""
                        };
                    }               
                    
                    this.state.avatar = this.state.dataUser.datos_basicos.ruta_avatar
                })
                .catch(function(error) {
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });
        },
    }
});



const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router,
    store,
    data: { loading: false },
    components: {
        menutoolbar,
    },
    mounted() {
        this.$store.dispatch("getDatos");
    },
});



router.beforeEach((to, from, next) => {
    app.loading = true
    next()
})

router.afterEach((to, from) => {
    app.loading = false
})