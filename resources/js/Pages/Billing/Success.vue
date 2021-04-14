<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">

    <form ref="form" class="box register-form">
      <h3 class="title has-text-centered">Splendido! <span class="emoji">🥁</span></h3>
      <p class="subtitle has-text-centered">You’re signed up and ready for the start of term.</p>

      <div class="has-widescreen-media">
        <iframe src="https://player.vimeo.com/video/428987759?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&color=33c2cf" width="1920" height="1080" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Benvenuti!"></iframe>
      </div>

      <h3 class="title is-size-4 mt-5">Welcome to our language school</h3>
      <p class="mb-2">This site is the Joy of Languages school. It’s the place where most learning takes place and if you take a look around you’ll find resources that you can start using straight away, like the companion courses.</p>
      <p class="mb-2">If it looks a little quiet at the moment don’t worry, that's because we’re busy getting things ready for the new term. Keep an eye out for emails from us as we’ll soon have you enrolled on a course. We can't wait to see you on the inside!</p>
      <a class="mt-3 button is-primary" :href="route('home')">Visit your dashboard</a>

      <h3 class="title is-size-4 mt-6">Finish setting up your profile</h3>
      <p class="mb-2">To help you get the most out of our platform we encourage everyone to add a photo and introduce themselves.</p>
      <a class="mt-3 button is-primary" :href="route('profile.show')">Edit your profile</a>

      <h3 class="title is-size-4 mt-6">How to contact us</h3>
      <p class="mb-2">You should receive an email with more details about your membership. If you need a hand with anything, you can always reply to the emails you receive from us or send one to <a href="mailto:katie@joyoflanguages.com">katie@joyoflanguages.com</a>. Across site you’ll see a blue ‘need help?’ button in the bottom right of the page which you can use if you run into problems.</p>

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
