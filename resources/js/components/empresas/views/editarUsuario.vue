<template>
  <v-container fluid  align-center justify-center>
    <v-form ref="form" v-model="valid" lazy-validation class="row">
      <v-row class="col-12 col-md-8 offset-md-2 row shadow-lg">
        <div class="col-12">
          <h1 class="titulos m-0 font-weight-bold">Editar datos</h1>
          </div>          
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-text-field
            v-model="datos.nombres"
            :counter="255"
            :rules="nombresRules"
            label="Nombres"
            required
          ></v-text-field>
        </div>
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-text-field
            v-model="datos.apellidos"
            :counter="255"
            :rules="apellidosRules"
            label="Apellidos"
            required
          ></v-text-field>
        </div>
        <div class="col-12 pt-0 pb-0">
          <v-text-field v-model="datos.email" :rules="emailRules" label="E-mail" :readonly="true"></v-text-field>
        </div>
        <div class="col-12 pt-0 pb-0">
          <v-text-field v-model="datos.razon_social" :readonly="true" label="Razón social" required></v-text-field>
        </div>
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-text-field v-model="datos.nit" :readonly="true" label="NIT"></v-text-field>
        </div>
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-text-field
            v-model="datos.telefono"
            :counter="20"
            :rules="telefonoRules"
            label="Telefono"
            required
          ></v-text-field>
        </div>
        <div class="col-12 col-md-12 pt-0 pb-0">
          <v-text-field
            v-model="datos.direccion"
            :counter="255"
            :rules="direccionRules"
            label="Dirección"
          ></v-text-field>
        </div>
        <div class="col-12 col-md-12 pt-0 pb-0">
          <v-text-field v-model="datos.url" :counter="255" label="Url - Página web" required></v-text-field>
        </div>
        <div class="col-12 col-md-12 pt-0 pb-0">          
          <ckeditor :editor="editor" v-model="datos.descripcion" :config="editorConfig"></ckeditor>
        </div>

        <div class="col-12 col-md-12 pt-0 pb-0">
          <v-checkbox
            v-model="checkbox"
            :rules="[v => !!v || 'Debes aceptar los terminos para continuar!']"
            label="Acepta los terminos y condiciones?"
            required
          ></v-checkbox>
        </div>
        <div class="col-12" v-if="errores">
          <v-alert
            outlined
            :color="color"
            icon="mdi-alert-box"
            v-for="(val, index) in errores"
            v-bind:key="index"
          >{{val}}</v-alert>
        </div>

        <v-snackbar v-model="snackbar.activo" :timeout="5000" :right="true" :top="true">
          {{ snackbar.text }}
          <v-btn color="blue" fab small dark @click="home">
            <v-icon>mdi-home-outline</v-icon>
          </v-btn>
          <v-btn color="red" fab small dark @click="snackbar.activo = false">
            <v-icon>mdi-close-box</v-icon>
          </v-btn>
        </v-snackbar>

        <div class="col-12 col-md-12 pt-0 pb-0">
          <v-btn :disabled="!valid" color="success" class="mr-4" @click="validate">Guardar</v-btn>

          <v-btn color="error" class="mr-4" @click="reset">Cancelar</v-btn>
        </div>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import ES from "@ckeditor/ckeditor5-build-classic/build/translations/es.js";
export default {
  data: () => ({
    valid: true,
    color: "",
    editor: ClassicEditor,
    editorConfig: {
      language: {
        ui: "es",
        content: "es"
      },
      toolbar: [
        "heading",
        "|",
        "bold",
        "italic",
        "link",
        "bulletedList",
        "numberedList",
        "|",
        "blockQuote",
        "insertTable",
        "mediaEmbed",
        "undo",
        "redo"
      ]
    },
    snackbar: {
      text: "",
      activo: false
    },
    datos: {
      nombres: "",
      apellidos: "",
      email: "",
      razon_social: "",
      nit: "",
      telefono: "",
      url: "",
      direccion: "",
      descripcion: ""
    },
    nombresRules: [
      v => !!v || "El nombre es requerido",
      v =>
        (v && v.length <= 255) ||
        "El nombre no puede tener mas de 255 caracteres"
    ],
    apellidosRules: [
      v => !!v || "El apellido es requerido",
      v =>
        (v && v.length <= 255) ||
        "El apellido no puede tener mas de 255 caracteres"
    ],
    emailRules: [
      v => !!v || "E-mail is required",
      v => /.+@.+\..+/.test(v) || "E-mail must be valid"
    ],
    direccionRules: [
      v => !!v || "La dirección es requerida",
      v =>
        (v && v.length <= 255) ||
        "La dirección no debe tener mas de 255 caracteres"
    ],
    telefonoRules: [
      v => !!v || "El número de teléfono es requerido",
      v =>
        (v && v.length <= 20) ||
        "El número de teléfono no puede tener mas de 20 caracteres"
    ],
    errores: [],
    success: false,
    checkbox: false
  }),
  mounted() {
    this.getDatos();
  },
  methods: {
    getDatos() {
      axios
        .get("/getEmpresas")
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
    validate() {
      if (this.$refs.form.validate()) {
        // let descripcion = CKEDITOR.instances["editorDescripcion"].getData();
        // this.datos.descripcion = descripcion;
        axios
          .post("/editEmpresas", this.datos)
          .then(response => {
            if (response.data.tipo == 0) {
              this.errores = response.data.mensaje;
              this.color = "red";
            } else if (response.data.tipo == 2) {
              this.errores = response.data.mensaje;
              this.color = "blue";
            }else if(response.data.tipo==5){
              EventBus.$emit('session');            
            } else {
              this.success = true;
              this.snackbar.text = response.data.mensaje;
              this.snackbar.activo = true;
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
    }
  }
};
</script>
<style scoped>
@media only screen and (max-width: 768px) {
  .shadow-lg {
    box-shadow: none !important;
  }
}

</style>