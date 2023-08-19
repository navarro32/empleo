<template >
  <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="200" offset-x>
    <template v-slot:activator="{ on, attrs }">
      <v-btn text v-bind="attrs" v-on="on"><v-app-bar-nav-icon></v-app-bar-nav-icon></v-btn>
    </template>

    <v-card>
      <v-list>
        <v-list-item>
          <v-list-item-avatar class="mr-3">
            <img :src="data.logo" :alt="data.nombre" />
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>{{data.nombre}}</v-list-item-title>
            <v-list-item-subtitle>{{data.email}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <v-list shaped>
        <v-list-item-group color="primary">
          <v-list-item @click="goTo()">
            <v-list-item-icon class="pr-3">
              <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Dashboard</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click="goTo2()">
            <v-list-item-icon class="pr-3">
              <v-icon>mdi-close-box-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Cerrar Sesión</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>     
    </v-card>
  </v-menu>
</template>

<script>
export default {
  props: ["user", "basicos"],
  data: () => ({
    fav: true,
    menu: false,
    message: false,
    hints: true,
    data: {
      nombre: "",
      logo: "",
      email: "",
      tipo: "",
    },
    items: [
      { text: "Dashboard", icon: "mdi-view-dashboard", to:"" },
      { text: "Cerrar Sesión", icon: "mdi-close-box-outline", to:"" },      
    ],
  }),
  mounted() {    
    this.data = {
      nombre:
        this.user.tipo_user == 2
          ? this.basicos.razon_social
          : this.user.nombres + " " + this.user.apellidos,
      logo:
        this.user.tipo_user == 2 ? this.basicos.logo : this.basicos.ruta_avatar,
      email: this.user.email,
      tipo: this.user.tipo_user == 2 ? "Empresas" : "Usuarios",
    };
    
  },
  methods: {
    goTo() {
      let go = this.user.tipo_user == 2 ? "/Empresas" : "/Usuarios"
      window.location.href = go;
    },
    goTo2(){
      $("#logout-form").submit()
    }
  },
};
</script>

<style lang="scss" scoped>
</style>