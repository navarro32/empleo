<template>
  <v-container fluid align-center justify-center>
    <v-hover v-slot:default="{ hover }" open-delay="100">
      <v-card class="mx-auto mt-5" outlined :elevation="hover ? 16 : 2">
        <v-img src="/images/sunshine.jpg" height="200px"></v-img>
        <v-avatar size="100" class="profile" v-if="$store.state.avatar!=''">
          <img :src="$store.state.avatar" />
          <div class="cambiar_foto" @click="toggleShow">
            <v-icon>mdi-camera</v-icon>
          </div>
        </v-avatar>
        <v-avatar size="100" class="profile" v-else>
          <img src="../img/default.jpg" />
          <div class="cambiar_foto" @click="toggleShow">
            <v-icon>mdi-camera</v-icon>
          </div>
        </v-avatar>

        <v-card-title style="position:relative">
          <h3 class="titulos_tarjetas">Datos Basicos</h3>
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
                @click="getDatos(); editable = !editable; edito()"
              >
                <v-icon>mdi-close-circle</v-icon>
              </v-btn>
            </template>
            <span>Cancelar</span>
          </v-tooltip>
          <v-tooltip left v-else-if="editable">
            <template v-slot:activator="{ on }">
              <v-btn
                v-on="on"
                fab
                color="primary"
                bottom
                right
                small
                absolute
                @click="editable = !editable; edito()"
              >
                <v-icon>mdi-account-edit</v-icon>
              </v-btn>
            </template>
            <span>Editar datos</span>
          </v-tooltip>
        </v-card-title>
        <hr class="m-0" />
        <v-card-text class="text--primary pt-0">
          <v-form ref="form" v-model="valid" lazy-validation class="row">
            <div class="col-12 col-md-6 pt-0 pb-0">
              <v-text-field
                :disabled="editable"
                v-model="datos.nombres"
                :counter="255"
                label="Nombres"
                required
                :error-messages="nombreErrors"
                @input="$v.datos.nombres.$touch()"
                @blur="$v.datos.nombres.$touch()"
              ></v-text-field>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0">
              <v-text-field
                v-model="datos.apellidos"
                :counter="255"
                label="Apellidos"
                required
                :error-messages="apellidoErrors"
                @input="$v.datos.apellidos.$touch()"
                @blur="$v.datos.apellidos.$touch()"
                :disabled="editable"
              ></v-text-field>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0">
              <v-text-field
                v-model="datos.email"
                label="E-mail"
                :disabled="true"
                :error-messages="emailErrors"
                @input="$v.datos.email.$touch()"
                @blur="$v.datos.email.$touch()"
              ></v-text-field>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0">
              <v-text-field
                v-model="datos.datos_basicos.documento"
                label="Documento"
                required
                :error-messages="docuErrors"
                @input="$v.datos.datos_basicos.documento.$touch()"
                @blur="$v.datos.datos_basicos.documento.$touch()"
                :disabled="editable"
              ></v-text-field>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-text-field
                v-model="datos.datos_basicos.telefono"
                :counter="20"
                label="Telefono"
                required
                :error-messages="teleErrors"
                @input="$v.datos.datos_basicos.telefono.$touch()"
                @blur="$v.datos.datos_basicos.telefono.$touch()"
                :disabled="editable"
              ></v-text-field>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-text-field
                v-model="datos.datos_basicos.celular"
                :counter="20"
                label="Celular"
                required
                :error-messages="celuErrors"
                @input="$v.datos.datos_basicos.celular.$touch()"
                @blur="$v.datos.datos_basicos.celular.$touch()"
                :disabled="editable"
              ></v-text-field>
            </div>

            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :return-value.sync="datos.datos_basicos.fecha_nacimiento"
                transition="scale-transition"
                offset-y
                min-width="290px"
                :error-messages="fechaErrors"
                @input="$v.datos.datos_basicos.fecha_nacimiento.$touch()"
                @blur="$v.datos.datos_basicos.fecha_nacimiento.$touch()"
                :disabled="editable"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="datos.datos_basicos.fecha_nacimiento"
                    label="Fecha de Nacimiento"
                    :error-messages="fechaErrors"
                    readonly
                    v-on="on"
                    :disabled="editable"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="datos.datos_basicos.fecha_nacimiento"
                  no-title
                  scrollable
                  locale="co"
                  :max="nowDate"
                  :value="datos.datos_basicos.fecha_nacimiento"
                >
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu.save(datos.datos_basicos.fecha_nacimiento)"
                  >OK</v-btn>
                </v-date-picker>
              </v-menu>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-select
                :items="sexo"
                v-model="datos.datos_basicos.genero"
                :error-messages="generoErrors"
                item-text="text"
                item-value="id"
                label="Género"
                :disabled="editable"
              ></v-select>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-select
                :items="estado_civil"
                v-model="datos.datos_basicos.estado_civil"
                :error-messages="estadoErrors"
                item-text="text"
                item-value="id"
                label="Estado Civil"
                :disabled="editable"
              ></v-select>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-autocomplete
                :items="departamentos"
                color="blue"
                item-text="departamento"
                item-value="id"
                label="Seleccione el departamento"
                v-model="datos.datos_basicos.departamento"
                :error-messages="dErrors"
                @change="$v.datos.datos_basicos.departamento.$touch()"
                @blur="$v.datos.datos_basicos.departamento.$touch()"
                :disabled="editable"
              ></v-autocomplete>
            </div>
            <div class="col-12 col-md-6 pt-0 pb-0" v-show="!editable">
              <v-autocomplete
                :items="ciudad"
                color="blue"
                :loading="isLoading"
                item-text="ciudad"
                item-value="id"
                label="Seleccione la ciudad"
                v-model="datos.datos_basicos.ciudad"
                :error-messages="ciudadErrors"
                @change="$v.datos.datos_basicos.ciudad.$touch()"
                @blur="$v.datos.datos_basicos.ciudad.$touch()"
                :disabled="editable"
              ></v-autocomplete>
            </div>

            <div class="col-12 col-md-12 pt-0 pb-0" v-show="!editable">
              <v-text-field
                v-model="datos.datos_basicos.direccion"
                :counter="255"
                label="Dirección"
                :error-messages="dirErrors"
                @input="$v.datos.datos_basicos.direccion.$touch()"
                @blur="$v.datos.datos_basicos.direccion.$touch()"
                :disabled="editable"
              ></v-text-field>
            </div>

            <div class="col-12 p-0 m-0" v-if="errores">
              <v-alert
                outlined
                :color="color"
                icon="mdi-alert-box"
                v-for="(val, index) in errores"
                v-bind:key="index"
              >{{val}}</v-alert>
            </div>

            <v-snackbar
              v-model="snackbar.activo"
              :timeout="5000"
              :right="true"
              :top="true"
            >{{ snackbar.text }}</v-snackbar>
            <div class="col-12 text-center align-content-center">
              <v-hover v-slot:default="{ hover }" v-show="!editable">
                <v-btn
                  :elevation="hover ? 16 : 2"
                  class="justify-center"
                  type="button"
                  @click="validate()"
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
      </v-card>
    </v-hover>
    <perfil></perfil>
    <experiencia></experiencia>
    <study></study>
    <my-upload
      field="image"
      @crop-success="cropSuccess"
      @crop-upload-success="cropUploadSuccess"
      @crop-upload-fail="cropUploadFail"
      v-model="show"
      :width="300"
      :height="300"
      url="/updateAvatar"
      :params="params"
      :headers="headers"
      img-format="png"
      lang-type="es-MX"
    ></my-upload>
  </v-container>
</template>

<script>
import { validationMixin } from "vuelidate";

import experiencia from "./edit/experiencia.vue";
import study from "./edit/studys.vue";
import perfil from "./edit/perfil.vue";
import myUpload from "vue-image-crop-upload";
import {
  required,
  minLength,
  maxLength,
  integer,
  numeric,
  minValue,
  maxValue,
  email
} from "vuelidate/lib/validators";
export default {
  components: {
    experiencia,
    study,
    perfil,
    "my-upload": myUpload
  },
  mixins: [validationMixin],
  validations: {
    datos: {
      nombres: {
        required,
        maxLength: maxLength(255)
      },
      apellidos: {
        required,
        maxLength: maxLength(255)
      },
      email: {
        required,
        email
      },
      datos_basicos: {
        documento: {
          required,
          integer
        },
        telefono: {
          required,
          integer
        },
        celular: {
          required,
          integer
        },
        fecha_nacimiento: {
          required
        },
        departamento: {
          required
        },
        ciudad: {
          required
        },
        genero: {
          required
        },
        estado_civil: {
          required
        },
        direccion: {
          required,
          maxLength: maxLength(255)
        }
      }
    }
  },
  beforeRouteLeave(to, from, next) {
    if (!this.$v.$anyDirty) {
      next();
    } else {
      const answer = window.confirm(
        "Estas seguro de salir sin guardar los cambios?"
      );
      if (answer) {
        next();
      } else {
        next(false);
      }
    }
  },
  data: () => ({
    valid: true,
    color: "",
    menu: false,
    nowDate: "",
    snackbar: {
      text: "",
      activo: false
    },
    sexo: [
      {
        id: "M",
        text: "Masculino"
      },
      {
        id: "F",
        text: "Femenino"
      }
    ],
    estado_civil: [
      {
        id: "Soltero",
        text: "Soltero (a)"
      },
      {
        id: "union_libre",
        text: "Unión Libre"
      },
      {
        id: "Casado",
        text: "Casado (a)"
      },
      {
        id: "Divorsiado",
        text: "Divorsiado (a)"
      },
      {
        id: "viudo",
        text: "Viudo (a)"
      }
    ],
    departamento: "",
    departamentos: [],
    ciudad: [],
    editable: true,
    datos: {
      nombres: "",
      apellidos: "",
      email: "",
      datos_basicos: {
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
      }
    },
    errores: [],
    success: false,
    checkbox: false,
    isLoading: false,
    show: false,
    params: {
      name: "avatar"
    },

    headers: {
      "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content")
    },
    imgDataUrl: "" // the datebase64 url of created image
  }),
  created() {
    this.restaAnos();
  },
  computed: {
    nombreErrors() {
      const errors = [];
      if (!this.$v.datos.nombres.$dirty) return errors;
      !this.$v.datos.nombres.maxLength &&
        errors.push("El nombre no puede tener más de 255 caracteres");
      !this.$v.datos.nombres.required && errors.push("El nombre es requerido.");
      return errors;
    },
    apellidoErrors() {
      const errors = [];
      if (!this.$v.datos.apellidos.$dirty) return errors;
      !this.$v.datos.apellidos.maxLength &&
        errors.push("El apellido no puede tener más de 255 caracteres");
      !this.$v.datos.apellidos.required &&
        errors.push("El apellido es requerido.");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.datos.email.$dirty) return errors;
      !this.$v.datos.email.required && errors.push("El correo es requerido.");
      !this.$v.datos.email.email && errors.push("El correo es incorrecto.");
      return errors;
    },
    docuErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.documento.$dirty) return errors;
      !this.$v.datos.datos_basicos.documento.required &&
        errors.push("El documento es requerido.");
      !this.$v.datos.datos_basicos.documento.integer &&
        errors.push("El documento debe ser numerico.");
      return errors;
    },
    teleErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.telefono.$dirty) return errors;
      !this.$v.datos.datos_basicos.telefono.required &&
        errors.push("El telefono es requerido.");
      !this.$v.datos.datos_basicos.telefono.integer &&
        errors.push("El telefono debe ser entero.");
      return errors;
    },
    celuErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.celular.$dirty) return errors;
      !this.$v.datos.datos_basicos.celular.required &&
        errors.push("El celular es requerido.");
      !this.$v.datos.datos_basicos.celular.integer &&
        errors.push("El celular debe ser entero.");
      return errors;
    },
    fechaErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.fecha_nacimiento.$dirty) return errors;
      !this.$v.datos.datos_basicos.fecha_nacimiento.required &&
        errors.push("La fecha de nacimiento es requerida.");
      return errors;
    },

    dErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.departamento.$dirty) return errors;
      !this.$v.datos.datos_basicos.departamento.required &&
        errors.push("El departamento es requerido.");
      return errors;
    },
    generoErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.genero.$dirty) return errors;
      !this.$v.datos.datos_basicos.genero.required &&
        errors.push("El genero es requerido.");
      return errors;
    },
    estadoErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.estado_civil.$dirty) return errors;
      !this.$v.datos.datos_basicos.estado_civil.required &&
        errors.push("El Estado civil es requerido.");
      return errors;
    },
    dirErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.direccion.$dirty) return errors;
      !this.$v.datos.datos_basicos.direccion.maxLength &&
        errors.push("La dirección no puede tener más de 255 caracteres");
      !this.$v.datos.datos_basicos.direccion.required &&
        errors.push("La dirección es requerida.");
      return errors;
    },
    ciudadErrors() {
      const errors = [];
      if (!this.$v.datos.datos_basicos.ciudad.$dirty) return errors;
      !this.$v.datos.datos_basicos.ciudad.required &&
        errors.push("La ciudad es requerida.");
      return errors;
    }
  },
  mounted() {
    this.getDatos();
    this.getDepartamentos();
  },
  watch: {
    "datos.datos_basicos.departamento": function(newValue, oldValue) {
      this.buscarCiudad(newValue);
    }
  },
  methods: {
    edito() {
      this.$store.dispatch("setEditable", this.editable);
    },
    getDatos() {
      axios
        .get("/getUser")
        .then(response => {
          this.datos = response.data;
          if (response.data.datos_basicos == null) {
            this.datos.datos_basicos = {
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
          this.$store.dispatch(
            "setAvatar",
            this.datos.datos_basicos.ruta_avatar
          );
          this.$store.commit("setdataUser", this.datos);
        })
        .catch(function(error) {
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    validate() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        // let descripcion = CKEDITOR.instances["editorDescripcion"].getData();
        // this.datos.descripcion = descripcion;
        axios
          .post("/editUser", this.datos)
          .then(response => {
            if (response.data.tipo == 0) {
              this.errores = response.data.mensaje;
              this.color = "red";
            } else if (response.data.tipo == 2) {
              this.errores = response.data.mensaje;
              this.color = "blue";
            } else if (response.data.tipo == 5) {
              EventBus.$emit("session");
            } else {
              let notify = {
                color: "success",
                position: "top-right",
                duration: "6000",
                title: "Exito",
                text: response.data.mensaje
              };
              this.$store.dispatch("showMessage", notify);

              this.success = true;
              // this.snackbar.text = response.data.mensaje;
              // this.snackbar.activo = true;
              this.editable = true;
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    reset() {
      this.$refs.form.reset();
      this.getDatos();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    home() {
      this.$router.push("/");
    },
    getDepartamentos() {
      axios
        .get("/getDepartamentos")
        .then(response => {
          this.departamentos = response.data;
        })
        .catch(function(error) {
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    buscarCiudad(val) {
      this.isLoading = true;
      axios
        .post("/getCiudades", {
          id: val
        })
        .then(response => {
          if (response.data.tipo == 5) {
            EventBus.$emit("session");
          } else {
            this.ciudad = response.data;
          }
        })
        .catch(function(error) {
          console.log(error);
        })
        .finally(() => (this.isLoading = false));
    },
    restaAnos() {
      let date = new Date();
      date.setMonth(date.getMonth() - 192);
      this.nowDate = date.toISOString().substr(0, 10);
      this.datos.fecha_nacimiento = date.toISOString().substr(0, 10);
    },
    // METODOS PARA CAMBIAR EL AVATAR
    toggleShow() {
      this.show = !this.show;
    },
    /**
     * crop success
     *
     * [param] imgDataUrl
     * [param] field
     */
    cropSuccess(imgDataUrl, field) {
      console.log("-------- crop success --------");
      this.imgDataUrl = imgDataUrl;
    },
    /**
     * upload success
     *
     * [param] jsonData  server api return data, already json encode
     * [param] field
     */
    cropUploadSuccess(jsonData, field) {
      if (jsonData.tipo == "1") {
        this.$store.dispatch("setAvatar", jsonData.mensaje);
        this.imgDataUrl = jsonData.mensaje;
      } else {
        let notify = {
          color: "danger",
          position: "top-right",
          duration: "6000",
          title: "Error",
          text: jsonData.mensaje
        };
        this.$store.dispatch("showMessage", notify);
      }
    },
    /**
     * upload fail
     *
     * [param] status    server api return error status, like 500
     * [param] field
     */
    cropUploadFail(status, field) {
      console.log("-------- upload fail --------");
      console.log(status);
      console.log("field: " + field);
    }
  }
};
</script>
<style>
@media only screen and (max-width: 768px) {
  .shadow-lg {
    box-shadow: none !important;
  }
}
@media only screen and (min-width: 991px) {
  .profile {
    position: absolute;
    top: 150px;
    left: 45%;
  }
}
.profile {
  background-color: white;
  border-radius: 50%;
  position: absolute;
  top: 150px;
  left: 45%;
  z-index: 1;
}
.cambiar_foto {
  position: absolute;
  bottom: 0px;
  background: black;
  opacity: 0.8;
  width: 100%;
  cursor: pointer;
  z-index: 2;
  display: none;
}
.cambiar_foto i {
  padding: 6px 0;
  color: white !important;
}
.profile:hover .cambiar_foto {
  display: block;
}
.v-card__title {
  background-color: #4d616b;
}
.v-dialog .v-card__title {
  background-color: #e4e4e4;
}
</style>