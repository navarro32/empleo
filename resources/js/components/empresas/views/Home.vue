
<template>
    
    <v-container fluid align-center justify-center>
        
      <v-row>
        <v-col cols="2">
          <v-btn icon class="ma-2" @click="prevCalendar()" left absolute>
            <v-icon>mdi-chevron-left</v-icon>
          </v-btn>
        </v-col>
        <v-col cols="4"></v-col>
        <v-col cols="4">
          <v-select v-model="type" item-text="text" item-value="id" :items="types" dense outlined hide-details class="ma-2" label="Ver por:"></v-select>
        </v-col>
        <v-spacer></v-spacer>
        <v-col cols="2">
          <v-btn icon class="ma-2" @click="nextCalendar()" right absolute>
            <v-icon>mdi-chevron-right</v-icon>
          </v-btn>
        </v-col>
      </v-row>
  
      <v-sheet col="12" height="60vh">
        <v-calendar ref="calendar" v-model="value" :type="type.id"></v-calendar>
      </v-sheet>
   
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        value: '',
        type: {
          id: "month",
          text: "mes"
        },
        types: [{
            id: "month",
            text: "mes"
          },
          {
            id: "week",
            text: "Semana"
          },
          {
            id: "day",
            text: "Dia"
          }
        ],
      };
    },
    methods: {
      prevCalendar() {
        this.$refs.calendar.prev();
      },
      nextCalendar() {
        this.$refs.calendar.next();
      },
    },
  };
  </script>
  
  <style lang="scss" scoped></style>
  