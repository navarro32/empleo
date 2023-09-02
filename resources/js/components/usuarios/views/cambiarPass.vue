<template>
  <div class="container align-center justify-center d-flex"  >
    <div class="row">
      <div class="col-12 col-md-10 col-lg-6 offset-md-1 offset-lg-3 card row p-0">
        <div class="card-header">
          <h1 class="titulos m-0">Cambiar contraseña</h1>
        </div>
        <div class="card-body">
          <form @submit.prevent="submit">
            <v-text-field
              :error-messages="passOldErrors"
              :counter="true"              
              label="Contraseña Anterior"
              required
              autocomplete="off"
              @input="$v.passwordOld.$touch()"
              @blur="$v.passwordOld.$touch()"
              v-on:keyup="validar"
              v-model="passwordOld"   
              type="password"    
                   
            ></v-text-field>
            <v-text-field
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="show1 = !show1"
              :type="show1 ? 'text' : 'password'"
              :error-messages="passErrors"
              :counter="true"
              label="Contraseña Nueva"
              required
              autocomplete="off"
              @input="$v.password.$touch()"
              @blur="$v.password.$touch()"
              v-on:keyup="validar"
              v-model="password"              
            ></v-text-field>
            <v-text-field
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="show2 = !show2"
              :type="show2 ? 'text' : 'password'"
              :error-messages="pass2Errors"
              :counter="true"
              label="Confirma la contraseña"
              required
              @input="$v.password2.$touch()"
              @blur="$v.password2.$touch()"
              v-on:keyup="validar"
              v-model="password2"
              
            ></v-text-field>
            <div class="col-12" v-if="errores">
              <v-alert
                outlined
                :color="color"
                icon="mdi-alert-box"
                v-for="(val, index) in errores"
                v-bind:key="index"
              >{{val}}</v-alert>
            </div>
            <div class="col-12 text-center align-content-center">
              <v-btn :disabled="!valid" color="success" class="mr-4" @click="submit">Cambiar</v-btn>
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
            
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength, sameAs } from "vuelidate/lib/validators";

export default {
  mixins: [validationMixin],

  validations: {
    passwordOld: {
      required: required
    },
    password: {
      required: required,
      minLength: minLength(8),
      goodPassword: password => {
        //a custom validator!
        return (
          password.length >= 8 &&
          /[a-z]/.test(password) &&
          /[A-Z]/.test(password) &&
          /[0-9]/.test(password)
        );
      }
    },
    password2: {
      required: required,
      sameAs: sameAs("password")
    }
  },

  data: () => ({
    valid: false,
    show1: false,
    show2: false,   
    password: "",
    password2: "",
    passwordOld: "",
    snackbar: {
      text: "",
      activo: false
    },
    errores: [],
    success: false
  }),

  computed: {
    passOldErrors() {
      const errors = [];
      if (!this.$v.passwordOld.$dirty) return errors;
      !this.$v.passwordOld.required &&
        errors.push("La contraseña es requerida.");
    },
    passErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.required && errors.push("La contraseña es requerida.");
      !this.$v.password.minLength &&
        errors.push("La contraseña debe contener al menos 8 caracteres");
      !this.$v.password.goodPassword &&
        errors.push(
          "La contraseña debe tener letras en mayusculas, minusculas y numeros"
        );
      return errors;
    },
    pass2Errors() {
      const errors = [];
      if (!this.$v.password2.$dirty) return errors;
      !this.$v.password2.sameAs &&
        errors.push(
          "La confirmación de contraseña debe ser igual a la contraseña"
        );
      !this.$v.password2.required &&
        errors.push("La confirmación es requerida.");
      return errors;
    }
  },
  mounted() {
  },
  methods: {
    submit() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        axios
          .post("/changePass", {
            passOld: this.passwordOld,
            pass: this.password,
            pass2: this.password2
          })
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
              this.errores=false
              this.clear();
              this.snackbar.text = response.data.mensaje;
              this.snackbar.activo = true;
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    validar() {
      if (
        !this.$v.passwordOld.$error &&
        !this.$v.password.$error &&
        !this.$v.password2.$error
      ) {
        this.valid = true;
      } else {
        this.valid = false;
      }
    },
    home() {
      this.$router.push("/");
    },
    clear () {
        this.$v.$reset()
        this.password = ''
        this.password2 = ''
        this.passwordOld = ''        
      },
  }
};
</script>

<style lang="scss" scoped>

</style>