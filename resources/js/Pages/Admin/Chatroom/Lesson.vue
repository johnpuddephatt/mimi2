<template>
<app-layout :padded="false">
  <div class="columns is-centered">

    <chatroom-menu :courses="courses" :lessons="lessons" :current="course.title" :$parameters="$parameters"/>

    <div class="section-content box column is-two-thirds p-5">
        <div class="mt-6 mb-6">
          <h3 class="title">{{ lesson.title }} 🗂️</h3>
        </div>
        <Chatroom :show_admin_interface="true" :replies="replies" :include_already_replied_to="include_already_replied_to" :$user="$user" :$parameters="$parameters" class="negative-margin"></Chatroom>

    </div>
  </div>
</app-layout>
</template>

<script>
import Chatroom from '@/components/Chatroom'
import ChatroomMenu from '@/components/ChatroomMenu'

export default {
  props: ['courses', 'course', 'lesson', 'lessons', 'replies', '$parameters', '$user', 'include_already_replied_to'],
  components: {
    Chatroom,
    ChatroomMenu
  },
  data() {
    return {
    }
  },

  mounted() {},

  methods: {}
};
</script>

<style lang="scss">
@import "../../../../sass/variables";

.section-content {
  padding: 3rem !important;
}
</style>
