<template>
  <div id="piechart" style="width: 900px; height: 500px;"></div>
</template>

<script>
export default {
  mounted() {
    google.charts.load("current", { packages: ["corechart"] });

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(this.drawChart);
  },
  methods: {
    drawChart() {
      let data = google.visualization.arrayToDataTable([
        ["Task", "Hours per Day"],
        ["Work", 11],
        ["Eat", 2],
        ["Commute", 2],
        ["Watch TV", 2],
        ["Sleep", 7]
      ]);

      let options = {
        title: "Mis estadisticas"
      };

      let chart = new google.visualization.PieChart(
        document.getElementById("piechart")
      );

      chart.draw(data, options);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>