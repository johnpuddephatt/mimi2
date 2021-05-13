<template>
  <aside class="column is-one-third p-5 mt-5">
    <div class="menu">
      <h2 class="title is-size-3">Chatroom manager</h2>
      <course-dropdown :courses="courses" :current="current" />

      <ul class="mt-4 menu-list">
        <li v-for="lesson in lessons"
        :key="lesson.id">
          <inertia-link
            class="is-flex is-justify-between"
            :class="{'is-active' : lesson.id == $page.props.parameters.lesson}"
            :href="route('chatroom.lesson', {'course': $page.props.parameters.course, 'lesson': lesson.id })"
            >
            <strong class="mr-1">{{ lesson.week.name}}</strong>
            <span class="text-overflow-ellipsis">{{ lesson.title }}</span>
            <span class="ml-1 tag is-rounded">{{ lesson.feedbackless_reply_count }} new</span>
          </inertia-link>
        </li>
      </ul>
    </div>
  </aside>

</template>

<script>
import CourseDropdown from '@/components/CourseDropdown'

export default {

  props: ['courses','lessons', 'current'],

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
  padding-right: 3rem !important;
  position: sticky;
  top: 5.5rem;
}
</style>
