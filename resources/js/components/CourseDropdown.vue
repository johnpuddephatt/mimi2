<template>
  <b-dropdown
    class="is-block"
    position="is-bottom-right"
    append-to-body
    aria-role="menu">
    <template #trigger>
        <button
            class="button is-fullwidth navbar-item text-overflow-ellipsis"
            role="button">
            <span class="">{{ current }}</span>
            <b-icon icon="menu-down"></b-icon>
        </button>
    </template>

    <b-dropdown-item has-link aria-role="menuitem">
      <inertia-link
        v-for="course in courses"
        :key="course.id"
        :href="route('chatroom.course', {'course': course.id })"
        >
        {{ course.title }}
      </inertia-link>
    </b-dropdown-item>
  </b-dropdown>

</template>

<script>
export default {
  props: ['courses','current'],

  data() {
    return {
    }
  },

  computed: {

  },

  mounted() {},

  watch: {

  },

  methods: {

  }
}
</script>

<style lang="scss">
@import "../../sass/variables";
</style>
