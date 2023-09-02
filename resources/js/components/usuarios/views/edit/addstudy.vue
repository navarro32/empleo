<template>
  <v-row justify="center">
    <v-dialog v-model="$store.state.studynew" persistent max-width="1200px">
      <v-card style="position:relative">
        <v-icon
          @click="$store.commit('setAddstudy')"
          style="position:absolute; top:10px; right:10px"
          class="btn-close"
        >mdi-close</v-icon>

        <v-card-title>
          <h5>Añadir Estudio</h5>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" :lazy-validation="lazy">
            <v-row>
              <v-col cols="12" sm="12" md="6">
                <v-text-field
                  class="pt-0 mt-0"
                  v-model="data.institucion"
                  label="Institución"
                  autocomplete="off"
                  hint="Institución donde realizo sus estudios"
                  prepend-icon="domain"
                  required
                  :rules="[
                          () => !!data.institucion || 'La Institución es requerida',
                          () => !!data.institucion && data.institucion.length <=255 || 'La Institución no puede tener mas de 255 caracteres'
                          ]"
                  :error-messages="errorMessages"
                >
                  <template v-slot:label>
                    Institución
                    <sup style="color:red">*</sup>
                  </template>
                </v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-text-field
                  class="pt-0 mt-0"
                  v-model="data.titulo"
                  label="Titulo obtenido"
                  autocomplete="off"
                  hint="Nombre del titulo obtenido"
                  prepend-icon="mdi-book-open-outline"
                  required
                  :rules="[
                          () => !!data.titulo || 'El titulo es requerido',
                          () => !!data.titulo && data.titulo.length <=255 || 'El titulo no puede tener mas de 255 caracteres'
                          ]"
                  :error-messages="errorMessages"
                >
                  <template v-slot:label>
                    Titulo obtenido
                    <sup style="color:red">*</sup>
                  </template>
                </v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-select
                  class="pt-0 mt-0"
                  v-model="data.estado"
                  hint="Estado del estudio"
                  :items="estado"
                  prepend-icon="mdi-format-list-checkbox"
                  label="Estado"
                  persistent-hint
                  single-line
                  required
                  :rules="[() => !!data.estado || 'El estado es requerido']"
                  :error-messages="errorMessages"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-autocomplete
                  class="pt-0 mt-0"
                  v-model="data.ciudad"
                  :search-input.sync="ciudad"
                  :items="ciudades"
                  :loading="isLoading"
                  item-text="ciudad"
                  item-value="id"
                  label="Ciudad"
                  placeholder="Busca la ciudad"
                  prepend-icon="mdi-database-search"
                  hide-no-data
                  clearable
                  required
                  :rules="[() => !!data.ciudad || 'La ciudad es requerida']"
                  :error-messages="errorMessages"
                >
                  <template
                    slot="selection"
                    slot-scope="data"
                  >{{ data.item.ciudad }} - {{ data.item.departamento}}</template>
                  <template v-slot:label>
                    Ciudad
                    <sup style="color:red">*</sup>
                  </template>
                </v-autocomplete>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-menu
                  v-model="date1"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      class="pt-0 mt-0"
                      v-model="data.fecha_inicio"
                      label="Fecha de Inicio"
                      prepend-icon="event"
                      readonly
                      v-on="on"
                      required
                    :rules="[() => !!data.fecha_inicio || 'La fecha inicial es requerida']"
                    :error-messages="errorMessages"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    :max="maxDate"
                    locale="es-co"
                    v-model="data.fecha_inicio"
                    @input="date1 = false"
                    
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-menu
                  v-model="date2"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      class="pt-0 mt-0"
                      v-model="data.fecha_fin"
                      label="Fecha de Finalización"
                      prepend-icon="event"
                      readonly
                      v-on="on"
                      required
                    :rules="[() => !!data.fecha_fin || 'La fecha final es requerida']"
                    :error-messages="errorMessages"
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
              </v-col>
              <v-col cols="12" class="text-right">
                <v-btn color="success" rounded small  @click="save()">Guardar</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      isLoading: false,
      data: {
        institucion: "",
        titulo: "",
        estado: "",
        ciudad: "",
        fecha_inicio: "",
        fecha_fin: ""
      },
      valid: true,
      lazy: false,
      estado: ["Culminado", "En curso", "Abandonado", "Aplazado"],
      errorMessages: "",
      e1: 1,
      ciudad: "",
      sectores: [],
      subsectores: [],
      ciudades: [],
      date1: false,
      date2: false,
      hoy: new Date().toISOString().substr(0, 10),
      maxDate: new Date().toISOString().substr(0, 10)
    };
  },
  mounted() {
    // this.getSectores();
  },
  watch: {
    data: function(newValue, oldValue) {
      this.validaform1();
      this.validaform2();
    },
    "data.sector": function(newValue, oldValue) {
      this.data.subsector = null;
      this.$refs.subsectores.internalSearch = null;
      this.getSubsectores(newValue);
    },
    "data.fecha_fin": function(newValue, oldValue) {
      this.maxDate = newValue;
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
      axios
        .post("/buscarCiudad", {
          valor: newValue
        })
        .then(response => {
          this.isLoading = false;
          this.ciudades = response.data;
        })
        .catch(error => {
          this.isLoading = false;
        });
    }
  },
  methods: {
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
    save() {
      if(!this.$refs.form.validate()){
        let notify = {
          color: "danger",
          position: "top-right",
          duration: "6000",
          title: "Error",
          text: 'Por favor revisa los campos requeridos'
        };
        this.$store.dispatch("showMessage", notify);
        return false
      }
      this.isLoading = true;
      axios
        .post("/saveStudy", this.data)
        .then(response => {
          if (response.data.tipo == 1) {
            let notify = {
              color: "success",
              position: "top-right",
              duration: "6000",
              title: "Exito",
              text: response.data.mensaje
            };
            this.$store.dispatch("showMessage", notify);
            this.$emit('added')
            this.$store.commit("setAddstudy");
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
    }
  }
};
</script>

<style lang="scss" scoped>
.btn-close:hover {
  color: black;
}
</style>