<template>
  <div class="col-12 row mb-4">
    <div class="input-group col-12 col-md-8">
      <div class="input-group-prepend">
        <i
          class="material-icons input-group-text"
          style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;"
        >insert_photo</i>
      </div>
      <div class="custom-file">
        <input
          type="file"
          @change="obtenerImagen"
          name="logo"
          class="custom-file-input"
          id="logo"          
          aria-describedby="inputGroupFileAddon01" 
          ref="myFileInput"         
        />
        <label class="custom-file-label" for="logo">{{nombre}}</label>        
      </div>
      <span class="invalid-feedback" role="alert" v-if="error!=''" style="display:block">
            <strong>{{ error }}</strong>
        </span>
        <span class="invalid-feedback" role="alert" v-if="errorvalidar!=''" style="display:block">
            <strong>{{ errorvalidar }}</strong>
        </span>
    </div>
    <div class="input-group col-12 col-md-4" v-show="imagenMiniatura!=''">
        <v-avatar size="100" style="position:absolute; top:0; right: 0;">
        <img id="img-avatar" :src="imagen">
        </v-avatar>
    </div>
  </div>
</template>

<script>
export default {
  props: ['errorvalidar'],  
  data() {
    return {
      imagenMiniatura: "",      
      nombre: "Carga el logo de tu empresa",
      error: ''
    };
  },
  methods: {
    obtenerImagen(e) {      
      let file = e.target.files[0];
      this.nombre = file.name;             
      if(file.size>'700000'){
          this.error='La imagen no puede pesar mas de 700 Kb';
          this.nombre='Carga el logo de tu empresa'
          this.imagenMiniatura =''   
          this.$refs.myFileInput.value = '';      
      }else{
          if(file.type!='image/jpeg' && file.type!='image/png' && file.type!='image/jpg'){
              this.error="El formato de la imagen es invalido, debe ser de formato JPG o PNG"
              this.nombre='Carga el logo de tu empresa';
              this.imagenMiniatura ='' 
              this.$refs.myFileInput.value = '';             
          }else{
              this.error=''              
              this.cargarImagen(file);  
          }
      }     
    },
    cargarImagen(file) {        
      let reader = new FileReader();        
      reader.onload = e => {
        this.imagenMiniatura = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  },
  computed: {
      imagen() {
          return this.imagenMiniatura
      }
  },
};
</script>

<style lang="scss" scoped>
.custom-file-label::after {
  content: none;
}
#img-avatar{
  width: 100%;
}
</style>