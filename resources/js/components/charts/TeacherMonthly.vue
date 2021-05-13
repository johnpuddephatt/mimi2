<template>
  <div>
    <b-field label="Teacher monthly feedback">
        <b-datepicker
          v-model="dateRange"
          type="month"
          placeholder="Click to select..."
          range>
        </b-datepicker>
    </b-field>
    <chart :labels="teachers" :datasets="datasets"></chart>
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
      teachers: [],
    }),

    watch: {
      dateRange() {
        this.fetchData();
      }
    },
    computed: {
      startDate() {
        if(this.dateRange) {
          return dayjs(this.dateRange[0]).startOf('month').format("YYYY-MM-DD");
        }
      },
      endDate() {
        if(this.dateRange) {
          return dayjs(this.dateRange[1]).endOf('month').format("YYYY-MM-DD");
        }
      }
    },
    mounted () {
      this.dateRange = [ new Date(dayjs().startOf('month').subtract(3,'month')), new Date(dayjs().endOf('month').subtract(1,'month'))];
    },
    methods: {
      fetchData() {
        this.isLoading = true;
        axios({
            method: 'get',
            url: `/stats/teacher-monthly/${ this.startDate }/${ this.endDate }`,
            timeout: 15000
          })
          .then(response => {
            this.teachers = response.data.map(entry => `${entry.first_name} ${entry.last_name}`)
            this.datasets = [
              {
                label: 'Data',
                backgroundColor: '#efa730',
                data: response.data.map(entry => entry.replies_count)
              }
            ];
            this.isLoading = false;
          })
      }
    }
  }
</script>

<style>

</style>
