<template>
  <app-layout :padded="false">
    <div class="columns is-centered">
      <chatroom-menu
        :new_count="new_count"
        :cohorts="cohorts"
        :lessons="lessons"
        :current="`${cohort.course.title} (${cohort.title})`"
      />

      <div class="column"></div>
    </div>
  </app-layout>
</template>

<script>
import Chatroom from "@/components/Chatroom";
import ChatroomMenu from "@/components/ChatroomMenu";

export default {
  props: ["new_count", "cohorts", "cohort", "lessons"],
  components: {
    Chatroom,
    ChatroomMenu
  },
  data() {
    return {};
  },

  mounted() {},

  methods: {}
};
</script>

<style lang="scss">
@import "../../../../sass/variables";
</style>
