<template>
  <div>
    <form>
      <div class="row fondo_azul">
        <div class="col-12 col-sm-12 col-md-8 pb-0 mb-0 pt-0 mt-0">
          <v-text-field
            :value="propsCampo"
            v-model="campo"
            autocapitalize="off"
            autocomplete="off"
            name="campo"
            solo
            class="col-12 mt-3"
            label="Busca tu empleo deseado..."
            clearable
            @keyup="limpiarUrl()"
          ></v-text-field>
        </div>
        <div class="col-12 col-sm-12 col-md-4 pb-0 mb-0 pt-0 mt-0">
          <v-select
            v-model="depart"
            :items="departamentos"
            placeholder="Departamento"
            multiple
            chips
            item-text="departamento"
            item-value="id"
            hint="Seleccione el departamento"
            background-color="white"
            return-object
            no-data-text
            @change="search(true)"
          >
            <template v-slot:prepend-item>
              <v-list-item ripple @click="toggle();">
                <v-list-item-action>
                  <v-icon :color="depart.length > 0 ? 'indigo darken-4' : ''">{{ icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>Seleccionar todos</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-divider class="mt-2"></v-divider>
            </template>
            <template v-slot:selection="{ item, index }">
              <v-chip v-if="index === 0 || index === 1">
                <span>{{ item.departamento }}</span>
              </v-chip>
              <span v-if="index === 2" class="grey--text caption">
                (+{{ depart.length - 2 }}
                <span v-if="(depart.length - 2)==1">Seleccionado</span>
                <span v-else>Seleccionados</span> )
              </span>
            </template>
          </v-select>
        </div>
        <div class="col-12 pt-0 mt-0">
          <v-radio-group row v-model="tipo_bus" @change="search(true)">
            <v-radio label="Una Palabra" value="1"></v-radio>
            <v-radio label="Búsqueda exacta" value="2"></v-radio>
          </v-radio-group>
        </div>
      </div>
    </form>
    <div class="resultados row">
      <div class="d-none d-lg-block col-lg-3 px-0">
        <v-btn depressed block elevation="3" class="mb-3" @click="limpiarFiltros(true)">Limpiar Filtros</v-btn>
        <v-expansion-panels v-if="filtros">
          <v-expansion-panel v-for="(item,i) in filtros" :key="i">
            <v-expansion-panel-header>{{item.name}}</v-expansion-panel-header>
            <v-expansion-panel-content>
              <template>
                <div class="row" v-if="item.type=='check'">
                  <div class="col-12" v-for="(campo,j) in item.campos" :key="j">
                    <v-switch
                      class="p-0 m-0"
                      v-model="item.campo"
                      multiple
                      :label="campo.valor"
                      :value="campo.id"
                      hide-details
                      @change="search(true)"
                    ></v-switch>
                  </div>
                </div>
                <div class="row" v-if="item.type=='range'">
                  <div class="col-12">
                    <v-range-slider
                      v-model="item.campo"
                      thumb-label
                      :tick-labels="item.campos"
                      tick-size="1"
                      max="20"
                      thumb-color="blue"
                      @change="search(true)"
                    >
                      <template v-slot:thumb-label="props">{{props.value+1}}</template>
                    </v-range-slider>
                    <template
                      v-if="item.campos[item.campo[0]].valor==item.campos[item.campo[1]].valor"
                    >
                      <b>{{ item.campos[item.campo[0]].valor }}</b>
                    </template>
                    <template v-else>
                      <b>{{ item.campos[item.campo[0]].valor }}</b> -
                      <b>{{ item.campos[item.campo[1]].valor }}</b>
                    </template>
                  </div>
                </div>
              </template>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </div>
      
        <list ref="lista" :props-data="infinitieId"></list>
      
    </div>
  </div>
</template>

<script>

import { mapActions } from "vuex";
import list from './list';
export default {
  props: ["propsCampo"],
  components: {
    list,
  },
  data() {
    return {
      departamentos: [],
      depart: [],
      tipo_bus: "1",
      empleos: [],
      campo: "",
      filtros: [],
      total:0,
      infinitieId: +new Date(),
    };
  },
  computed: {
    likesAllFruit() {
      return this.depart.length === this.departamentos.length;
    },
    likesSomeFruit() {
      return this.depart.length > 0 && !this.likesAllFruit;
    },
    icon() {
      if (this.likesAllFruit) return "mdi-close-box";
      if (this.likesSomeFruit) return "mdi-minus-box";
      return "mdi-checkbox-blank-outline";
    },
  },
  watch: {
    depart(newValue, oldValue) {
      sessionStorage.departamentos = JSON.stringify(newValue);
    },
    tipo_bus(newValue, oldValue){
      sessionStorage.tipo_bus = newValue
    }
  },
  mounted() {
    this.$store.state.infinitieId=this.infinitieId
    this.tipo_bus = (sessionStorage.tipo_bus)?sessionStorage.tipo_bus:'1';
    this.cargarMenu().then((res) => {
      this.comprobarFiltros().then((res) => {
        this.getDepartamentos().then((res) => {
          this.search();
        });
      });
    });
    this.campo = this.propsCampo != "" ? this.propsCampo : "";
  },

  methods: {
    ...mapActions(["buscar"]),
    cargarMenu() {
      return new Promise((resolve, reject) => {
        axios.get("../../storage/filtros.json").then((response) => {
          this.filtros = response.data;
          resolve();
        });
      });
    },
    getDepartamentos() {
      return new Promise((resolve, reject) => {
        axios
          .get("/getDepartamentos")
          .then((response) => {
            this.departamentos = response.data;
            if (sessionStorage.departamentos) {
              this.depart = JSON.parse(sessionStorage.departamentos);              
            } else {
              this.depart = response.data;
            }
            resolve();
          })
          .catch(function (error) {
            console.log(error);
          });
      });
    },
    toggle() {
      this.$nextTick(() => {
        if (!this.likesAllFruit) {
          this.depart = this.departamentos.slice();
          this.search()
        }else{
          this.depart=[]
        } 
      });
    },
    search(limpiar=false) {
      let campo = this.campo == "" ? this.propsCampo : this.campo;
      let depar =
        this.depart.length === 0 ||
        this.depart.length == this.departamentos.length
          ? "all"
          : this.depart;
      let tipo = this.tipo_bus;
      let busquedad = {
        campo:this.campo,
        depar,
        tipo,
        filtros: this.filtros,
      };
      
      this.updateUrlParameter();
      // ACCION DE IR A BUSCAR
      this.$store.commit('updateBusquedad', busquedad)
      if(limpiar){
        // this.$refs.lista.$refs.InfiniteComponent.stateChanger.reset()
        // this.$refs.lista.$refs.InfiniteComponent.stateChanger.loaded()
        this.infinitieId += 1;
      }else{
        setTimeout(() => {
          this.buscar()
      }, 500);
       
      }
      
      
    },
    limpiarFiltros() {
      this.cargarMenu();
      let url = window.location.href.split("?");
      let params = url[1].split("&");
      let new_params = params[0];
      let newUrl = `${url[0]}?${new_params}`;
      history.pushState(null, "", "?" + new_params);
      this.depart = this.departamentos
      setTimeout(() => {
        this.search()
      }, 100);
    },
    updateUrlParameter() {
      let url = window.location.href.split("?");
      if (url.length == 2) {
        let params = url[1].split("&");
        let filtros = this.filtros
          .map((fil) => {
            let filtros = "";
            if (fil.campo.length > 0) {
              filtros = `${fil.field}=${fil.campo.join()}`;
            }
            return filtros;
          })
          .filter((fil) => fil != "");
        if (filtros.length > 0) {
          let new_params = params[0] + "&" + filtros.join("&");
          let newUrl = `${url[0]}?${new_params}`;          
          history.pushState(null, "", "?" + new_params);
          
        }
      }
    },
    comprobarFiltros() {
      return new Promise((resolve, reject) => {
        let url = window.location.href.split("?");
        const filtrosUrl = url[1].split("&");
        filtrosUrl.forEach((element) => {
          const fil = element.split("=");
          if (fil[0] && fil[1]) {
            this.filter(fil[0], fil[1]);
          }
        });
        resolve();
      });
    },

    filter(value, insert) {
      this.filtros = this.filtros.map((option, key) => {
        if (option.field == value) {
          option.campo = insert.split`,`.map((x) => +x);
        }
        return option;
      });
    },
    limpiarUrl(){
      const url = new URL(window.location.href);
      // Obtén el valor actual del parámetro 'param1'
      // const currentValue = url.searchParams.get('campo');

      // Cambia el valor del parámetro 'param1' a 'newvalue'
      url.searchParams.set('campo', this.campo);

      // Obtén la nueva URL con el parámetro modificado
      const newUrl = url.toString();

      // Cambia la URL actual a la nueva URL con el parámetro modificado
      window.history.replaceState({}, '', newUrl);
      this.search()
    }
  },
};
</script>

<style lang="scss" scoped>
.fondo_azul {
  background-color: #d1dbff;
}

</style>