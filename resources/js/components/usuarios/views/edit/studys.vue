<template>
  <div>
    <v-hover
        v-slot:default="{ hover }"
        open-delay="100"
      >
    <v-card class="mx-auto mt-5" outlined :elevation="hover ? 16 : 2">
      <v-card-title><h3 class="titulos_tarjetas w-100 text-center">Estudios</h3></v-card-title>
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
                @click="$store.commit('setAddstudy')"
              >
                <v-icon>mdi-pen-plus</v-icon>
              </v-btn>
            </template>
            <span>Agregar Estudio</span>
          </v-tooltip>
          <v-expansion-panel v-bind:key="item.id" v-for="(item, i) in datos">
            <v-expansion-panel-header v-slot="{ open }">
              <v-row no-gutters>
                <v-col cols="12" md="7">
                  {{ item.institucion }}
                </v-col>
                <v-col cols="12" md="5" class="text--secondary">
                  <v-fade-transition leave-absolute>
                    <p v-if="open" class="titulo_header_panel mb-0">
                      Tiempo estudiado
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
                <span>Eliminar Estudio</span>
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
                <span>Editar Estudio</span>
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
                <span>Editar estudio</span>
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
                    v-model="item.institucion"
                    :counter="255"
                    label="Institución"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-text-field
                    v-else
                    v-model="data.institucion"
                    :counter="255"
                    label="Institución"
                  ></v-text-field>
                </div>
                <div class="col-12 col-md-6 pt-0 pb-0">
                  <v-text-field
                    v-if="readonly"
                    v-model="item.titulo"
                    :counter="255"
                    label="Titulo"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-text-field v-else v-model="data.titulo" label="Titulo"></v-text-field>
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
                    v-if="readonly"
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
                    v-model="item.estado"
                    :counter="255"
                    label="Estado"
                    :readonly="readonly"
                  ></v-text-field>
                  <v-select
                    v-else                    
                    v-model="data.estado"
                    hint="Estado del estudio"
                    :items="estado"
                    label="Estado"
                    persistent-hint
                    single-line
                    required
                    :rules="[() => !!data.estado || 'El estado es requerido']"
                    :error-messages="errorMessages"
                  ></v-select>
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
              @click="$store.commit('setAddstudy')"
            >
              <v-icon>mdi-pen-plus</v-icon>
            </v-btn>
          </template>
          <span>Agregar estudio</span>
        </v-tooltip>
        <p class="text-center mt-3">No tienes estudios agregados...</p>
      </v-card-text>
      <addstudy @added="getDatos()"></addstudy>
      <deletecorfirm @estudios="onClickChild"></deletecorfirm>
    </v-card>
    </v-hover>
  </div>
</template>

<script>
import addstudy from "./addstudy";
import deletecorfirm from "../../../web/deletecorfirm.vue";
export default {
  components: {
    addstudy,
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
        institucion: "",
        titulo: "",
        estado: "",
        ciudad: "",
        fecha_inicio: "",
        fecha_fin: ""
      },
      estado: ["Culminado", "En curso", "Abandonado", "Aplazado"],
      maxDate: new Date().toISOString().substr(0, 10),
      hoy: new Date().toISOString().substr(0, 10),
      panelIndex: -9
    };
  },
  mounted() {
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
        .get("/getStudys")
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
    guardar() {
      this.isLoading = true;
      let datos = this.data;      
      
      axios
        .post("/uddateStudy", datos)
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
    eliminar(id) {
      let deleted = {
        state: true,
        mensaje: "Estas seguro de eliminar el estudio?",
        id: id,
        ruta: "/deleteStudy",
        wait: true,
        type:'estudios'
      };
      this.$store.commit("setDeleted", deleted);
    },
    changepanel(id) {
      if (!this.readonly && id !== undefined) {
        this.cargarData(id);
      }
    },
    onClickChild(value) {
      this.getDatos();
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
