<template>
  <app-layout>
    <div class="columns is-centered">
      <div
        class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless"
      >
        <a class="back-link has-text-dark" :href="route('cohort.index')"
          >&larr; Back to all classes</a
        >
        <div class="box">
          <div class="mb-3">
            <h3 class="title has-text-centered">{{ course.title }}</h3>
            <div
              class="subtitle has-text-centered"
              v-html="course.description"
            ></div>
          </div>

          <div v-if="course.archived" class="notification is-warning">
            <strong>This class has finished and has now been archived.</strong>
          </div>

          <inertia-link
            v-for="week in course.weeks"
            :key="week.id"
            class="button is-medium is-justify-between is-fullwidth is-outined "
            :href="
              route('week.show', {
                course: $page.props.parameters.course,
                cohort: $page.props.parameters.cohort,
                week: week.number
              })
            "
          >
            <span class="text-overflow-ellipsis">
              {{ week.name }}
            </span>
            <b-icon icon="arrow-right" />
          </inertia-link>

          <section
            v-if="!course.weeks.length"
            class="section is-medium has-background-light has-text-centered"
          >
            No weeks have been added to this course yet.
          </section>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
export default {
  props: ["course"],
  components: {},
  data() {
    return {};
  },

  mounted() {},

  methods: {}
};
</script>

<style lang="scss">
@import "../../../sass/variables";
</style>
