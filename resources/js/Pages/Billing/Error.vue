<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">

    <form ref="form" class="box register-form">
      <h3 class="title has-text-centered">C’è stato un problema <span class="emoji">🤕</span></h3>
      <p class="subtitle has-text-centered">There has been an error processing your payment.</p>

      <p>We’ve been unable to process your payment, please try again. If the problem persists, drop us an email at <a href="mailto:katie@joyoflanguages.com">katie@joyoflanguages.com</a></p>
    </form>


  </div>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
  props: ['errors', '$parameters'],
  components: {
    AppLayout,
  },
  data() {
    return {

    }
  },
  mounted() {
  },
  computed: {

  },
  methods: {
  }
}
</script>

<style lang="scss">

</style>
