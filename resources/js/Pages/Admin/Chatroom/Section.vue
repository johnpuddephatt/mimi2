<template>
  <app-layout :padded="false">
    <div class="columns is-centered">
      <chatroom-menu
        :new_count="new_count"
        :cohorts="cohorts"
        :lessons="lessons"
        :current="cohort.title"
      />

      <div class="section-content box column is-two-thirds p-5">
        <div class="mt-6 mb-6">
          <h3 class="title is-flex">
            <span class="text-overflow-ellipsis">{{ lesson.title }}</span>
            🗂&nbsp;
            <a
              target="_blank"
              :href="
                route('lesson.show', {
                  cohort: $page.props.parameters.cohort,
                  course: $page.props.parameters.course,
                  week: lesson.week.number,
                  lesson: lesson.id
                })
              "
              class="button ml-a"
              >View this lesson</a
            >
          </h3>
        </div>
        <Chatroom
          :show_admin_interface="true"
          :in_chatroom_manager="true"
          :comments="comments"
          :replies="replies"
          :include_already_replied_to="include_already_replied_to"
          class="negative-margin"
        ></Chatroom>
      </div>
    </div>
  </app-layout>
</template>

<script>
import Chatroom from "@/components/Chatroom";
import ChatroomMenu from "@/components/ChatroomMenu";

export default {
  props: [
    "new_count",
    "cohorts",
    "cohort",
    "lesson",
    "lessons",
    "comments",
    "replies",
    "include_already_replied_to"
  ],
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

.section-content {
  padding: 3rem !important;
}
</style>
