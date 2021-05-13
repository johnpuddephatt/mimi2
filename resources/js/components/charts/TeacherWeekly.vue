<template>
  <div>
    <b-field class="" label="Teacher weekly feedback">
      <p class="mt-2 mr-1">Week commencing:</p>
      <div class="select">
        <select v-model="date">
          <option v-for="week in weeks" :value="week.robot" :selected="date == week.robot">{{ week.human }}</option>
        </select>
      </div>

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
      date: null,
      datasets: null,
      number_of_weeks: 12,
      weeks: [],
      teachers: [],
    }),

    watch: {
      date(newDate,oldDate) {

        if(newDate && newDate != oldDate) {
          this.fetchData();
        }
      }
    },
    computed: {
    },
    mounted () {
      this.date = dayjs().startOf('week').add(1, 'days').format("YYYY-MM-DD");
      this.enumerateWeeks();
    },
    methods: {
      enumerateWeeks() {
        for(let i = 0; i < this.number_of_weeks; i++) {
          let date = dayjs(this.date, "YYYY-MM-DD").subtract(i, 'weeks');
          this.weeks.push({
            robot: date.format("YYYY-MM-DD"),
            human: date.format("dddd D MMM , YYYY")
          })
        };
      },
      fetchData() {
        this.isLoading = true;
        axios({
            method: 'get',
            url: `/stats/teacher-weekly/${ this.date }`,
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
