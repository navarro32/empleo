<template>
<v-avatar size="100" class="profile" v-if="$store.state.avatar!=''">
      <img :src="$store.state.avatar" />
    </v-avatar>
    <v-avatar size="100" class="profile" v-else>
      <img src="../img/default.jpg" />
    </v-avatar>
  <!-- <div v-if="$store.state.editable" class="profile">
    <v-avatar size="100" class="profile" v-if="$store.state.avatar!=''">
      <img :src="$store.state.avatar" />
    </v-avatar>
    <v-avatar size="100" class="profile" v-else>
      <img src="../img/default.jpg" />
    </v-avatar>
  </div> -->
  <!-- <div v-else class="profile">
    <v-avatar size="100" class="profile" style="display: contents;">
      <img :src="imagenMiniatura" v-if="imagenMiniatura!=''" />
      <img :src="$store.state.avatar" v-else-if="$store.state.avatar!=''" />
      <img src="../img/default.jpg" v-else />
      <v-icon
        color="black"
        large
        style="position: absolute;bottom: 0px;cursor: pointer;z-index: 1;height: 30px;width: 30px;right: 0;"
        @click="saveImage()"
        v-if="imagenMiniatura!=''"
      >mdi-content-save-outline</v-icon>
      <v-icon
        color="black"
        large
        style="position: absolute;bottom: 0px;cursor: pointer;z-index: 1;height: 30px;width: 30px;right: 0;"
        @click="clickicon()"
        v-else
      >mdi-camera</v-icon>
      <input
        type="file"
        @change="obtenerImagen"
        name="logo"
        class="custom-file-input"
        id="logo"
        aria-describedby="inputGroupFileAddon01"
        ref="myFileInput"
        style="display:none"
      />
    </v-avatar>
    <v-snackbar v-model="snackbar2" :multi-line="true" :right="true" v-show="error!=''">
      <p v-html="error"></p>
      <v-btn color="red" text @click="snackbar2 = false">Close</v-btn>
    </v-snackbar>
  </div> -->
</template>

<script>
export default {
  props: ["ruta"],
  data() {
    return {
      imagenMiniatura: "",
      nombre: "Carga el logo de tu empresa",
      error: "",
      snackbar2: false
    };
  },
  mounted() {},
  methods: {
    clickicon() {
      this.$refs.myFileInput.click();
    },
    obtenerImagen(e) {
      let file = e.target.files[0];
      this.nombre = file.name;
      if (file.size > "700000") {
        
        this.error = "La imagen no puede pesar mas de 700 Kb";
        this.nombre = "Carga el logo de tu empresa";
        this.imagenMiniatura = "";
        this.$refs.myFileInput.value = "";
      } else {
        if (
          file.type != "image/jpeg" &&
          file.type != "image/png" &&
          file.type != "image/jpg"
        ) {
          
          this.error =
            "El formato de la imagen es invalido, debe ser de formato JPG o PNG";
          this.nombre = "Carga el logo de tu empresa";
          this.imagenMiniatura = "";
          this.$refs.myFileInput.value = "";
        } else {
          this.error = "";
          this.cargarImagen(file);
          return false;
        }
      }
      let notify = {
        color: "danger",
        position: "top-right",
        duration: "6000",
        title: "Error",
        text: this.error
      };
      this.$store.dispatch("showMessage", notify);
    },
    cargarImagen(file) {
      let reader = new FileReader();
      reader.onload = e => {
        this.imagenMiniatura = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    saveImage() {
      var bodyFormData = new FormData();
      bodyFormData.append("image", this.$refs.myFileInput.files[0]);
      axios({
        method: "post",
        url: "/updateAvatar",
        data: bodyFormData,
        headers: { "Content-Type": "multipart/form-data" }
      })
        .then(response => {
          if (response.data.tipo == "1") {
            this.$store.dispatch("setAvatar", response.data.mensaje);
            this.$store.dispatch("setEditable", true);
            this.imagenMiniatura = "";
          } else {
            let notify = {
              color: "danger",
              position: "top-right",
              duration: "6000",
              title: "Error",
              text: response.data.mensaje
            };
            this.$store.dispatch("showMessage", notify);
            this.imagenMiniatura = "";
            this.$store.dispatch("setEditable", true);
            // this.snackbar2=true
            // this.error=response.data.mensaje
            // this.imagenMiniatura=''
            // setTimeout(() => {
            //     this.$store.dispatch("setEditable", true);
            // }, 3000);
          }
        })
        .catch(function(response) {
          //handle error
          console.log(response);
        });
    }
  },
  computed: {
    imagen() {
      return this.imagenMiniatura;
    }
  }
};
</script>

<style lang="scss" scoped>
.custom-file-label::after {
  content: none;
}
#img-avatar {
  width: 100%;
}
</style>