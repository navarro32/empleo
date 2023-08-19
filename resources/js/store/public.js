import Vue from 'vue'
import Vuex from 'vuex'
import { reject } from 'lodash'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
    data: [],
    loading: true,
    settings: {
      current_page: 1,
      per_page: 14,
      to: 0,
      total: 0
    },
    busquedad: null,
    complete: false,
    infinitieId:'',
    buscando: true
  },
  mutations: {
    limpiarBusquedad(state){
      state.data=[]
      state.settings.current_page = 1
      state.complete=false
    },
    updateBusquedad(state, payload) {
      state.busquedad = payload;
      state.data=[]
      state.settings.current_page = 1
      state.complete=false
    },
  },
  actions: {
    buscar(state) {      
      return new Promise((resolve, reject) => {
        let estado=false;   
        this.state.buscando=true    
          axios
            .post(`/buscar?page=${this.state.settings.current_page}&number=${this.state.settings.per_page}`, state.state.busquedad)
            .then((response) => {
              this.state.buscando=false
              if (response.data.code == 200) {
                this.state.data = this.state.data.concat(response.data.response.data);
                this.state.settings.to = response.data.response.to;
                this.state.settings.total = response.data.response.total;
                this.state.loading = false
                if (response.data.response.next_page_url) {
                  this.state.complete = false
                  this.state.settings.current_page++;
                  estado=true;
                } else if (!response.data.response.next_page_url) {
                  this.state.complete = true
                  estado=true
                }
              }
              resolve(estado)
            })
            .catch(function (error) {
              console.log(error);
            });    
        
      });    

    },
    aplicar({ commit, state }, id=null){
      if(id!==null){
        axios
            .post(`/aplicar`, {id})
            .then((response) => {
              console.log(response)
            })
            .catch(error=> {
              if (error.response && error.response.status === 401) {
                localStorage.setItem('redirectPath', id);                
                window.location.replace("/personas/login?id="+id);
              }
            });
      }
    }
  }
})