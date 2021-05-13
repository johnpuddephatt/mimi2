<template>
  <div>
    <b-field label="Student videos posted">
        <b-datepicker
          v-model="dateRange"
          type="month"
          placeholder="Click to select..."
          range>
        </b-datepicker>
    </b-field>
    <chart :labels="months" :datasets="datasets"></chart>
  </div>
</template>
<script>
  import dayjs from 'dayjs';
  import Chart from '@/components/chart';

  export default {
    components: {
      Chart
    },
    data: () => ({
      isLoading: true,
      dateRange: null,
      datasets: null,
      months: [],
      startDate: null,
      endDate: null
    }),
    watch: {
      dateRange() {
        var startDate = dayjs(this.dateRange[0]).startOf('month');
        this.startDate = startDate.format("YYYY-MM-DD");
        var endDate = dayjs(this.dateRange[1]).endOf('month');
        this.endDate = endDate.format("YYYY-MM-DD");
        this.months = [];
        while (startDate.isBefore(endDate)) {
          this.months.push(startDate.format("MMM"));
          startDate = startDate.add(1, 'month');
        }
        this.fetchData();
      }
    },
    computed: {
    },
    mounted () {
      this.dateRange = [ new Date(dayjs().startOf('month').subtract(3,'month')), new Date(dayjs().endOf('month').subtract(1,'month'))];
    },
    methods: {
      fetchData() {
        this.isLoading = true;
        axios({
            method: 'get',
            url: `/stats/replies-monthly/${ this.startDate }/${ this.endDate }`,
            timeout: 15000
          })
          .then(response => {
            this.datasets = [
              {
                label: 'Data',
                backgroundColor: '#efa730',
                data: this.getMonthlyValues(response.data)
              }
            ];
            this.isLoading = false;
          })
      },
      getMonthlyValues(data) {
        let output = [];
        this.months.forEach(month => {
          output.push(data[month] || 0);
        });
        return output;
      }
    }
  }
</script>

<style>

</style>
