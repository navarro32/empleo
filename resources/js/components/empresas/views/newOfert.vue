<template>
  <v-container fluid>
    <v-form ref="form" class="row" @submit.prevent="submit"  dusk="formulario_envio">
      <v-row class="col-12 col-md-8 offset-md-2 row shadow-lg shadow-lg">
        <div class="col-12">
          <h1 class="titulos m-0 font-weight-bold">Crear Oferta</h1>
        </div>
        <div class="col-12 col-md-12 pt-0 pb-0">
          <v-text-field
            v-model="datos.nombres"
            :error-messages="nombreErrors"
            id="oferta"
            :counter="255"
            label="Digita el cargo o el nombre de la oferta"
            required
            @input="$v.datos.nombres.$touch()"
            @blur="$v.datos.nombres.$touch()"
          ></v-text-field>
        </div>
        <div class="col-6 col-md-6 pt-0 pb-0">
          <v-text-field
            label="Sueldo"
            id="sueldo"
            v-model="datos.sueldo"
            @input="$v.datos.sueldo.$touch()"
            @blur="$v.datos.sueldo.$touch()"
            :error-messages="sueldoErrors"
            required
            type="number"
            prefix="$"
          ></v-text-field>
        </div>
        <div class="col-6 col-md-6 pt-0 pb-0 d-flex">
          <p class="mb-0 d-flex align-items-center">${{ formatMoney(datos.sueldo) }}</p>
        </div>
        <div class="col-12 col-md-12 pt-0 pb-0" id="div_areas">
          <v-autocomplete
         
            :items="areas"
            id="area"
            color="blue"
            item-text="area"
            item-value="id"
            label="Seleccione el area"
            v-model="datos.area"
            :error-messages="areaErrors"
            @change="$v.datos.area.$touch()"
            @blur="$v.datos.area.$touch()"
          ></v-autocomplete>
          <input type="text" id="area_hidden" v-model="datos.area" @value="datos.area" style="position: absolute; left: 10000000px;">
        </div>
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-autocomplete
            id="departamento"
            :items="departamentos"
            color="blue"
            item-text="departamento"
            item-value="id"
            label="Seleccione el departamento"
            v-model="departamento"
            :error-messages="dErrors"
            @change="$v.datos.departamento.$touch()"
            @blur="$v.datos.departamento.$touch()"
          ></v-autocomplete>
          <input type="text" id="departamento_hidden" v-model="departamento"  style="position: absolute; left: 10000000px;">
        </div>
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-autocomplete
          id="ciudad"
            :items="ciudades"
            color="blue"
            :loading="isLoading"
            item-text="ciudad"
            item-value="id"
            label="Seleccione la ciudad"
            v-model="datos.ciudades"
            :error-messages="ciudadesErrors"
            @change="$v.datos.ciudades.$touch()"
            @blur="$v.datos.ciudades.$touch()"
          ></v-autocomplete>
          <input type="text" id="ciudad_hidden" v-model="datos.ciudades" @value="datos.ciudades"  style="position: absolute; left: 10000000px;">
        </div>
        <div class="col-12 col-md-6 pt-0 pb-0">
          <v-text-field
            id="vacante"
            v-model="datos.vacantes"
            label="Digita la cantidad de vacantes"
            type="number"
            :counter="3"
            :error-messages="vacantesErrors"
            @change="$v.datos.vacantes.$touch()"
            @blur="$v.datos.vacantes.$touch()"
          ></v-text-field>
        </div>
        <div class="col-7 col-md-7 pt-0 pb-0">
          <v-text-field
            id="experiencia"
            v-model="datos.experiencia"
            label="Digita la cantidad de experiencia"
            min="0"
            max="999"
            type="number"
            :counter="3"
            maxlength="3"
            :error-messages="experienciaErrors"
            @change="$v.datos.experiencia.$touch()"
            @blur="$v.datos.experiencia.$touch()"
          ></v-text-field>
        </div>
        <div class="col-5 col-md-5 pt-0 pb-0">
          <v-select
            id="tipo_fecha"
            v-model="datos.tipoFecha"
            :items="tipoFecha"
            item-text="tipo"
            item-value="id"
            label="Seleccione"
            :error-messages="tipoFechaErrors"
            @change="$v.datos.tipoFecha.$touch()"
            @blur="$v.datos.tipoFecha.$touch()"
          ></v-select>
          <input type="text" id="tipo_fecha_hidden" v-model="datos.tipoFecha" @value="datos.tipoFecha"  style="position: absolute; left: 10000000px;">
        </div>
        <div class="col-12 col-md-8 pt-0 pb-0">
          <v-select
          id="tipo_contrato"
            v-model="datos.contrato"
            :items="tipoContrato"
            item-text="tipo"
            item-value="id"
            label="Seleccione el tipo de contrato"
            :error-messages="contratoErrors"
            @change="$v.datos.contrato.$touch()"
            @blur="$v.datos.contrato.$touch()"
          ></v-select>
          <input type="text" id="tipo_contrato_hidden" v-model="datos.contrato" @value="datos.contrato" style="position: absolute; left: 10000000px;">
        </div>
        <div class="col-12 col-md-12 pt-0 pb-0">
          <label
            class="label-bootstrap"
            :class="{ 'text-error': descErrors[0] }"
          >Descripción de la oferta</label>
          <ckeditor
            id="texto_textarea"
            @change="$v.datos.descripcion.$touch()"
            :editor="editor"
            v-model="datos.descripcion"
            :config="editorConfig"
          ></ckeditor>
          <textarea id="texto_textarea_hidden" v-model="datos.descripcion" style="position: absolute; left: 10000000px;" ></textarea>

          <div class="text-error" v-if="descErrors[0]">{{ descErrors[0] }}</div>
        </div>
        <div class="col-12 text-center">
          <p class="typo__p" v-if="submitStatus === 'OK'">
            <v-alert outlined color="green" class="pt-0 pb-0">
              <v-row align="center">
                <v-col class="grow">Registro exitoso</v-col>
                <v-col class="shrink">
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn color="primary" fab x-small dark v-on="on">
                        <v-icon>mdi-home</v-icon>
                      </v-btn>
                    </template>
                    <span>Ir al inicio</span>
                  </v-tooltip>
                </v-col>
              </v-row>
            </v-alert>
          </p>
          <p class="typo__p" v-if="submitStatus === 'ERROR'">
            <v-alert
              outlined
              type="error"
              icon="mdi-alert-box"
              dismissible
            >Por favor verifica los datos.</v-alert>
          </p>
          <div class="col-12" v-if="errores">
          <v-alert
            outlined
            type="error"
            icon="mdi-alert-box"
            v-for="(val, index) in errores"
            v-bind:key="index"
          >{{val}}</v-alert>
        </div>
          <p class="typo__p" v-if="submitStatus === 'PENDING'">Enviando...</p>
          <v-btn
          id="guardar"
            class="ma-2"
            type="submit"
            tile
            outlined
            color="success"
            :disabled="submitStatus === 'PENDING'"
          >
            <v-icon left>mdi-pencil</v-icon>Guardar
          </v-btn>
          <v-btn @click="clear" outlined color="orange">Limpiar</v-btn>
        </div>
      </v-row>
    </v-form>
    <v-dialog id="mensaje_exito" v-model="dialog" persistent max-width="500">      
      <v-card id="contenido_mensaje">
        <v-card-title class="headline">Exito</v-card-title>
        <v-card-text>{{ success }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">Crear nueva</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import ES from "@ckeditor/ckeditor5-build-classic/build/translations/es.js";
import { validationMixin } from "vuelidate";
import {
  required,
  minLength,
  maxLength,
  numeric,
  minValue,
  maxValue
} from "vuelidate/lib/validators";
export default {
  mixins: [validationMixin],
  validations: {
    datos: {
      nombres: {
        required,
        maxLength: maxLength(255)
      },
      sueldo: {
        required,
        numeric,
        minValue: minValue(0),
        maxValue: maxValue(100000000)
      },
      area: {
        required
      },
      departamento: {
        required
      },
      ciudades: {
        required
      },
      vacantes: {
        required,
        numeric,
        minValue: minValue(1),
        maxValue: maxValue(999)
      },
      experiencia: {
        required,
        numeric,
        minValue: minValue(0),
        maxValue: maxValue(24)
      },
      tipoFecha: {
        required
      },
      contrato: {
        required
      },
      descripcion: {
        required
      }
    }
  },
  computed: {
    nombreErrors() {
      const errors = [];
      if (!this.$v.datos.nombres.$dirty) return errors;
      !this.$v.datos.nombres.maxLength &&
        errors.push(
          "El nombre de la oferta no puede tener más de 255 caracteres"
        );
      !this.$v.datos.nombres.required && errors.push("El nombre es requerido.");
      return errors;
    },
    sueldoErrors() {
      const errors = [];
      if (!this.$v.datos.sueldo.$dirty) return errors;
      !this.$v.datos.sueldo.required && errors.push("El sueldo es requerido.");
      !this.$v.datos.sueldo.numeric &&
        errors.push("El sueldo debe ser numerico.");
      !this.$v.datos.sueldo.minValue &&
        errors.push("El sueldo debe ser minimo de 0.");
      !this.$v.datos.sueldo.maxValue &&
        errors.push("El sueldo debe ser maximo de 100'000.000.");
      return errors;
    },
    areaErrors() {
      const errors = [];
      if (!this.$v.datos.area.$dirty) return errors;
      !this.$v.datos.area.required && errors.push("El nombre es requerido.");
      return errors;
    },
    dErrors() {
      const errors = [];
      if (!this.$v.datos.departamento.$dirty) return errors;
      !this.$v.datos.departamento.required &&
        errors.push("El departamento es requerido.");
      return errors;
    },
    ciudadesErrors() {
      const errors = [];
      if (!this.$v.datos.ciudades.$dirty) return errors;
      !this.$v.datos.ciudades.required &&
        errors.push("La ciudad es requerida.");
      return errors;
    },
    vacantesErrors() {
      const errors = [];
      if (!this.$v.datos.sueldo.$dirty) return errors;
      !this.$v.datos.vacantes.required &&
        errors.push("La vacante es requerido.");
      !this.$v.datos.vacantes.numeric &&
        errors.push("La vacante debe ser numerico.");
      !this.$v.datos.vacantes.minValue &&
        errors.push("La vacante debe ser minimo de 0.");
      !this.$v.datos.vacantes.maxValue &&
        errors.push("La vacante debe ser maximo de 999.");
      return errors;
    },
    experienciaErrors() {
      const errors = [];
      if (!this.$v.datos.sueldo.$dirty) return errors;
      !this.$v.datos.experiencia.required &&
        errors.push("La experiencia es requerido.");
      !this.$v.datos.experiencia.numeric &&
        errors.push("La experiencia debe ser numerico.");
      !this.$v.datos.experiencia.minValue &&
        errors.push("La experiencia debe ser minimo de 0.");
      !this.$v.datos.experiencia.maxValue &&
        errors.push("La experiencia debe ser maximo de 999.");
      return errors;
    },
    tipoFechaErrors() {
      const errors = [];
      if (!this.$v.datos.tipoFecha.$dirty) return errors;
      !this.$v.datos.tipoFecha.required &&
        errors.push("El tipo de fecha es requerido.");
      return errors;
    },
    contratoErrors() {
      const errors = [];
      if (!this.$v.datos.contrato.$dirty) return errors;
      !this.$v.datos.contrato.required &&
        errors.push("El contrato es requerido.");
      return errors;
    },
    descErrors() {
      const errors = [];
      if (!this.$v.datos.descripcion.$dirty) return errors;
      !this.$v.datos.descripcion.required &&
        errors.push("La descripcion es requerida.");
      return errors;
    }
  },
  data() {
    return {
      dialog:false,
      success:'',
      datos: {
        nombres: "",
        sueldo: "",
        area: "",
        departamento: "",
        ciudades: "",
        vacantes: "",
        experiencia: "",
        tipoFecha: "",
        contrato: "",
        descripcion: ""
      },
      submitStatus: null,
      hasSaved: false,
      isEditing: null,
      model: null,
      isLoading: false,
      editor: ClassicEditor,
      editorConfig: {
        language: {
          ui: "es",
          content: "es"
        },
        required: true,
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
      tipoFecha: [
        { id: 1, tipo: "Meses" },
        { id: 2, tipo: "años" }
      ],
      tipoContrato: [
        { id: 1, tipo: "A término fijo" },
        { id: 2, tipo: "Término indefinido" },
        { id: 3, tipo: "Por prestación de servicios" },
        { id: 4, tipo: "Por Obra o labor" }
      ],
      departamento: "",
      departamentos: [],
      areas: [],
      ciudades: [],
      errores: [],
    };
  },
  mounted() {
    this.getAreas();
    this.getDepartamentos();
  },
  watch: {
    departamento(newValue, oldValue) {
      this.datos.departamento = newValue;
      this.buscarCiudad(newValue);
    }
  },
  methods: {
    getAreas() {
      axios
        .get("/getAreas")
        .then(response => {
          this.areas = response.data;
        })
        .catch(function(error) {
          console.log(error);
        })
        .then(function() {
          // always executed
        });
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
      this.isLoading= true
      axios
        .post("/getCiudades", {
          id: val
        })
        .then(response => {
          if(response.data.tipo==5){
            EventBus.$emit('session');            
          }else{
            this.ciudades = response.data;
            this.datos.ciudades = null
          }
          
        })
        .catch(function(error) {
          console.log(error);
        })
        .finally(() => (this.isLoading = false));
    },
    submit() {
      console.log("Datos enviados:", JSON.stringify(this.datos));
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submitStatus = "ERROR";
      } else {
        // do your submit logic here
        this.submitStatus = "PENDING";
        axios.post('/saveOfert', this.datos)
        .then((response)=>{
          console.log(response)
          console.log(response.status)
          console.log(response.statusText)
           if (response.data.tipo == 0) {
              this.errores = response.data.mensaje;
              this.color = "red";
            } else if(response.data.tipo==5){
              EventBus.$emit('session');            
            }else {
               this.success=response.data.mensaje
               this.dialog=true
               this.clear()
            }
         
        })
        .catch(function (error) {
          console.log("dio error")
          console.log(error);
        });
      }
    },
    clear () {
        this.$v.$reset()
          this.datos.nombres=''          
          this.datos.sueldo= ""
          this.datos.area= ""
          this.datos.departamento= ""
          this.datos.ciudades= ""
          this.datos.vacantes= ""
          this.datos.experiencia= ""
          this.datos.tipoFecha= ""
          this.datos.contrato= ""
          this.datos.descripcion= ""  
          this.submitStatus=null
      },
    formatMoney: function(
      amount,
      decimalCount = 0,
      decimal = ",",
      thousands = "."
    ) {
      try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(
          (amount = Math.abs(Number(amount) || 0).toFixed(decimalCount))
        ).toString();
        let j = i.length > 3 ? i.length % 3 : 0;

        return (
          negativeSign +
          (j ? i.substr(0, j) + thousands : "") +
          i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
          (decimalCount
            ? decimal +
              Math.abs(amount - i)
                .toFixed(decimalCount)
                .slice(2)
            : "")
        );
      } catch (e) {
        console.log(e);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.label-bootstrap {
  margin-bottom: 0;
  font-size: 14px;
}
@media only screen and (max-width: 768px) {
  .shadow-lg {
    box-shadow: none !important;
  }
}
.text-error {
  color: red;
  font-size: 14px;
}
</style>