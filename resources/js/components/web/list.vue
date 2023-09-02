<template>
  <div class="col-12 col-lg-9 pr-0">
    <v-card outlined>
      <v-card-text v-if="!$store.state.buscando">Se han encontrado: {{ $store.state.settings.total }} resultados</v-card-text>
      <v-card-text v-else><v-text-field color="blue" loading disabled></v-text-field></v-card-text>
    </v-card>
    <v-tabs
      v-model="tab"
      background-color="#314ba3"
      class="elevation-2 mt-4"
      dark
      right
      style="position:relative"
    >
      <v-tabs-slider></v-tabs-slider>
      <v-tab :href="`#tab-1`">
        <v-icon>mdi-format-list-bulleted</v-icon>
      </v-tab>
      <v-tab :href="`#tab-2`">
        <v-icon>mdi-grid</v-icon>
      </v-tab>
      <v-tab-item :value="'tab-1'" class="px-4 fijo">
        <div class="row">
          <div class="col-12 row" v-if="$store.state.loading">
            <v-skeleton-loader class="col-12 col-md-12" type="list-item-two-line"></v-skeleton-loader>
            <v-skeleton-loader class="col-12 col-md-12" type="list-item-two-line"></v-skeleton-loader>
            <v-skeleton-loader class="col-12 col-md-12" type="list-item-two-line"></v-skeleton-loader>
          </div>
          <div class="col-12" v-else v-for="data in  $store.state.data" :key="data.key">
            <v-card outlined>
              <v-card-title>
                {{data.titulo}}
                <v-tooltip left>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="#314ba3"
                      dark
                      small
                      absolute
                      bottom
                      right
                      fab
                      v-bind="attrs"
                      v-on="on"
                      @click="info=data; dialog=true"
                    >
                      <v-icon>mdi-eye</v-icon>
                    </v-btn>
                  </template>
                  <span>Vista rápida</span>
                </v-tooltip>
              </v-card-title>
              <v-card-subtitle>
                <v-icon size="20">mdi-map-marker</v-icon>
                {{ data.ciudad.ciudad }}
              </v-card-subtitle>
              <v-card-text>{{ data.description_large }}</v-card-text>
            </v-card>
          </div>
        </div>
      </v-tab-item>

      <v-tab-item :value="'tab-2'" class="px-4 fijo">
        <div class="row">
          <div class="col-12 row" v-if="$store.state.loading">
            <v-skeleton-loader class="col-12 col-md-4" type="card-avatar"></v-skeleton-loader>
            <v-skeleton-loader class="col-12 col-md-4" type="card-avatar"></v-skeleton-loader>
            <v-skeleton-loader class="col-12 col-md-4" type="card-avatar"></v-skeleton-loader>
          </div>

          <div
            class="col-6 col-md-6 col-lg-6"
            v-else
            v-for="data in  $store.state.data"
            :key="data.key"
          >
            <v-card outlined>
              <v-card-title>
                {{data.titulo}}
                <v-tooltip left>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="#314ba3"
                      dark
                      small
                      absolute
                      bottom
                      right
                      fab
                      v-bind="attrs"
                      v-on="on"
                      @click="info=data; dialog=true"
                    >
                      <v-icon>mdi-eye</v-icon>
                    </v-btn>
                  </template>
                  <span>Vista rápida</span>
                </v-tooltip>
              </v-card-title>
              <v-card-subtitle>
                <v-icon size="20">mdi-map-marker</v-icon>
                {{ data.ciudad.ciudad }}
              </v-card-subtitle>
              <v-card-text>{{ data.description }}</v-card-text>
            </v-card>
          </div>
        </div>
      </v-tab-item>
    </v-tabs>
    <infinite-loading
      ref="InfiniteComponent"
      :identifier="propsData"
      @infinite="infiniteHandler"
      spinner="spiral"
    >
      <div slot="no-more">
        <p class="pt-4 font-weight-bold">Fin</p>
      </div>
    </infinite-loading>

    <v-dialog v-model="dialog" width="auto" max-width="600">
      <v-card>
        <v-card-title class="headline grey lighten-2">
          <h4 class="mb-0" style="font-size:1.3rem">{{info.titulo}}</h4>
        </v-card-title>
        <v-card-subtitle v-if="info.ciudad" class="mt-1 row mx-0">
          <p class="col-6 mb-0 px-0">
            <v-icon size="20">mdi-map-marker</v-icon>
            {{ info.ciudad.ciudad }}
          </p>
          <p class="col-6 mb-0 px-0 text-right red--text">
            <v-icon class="red--text" style="font-size:14px">mdi-currency-usd</v-icon>
            {{ info.salario }}
          </p>
          <p class="mb-0 col-12 px-0">
            <v-icon>mdi-calendar-range</v-icon>
            {{info.publicado}}
          </p>
        </v-card-subtitle>
        <v-card-text>{{ info.description_large }}</v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>          
          <v-btn class="ma-2 boton mr-2" outlined color="indigo" :href="`/oferta/${info.slug}?uuid=${info.uuid}`">Ver Oferta</v-btn>
          <v-btn class="ma-2 text-white" raised color="#314ba3"  @click="aplicar(info.uuid)">Aplicar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import InfiniteLoading from "vue-infinite-loading";
export default {
  props: ["propsData"],
  data() {
    return {
      tab: null,
      primera: false,
      tabs: 2,
      page: 0,
      dialog: false,
      info: {},
    };
  },
  components: {
    InfiniteLoading,
  },
  methods: {
    ...mapActions(["buscar", "aplicar"]),
    infiniteHandler($state) {
      if (this.propsData != this.$store.state.infinitieId) {
        this.buscar().then((response) => {
          console.log(response);
          if (response && !this.$store.state.complete) {
            $state.loaded();
          } else {
            $state.complete();
          }
        });
      } else {
        if (this.$store.state.busquedad) {
          if (!this.$store.state.complete) {
            this.buscar().then((response) => {
              if (response) {
                $state.loaded();
              } else {
                $state.complete();
              }
            });
          } else {
            $state.complete();
          }
        } else {
          setTimeout(() => {
            $state.loaded();
          }, 2000);
        }
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.v-card__title {
  font-size: 1rem;
  font-weight: bold;
}
.boton:hover{
  text-decoration: none;;
}
</style>