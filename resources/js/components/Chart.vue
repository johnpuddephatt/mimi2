<script>
  import { Bar } from 'vue-chartjs'

  export default {
    extends: Bar,
    props: ['labels','datasets'],
    data: () => ({
      chartdata: {
        labels: [],
        datasets: []
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        scales: {
        yAxes: [{
            display: true,
            ticks: {
                beginAtZero: true,
                min: 0
            }
        }]
    },
      }
    }),
    watch: {
      labels: function() {
        this.chartdata.labels = this.labels;
        this.renderChart(this.chartdata, this.options);
      },
      datasets: function() {
        this.chartdata.datasets = this.datasets;
        this.renderChart(this.chartdata, this.options);
      }
    },
    mounted () {
    }
  }
</script>

<style>

</style>
