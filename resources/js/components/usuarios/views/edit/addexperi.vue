<template>
  <v-row justify="center">
    <v-dialog v-model="$store.state.experinew" persistent max-width="1200px">
      <v-card style="position:relative">
        <v-icon
          @click="$store.commit('setAddexperi')"
          style="position:absolute; top:10px; right:10px"
          class="btn-close"
        >mdi-close</v-icon>

        <v-card-title>
          <h5>Añadir Experiencia</h5>
        </v-card-title>
        <v-card-text class="pl-0 pr-0 pb-0">
          <v-stepper v-model="e1">
            <v-stepper-header>
              <v-stepper-step :complete="e1 > 1" step="1">Datos de la empresa</v-stepper-step>
              <v-divider></v-divider>
              <v-stepper-step :complete="e1 > 2" step="2">Datos del cargo</v-stepper-step>
            </v-stepper-header>
            <v-stepper-items>
              <v-stepper-content step="1">
                <!-- PASO 1 -->
                <v-row>
                  <v-col cols="12" sm="12" md="12">
                    <v-text-field
                      class="pt-0 mt-0"
                      v-model="data.empresa"
                      label="Empresa"
                      autocomplete='off'
                      hint="Razón social de la empresa en la que trabaja (ó)"
                      prepend-icon="domain"
                      required
                      :rules="[
                          () => !!data.empresa || 'La empresa es requerida',
                          () => !!data.empresa && data.empresa.length <=255 || 'La ciudad no puede tener mas de 255 caracteres'
                          ]"
                      :error-messages="errorMessages"
                    >
                      <template v-slot:label>
                        Empresa
                        <sup style="color:red">*</sup>
                      </template>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-autocomplete
                      class="pt-0 mt-0"
                      v-model="data.sector"
                      :items="sectores"
                      :loading="isLoading"
                      item-text="sector"
                      item-value="id"
                      label="Sector"
                      placeholder="Digita el sector"
                      clearable
                      prepend-icon="home"
                      hide-no-data
                      required
                      :rules="[() => !!data.sector || 'El sector es requerido']"
                      :error-messages="errorMessages"
                    >
                      <template v-slot:label>
                        Sector
                        <sup style="color:red">*</sup>
                      </template>
                    </v-autocomplete>
                  </v-col>
                  <v-col cols="12" sm="6" md="6">
                    <v-autocomplete
                      class="pt-0 mt-0"
                      ref="subsectores"
                      v-model="data.subsector"
                      :items="subsectores"
                      :loading="isLoading"
                      item-text="subsector"
                      item-value="id"
                      label="SubSector"
                      placeholder="Digita el subsector"
                      clearable
                      prepend-icon="home"
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
                    <v-text-field
                      class="pt-0 mt-0"
                      v-model="data.telefono"
                      label="Telefono"
                      hint="Telefono del jefe inmediato"
                      prepend-icon="mdi-cellphone"
                      required
                      pattern="[0-9]"
                      type="number"
                      :rules="[
                          () => !!data.telefono || 'El telefono es requerido',
                          () => !!data.telefono && data.telefono.length <=12 || 'El número de telefono no puede tener mas de 10 números',
                          (v) => /^([0-9])+$/.test(v) || 'El telefono es invalido'
                          ]"
                      :error-messages="errorMessages"
                    >
                      <template v-slot:label>
                        Telefono
                        <sup style="color:red">*</sup>
                      </template>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" class="text-right">
                    <v-btn color="primary" @click="e1 = 2" :disabled="form1" rounded small>Continuar</v-btn>
                  </v-col>
                </v-row>
              </v-stepper-content>
              <v-stepper-content step="2">
                <!-- PASO 2 -->
                <v-row>
                  <v-col cols="12" sm="12" md="4">
                    <v-switch
                      class="pt-0 mt-0 pl-3"
                      v-model="data.trabaja"
                      label="¿Trabaja aquí?"
                      color="primary"
                    ></v-switch>
                  </v-col>
                  <v-col cols="12" sm="6" md="4">
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
                          v-model="data.start_end"
                          label="Fecha de Inicio"
                          prepend-icon="event"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        :max="maxDate"
                        locale="es-co"
                        v-model="data.start_end"
                        @input="date1 = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col cols="12" sm="6" md="4" v-show="!data.trabaja">
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
                          v-model="data.end_end"
                          label="Fecha de Finalización"
                          prepend-icon="event"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        :min="data.start_end"
                        :max="hoy"
                        locale="es-co"
                        v-model="data.end_end"
                        @input="date2 = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>
                  <v-col cols="12" sm="12" md="8">
                    <v-text-field
                      class="pt-0 mt-0"
                      v-model="data.cargo"
                      label="Cargo"
                      hint="Cargo que ocupa (ó) en la empresa"
                      prepend-icon="mdi-briefcase-account"
                      :rules="[
                          () => !!data.cargo || 'El cargo es requerido',
                          () => !!data.cargo && data.cargo.length <=255 || 'El cargo no puede tener mas de 255 caracteres'
                          ]"
                      :error-messages="errorMessages"
                      counter
                    >
                      <template v-slot:label>
                        Cargo
                        <sup style="color:red">*</sup>
                      </template>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-textarea
                      class="pt-0 mt-0"
                      v-model="data.responsabilidades"
                      label="Responsabilidades"
                      value
                      prepend-icon="comment"
                      rows="2"
                      hint="Que responsabilidades tiene (tenia) en la empresa"
                      clearable
                      :rules="[
                          () => !!data.responsabilidades || 'El campo de responsabilidades es requerido',
                          () => !!data.responsabilidades && data.responsabilidades.length <=2500 || 'El campo de responsabilidades no puede tener mas de 2500 caracteres'
                          ]"
                      :error-messages="errorMessages"
                      counter
                    >
                      <template v-slot:label>
                        Responsabilidades
                        <sup style="color:red">*</sup>
                      </template>
                    </v-textarea>
                  </v-col>
                  <v-col cols="12" class="text-right">
                    <v-btn color="default" @click="e1 = 1" rounded small>Anterior</v-btn>
                    <v-btn color="success" rounded small :disabled="form2" @click="save()">Guardar</v-btn>
                  </v-col>
                </v-row>
              </v-stepper-content>
            </v-stepper-items>
          </v-stepper>
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
    this.getSectores();
  },
  computed: {
    form1() {
      if (
        this.data.empresa != "" &&
        this.data.sector != "" &&
        this.data.subsector != "" &&
        this.data.ciudad != "" &&
        this.data.telefono != ""
      ) {
        return false;
      } else {
        return true;
      }
    },
    form2() {
      if (
        this.data.responsabilidades != "" &&
        this.data.cargo != "" &&
        this.data.start_end != ""
      ) {
        return false;
      } else {
        return true;
      }
    }
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
    "data.end_end": function(newValue, oldValue) {
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
    validaform1() {
      if (
        this.data.empresa != "" &&
        this.data.sector != "" &&
        this.data.subsector != "" &&
        this.data.ciudad != "" &&
        this.data.telefono != ""
      ) {
        this.form1 = false;
      } else {
        this.form1 = true;
      }
    },
    validaform2() {
      if (
        this.data.responsabilidades != "" &&
        this.data.cargo != "" &&
        this.data.start_end != ""
      ) {
        return false;
      } else {
        return true;
      }
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
    save() {
      this.isLoading = true;
      axios
        .post("/saveExpe", this.data)
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
            this.$store.commit('setAddexperi')
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