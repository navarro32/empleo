<template>
  <div>
    <vs-dialog prevent-close blur v-model="$store.state.deleted.state">
      <template #header>
        <h4 class="not-margin">Eliminar</h4>
      </template>
      <div class="con-content">
        <p align="center" justify="center">{{ $store.state.deleted.mensaje }}</p>
        <vs-row align="center" justify="center">
          <vs-col w="4" align="center" justify="center">
            <vs-button block circle @click="close()">NO</vs-button>
          </vs-col>

          <vs-col w="4" align="center" justify="center">
            <vs-button block danger circle @click="deleted()">SI</vs-button>
          </vs-col>
        </vs-row>
      </div>
    </vs-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      mensaje_deleted: ""
    };
  },  
  methods: {
    close() {
      let deleted = {
        state: false
      };
      this.$store.commit("setDeleted", deleted);
    },
    deleted() {
      if (
        this.$store.state.deleted.id != "" &&
        this.$store.state.deleted.ruta != ""
      ) {
        axios
          .post(this.$store.state.deleted.ruta, {
            id: this.$store.state.deleted.id
          })
          .then(response => {
            let notify = {              
              position: "top-right",
              duration: "6000",             
            };
            if(response.data.tipo==1){
              notify.title='Exito';
              notify.color= "success";    
              let deleted = {
                state: false,
                wait:false,
                type:this.$store.state.deleted.type
              };
              this.$store.commit("setDeleted", deleted);    
              
            }else{
              notify.title='Error';
              notify.color= "danger";
            }
            notify.text =response.data.mensaje;
            this.$store.dispatch("showMessage", notify);
            this.$emit(this.$store.state.deleted.type, true)
          })
          .catch(function(error) {
            console.log(error);
          })
          .then(function() {
            // always executed
          });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
</style>