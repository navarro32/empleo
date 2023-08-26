<template>
  <div>
    <v-app id="inspire">       

      <v-content>
        <v-container fluid fill-height>
          <v-layout align-center justify-center>
            <v-flex text-xs-center></v-flex>
          </v-layout>
        </v-container>
      </v-content>
      <v-footer color="#3c4c9f" app>
        <span class="white--text">&copy; 2019</span>
      </v-footer>
    </v-app>
  </div>
</template>

<script>
import menutoolbar from './modulos.vue'
export default {
    components: {
        menutoolbar,
    },
  data: () => ({
    drawer: false
  })
};
</script> 

<style lang="scss" scoped>
</style>