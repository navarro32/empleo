<template>
  <div class="container">
    <div class="row">
      <div class="col-12 text-center">
        <h1 class="titulos">Lista de Ofertas</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-6 offset-md-6">
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Buscar"
          single-line
          hide-details
        ></v-text-field>
      </div>
    </div>

    <v-dialog
      v-model="$store.state.dialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <ofertas ref="Ofertas" v-on:getOferts="getOferts"></ofertas>
    </v-dialog>

    <v-data-table
      :headers="headers"
      item-key="uuid"
      :items="ofertas"
      :items-per-page="5"
      class="elevation-1"
      :search="search"
    >
      <template v-slot:item.action="{ item }">
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <v-btn icon v-on="on" @click="editItem(item)">
              <v-icon small>mdi-pencil</v-icon>
            </v-btn>
          </template>
          <span>Editar Oferta</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-btn icon v-on="on" @click="deleteItem(item)">
              <v-icon small>mdi-delete</v-icon>
            </v-btn>
          </template>
          <span>Eliminar Oferta</span>
        </v-tooltip>
      </template>
    </v-data-table>
    <v-dialog v-model="dialog" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Estás seguro?</v-card-title>
        <v-card-text>Al eliminar el registro ya no podrá verlo y los candidatos ya no podrán ver la oferta laboral, estas seguro de eliminarlo?.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="gray darken-1" text  @click="dialog = false">Cancelar</v-btn>
          <v-btn color="red darken-1" text  @click="eliminar">Eliminar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar" color="success" :timeout="3000" :right="true">
      {{ text }}
      <v-btn color="black" text @click="snackbar = false">Cerrar</v-btn>
    </v-snackbar>
  </div>
</template>
<script>
import ofertas from "./eidtOfert.vue";
export default {
  components: {
    ofertas
  },
  data() {
    return {
      headers: [
        {
          text: "Titulo",
          align: "left",
          sortable: false,
          value: "titulo"
        },
        { text: "Sueldo", value: "sueldo" },
        { text: "Departamento / Ciudad", value: "ciudad" },
        { text: "Vacantes", value: "vacantes" },
        { text: "Experiencia", value: "experiencia" },
        { text: "Estado", value: "estado" },
        { text: "", value: "action", sortable: false }
      ],
      id: "",
      ofertas: [],
      search: "",
      dialog: false,
      selected: [],
      snackbar: false,
      text:''
    };
  },
  mounted() {
    this.getOferts();
  },
  methods: {
    getOferts() {
      axios
        .get("/getOferts")
        .then(response => {
          this.ofertas = response.data;
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
    editItem(item) {
      this.$store.commit("setEmpresa", item);
      this.$store.dispatch("setDialog", true);
      // this.$emit('getOfert');
      setTimeout(() => {
        this.$refs.Ofertas.getOfert();
      }, 50);
    },
    deleteItem(item) {
      this.id = item.id;
      this.dialog = true;
    },
    eliminar() {
      axios
        .delete(`/deleteOfert/${this.id}`)
        .then(response => {
          if (response.data.tipo == 5) {
            EventBus.$emit("session");
          } else {
            this.text = response.data.mensaje;
            this.snackbar = true;
            this.dialog = false;
            this.id = null;
            this.getOferts();
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>


<style lang="scss" scoped>
</style>