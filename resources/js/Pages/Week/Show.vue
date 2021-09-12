<template>
  <app-layout>
    <div class="columns is-centered">
      <div
        class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless"
      >
        <inertia-link
          class="back-link has-text-dark"
          :href="
            route('cohort.show', {
              cohort: $page.props.parameters.cohort,
              course: $page.props.parameters.course
            })
          "
          >{{ course.title }}</inertia-link
        >
        > {{ week.name }}
        <div class="box">
          <h3 class="title">{{ course.title }}</h3>
          <h2 class="subtitle mb-4">{{ week.name }}</h2>

          <div v-if="course.archived" class="notification is-warning">
            <strong>This class has finished and has now been archived.</strong>
          </div>

          <div class="content mb-4" v-html="week.description"></div>

          <h3 v-if="week.lessons.length" class="title is-size-5">
            This week’s lessons
          </h3>
          <b-button
            v-for="lesson in week.lessons"
            :key="lesson.id"
            tag="a"
            size="is-medium"
            :href="
              route('lesson.show', {
                cohort: $page.props.parameters.cohort,
                course: $page.props.parameters.course,
                week: $page.props.parameters.week,
                lesson: lesson.id
              })
            "
            class="is-justify-between"
            icon-right="arrow-right"
            expanded
            outlined
          >
            <span class="text-overflow-ellipsis">
              {{ lesson.title }}
              <span v-if="!lesson.live" class="tag is-primary ml-2"
                >Coming soon</span
              >
            </span>
          </b-button>

          <section
            v-if="!week.lessons.length"
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
  props: ["course", "week"],
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
