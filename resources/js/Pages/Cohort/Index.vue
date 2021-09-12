<template>
  <app-layout>
    <div class="columns is-centered">
      <div
        class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless"
      >
        <div class="box">
          <div class="mb-5">
            <h3 class="title has-text-centered">Classi 🗂️</h3>
            <p
              v-if="$page.props.user.is_admin"
              class="subtitle has-text-centered"
            >
              Hi Admin! Here are all the active classes
            </p>
            <p v-else class="subtitle has-text-centered">
              You’re enrolled on the classes below
            </p>
          </div>

          <inertia-link
            v-for="cohort in cohorts"
            :key="cohort.id"
            class="button is-medium is-justify-between is-fullwidth is-outined "
            :href="
              route('cohort.show', {
                cohort: cohort.id,
                course: cohort.course_id
              })
            "
          >
            <span class="text-overflow-ellipsis">{{
              cohort.course.title
            }}</span>
            <span
              v-if="cohort.title != 'Default'"
              class="tag is-primary ml-2"
              >{{ cohort.title }}</span
            >
            <b-icon icon="arrow-right" />
          </inertia-link>

          <h3 class="heading mt-8 mb-2" v-if="inactive_cohorts.length">
            Past classes
          </h3>

          <inertia-link
            v-for="cohort in inactive_cohorts"
            :key="cohort.id"
            class="button is-medium is-justify-between is-fullwidth is-outined "
            :href="
              route('cohort.show', {
                cohort: cohort.id,
                course: cohort.course_id
              })
            "
          >
            <span class="text-overflow-ellipsis">{{
              cohort.course.title
            }}</span>
            <!-- <span v-if="!cohort.active" class="tag is-light ml-2"
              >Archived</span
            > -->
            <b-icon v-if="cohort.active" icon="arrow-right" />
            <!-- <span v-else class="tag is-primary ml-2">Coming soon</span> -->
          </inertia-link>

          <section
            v-if="!cohorts.length && !inactive_cohorts.length"
            class="section is-medium has-background-light has-text-centered"
          >
            <p>You’re not enrolled in any classes.</p>
          </section>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
export default {
  props: ["cohorts", "inactive_cohorts"],
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
