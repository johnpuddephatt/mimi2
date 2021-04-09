<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">

    <form ref="form" class="box register-form">
      <h3 class="title has-text-centered">Splendido! <span class="emoji">🥁</span></h3>
      <p class="subtitle has-text-centered">You’re signed up and ready to learn.</p>

      <iframe src="https://player.vimeo.com/video/428987759?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" width="1920" height="1080" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Benvenuti!"></iframe>

      <h3>The members' area</h3>
      <p>In your members area, you'll find resources that you can start using straight away, like the companion courses and Italian Q&amp;A. We can't wait to see you on the inside! Click here to go to the members area.</p>

      <h3>How to contact us</h3>
      <p>You should receive an email with more details about the membership. If you need a hand with anything, you can always reply to the emails you receive from us or send one to <a href="mailto:katie@joyoflanguages.com">katie@joyoflanguages.com</a></p>

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
