<template>
  <div>
    <v-hover v-slot:default="{ hover }" open-delay="100">
      <v-card class="mx-auto mt-5" outlined :elevation="hover ? 16 : 2">
        <ValidationObserver ref="observer" v-slot="{ touched, dirty }">
          <v-card-title style="position:relative">
            <h3 class="titulos_tarjetas w-100 text-center">Perfil Laboral</h3>
            <v-tooltip left v-if="!editable">
              <template v-slot:activator="{ on }">
                <v-btn
                  v-on="on"
                  fab
                  color="primary"
                  bottom
                  right
                  small
                  absolute
                  @click="editable = !editable;"
                >
                  <v-icon>mdi-close-circle</v-icon>
                </v-btn>
              </template>
              <span>Cancelar</span>
            </v-tooltip>

            <v-tooltip left v-else>
              <template v-slot:activator="{ on }">
                <v-btn
                  v-on="on"
                  fab
                  color="primary"
                  bottom
                  right
                  small
                  absolute
                  @click="editable = !editable; "
                >
                  <v-icon>mdi-account-edit</v-icon>
                </v-btn>
              </template>
              <span>Editar datos</span>
            </v-tooltip>
          </v-card-title>
          <v-card-text class="text--primary pt-0">
            <v-form ref="form" v-model="valid" lazy-validation class="row">
              <div class="col-12 col-md-12 pt-0 pb-0">
                <ValidationProvider v-slot="{ errors }" name="nombre" rules="required|max:255">
                  <v-text-field
                    v-model="data.profesion"
                    :counter="255"
                    label="Profesión actual"
                    :error-messages="errors"
                    required
                    :disabled="editable"
                    @input="updateData"
                  ></v-text-field>
                </ValidationProvider>
              </div>
              <div class="col-12 col-md-4 pt-0 pb-0">
                <ValidationProvider v-slot="{ errors  }" name="años" rules="required">
                  <v-select
                    :items="items"
                    v-model="data.experiencia"
                    :error-messages="errors"
                    label="Años de experiencia"
                    required
                    :disabled="editable"
                    @input="updateData"
                  ></v-select>
                </ValidationProvider>
              </div>
              <div class="col-12 col-md-4 pt-0 pb-0">
                <ValidationProvider v-slot="{ errors  }" name="aspiracion" rules="required">
                  <v-select
                    :items="salarios"
                    v-model="data.aspiracion"
                    :error-messages="errors"
                    item-text="valor"
                    item-value="id"
                    label="Expectativa salarial"
                    required
                    :disabled="editable"
                    @input="updateData"
                  ></v-select>
                </ValidationProvider>
              </div>
              <div class="col-12 col-md-4 pt-0 pb-0">
                <v-checkbox
                  v-model="data.movilidad"
                  :disabled="editable"
                  label="Disponibilidad de viajar"
                  @input="updateData"
                ></v-checkbox>
              </div>
              <div class="col-12 col-md-12 pt-0 pb-0">
                <ValidationProvider v-slot="{ errors }" name="perfil" rules="required|max:5000">
                  <v-textarea
                    v-model="data.descripcion"
                    :counter="5000"
                    label="Describe tu perfil (tus logros, objetivos, etc.)"
                    :error-messages="errors"
                    :disabled="editable"
                    rows="3"
                    required
                    @input="updateData"
                  ></v-textarea>
                </ValidationProvider>
              </div>
              <div class="col-12 text-center align-content-center">
                <v-hover v-show="dirty && !editable" v-slot:default="{ hover }">
                  <v-btn
                    :elevation="hover ? 16 : 2"
                    class="justify-center"
                    type="button"
                    @click="save()"
                    tile
                    rounded
                    color="success"
                  >
                    <v-icon left>mdi-pencil</v-icon>Guardar
                  </v-btn>
                </v-hover>
              </div>
            </v-form>
          </v-card-text>
        </ValidationObserver>
      </v-card>
    </v-hover>
  </div>
</template>

<script>
import { required, email, max } from "vee-validate/dist/rules";
import salarios from "../../../../data/salarios.json";
import { mapState } from "vuex";
import {
  extend,
  ValidationObserver,
  ValidationProvider,
  setInteractionMode
} from "vee-validate";
setInteractionMode("eager");

extend("required", {
  ...required,
  message: "El campo {_field_} es requerido "
});

extend("max", {
  ...max,
  message: "El campo {_field_} no puede ser mayor a  {length} caracteres"
});

export default {
  components: {
    ValidationProvider,
    ValidationObserver
  },
  data() {
    return {
      salarios,
      valid: false,
      editable: true,
      firtsChange: false,
      guardado: false
    };
  },
  computed: {
    items() {
      const numbers = [...Array(21).keys()];
      return numbers;
    },
    ...mapState({
      data: state =>
        state.dataUser.perfil !== null
          ? state.dataUser.perfil
          : {
              movilidad: false,
              profesion: "",
              experiencia: "",
              aspiracion: "",
              descripcion: ""
            }
    })
  },
  methods: {
    save() {
      this.$refs.observer.validate();
      if (this.$refs.observer.flags.valid) {
        axios
          .post("/savePerfil", this.data)
          .then(response => {
            let color = "success";
            if (response.data.tipo == 1) {
              color = "success";
              this.editable = true;
            } else {
              color = "danger";
            }
            let notify = {
              color: color,
              position: "top-right",
              duration: "6000",
              title: "Exito",
              text: response.data.mensaje
            };
            this.$store.dispatch("showMessage", notify);
          })
          .catch(function(error) {
            console.log(error);
          })
          .then(function() {
            // always executed
          });
      } else {
        let notify = {
          color: "danger",
          position: "top-right",
          duration: "6000",
          title: "Error",
          text: "Verifica los campos obligatorios"
        };
        this.$store.dispatch("showMessage", notify);
      }
    },
    updateData(e) {
      this.$store.commit("setPerfil", this.data);
    }
  }
};
</script>

<style lang="scss">
label {
  margin-bottom: 0 !important;
}
</style>