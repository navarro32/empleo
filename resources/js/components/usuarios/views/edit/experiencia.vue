<template>
  <div>
    <v-hover
        v-slot:default="{ hover }"
        open-delay="100"
      >
    <v-card class="mx-auto mt-5" outlined :elevation="hover ? 16 : 2">
      <v-card-title><h3 class="titulos_tarjetas w-100 text-center">Experiencias</h3></v-card-title>
      <v-card-text class="text--primary p-0" v-if="datos.length">
        <v-expansion-panels focusable v-model="panelIndex">
          <v-tooltip left>
            <template v-slot:activator="{ on }">
              <v-btn
                fab
                color="primary"
                style="top:-50px;border-radius: 50%;"
                top
                right
                small
                absolute
                v-on="on"
                @click="$store.commit('setAddexperi')"
              >
                <v-icon>mdi-briefcase-plus</v-icon>
              </v-btn>
            </template>
            <span>Agregar experiencia</span>
          </v-tooltip>
          <v-expansion-panel v-bind:key="item.id" v-for="(item, i) in datos">
            <v-expansion-panel-header v-slot="{ open }">
              <v-row no-gutters>
                <v-col cols="12" md="7">
                  {{ item.empresa }}
                </v-col>
                <v-col cols="12" md="5" class="text--secondary">
                  <v-fade-transition leave-absolute>
                    <p v-if="open" class="titulo_header_panel mb-0">
                      Tiempo trabajado
                      {{ item.tiempo }} meses.
                    </p>
                    <v-row v-else no-gutters style="width: 100%">
                      <v-col cols="6" class="titulo_header_panel">
                        <b>Inicio:</b>
                        {{ item.fecha_inicio || "" }}
                      </v-col>
                      <v-col cols="6" class="titulo_header_panel">
                        <b v-if="item.fecha_fin != ''">Fin:</b>
                        {{ item.fecha_fin || "Actualidad" }}
                      </v-col>
                    </v-row>
                  </v-fade-transition>
                </v-col>
              </v-row>
            </v-expansion-panel-header>
            <v-expansion-panel-content style="position:relative">
              <v-tooltip left>
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    color="error"
                    style="border-radius: 50%;"
                    top
                    right
                    small
                    absolute
                    v-on="on"
                    @click="eliminar(item.id)"
                  >
                    <v-icon>mdi-trash-can-outline</v-icon>
                  </v-btn>
                </template>
                <span>Eliminar experiencia</span>
              </v-tooltip>

              <v-tooltip right>
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    color="primary"
                    style="border-radius: 50%;"
                    top
                    left
                    small
                    absolute
                    v-on="on"
                    @click="readonly = !readonly"
                  >
                    <v-icon>mdi-file-edit-outline</v-icon>
                  </v-btn>
                </template>
                <span>Editar experiencia</span>
              </v-tooltip>

              <v-tooltip right v-if="readonly">
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    color="primary"
                    style="border-radius: 50%;"
                    top
                    left
                    small
                    absolute
                    v-on="on"
                    @click="readonly = !readonly; cargarData(i)"
                  >
                    <v-icon>mdi-file-edit-outline</v-icon>
                  </v-btn>
                </template>
                <span>Editar experiencia</span>
              </v-tooltip>
              <v-tooltip right v-else>
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    color="success"
                    style="border-radius: 50%;"
                    top
                    left
                    small
                    absolute
                    v-on="on"
                    @click="guardar(); "
                  >
                    <v-icon>mdi-content-save</v-icon>
                  </v-btn>
                </template>
                <span>Guardar cambios</span>
              </v-tooltip>

              <v-form ref="form" lazy-validation class="row pt-3">
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.empresa"
                    :counter="255"
                    label="Empresa"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-text-field v-else v-model="data.empresa" :counter="255" label="Empresa"></v-text-field>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.cargo"
                    :counter="255"
                    label="Cargo"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-text-field v-else v-model="data.cargo" label="Empresa"></v-text-field>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.sector"
                    :counter="255"
                    label="Sector"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-autocomplete
                    v-else
                    v-model="data.sectorselected"
                    :items="sectores"
                    :loading="isLoading"
                    item-text="sector"
                    item-value="id"
                    :value="item.sectorselected"
                    label="Sector"
                    placeholder="Digita el sector"
                    clearable
                    hide-no-data
                    required
                    @change="getSubsectores(item.sectorselected)"
                    :rules="[() => !!data.sector || 'El sector es requerido']"
                    :error-messages="errorMessages"
                  >
                    <template v-slot:label>
                      Sector
                      <sup style="color:red">*</sup>
                    </template>
                  </v-autocomplete>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.subsector"
                    :counter="255"
                    label="SubSector"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-autocomplete
                    v-else
                    ref="subsectores"
                    v-model="data.subsectorselected"
                    :items="subsectores"
                    :loading="isLoading"
                    item-text="subsector"
                    item-value="id"
                    label="SubSector"
                    placeholder="Digita el subsector"
                    clearable
                    hide-no-data
                    required
                    :rules="[() => !!data.subsector || 'El subsector es requerido']"
                    :error-messages="errorMessages"
                  >
                    <template v-slot:label>
                      SubSector
                      <sup style="color:red">*</sup>
                    </template>
                  </v-autocomplete>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.fecha_inicio"
                    label="Fecha de Inicio"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-menu
                    v-else
                    v-model="date1"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="data.fecha_inicio"
                        label="Fecha de Inicio"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      :max="maxDate"
                      locale="es-co"
                      v-model="data.fecha_inicio"
                      @input="date1 = false"
                    ></v-date-picker>
                  </v-menu>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly && item.trabajo_aqui==0"
                    v-model="item.fecha_fin"
                    label="Fecha de Finalización"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-menu
                    v-else-if="!readonly && !data.trabajo_aqui"
                    v-model="date2"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="data.fecha_fin"
                        label="Fecha de Finalización"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      :min="data.fecha_inicio"
                      :max="hoy"
                      locale="es-co"
                      v-model="data.fecha_fin"
                      @input="date2 = false"
                    ></v-date-picker>
                  </v-menu>
                </div>
                <div class="col-12">
                  <label for>Trabajo Aquí</label>
                  <v-switch
                    v-if="readonly"
                    class="mt-0 pt-0"
                    v-model="item.trabajo_aqui"
                    :readonly="readonly"
                  ></v-switch>
                  <v-switch v-else class="mt-0 pt-0" v-model="data.trabajo_aqui"></v-switch>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.ciudad"
                    :counter="255"
                    label="Ciudad"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-autocomplete
                    v-else
                    v-model="data.ciudad"
                    :search-input.sync="ciudad"
                    :items="ciudades"
                    :loading="isLoading"
                    item-text="ciudad"
                    item-value="id"
                    label="Ciudad"
                    placeholder="Busca la ciudad"
                    hide-no-data
                    clearable
                    required
                    :rules="[() => !!data.ciudad || 'La ciudad es requerida']"
                    :error-messages="errorMessages"
                  ></v-autocomplete>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.telefono"
                    :counter="255"
                    label="Teléfono de la empresa"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-text-field
                    v-else
                    v-model="data.telefono"
                    :counter="255"
                    label="Teléfono de la empresa"
                    :readonly="readonly"
                  ></v-text-field>
                </div>

                <div class="col-12 pt-0 pb-0">
                  <v-textarea
                    v-if="readonly"
                    :readonly="readonly"
                    label="Responsabilidades"
                    auto-grow
                    v-model="item.responsabilidades"
                  ></v-textarea>
                  <v-textarea
                    v-else
                    label="Responsabilidades"
                    auto-grow
                    v-model="data.responsabilidades"
                  ></v-textarea>
                </div>
              </v-form>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card-text>
      <v-card-text v-else style="position:relative">
        <v-tooltip left>
          <template v-slot:activator="{ on }">
            <v-btn
              fab
              color="primary"
              style="border-radius: 50%;"
              top
              right
              small
              absolute
              v-on="on"
              @click="$store.commit('setAddexperi')"
            >
              <v-icon>mdi-briefcase-plus</v-icon>
            </v-btn>
          </template>
          <span>Agregar experiencia</span>
        </v-tooltip>
        <p class="text-center mt-3">No tienes experiencias registradas...</p>
      </v-card-text>
      <addexperiencia @added="getDatos()"></addexperiencia>
      <deletecorfirm @experiencia="onClickChild"></deletecorfirm>
    </v-card>  
    </v-hover>  
  </div>
</template>

<script>
import addexperiencia from "./addexperi.vue";
import deletecorfirm from '../../../web/deletecorfirm.vue';
export default {
  components: {
    addexperiencia,
    deletecorfirm
  },
  data() {
    return {          
      editable: false,
      datos: [],
      sectores: [],
      subsectores: [],
      ciudad: "",
      ciudades: [],
      isLoading: false,
      readonly: true,
      errorMessages: "",
      date1: false,
      date2: false,
      data: {
        empresa: "",
        sector: "",
        subsector: "",
        start_end: "",
        end_end: "",
        trabaja: false,
        cargo: "",
        ciudad: "",
        telefono: null,
        responsabilidades: ""
      },
      maxDate: new Date().toISOString().substr(0, 10),
      hoy: new Date().toISOString().substr(0, 10),
      panelIndex: -9
    };
  },
  mounted() {
    this.getSectores();
    this.getDatos();
  },
  watch: {
    "data.fecha_fin": function(newValue, oldValue) {
      this.maxDate = newValue;
    },
    panelIndex: function() {
      this.changepanel(this.panelIndex);
    },
    ciudad: function(newValue, oldValue) {
      if (newValue !== null && newValue !== undefined) {
        if (newValue.length < 3) {
          return false;
        }
      } else {
        return false;
      }

      this.isLoading = true;

      // Lazily load input items
      this.buscarCiudad(newValue);
    }
  },
  methods: {
    
    getDatos() {
      axios
        .get("/getExperience")
        .then(response => {
          this.datos = response.data;
        })
        .catch(function(error) {
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    cargarData(id) {
      this.data = this.datos[id];
      this.getSubsectores(this.datos[id].sectorselected.id);
      // this.ciudad = this.datos[id].ciudad;
      this.buscarCiudad(this.datos[id].ciudad).then(res => {
        if (res.length > 0) {
          this.data.ciudad = res[0].id;
        }
      });
    },
    buscarCiudad(ciudad) {
      return new Promise((resolve, reject) => {
        axios
          .post("/buscarCiudad", {
            valor: ciudad
          })
          .then(response => {
            this.isLoading = false;
            this.ciudades = response.data;
            resolve(response.data);
          })
          .catch(error => {
            this.isLoading = false;
          });
      });
    },
    getSectores() {
      axios
        .get("/getSectores")
        .then(response => {
          this.sectores = response.data;
        })
        .catch(function(error) {
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    getSubsectores(val) {
      this.isLoading = true;
      axios
        .post("/getSubsectores", {
          id: val
        })
        .then(response => {
          if (response.data.tipo == 5) {
            EventBus.$emit("session");
          } else {
            this.subsectores = response.data;
          }
        })
        .catch(function(error) {
          console.log(error);
        })
        .finally(() => (this.isLoading = false));
    },
    guardar() {
      this.isLoading = true;
      let datos = this.data;
      console.log(datos);
      datos.sector = datos.sectorselected.id;
      datos.subsector = datos.subsectorselected.id;
      datos.trabaja = datos.trabajo_aqui;
      axios
        .post("/uddateExpe", datos)
        .then(response => {
          if (response.data.tipo == 1) {
            let notify = {
              color: "success",
              position: "top-right",
              duration: "6000",
              title: "Exito",
              text: response.data.mensaje
            };
            this.readonly = !this.readonly;
            this.$store.dispatch("showMessage", notify);
            this.getDatos();
          } else {
            let notify = {
              color: "danger",
              position: "top-right",
              duration: "6000",
              title: "Error",
              text: response.data.mensaje
            };
            this.$store.dispatch("showMessage", notify);
          }
        })
        .catch(function(error) {
          console.log(error);
        })
        .finally(() => (this.isLoading = false));
    },
    eliminar(id){        
        let deleted = {
            state:true,
            mensaje:'Estas seguro de eliminar la experiencia?',
            id:id,
            ruta:'/deleteExp',
            wait:true,
            type:'experiencia'
        }      
        this.$store.commit("setDeleted", deleted);    

             
    },
    changepanel(id) {
      if (!this.readonly && id !== undefined) {
        this.cargarData(id);
      }
    },
    onClickChild (value) {
      this.getDatos()
    }
  }
};
</script>

<style lang="scss" scoped>
@media screen and (max-width: 768px) {
  .titulo_header_panel {
    margin-top: 10px;
  }
}
.bounce-enter-active {
  animation: bounce-in 0.5s;
}
.bounce-leave-active {
  animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.5);
  }
  100% {
    transform: scale(1);
  }
}
</style>
