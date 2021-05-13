<template>
<app-layout :padded="false">
  <div class="columns is-centered">

    <chatroom-menu :courses="courses" :lessons="lessons" :current="course.title"/>

    <div class="column"></div>
  </div>
</app-layout>
</template>

<script>
import Chatroom from '%/components/Chatroom'
import ChatroomMenu from '%/components/ChatroomMenu'

export default {
  props: ['courses', 'course', 'lessons'],
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
</style>
