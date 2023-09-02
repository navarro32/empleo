<template>
  <div class="container align-center justify-center d-flex">
    <div class="row">
      <div class="text-center w-100">
        <v-avatar size="140" class="profile" v-if="$store.state.avatar!=''">
          <img :src="$store.state.avatar" />
        </v-avatar>
        <v-avatar size="140" class="profile" v-else>
          <img src="../img/default.jpg" />
        </v-avatar>
      </div>
      <div
        class="text-center col-6 offset-3 col-sm-6 offset-sm-3 col-md-4 col-lg-4 offset-md-4 offset-lg-4 row p-0 center"
      >
        <vs-button block @click="toggleShow" animation-type="vertical">
          Subir Imagen
          <template #animate>
            <v-icon color="white">mdi-image</v-icon>
          </template>
        </vs-button>
      </div>

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
    </div>
  </div>
</template>

<script>
import myUpload from "vue-image-crop-upload";

export default {
  data() {
    return {
      show: false,
      params: {
        name: "avatar"
      },

      headers: {
        "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content")
      },
      imgDataUrl: "" // the datebase64 url of created image
    };
  },
  mounted() {
    console.log(this.$store.state);
  },
  components: {
    "my-upload": myUpload
  },
  methods: {
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

<style lang="scss">
@media only screen and (max-width: 991px) {
  .vue-image-crop-upload .vicp-wrap {
    width: 80%;
  }
}
</style>