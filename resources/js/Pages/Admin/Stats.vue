<template>
  <app-layout>
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
        <div class="box">
          <h3 class="title has-text-centered">Statistics 📈</h3>
          <p class="subtitle has-text-centered">With great power comes great responsibility</p>
          <chart-teacher-monthly></chart-teacher-monthly>
          <br><br>
          <chart-teacher-weekly></chart-teacher-weekly>
          <br><br>
          <chart-replies-monthly></chart-replies-monthly>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import ChartTeacherWeekly from '@/components/charts/TeacherWeekly'
import ChartTeacherMonthly from '@/components/charts/TeacherMonthly'
import ChartRepliesMonthly from '@/components/charts/RepliesMonthly'

export default {
  props: {
    courses: Array,
  },
  components: {
    AppLayout,
    ChartTeacherWeekly,
    ChartTeacherMonthly,
    ChartRepliesMonthly
  }
}
</script>
