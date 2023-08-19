<template>
  <div>
    <v-navigation-drawer v-model="drawer" app disable-route-watcher>
      <v-list>
        <v-subheader>Menú</v-subheader>
        <v-list-item-group color="primary">
          <router-link
            :to="item.action"
            v-slot="{ href, route, navigate, isActive, isExactActive }"
            v-for="(item, i) in items"
            :key="i"
          >
            <v-list-item
              :tabindex="i"
              :class="[isActive && 'v-item--active', isExactActive && 'v-list-item--active']"
              @click="navigate"
            >
              <v-list-item-icon>
                <v-icon v-text="item.icon"></v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title v-text="item.text"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </router-link>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>
        <router-link to="/">{{ datosP.razon_social }}</router-link>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-avatar>
        <img :src="datosP.logo" :alt="datosP.razon_social" />
      </v-avatar>
      <v-menu v-model="menu" transition="slide-y-transition" bottom>
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-card>
          <v-list>
            <v-list-item>
              <v-list-item-avatar>
                <img :src="datosP.logo" :alt="datosP.razon_social" />
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>{{ datosP.nombres }} {{ datosP.apellidos }}</v-list-item-title>
                <v-list-item-subtitle>{{ datosP.url }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <v-divider></v-divider>

          <v-list>
            <v-list-item-group>
              <router-link
                to="/editarPerfil"
                v-slot="{ href, route, navigate, isActive, isExactActive }"
              >
                <v-list-item
                  :class="[isActive && 'v-item--active', isExactActive && 'v-list-item--active']"
                  @click="navigate"
                >
                  <v-list-item-icon>
                    <v-icon>mdi-account</v-icon>
                  </v-list-item-icon>
                  <v-list-item-title>Editar Perfil</v-list-item-title>
                </v-list-item>
              </router-link>
              <router-link
                to="/cambiarClave"
                v-slot="{ href, route, navigate, isActive, isExactActive }"
              >
                <v-list-item
                  :class="[isActive && 'v-item--active', isExactActive && 'v-list-item--active']"
                  @click="navigate"
                >
                  <v-list-item-icon>
                    <v-icon>mdi-account-key</v-icon>
                  </v-list-item-icon>
                  <v-list-item-title>Cambiar Contraseña</v-list-item-title>
                </v-list-item>
              </router-link>
              <v-list-item @click="cerrarSe">
                <v-list-item-icon>
                  <v-icon>mdi-close-box-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Cerrar Sesión</v-list-item-title>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>
    <form id="logout-form" ref="cerrarSe" :action="href" method="POST" style="display: none;">
      <input type="hidden" name="_token" :value="csrf" />
    </form>
    <v-dialog v-model="dialog" persistent max-width="500">
      <v-card>
        <v-card-title class="headline">Su sessión ha vencido.</v-card-title>
        <v-card-text>Su sessión ha vencido por inactividad, por favor ingresa nuevamente.</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" class="ma-2 white--text" @click="login()">
            Salir
            <v-icon right dark>mdi-close-octagon-outline</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["datos", "href"],
  data: () => ({
    dialog: false,
    datosP: {},
    drawer: false,
    fav: true,
    menu: false,
    message: false,
    hints: true,
    items: [
      { text: "Estadisticas", icon: "mdi-poll", action: "/Estadisticas" },
      {
        text: "Ofertas Publicadas",
        icon: "mdi-clipboard-list-outline",
        action: "/Ofertas"
      },
      {
        text: "Publicar Oferta",
        icon: "mdi-newspaper-plus",
        action: "/nuevaOferta"
      }
    ],
    csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content")
  }),
  mounted() {
    this.datosP = JSON.parse(this.datos);
    EventBus.$on("session", () => {
      this.dialog = true;
    });
  },
  methods: {    
    cerrarSe() {
      this.$refs.cerrarSe.submit();
    },
    login() {
      this.$refs.cerrarSe.submit();
    },
    modalSession() {
      this.dialog = true;
    }
  }
};
</script>

<style lang="scss" scoped>
.v-application a {
  color: rgba(0, 0, 0, 0.87) !important;
}
.v-application a:hover {
  text-decoration: none;
}
</style>