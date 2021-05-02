<template>
  <aside class="column is-one-third menu p-5 mt-5">

    <course-dropdown :courses="courses" :current="current" />

    <ul class="mt-4 menu-list">
      <li v-for="lesson in lessons"
      :key="lesson.id">
        <inertia-link
          class="is-justify-between text-overflow-ellipsis"
          :class="{'is-active' : lesson.id == $parameters.lesson}"
          :href="route('chatroom.lesson', {'course': $parameters.course, 'lesson': lesson.id })"
          >
          {{ lesson.title }}
        </inertia-link>
      </li>
    </ul>
  </aside>

</template>

<script>
import CourseDropdown from '@/components/CourseDropdown'

export default {

  props: ['courses','lessons', 'current', '$parameters'],

  components: {
    CourseDropdown
  },

  data() {
    return {
    }
  },

  computed: {
  },

  mounted() {
  },

  watch: {
  },

  methods: {

  }

}
</script>

<style lang="scss">
@import "../../sass/variables";

.menu {
  position: sticky;
  top: 5.5rem;
}
</style>
