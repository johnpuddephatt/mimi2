<template>
  <b-dropdown
    class="is-block"
    position="is-bottom-right"
    append-to-body
    aria-role="menu"
  >
    <template #trigger>
      <button
        class="button is-fullwidth navbar-item text-overflow-ellipsis"
        role="button"
      >
        <span class="">{{ current }}</span>
        <b-icon icon="chevron-down"></b-icon>
      </button>
    </template>

    <b-dropdown-item has-link aria-role="menuitem">
      <inertia-link
        v-for="cohort in cohorts"
        :key="cohort.id"
        :href="
          route('chatroom.cohort', {
            cohort: cohort.id,
            course: cohort.course_id
          })
        "
      >
        {{ cohort.course.title }}&nbsp;<em>{{ cohort.title }}</em>
      </inertia-link>
    </b-dropdown-item>
  </b-dropdown>
</template>

<script>
export default {
  props: ["cohorts", "current"],

  data() {
    return {};
  },

  computed: {},

  mounted() {},

  watch: {},

  methods: {}
};
</script>

<style lang="scss">
@import "../../sass/variables";
</style>
