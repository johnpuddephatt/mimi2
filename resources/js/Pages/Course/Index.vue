<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
    <div class="box">
      <div class="mb-5">
        <h3 class="title has-text-centered">Corsi 🗂️</h3>
        <p v-if="$user.is_admin" class="subtitle has-text-centered">Hi Admin! Here are all the active courses</p>
        <p v-else class="subtitle has-text-centered">You’re enrolled on the courses below</p>
      </div>

        <inertia-link
          v-for="course in courses"
          :key="course.id"
          class="button is-medium is-justify-between is-fullwidth is-outined "
          :href="route('course.show', {'course': course.id })"
          >
          <span class="text-overflow-ellipsis">{{ course.title }}</span>
          <span v-if="course.archived" class="tag is-light ml-2">Archived</span>
          <span v-else-if="!course.live" class="tag is-primary ml-2">Coming soon</span>

          <b-icon icon="arrow-right" />
        </inertia-link>


        <section v-if="!courses.length" class="section is-medium has-background-light has-text-centered">
            <a v-if="$user.is_admin" class="button" :href="route('course.new')">Add the first course</a>
            <p>You’re not enrolled in any courses.</p>
        </section>

    </div>
  </div>

</app-layout>
</template>

<script>
export default {
  props: ['courses', '$parameters', '$user'],
  components: {},
  data() {
    return {
    }
  },

  mounted() {},

  methods: {}
};
</script>

<style lang="scss">
@import "../../../sass/variables";
</style>
